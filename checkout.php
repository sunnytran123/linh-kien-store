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
        echo '<div style="color:red;text-align:center;font-size:18px;margin-bottom:10px;">'.$error.'</div>';
    } else {
        $conn->begin_transaction();
        try {
            // Tạo một đơn hàng mới
            $stmt = $conn->prepare("INSERT INTO donhang (manguoidung, ten_nguoi_dat, ngaydat, trangthai, tongtien, diachigiao, sdt, phuongthuctt) 
                                   VALUES (?, ?, NOW(), 'Chờ xác nhận', ?, ?, ?, ?)");
            $stmt->bind_param("isdsss", $userId, $fullname, $total, $address, $phone, $paymentMethod);
            $stmt->execute();
            $orderId = $conn->insert_id;

            // Chuẩn bị statement cho chi tiết đơn hàng (thêm sizeid)
            $stmt = $conn->prepare("INSERT INTO chitietdonhang (madonhang, masanpham, sizeid, soluong, gia) VALUES (?, ?, ?, ?, ?)");

            if (isset($_SESSION['buy_now'])) {
                // Thêm sản phẩm mua ngay vào đơn hàng
                $sizeid = isset($buyNowInfo['sizeid']) ? $buyNowInfo['sizeid'] : 0;
                $stmt->bind_param("iiiid", $orderId, $buyNowInfo['product_id'], $sizeid, $quantity, $price);
                $stmt->execute();
                unset($_SESSION['buy_now']);
            } elseif (isset($_SESSION['checkout_items'])) {
                // Thêm tất cả sản phẩm từ giỏ hàng vào cùng một đơn hàng
                foreach ($_SESSION['checkout_items'] as $item) {
                    $sizeid = isset($item['sizeid']) ? $item['sizeid'] : 0;
                    $stmt->bind_param("iiiid", $orderId, $item['masanpham'], $sizeid, $item['soluong'], $item['final_price']);
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
            header("Location: order_history.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo '<div style="color:red;text-align:center;font-size:18px;margin-bottom:10px;">Có lỗi xảy ra: '.htmlspecialchars($e->getMessage()).'</div>';
        }
    }
}
?>

<?php
include 'header.php';
?>

<style>
/* --- Toàn bộ CSS checkout như ban đầu --- */
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
.product-item {
    padding-bottom: 10px;
    border-bottom: 1px solid #ccc;
    margin-bottom: 10px;
}
.product-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
}
.product-info {
    padding-bottom: 10px;
    margin-bottom: 10px;
}
.product-info h3 {
    padding-bottom: 10px;
    margin-bottom: 10px;
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
.summary-item.total {
    font-weight: bold;
    font-size: 18px;
    border-top: 2px solid #eee;
    margin-top: 20px;
    padding-top: 20px;
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
@media (max-width: 768px) {
    .checkout-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .checkout-container {
        padding: 0 5px;
    }
}
/* --- Order summary chuyên nghiệp hơn --- */
.order-summary {
    background: #fff;
    padding: 32px 28px;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    border: 1px solid #e0e0e0;
    position: sticky;
    top: 20px;
    min-width: 340px;
}
.product-item {
    display: flex;
    align-items: flex-start;
    gap: 18px;
    padding: 18px 0 12px 0;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 10px;
}
.product-item img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #eee;
}
.product-info {
    flex: 1;
    min-width: 0;
}
.product-info h3 {
    font-size: 17px;
    color: #222;
    margin: 0 0 6px 0;
    font-weight: 600;
}
.product-info p {
    margin: 0 0 4px 0;
    font-size: 15px;
    color: #555;
}
.summary-items {
    margin-top: 24px;
}
.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    color: #444;
    font-size: 16px;
}
.summary-item.total {
    font-weight: bold;
    font-size: 20px;
    border-top: 2px solid #eee;
    margin-top: 24px;
    padding-top: 18px;
    color: #2e7d32;
}
</style>

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
                    <label for="province">Tỉnh/Thành phố</label>
                    <select id="province" name="province" required style="width:100%;padding:12px;border:1px solid #ddd;border-radius:5px;font-size:16px;"></select>
                </div>
                <div class="form-group">
                    <label for="district">Quận/Huyện</label>
                    <select id="district" name="district" required style="width:100%;padding:12px;border:1px solid #ddd;border-radius:5px;font-size:16px;"></select>
                </div>
                <div class="form-group">
                    <label for="ward">Phường/Xã</label>
                    <select id="ward" name="ward" required style="width:100%;padding:12px;border:1px solid #ddd;border-radius:5px;font-size:16px;"></select>
                </div>
                <div class="form-group">
                    <label for="address_detail">Địa chỉ chi tiết (số nhà, tên đường...)</label>
                    <input type="text" id="address_detail" name="address_detail" required>
                </div>
                <input type="hidden" id="address" name="address">

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
                <?php
                    // Lấy thông tin màu và size nếu có
                    $colorName = '';
                    $sizeName = '';
                    if (isset($buyNowInfo['colorid']) && $buyNowInfo['colorid']) {
                        $stmt = $conn->prepare("SELECT tenmau FROM mausac WHERE mausacid = ?");
                        $stmt->bind_param("i", $buyNowInfo['colorid']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $colorName = $row['tenmau'];
                        }
                    }
                    if (isset($buyNowInfo['sizeid']) && $buyNowInfo['sizeid']) {
                        $stmt = $conn->prepare("SELECT kichco FROM size WHERE sizeid = ?");
                        $stmt->bind_param("i", $buyNowInfo['sizeid']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $sizeName = $row['kichco'];
                        }
                    }
                ?>
                <div class="product-item">
                    <img src="picture/<?php echo $product['duongdan']; ?>" alt="<?php echo $product['tensanpham']; ?>" style="width: 100px; height: 100px; object-fit: cover;">
                    <div class="product-info">
                        <h3><?php echo $product['tensanpham']; ?></h3>
                        <?php if ($colorName): ?><p style="font-size: 16px;">Màu sắc: <?php echo htmlspecialchars($colorName); ?></p><?php endif; ?>
                        <?php if ($sizeName): ?><p style="font-size: 16px;">Size: <?php echo htmlspecialchars($sizeName); ?></p><?php endif; ?>
                        <p style="font-size: 16px;">Số lượng: <?php echo $quantity; ?></p>
                        <p style="font-size: 16px;">Đơn giá: <?php echo number_format($price, 0, ',', '.'); ?> VNĐ</p>
                    </div>
                </div>
            <?php elseif (isset($_SESSION['checkout_items'])): ?>
                <!-- Hiển thị sản phẩm từ giỏ hàng -->
                <?php foreach ($_SESSION['checkout_items'] as $item): ?>
                    <?php
                        // Lấy tên màu sắc
                        $colorName = '';
                        if (isset($item['mausacid']) && $item['mausacid']) {
                            $stmt = $conn->prepare("SELECT tenmau FROM mausac WHERE mausacid = ?");
                            $stmt->bind_param("i", $item['mausacid']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($row = $result->fetch_assoc()) {
                                $colorName = $row['tenmau'];
                            }
                        }
                        // Lấy tên size
                        $sizeName = '';
                        if (isset($item['sizeid']) && $item['sizeid']) {
                            $stmt = $conn->prepare("SELECT kichco FROM size WHERE sizeid = ?");
                            $stmt->bind_param("i", $item['sizeid']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($row = $result->fetch_assoc()) {
                                $sizeName = $row['kichco'];
                            }
                        }
                    ?>
                    <div class="product-item" style="padding-bottom: 10px; border-bottom: 1px solid #ccc; margin-bottom: 10px;">
                        <img src="picture/<?php echo $item['duongdan']; ?>" alt="<?php echo $item['tensanpham']; ?>" style="width: 100px; height: 100px; object-fit: cover;">
                        <div class="product-info" style="padding-bottom: 10px; margin-bottom: 10px;">
                            <h3 style="padding-bottom: 10px; margin-bottom: 10px;"><?php echo $item['tensanpham']; ?></h3>
                            <?php if ($colorName): ?><p style="font-size: 16px;">Màu sắc: <?php echo htmlspecialchars($colorName); ?></p><?php endif; ?>
                            <?php if ($sizeName): ?><p style="font-size: 16px;">Size: <?php echo htmlspecialchars($sizeName); ?></p><?php endif; ?>
                            <p style="font-size: 16px;">Số lượng: <?php echo $item['soluong']; ?></p>
                            <p style="font-size: 16px;">Đơn giá: <?php echo number_format($item['final_price'], 0, ',', '.'); ?> VNĐ</p>
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

<script>
// OAI API lấy địa chỉ
const provinceSelect = document.getElementById('province');
const districtSelect = document.getElementById('district');
const wardSelect = document.getElementById('ward');

// Load tỉnh/thành
fetch('https://provinces.open-api.vn/api/p/').then(res => res.json()).then(data => {
    provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành</option>' + data.map(p => `<option value="${p.code}" data-name="${p.name}">${p.name}</option>`).join('');
});

provinceSelect.addEventListener('change', function() {
    const provinceCode = this.value;
    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
    if (!provinceCode) return;
    fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`).then(res => res.json()).then(data => {
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>' + data.districts.map(d => `<option value="${d.code}" data-name="${d.name}">${d.name}</option>`).join('');
    });
});

districtSelect.addEventListener('change', function() {
    const districtCode = this.value;
    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
    if (!districtCode) return;
    fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`).then(res => res.json()).then(data => {
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>' + data.wards.map(w => `<option value="${w.code}" data-name="${w.name}">${w.name}</option>`).join('');
    });
});

// Khi submit form, ghép địa chỉ đầy đủ
const checkoutForm = document.querySelector('form');
checkoutForm.addEventListener('submit', function(e) {
    const fullname = document.querySelector('input[name="fullname"]').value.trim();
    const phone = document.querySelector('input[name="phone"]').value;
    
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
    
    const provinceName = provinceSelect.options[provinceSelect.selectedIndex]?.getAttribute('data-name') || '';
    const districtName = districtSelect.options[districtSelect.selectedIndex]?.getAttribute('data-name') || '';
    const wardName = wardSelect.options[wardSelect.selectedIndex]?.getAttribute('data-name') || '';
    const detail = document.getElementById('address_detail').value.trim();
    if (!provinceName || !districtName || !wardName || !detail) {
        e.preventDefault();
        alert('Vui lòng nhập đầy đủ địa chỉ giao hàng!');
        return;
    }
    document.getElementById('address').value = `${detail}, ${wardName}, ${districtName}, ${provinceName}`;
});
</script>
<?php include 'footer.php'; ?> 