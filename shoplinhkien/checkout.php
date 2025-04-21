<?php
session_start();
require_once 'connect.php';
require_once 'functions.php';

// Thêm hàm validateName()
function validateName($name) {
    // Kiểm tra tên có ít nhất 2 từ
    $words = explode(' ', trim($name));
    return count($words) >= 2;
}

function validatePhone($phone) {
    // Kiểm tra số điện thoại:
    // 1. Phải là số
    // 2. Bắt đầu bằng số 0
    // 3. Phải từ 10 số trở lên
    return preg_match('/^0\d{9,}$/', $phone);
}

// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng
$userId = $_SESSION['id'];
$userQuery = "SELECT * FROM nguoi_dung WHERE id = ?";
$stmt = mysqli_prepare($conn, $userQuery);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$userResult = mysqli_stmt_get_result($stmt);
$userInfo = mysqli_fetch_assoc($userResult);

// Xử lý AJAX request cho việc chọn sản phẩm từ giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_items'])) {
    $selectedItems = json_decode($_POST['selected_items'], true);
    
    if (empty($selectedItems)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn sản phẩm để thanh toán']);
        exit;
    }
    
    // Lấy thông tin các sản phẩm được chọn từ giỏ hàng
    $selectedProducts = [];
    foreach ($selectedItems as $itemId) {
        $stmt = $conn->prepare("SELECT c.*, s.tensanpham, s.gia, h.duongdan 
                               FROM chitietgiohang c 
                               JOIN sanpham s ON c.masanpham = s.sanphamid 
                               LEFT JOIN hinhanhsanpham h ON s.sanphamid = h.masanpham 
                               WHERE c.chitietgiohangid = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        if ($product) {
            // Kiểm tra khuyến mãi
            $promotion = getProductPromotion($conn, $product['masanpham']);
            if ($promotion) {
                $product['final_price'] = calculateDiscountedPrice($product['gia'], $promotion);
            } else {
                $product['final_price'] = $product['gia'];
            }
            $selectedProducts[] = $product;
        }
    }
    
    // Lưu vào session và xóa session buy_now nếu có
    $_SESSION['checkout_items'] = $selectedProducts;
    unset($_SESSION['buy_now']);
    
    echo json_encode(['success' => true]);
    exit;
}

// Tính toán giá và tổng tiền
$subtotal = 0;
$shippingFee = 0;
$total = 0;

