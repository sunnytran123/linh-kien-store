<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'connect.php';
require_once 'functions.php';
// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
    header("Location: login.php");
    exit();
}
?>
<style>
    * {
            margin: 0;
            padding: 0;
            font-size: 24px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
.cart-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th, .cart-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    vertical-align: middle; /* Căn giữa theo chiều dọc */
}

.product-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-info img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.price-quantity-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f5f5f5;
    padding: 5px;
    border-radius: 4px;
}

.quantity-btn {
    width: 25px;
    height: 25px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.quantity-btn:hover {
    background: #e0e0e0;
}

.quantity {
    min-width: 30px;
    text-align: center;
    font-size: 14px;
}

.original-price {
    color: #999;
    text-decoration: line-through;
    font-size: 14px;
    display: block;
}

.discounted-price, .normal-price {
    color: #ff4d4d;
    font-weight: bold;
    font-size: 16px;
}

.remove-btn {
    background: none;
    border: none;
    color: #ff4d4d;
    cursor: pointer;
    font-size: 18px;
    padding: 8px;
    transition: color 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.remove-btn:hover {
    color: #ff1a1a;
}

/* Checkbox styles */
input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #8BC34A;
}

.total-label {
    text-align: right;
    font-weight: bold;
    font-size: 16px;
}

.total-amount {
    font-weight: bold;
    color: #ff4d4d;
    font-size: 18px;
}

.cart-actions {
    margin-top: 20px;
    text-align: right;
}

.checkout-btn {
    display: inline-block;
    padding: 12px 25px;
    background-color: #8BC34A;
    color: white;
    text-decoration: none;    
    font-weight: bold;
    border-color: #8BC34A;
    transition: background-color 0.3s;
}

.checkout-btn:hover {
    background-color: #7CB342;
}

/* Responsive */
@media (max-width: 768px) {
    .cart-table th, .cart-table td {
        padding: 10px;
    }

    .product-info img {
        width: 60px;
        height: 60px;
    }

    .quantity-btn {
        width: 25px;
        height: 25px;
    }
}

.fa-trash-alt {
    font-size: 20px;
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

nav a {
    text-decoration: none;
    color: #333;
    margin: 0 15px;
    transition: color 0.3s;
}

nav a:hover {
    color: #8BC34A;
}

nav .dropdown {
    position: relative;
    display: inline-block;
}

nav .dropdown-menu {
    display: none;
    position: absolute;
    background: white;
    min-width: 270px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    border-radius: 4px;
    padding: 8px 0;
    z-index: 1000;
}

nav .dropdown:hover .dropdown-menu {
    display: block;
}

nav .dropdown-menu li {
    list-style: none;
}

nav .dropdown-menu li a {
    text-decoration: none;
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #333;
    white-space: nowrap;
}

nav .dropdown-menu li a i {
    width: 20px;
    text-align: center;
}

nav .dropdown-menu li a:hover {
    background: #f5f5f5;
    color: #8BC34A;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #8BC34A;
    color: white;
    padding: 15px 20px;
    border-radius: 50px 80px 50px 80px;
    max-width: 95%;
    height: 70px;
    margin: 10px auto;
    flex-wrap: nowrap;
}

.top-info {
    display: flex;
    gap: 15px;
}

.contact-info {
    display: flex;
    flex-direction: row;
    gap: 15px;
    align-items: center;
}

.contact-info div {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 24px;
}

.contact-info i {
    color: #FFD700;
    font-size: 24px;
    margin-right: 5px;
}

.top-links {
    display: flex;
    gap: 15px;
    align-items: center;
    white-space: nowrap;
}

.top-links a {
    color: white;
    text-decoration: none;
    transition: color 0.3s;
}

.top-links a:hover {
    color: #FFD700;
}
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    /* Header và Top bar giữ nguyên từ home */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #8BC34A;
        color: white;
        padding: 15px 20px;
        border-radius: 50px 80px 50px 80px;
        max-width: 95%;
        height: 70px;
        margin: 10px auto;
        flex-wrap: nowrap;
    }

    .top-info {
        display: flex;
        gap: 15px;
    }

    .top-info i {
        color: #FFD700;
        margin-right: 5px;
    }

    /* CSS cho container giỏ hàng */
    .cart-container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .cart-title {
        color: #333;
        font-size: 28px;
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid #8BC34A;
    }

    /* CSS cho item trong giỏ hàng */
    .cart-item {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: auto 150px 1fr auto;
        align-items: center;
        gap: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .cart-item:hover {
        transform: translateY(-5px);
    }

    .item-checkbox {
        display: flex;
        align-items: center;
    }

    .item-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #8BC34A;
    }

    .item-image {
        width: 150px;
        height: 150px;
        border-radius: 10px;
        overflow: hidden;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-details {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .item-name {
        font-size: 20px;
        color: #333;
        margin-bottom: 10px;
    }

    .item-price {
        color: #8BC34A;
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .item-controls {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .item-quantity {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quantity-btn {
        background: #8BC34A;
        color: white;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 18px;
        transition: background 0.3s ease;
    }

    .quantity-btn:hover {
        background: #7CB342;
    }

    .quantity-input {
        width: 60px;
        height: 35px;
        text-align: center;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
    }

    .item-remove {
        color: #ff5252;
        cursor: pointer;
        font-size: 20px;
        transition: color 0.3s ease;
        display: flex;
        align-items: center;
    }

    .item-remove:hover {
        color: #ff1744;
    }

    /* CSS cho phần tổng kết */
    .cart-summary {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 15px;
        margin-top: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        font-size: 18px;
    }

    .summary-total {
        font-size: 24px;
        color: #333;
        font-weight: bold;
        border-top: 2px solid #ddd;
        margin-top: 15px;
        padding-top: 15px;
    }

    .checkout-button {
        background: #8BC34A;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 10px;
        font-size: 18px;
        width: 100%;
        margin-top: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: background 0.3s ease;
    }

    .checkout-button:hover {
        background: #7CB342;
    }

    /* CSS cho giỏ hàng trống */
    .empty-cart {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-cart i {
        font-size: 60px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .empty-cart p {
        color: #666;
        font-size: 20px;
        margin-bottom: 20px;
    }

    .shop-now {
        display: inline-block;
        background: #8BC34A;
        color: white;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 18px;
        transition: background 0.3s ease;
    }

    .shop-now:hover {
        background: #7CB342;
    }

    /* CSS cho nút tiếp tục mua sắm */
    .continue-shopping {
        display: inline-flex;
        align-items: center;
        color: #666;
        text-decoration: none;
        margin-top: 30px;
        font-size: 18px;
        transition: color 0.3s ease;
    }

    .continue-shopping i {
        margin-right: 8px;
    }

    .continue-shopping:hover {
        color: #8BC34A;
    }

    /* CSS cho Header */
    header {
        padding: 20px 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
    }

    .logo {
        font-size: 24px;
        font-weight: bold;
        color: #8BC34A;
        text-decoration: none;
    }

    .header-icons {
        display: flex;
        gap: 15px;
    }

    .header-icons i {
        color: #8BC34A;
        margin-right: 5px;
    }

    .header-icons a {
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Dropdown styles */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-toggle {
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        background: white;
        min-width: 270px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: 4px;
        padding: 8px 0;
        z-index: 1000;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    .dropdown-menu li {
        list-style: none;
    }

    .dropdown-menu li a {
        text-decoration: none;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #333;
        white-space: nowrap;
    }

    .dropdown-menu li a i {
        width: 20px;
        text-align: center;
    }

    .dropdown-menu li a:hover {
        background: #f5f5f5;
        color: #8BC34A;
    }

    .dropdown-menu i {
        color: #8BC34A;
    }

    /* Cart count badge */
    .cart-count {
        background: #8BC34A;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 12px;
        margin-left: 5px;
    }

    .checkout-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .checkout-button {
        transition: opacity 0.3s ease;
    }
</style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="cart-container">
    <h1 style="text-align: center; font-size: 30px; font-weight: bold;padding-bottom: 20px;">Giỏ hàng của bạn</h1>
    <table class="cart-table">
        <thead>
            <tr>
                <th width="5%"></th>
                <th width="35%">Sản phẩm</th>
                <th width="20%">Giá</th>
                <th width="15%">Số lượng</th>
                <th width="15%">Tổng</th>
                <th width="10%">Xóa</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT g.*, c.soluong, c.chitietgiohangid, c.masanpham, s.tensanpham, s.gia, h.duongdan 
                FROM giohang g 
                JOIN chitietgiohang c ON g.giohangid = c.magiohang
                JOIN sanpham s ON c.masanpham = s.sanphamid 
                LEFT JOIN hinhanhsanpham h ON s.sanphamid = h.masanpham 
                WHERE g.manguoidung = ?
                GROUP BY c.chitietgiohangid";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        $total = 0; // Sẽ được tính bằng JavaScript
        while ($row = $result->fetch_assoc()) {
            $promotion = getProductPromotion($conn, $row['masanpham']);
            if ($promotion) {
                $discounted_price = calculateDiscountedPrice($row['gia'], $promotion);
                $subtotal = $discounted_price * $row['soluong'];
            } else {
                $subtotal = $row['gia'] * $row['soluong'];
            }
            // Không cộng dồn vào $total nữa
        ?>
            <tr>
                <td>
                    <input type="checkbox" class="product-checkbox" value="<?php echo $row['chitietgiohangid']; ?>">
                </td>
                <td class="product-info">
                    <img src="picture/<?php echo $row['duongdan']; ?>" alt="<?php echo $row['tensanpham']; ?>">
                    <span><?php echo $row['tensanpham']; ?></span>
                </td>
                <td class="price-info">
                    <?php if ($promotion): ?>
                        <span class="original-price"><?php echo number_format($row['gia'], 0, ',', '.'); ?>đ</span>
                        <span class="discounted-price"><?php echo number_format($discounted_price, 0, ',', '.'); ?>đ</span>
                    <?php else: ?>
                        <span class="normal-price"><?php echo number_format($row['gia'], 0, ',', '.'); ?>đ</span>
                    <?php endif; ?>
                </td>
                <td class="quantity-info">
                    <div class="quantity-controls">
                        <button class="quantity-btn minus" onclick="updateQuantity(<?php echo $row['chitietgiohangid']; ?>, 'decrease')">-</button>
                        <span class="quantity"><?php echo $row['soluong']; ?></span>
                        <button class="quantity-btn plus" onclick="updateQuantity(<?php echo $row['chitietgiohangid']; ?>, 'increase')">+</button>
                    </div>
                </td>
                <td class="subtotal"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</td>
                <td>
                    <button onclick="removeFromCart(<?php echo $row['chitietgiohangid']; ?>)" class="remove-btn">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="total-label">Tổng cộng:</td>
                <td colspan="2" class="total-amount">0đ</td>
            </tr>
        </tfoot>
    </table>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="cart-actions">
        <button onclick="processSelectedItems()" class="checkout-btn">Tiến hành thanh toán</button>
</div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
// Thêm function tính tổng khi checkbox thay đổi
function calculateTotal() {
    let total = 0;
    const checkboxes = document.getElementsByClassName('product-checkbox');
    for(let checkbox of checkboxes) {
        if(checkbox.checked) {
            const row = checkbox.closest('tr');
            const subtotalText = row.querySelector('.subtotal').textContent;
            const subtotal = parseInt(subtotalText.replace(/[^\d]/g, '')); // Loại bỏ ký tự không phải số
            total += subtotal;
        }
    }
    document.querySelector('.total-amount').textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
}

// Thêm event listener cho tất cả checkbox
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.getElementsByClassName('product-checkbox');
    for(let checkbox of checkboxes) {
        checkbox.addEventListener('change', calculateTotal);
    }
    // Khởi tạo tổng ban đầu là 0
    document.querySelector('.total-amount').textContent = '0đ';
});

function removeFromCart(id) {
    if(confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        $.ajax({
            url: 'remove_cart_item.php',
            type: 'POST',
            data: {
                id: id
            },
            success: function(response) {
                location.reload();
            }
        });
    }
}
// Thêm vào phần script của cart.php
function processSelectedItems() {
    const checkboxes = document.getElementsByClassName('product-checkbox');
    const selectedItems = [];
    
    for(let checkbox of checkboxes) {
        if(checkbox.checked) {
            selectedItems.push(checkbox.value);
        }
    }
    
    if(selectedItems.length === 0) {
        alert('Vui lòng chọn sản phẩm để thanh toán');
        return;
    }
    
    // Sửa lại phần fetch để sử dụng FormData
    const formData = new FormData();
    formData.append('selected_items', JSON.stringify(selectedItems));
    
    fetch('checkout.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.href = 'checkout.php';
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}
</script>
</body>
</html> 
