<?php
session_start();
require_once 'connect.php';
require_once 'functions.php';
// Lấy ID sản phẩm từ URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin sản phẩm sử dụng hàm từ connect.php
$product = getProductDetail($productId);

// Nếu không tìm thấy sản phẩm, chuyển hướng về trang chủ
if (!$product) {
    header('Location: home.php');
    exit();
}

// Lấy tổng tồn kho từ bảng sanpham_size
$sql = "SELECT SUM(soluong) as tonkho FROM sanpham_size WHERE sanphamid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$product['tonkho'] = $row['tonkho'] ?? 0;

// Lấy tất cả ảnh sản phẩm
$images = [];
$sql = "SELECT duongdan FROM hinhanhsanpham WHERE masanpham = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $images[] = $row['duongdan'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm - Phụ Kiện Giá Rẻ</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-size: 24px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        .top-bar {
            display: flex;
            justify-content: center; /* căn giữa theo chiều ngang */
            align-items: center;     /* căn giữa theo chiều dọc */
            background-color: #8BC34A;
            color: white;
            padding: 15px 20px;
            border-radius: 50px 80px 50px 80px;
            max-width:95%;
            height: 70px;
            margin: 10px auto;
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


        header {
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .header-icons {
            display: flex;
            gap: 15px;
        }
        .header-icons i {
            color: #8BC34A; /* Màu vàng */
            margin-right: 5px;
        }
        .header-icons a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #8BC34A;
            text-decoration: none;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            padding: 15px 50px;
            background: #f5f5f5;
            gap: 8px;
            font-size: 16px;
        }
        .breadcrumb a {
            color: #8BC34A;
            text-decoration: none;
            font-size: 16px;
        }
        .breadcrumb span {
            color: #666;
            font-size: 16px;
        }
        .breadcrumb-separator {
            color: #bbb;
            font-size: 18px;
            margin: 0 4px;
            display: flex;
            align-items: center;
            user-select: none;
        }

        .product-detail {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            display: flex;
            gap: 40px;
        }

        .product-gallery {
            flex: 1;
            /* max-width: 420px; */
        }
        #productCarousel {
            width: 100%;
            height: 550px;
            border-radius: 8px;
            overflow: hidden;
            background: #f5f5f5;
            margin-bottom: 20px;
        }
        #productCarousel .carousel-inner {
            width: 100%;
            height: 550px;
        }
        #productCarousel img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .thumbnail-images {
            display: flex;
            gap: 10px;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            overflow: hidden;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail:hover {
            border-color: #8BC34A;
        }

        .product-info {
            flex: 1;
        }

        .product-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
        }

        .product-code {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .product-price {
            font-size: 32px;
            color: #8BC34A;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .stock-status {
            color: #4CAF50;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .product-actions {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-selector button {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .quantity-selector input {
            width: 50px;
            height: 30px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .button-group {
            display: flex;
            gap: 15px;
        }

        .add-to-cart-btn, .buy-now-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn {
            background-color: #8BC34A;
            color: white;
        }

        .buy-now-btn {
            background-color: #ff4d4d;
            color: white;
        }

        .add-to-cart-btn:hover {
            background-color: #7cb342;
        }

        .buy-now-btn:hover {
            background-color: #ff3333;
        }

        .add-to-cart-btn:disabled, .buy-now-btn:disabled {
            background-color: #cccccc !important;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .stock-status {
            margin: 15px 0;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .product-price {
            font-size: 24px;
            color: #8BC34A;
            font-weight: bold;
            margin: 10px 0;
        }

        .product-code {
            color: #666;
            margin: 5px 0;
        }

        .product-description {
            margin: 15px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
            white-space: pre-line;
            line-height: 0.8;
            font-size: 40px;
        }

        .specs-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .specs-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            line-height: 1.6;
        }

        .specs-list li:last-child {
            border-bottom: none; 
        }

        .specs-list strong {
            color: #333;
            margin-right: 10px;
            min-width: 150px;
            display: inline-block;
        }

        .description-tabs {
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            padding-bottom: 20px;
        }

        .tab-button {
            padding: 10px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 24px;
            font-weight: bold;
            color: #666;
            margin-bottom: 10px;
        }

        .tab-button.active {
            color: #8BC34A;
            border-bottom: 2px solid #8BC34A;
        }

        .tab-content {
            font-size: 16px;
            line-height: 1.6;
            color: #666;
        }

        .shipping-info {
            margin-top: 20px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .shipping-info h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }

        .shipping-info p {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .product-features {
            margin-top: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 16px;
            color: #666;
        }

        .feature-item i {
            color: #8BC34A;
            margin-right: 10px;
        }
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

        button:disabled {
            background-color: #cccccc !important;
            cursor: not-allowed;
            opacity: 0.7;
        }

        input:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            background-color: #fff;
            min-width: 200px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            border-radius: 4px;
            padding: 8px;
            z-index: 1000;
        }

        .dropdown-menu li {
            list-style: none;
        }

        .dropdown-menu li a {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s;
        }

        .dropdown-menu li a:hover {
            background-color: #f5f5f5;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu i {
            margin-right: 8px;
            color: #8BC34A;
        }

        .cart-count {
            background-color: #8BC34A;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: relative;
            top: -10px;
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
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

        /* CSS cho thông báo khi hết hàng */
        .out-of-stock {
            color: #f44336;
            font-size: 16px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .out-of-stock i {
            font-size: 18px;
        }

        .price-container {
            margin: 10px 0;
        }

        .price-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .original-price {
            color: #999;
            text-decoration: line-through;
            font-size: 14px;
        }

        .discounted-price, .normal-price {
            color: #ff4d4d;
            font-size: 18px;
            font-weight: bold;
        }

        .discount-badge {
            background: #ff4d4d;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 12px;
            display: inline-block;
            margin-left: 5px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            margin: 0 15px;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #8BC34A;
        }

        nav .dropdown-menu a {
            text-decoration: none;
            padding: 8px 16px;
            display: block;
            color: #333;
            margin: 0;
        }

        nav .dropdown-menu a:hover {
            background: #f5f5f5;
            color: #8BC34A;
        }
    </style>
</head>
<body>
<div class="top-bar">
        <div class="top-info">
            <span><i class="fas fa-map-marker-alt"></i>Cà Mau</span>
            <span><i class="fas fa-envelope"></i>phuongthuy091203@gmail.com</span>
        </div>
        <!-- <div class="top-links">
            <a href="#">Chính sách bảo mật</a> |
            <a href="#">Điều khoản sử dụng</a> |
            <a href="#">Hoàn trả & Đổi trả</a>
        </div> -->
    </div>

    <header>
        <a href="home.php" class="logo">Sunny Store</a>
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

    <div class="breadcrumb">
        <a href="home.php">Trang chủ</a>
        <span class="breadcrumb-separator">&gt;</span>
        <a href="?category=<?php echo $product['madanhmuc']; ?>"><?php echo $product['tendanhmuc']; ?></a>
        <span class="breadcrumb-separator">&gt;</span>
        <span><?php echo $product['tensanpham']; ?></span>
    </div>

    <div class="product-detail">
        <div class="product-gallery">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $idx => $img): ?>
                        <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                            <img src="picture/<?php echo $img; ?>" class="d-block w-100" alt="Ảnh sản phẩm">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($images) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-info">
            <h1 class="product-title"><?php echo $product['tensanpham']; ?></h1>
            <div class="product-code">Mã sản phẩm: <?php echo $product['sanphamid']; ?></div>
            <div class="price-container">
                <?php 
                $promotion = getProductPromotion($conn, $product['sanphamid']);
                if ($promotion): 
                    $discounted_price = calculateDiscountedPrice($product['gia'], $promotion);
                ?>
                    <div class="price-wrapper">
                        <span class="original-price"><?php echo number_format($product['gia'], 0, ',', '.'); ?>đ</span>
                        <span class="discounted-price"><?php echo number_format($discounted_price, 0, ',', '.'); ?>đ</span>
                    </div>
                    <span class="discount-badge">-<?php echo $promotion['giatrigiamgia']; ?>%</span>
                <?php else: ?>
                    <span class="normal-price"><?php echo number_format($product['gia'], 0, ',', '.'); ?>đ</span>
                <?php endif; ?>
            </div>
            
            <div class="stock-status">
                <?php if ($product['tonkho'] > 0): ?>
                    <i class="fas fa-check-circle" style="color: #4CAF50;"></i> Còn hàng
                <?php else: ?>
                    <i class="fas fa-times-circle" style="color: #f44336;"></i> Hết hàng
                <?php endif; ?>
            </div>

            <div class="product-actions">
                <div class="quantity-selector">
                    <button onclick="decreaseQuantity()" <?php echo $product['tonkho'] <= 0 ? 'disabled' : ''; ?>>-</button>
                    <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['tonkho']; ?>" 
                           <?php echo $product['tonkho'] <= 0 ? 'disabled' : ''; ?>>
                    <button onclick="increaseQuantity()" <?php echo $product['tonkho'] <= 0 ? 'disabled' : ''; ?>>+</button>
                </div>

                <div class="button-group">
                    <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product['sanphamid']; ?>)" 
                            <?php echo $product['tonkho'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>

                    <button class="buy-now-btn" onclick="buyNow(<?php echo $product['sanphamid']; ?>)"
                            <?php echo $product['tonkho'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-bolt"></i> Mua ngay
                    </button>
                </div>
            </div>

            <div class="product-features">
                <div class="feature-item">
                    <i class="fas fa-check"></i> Bảo hành chính hãng 12 tháng
                </div>
                <div class="feature-item">
                    <i class="fas fa-truck"></i> Giao hàng toàn quốc
                </div>
                <div class="feature-item">
                    <i class="fas fa-undo"></i> Đổi trả trong 7 ngày
                </div>
            </div>

            <div class="shipping-info">
                <h3>Thông tin vận chuyển</h3>
                <p>- Giao hàng nhanh trong 2h tại TP.HCM</p>
                <p>- Giao hàng tiêu chuẩn 2-3 ngày toàn quốc</p>
                <p>- Miễn phí giao hàng cho đơn từ 500.000đ</p>
            </div>
        </div>
    </div>

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="product-description">
            <div class="description-tabs">
                 <button class="tab-button active">Mô tả sản phẩm</button>
                <h5 style="margin-top: 10px;"><?php echo $product['tensanpham']; ?></h5>
                <?php 
                echo nl2br($product['mota']); 
                ?>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Liên hệ</h3>
                <p><i class="fas fa-map-marker-alt"></i> 126 Nguyễn Thiện Thành</p>
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
            <p>&copy; 2025 Phụ Kiện Giá Rẻ. Mọi quyền được bảo lưu.</p>
        </div>
    </footer>

    <script>
        function decreaseQuantity() {
            var input = document.getElementById('quantity');
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function increaseQuantity() {
            var input = document.getElementById('quantity');
            var max = parseInt(input.getAttribute('max'));
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function addToCart(productId) {
            const quantity = parseInt(document.getElementById('quantity').value);
            
            if (isNaN(quantity) || quantity <= 0) {
                alert('Vui lòng nhập số lượng hợp lệ');
                return;
            }
            
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã thêm sản phẩm vào giỏ hàng');
                    window.location.href = 'cart.php';
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi kết nối với server');
            });
        }

        function buyNow(productId) {
            const quantity = parseInt(document.getElementById('quantity').value);
            
            if (isNaN(quantity) || quantity <= 0) {
                alert('Vui lòng nhập số lượng hợp lệ');
                return;
            }
            
            // Gửi request đến buy_now.php
            fetch('buy_now.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Chuyển hướng đến trang thanh toán
                    window.location.href = 'checkout.php?buy_now=1';
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi kết nối với server');
            });
        }
    </script>
</body>
</html>