if (isset($_SESSION['buy_now'])) {
    // Xử lý mua ngay
    $buyNowInfo = $_SESSION['buy_now'];
    $stmt = $conn->prepare("SELECT s.*, h.duongdan 
                           FROM sanpham s 
                           LEFT JOIN hinhanhsanpham h ON s.sanphamid = h.masanpham 
                           WHERE s.sanphamid = ?");
    $stmt->bind_param("i", $buyNowInfo['product_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if ($product) {
        $promotion = getProductPromotion($conn, $product['sanphamid']);
        if ($promotion) {
            $price = calculateDiscountedPrice($product['gia'], $promotion);
        } else {
            $price = $product['gia'];
        }
        $quantity = $buyNowInfo['quantity'];
        $subtotal = $price * $quantity;
    }
    
    unset($_SESSION['checkout_items']); // Xóa session checkout_items nếu có
} elseif (isset($_SESSION['checkout_items'])) {
    // Xử lý mua từ giỏ hàng
    foreach ($_SESSION['checkout_items'] as $item) {
        $subtotal += $item['final_price'] * $item['soluong'];
    }
}

// Tính phí vận chuyển và tổng tiền
$shippingFee = ($subtotal >= 500000) ? 0 : 30000;
$total = $subtotal + $shippingFee;

// Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $fullname = trim($_POST['fullname']);
    $address = trim($_POST['address']);
    $phone = $_POST['phone'];
    $paymentMethod = $_POST['payment_method'];
    $error = '';

    // Validate dữ liệu
    if (!validateName($fullname)) {
        $error = "Họ tên người nhận phải có ít nhất 2 từ!";
    }
    elseif (!validatePhone($phone)) {
        $error = "Số điện thoại phải là số, bắt đầu bằng số 0 và phải từ 10 số trở lên!";
    }
    elseif (strlen($address) < 10) {
        $error = "Địa chỉ giao hàng phải có ít nhất 10 ký tự!";
    }

    if (!empty($error)) {
        echo json_encode(['success' => false, 'message' => $error]);
        exit;
    }

    // Bắt đầu transaction
    $conn->begin_transaction();
    
    try {
        // Tạo một đơn hàng mới
        $stmt = $conn->prepare("INSERT INTO donhang (manguoidung, ten_nguoi_dat, ngaydat, trangthai, tongtien, diachigiao, sdt, phuongthuctt) 
                               VALUES (?, ?, NOW(), 'Chờ xác nhận', ?, ?, ?, ?)");
        $stmt->bind_param("issdss", $userId, $fullname, $total, $address, $phone, $paymentMethod);
        $stmt->execute();
        $orderId = $conn->insert_id;
        
        // Chuẩn bị statement cho chi tiết đơn hàng
        $stmt = $conn->prepare("INSERT INTO chitietdonhang (madonhang, masanpham, soluong, gia) VALUES (?, ?, ?, ?)");
        
        if (isset($_SESSION['buy_now'])) {
            // Thêm sản phẩm mua ngay vào đơn hàng
            $stmt->bind_param("iiid", $orderId, $buyNowInfo['product_id'], $quantity, $price);
            $stmt->execute();
            
            unset($_SESSION['buy_now']);
        } elseif (isset($_SESSION['checkout_items'])) {
            // Thêm tất cả sản phẩm từ giỏ hàng vào cùng một đơn hàng
            foreach ($_SESSION['checkout_items'] as $item) {
                $stmt->bind_param("iiid", $orderId, $item['masanpham'], $item['soluong'], $item['final_price']);
                $stmt->execute();
                
                // Xóa sản phẩm khỏi giỏ hàng
                $deleteStmt = $conn->prepare("DELETE FROM chitietgiohang WHERE chitietgiohangid = ?");
                $deleteStmt->bind_param("i", $item['chitietgiohangid']);
                $deleteStmt->execute();
            }
            
            unset($_SESSION['checkout_items']);
        }
        
        // Commit transaction
        $conn->commit();
        
        // Chuyển hướng đến trang lịch sử đơn hàng
        header("Location: order_history.php");
        exit();
        
    } catch (Exception $e) {
        // Rollback nếu có lỗi
        $conn->rollback();
        $error = "Có lỗi xảy ra: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
     
    * {
        margin: 0;
        padding: 0;
        font-size: 24px;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }


    /* CSS cho top-bar */
    .top-bar {
        background-color: #8BC34A;
        color: white;
        padding: 15px 20px;
        border-radius: 50px 80px 50px 80px;
        max-width: 95%;
        height: 70px;
        margin: 10px auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: nowrap;
    }

    .top-info, .top-links {
        display: flex;
        gap: 15px;
    }

    .top-info i {
        color: #FFD700;
        margin-right: 5px;
    }

    .top-links a {
        color: white;
        text-decoration: none;
    }

    .top-links a:hover {
        text-decoration: underline;
    }

    /* CSS cho header chính */
    header {
        background: white;
        padding: 15px 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    /* CSS cho logo */
    .logo {
        font-size: 24px;
        font-weight: bold;
        color: #8BC34A;
        text-decoration: none;
        transition: color 0.3s;
        margin-left: 50px;
    }

    .logo:hover {
        color: #689F38;
    }

    /* CSS cho navigation */
    nav {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    nav a {
        text-decoration: none;
        color: #333;
        transition: color 0.3s;
    }

    nav a:hover {
        color: #8BC34A;
    }

    /* CSS cho dropdown trong nav */
    nav .dropdown {
        position: relative;
    }

    nav .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px 0;
        min-width: 200px;
        display: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    nav .dropdown:hover .dropdown-menu {
        display: block;
    }

    nav .dropdown-menu li {
        list-style: none;
    }

    nav .dropdown-menu a {
        padding: 8px 16px;
        display: block;
        color: #333;
        text-decoration: none;
    }

    nav .dropdown-menu a:hover {
        background-color: #f5f5f5;
        color: #8BC34A;
    }

    /* CSS cho header icons */
    .header-icons {
        display: flex;
        gap: 15px;
    }

    .header-icons a {
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .header-icons i {
        color: #8BC34A;
    }

    /* CSS cho user dropdown */
    .header-icons .dropdown {
        position: relative;
        margin-right: 20px;
    }

    .header-icons .dropdown-toggle {
        text-decoration: none;
        color: #333;
        display: flex;
        align-items: center;
    }

    .header-icons .dropdown-toggle i {
        color: #8BC34A;
        margin-right: 10px;
    }

    .header-icons .dropdown-menu {
        display: none;
        position: absolute;
        background: white;
        border: 1px solid #ddd;
        list-style: none;
        padding: 10px;
        min-width: 230px;
        z-index: 100;
        top: 100%;
        left: 0;
        border-radius: 5px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .header-icons .dropdown:hover .dropdown-menu {
        display: block;
    }

    .header-icons .dropdown-menu li {
        list-style: none;
    }

    .header-icons .dropdown-menu li a {
        display: block;
        padding: 8px 16px;
        text-decoration: none;
        color: #333;
        transition: background-color 0.3s;
    }

    .header-icons .dropdown-menu li a:hover {
        background-color: #f5f5f5;
    }

    .header-icons .dropdown-menu i {
        margin-right: 8px;
        color: #8BC34A;
    }

    /* CSS cho cart count */
    .cart-count {
        background: #8BC34A;
        color: white;
        padding: 2px 6px;
        border-radius: 50%;
        font-size: 12px;
        margin-left: 5px;
    }

    /* CSS cho responsive */
    @media (max-width: 768px) {
        .top-bar {
            flex-direction: column;
            height: auto;
            padding: 10px;
            gap: 10px;
        }

        .top-info, .top-links {
            flex-wrap: wrap;
            justify-content: center;
            text-align: center;
        }

        header {
            padding: 10px;
        }

        .header-icons {
            gap: 10px;
        }
    }

    /* Checkout container styles */
    .checkout-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .page-title {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    /* Form styles */
    .checkout-form {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .form-title {
        color: #333;
        margin-bottom: 20px;
        font-size: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-group input::placeholder {
        color: #999;
    }

    .form-group input:focus {
        outline: none;
        border-color: #8BC34A;
        box-shadow: 0 0 0 2px rgba(139, 195, 74, 0.1);
    }

    /* Payment methods */
    .payment-methods {
        margin: 20px 0;
    }

    .payment-method {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-method:hover {
        border-color: #8BC34A;
        background-color: #f9f9f9;
    }

    .payment-method.selected {
        border-color: #8BC34A;
        background-color: #f1f8e9;
    }

    .payment-method i {
        font-size: 24px;
        color: #666;
    }

    .payment-method-title {
        font-weight: 500;
        color: #333;
    }

    .payment-method input[type="radio"] {
        margin: 0;
    }

    /* Order summary styles */
    .order-summary {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: sticky;
        top: 20px;
    }

    .summary-title {
        color: #333;
        margin-bottom: 20px;
        font-size: 20px;
    }

    .cart-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .cart-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-title {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .cart-item-price {
        color: #8BC34A;
        font-weight: bold;
    }

    .summary-items {
        margin-top: 20px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        color: #666;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #eee;
        font-weight: bold;
        font-size: 18px;
    }

    .checkout-button {
        width: 100%;
        padding: 15px;
        background-color: #8BC34A;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
        transition: background-color 0.3s;
    }

    .checkout-button:hover {
        background-color: #7cb342;
    }

    /* Footer styles */
    footer {
    background-color: #333;
    color: white;
    padding: 40px 0 20px;
    margin-top: 50px;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        padding: 0 20px;
    }

    .footer-section h3 {
        color: #8BC34A;
        margin-bottom: 20px;
        font-size: 18px;
    }

    .footer-section p, 
    .footer-section a {
        color: #fff;
        text-decoration: none;
        margin: 10px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: color 0.3s;
    }

    .footer-section a:hover {
        color: #8BC34A;
    }

    .footer-section i {
        color: #8BC34A;
        width: 20px;
        text-align: center;
    }

    .footer-section ul {
        list-style: none;
        padding: 0;
    }

    .footer-section ul li {
        margin-bottom: 10px;
    }

    .footer-bottom {
        text-align: center;
        padding-top: 20px;
        margin-top: 30px;
        border-top: 1px solid #444;
        color: #888;
    }

    @media (max-width: 768px) {
        .footer-container {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .footer-section {
            text-align: center;
        }

        .footer-section p, 
        .footer-section a {
            justify-content: center;
        }
    }
    /* Shipping note styles */
    .shipping-note {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
        font-style: italic;
    }

    .original-price {
        text-decoration: line-through;
        color: #999;
        margin-right: 10px;
    }

    .final-price {
        color: #ff4d4d;
        font-weight: bold;
    }

    .price {
        margin: 10px 0;
    }

    .subtotal {
        color: #ff4d4d;
        font-weight: bold;
    }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="top-info">
            <span><i class="fas fa-map-marker-alt"></i>Trà Vinh</span>
            <span><i class="fas fa-envelope"></i>tuyenvt240384@sv-onuni.edu.vn</span>
        </div>
        <div class="top-links">
            <a href="#">Chính sách bảo mật</a> |
            <a href="#">Điều khoản sử dụng</a> |
            <a href="#">Hoàn trả & Đổi trả</a>
        </div>
    </div>

    <header>
        <a href="home.php" class="logo">TVeShop</a>
        <nav>
            <a href="home.php">Trang chủ</a>
            <div class="dropdown">
                <a href="#">Danh Mục</a>
                <ul class="dropdown-menu">
                    <?php
                        $categories = getCategoriesWithCount();
                        while($cat = mysqli_fetch_assoc($categories)) {
                            echo "<li><a href='?category={$cat['danhmucid']}'>{$cat['tendanhmuc']}</a></li>";
                        }
                    ?>
                </ul>
            </div>
            <a href="promotion.php">Khuyến mãi</a>
            <a href="https://www.facebook.com/messages/t/100053572991660">Liên hệ</a>
        </nav>
        
        <div class="header-icons">
            <a href="cart.php">
                <i class="fas fa-shopping-cart"></i> 
                Giỏ hàng
                <?php if(isset($_SESSION['id'])): ?>
                    <span class="cart-count"><?php echo getCartItemCount($_SESSION['id']); ?></span>
                <?php endif; ?>
            </a>
            <?php if(isset($_SESSION['id'])): ?>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-user"></i> <?php echo $_SESSION['ten_dang_nhap']; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="order_history.php"><i class="fas fa-history"></i> Lịch sử</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-user"></i> Đăng nhập</a>
            <?php endif; ?>
        </div>        
    </header>

    <div class="checkout-container">
        <h1 class="page-title">Thanh Toán</h1>
        <div class="checkout-grid">
            <div class="checkout-form">
                <h2 class="form-title">Thông tin giao hàng</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="fullname">Họ và tên</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" required pattern="0[0-9]{9,}" title="Số điện thoại phải bắt đầu bằng số 0 và phải từ 10 số trở lên" minlength="10">
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ giao hàng</label>
                        <input type="text" id="address" name="address" required>
                    </div>

                    <h2 class="form-title">Phương thức thanh toán</h2>
                    <div class="payment-methods">
                        <div class="payment-method selected">
                            <input type="radio" name="payment_method" value="COD" checked>
                            <i class="fas fa-money-bill-wave"></i>
                            <div class="payment-method-title">Thanh toán khi nhận hàng (COD)</div>
                        </div>
                        <div class="payment-method">
                            <input type="radio" name="payment_method" value="MOMO">
                            <i class="fas fa-wallet"></i>
                            <div class="payment-method-title">Ví MoMo</div>
                        </div>
                        <div class="payment-method">
                            <input type="radio" name="payment_method" value="VNPAY">
                            <i class="fas fa-qrcode"></i>
                            <div class="payment-method-title">VNPay</div>
                        </div>
                        <div class="payment-method">
                            <input type="radio" name="payment_method" value="BANK">
                            <i class="fas fa-university"></i>
                            <div class="payment-method-title">Chuyển khoản ngân hàng</div>
                        </div>
                        <div class="payment-method">
                            <input type="radio" name="payment_method" value="CARD">
                            <i class="fas fa-credit-card"></i>
                            <div class="payment-method-title">Thẻ tín dụng/Ghi nợ</div>
                        </div>
                    </div>
                    
                    <button type="submit" name="place_order" class="checkout-button">Đặt hàng</button>
                </form>
            </div>

            <div class="order-summary">
                <h2 class="summary-title">Đơn hàng của bạn</h2>
                <?php if (isset($_SESSION['buy_now']) && isset($product)): ?>
                    <!-- Hiển thị sản phẩm mua ngay -->
                    <div class="product-item">
                        <img src="picture/<?php echo $product['duongdan']; ?>" alt="<?php echo $product['tensanpham']; ?>" style="width: 100px; height: 100px; object-fit: cover;">
                        <div class="product-info">
                            <h3><?php echo $product['tensanpham']; ?></h3>
                            <p>Số lượng: <?php echo $quantity; ?></p>
                            <p>Đơn giá: <?php echo number_format($price, 0, ',', '.'); ?> VNĐ</p>
                        </div>
                    </div>
                <?php elseif (isset($_SESSION['checkout_items'])): ?>
                    <!-- Hiển thị sản phẩm từ giỏ hàng -->
                    <?php foreach ($_SESSION['checkout_items'] as $item): ?>
    <div class="product-item" style="padding-bottom: 10px; border-bottom: 1px solid #ccc; margin-bottom: 10px;">
        <img src="picture/<?php echo $item['duongdan']; ?>" alt="<?php echo $item['tensanpham']; ?>" style="width: 100px; height: 100px; object-fit: cover;">
        <div class="product-info" style="padding-bottom: 10px; margin-bottom: 10px;">
            <h3 style="padding-bottom: 10px; margin-bottom: 10px;"><?php echo $item['tensanpham']; ?></h3>
            <p style="padding-bottom: 10px; margin-bottom: 10px;">Số lượng: <?php echo $item['soluong']; ?></p>
            <p>Đơn giá: <?php echo number_format($item['final_price'], 0, ',', '.'); ?> VNĐ</p>
        </div>
    </div>
    <?php endforeach; ?>
                <?php endif; ?>

                <div class="summary-items">
                    <div class="summary-item">
                        <span>Tạm tính</span>
                        <span><?php echo number_format($subtotal, 0, ',', '.'); ?> VNĐ</span>
                    </div>
                    <div class="summary-item">
                        <span>Phí vận chuyển</span>
                        <span><?php echo $shippingFee > 0 ? number_format($shippingFee, 0, ',', '.') . ' VNĐ' : 'Miễn phí'; ?></span>
                    </div>
                    <div class="summary-item total">
                        <span>Tổng cộng</span>
                        <span><?php echo number_format($total, 0, ',', '.'); ?> VNĐ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Liên hệ</h3>
                <p><i class="fas fa-map-marker-alt"></i>126 Nguyễn Thiện Thành</p>
                <p><i class="fas fa-phone"></i> 0123 456 789</p>
                <p><i class="fas fa-envelope"></i>tuyenvt@gmail.com</p>
            </div>
            
            <div class="footer-section">
                <h3>Liên kết nhanh</h3>
                <ul>
                <li><a href="#"><i class="fas fa-home"></i> Trang chủ</a></li>
                    <li><a href="#"><i class="fas fa-shopping-bag"></i> Sản phẩm</a></li>
                    <li><a href="#"><i class="fas fa-tags"></i> Khuyến mãi</a></li>
                    <li><a href="#"><i class="fas fa-shield-alt"></i> Chính sách bảo hành</a></li>
                </ul>
            </div>
    
            <div class="footer-section">
                <h3>Kết nối với chúng tôi</h3>
                <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
                <a href="#"><i class="fab fa-youtube"></i> YouTube</a>
                <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 TVeShop. Mọi quyền được bảo lưu.</p>
        </div>
    </footer>

    <script>
    // Thêm class selected khi chọn phương thức thanh toán
    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', function() {
            // Bỏ selected khỏi tất cả các phương thức
            document.querySelectorAll('.payment-method').forEach(m => {
                m.classList.remove('selected');
            });
            // Thêm selected vào phương thức được chọn
            this.classList.add('selected');
            // Chọn radio button
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        const fullname = document.querySelector('input[name="fullname"]').value.trim();
        const phone = document.querySelector('input[name="phone"]').value;
        const address = document.querySelector('input[name="address"]').value.trim();
        
        // Kiểm tra tên
        if (fullname.split(' ').filter(word => word.length > 0).length < 2) {
            e.preventDefault();
            alert('Họ tên người nhận phải có ít nhất 2 từ!');
            return;
        }
        
        // Kiểm tra số điện thoại
        if (!/^0\d{9,}$/.test(phone)) {
            e.preventDefault();
            alert('Số điện thoại phải là số, bắt đầu bằng số 0 và phải từ 10 số trở lên!');
            return;
        }
        
        // Kiểm tra địa chỉ
        if (address.length < 10) {
            e.preventDefault();
            alert('Vui lòng nhập địa chỉ giao hàng chi tiết!');
            return;
        }
    });
    </script>
</body>
</html> 