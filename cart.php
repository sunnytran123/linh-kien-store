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
        max-width: 1350px;
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
        vertical-align: middle;
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
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f5f5f5;
        padding: 5px;
        border-radius: 4px;
    }
    .quantity-btn {
        width: 35px;
        height: 35px;
        background: #8BC34A;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 18px;
        transition: background 0.3s;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .quantity-btn:focus {
        outline: none;
    }
    .quantity {
        min-width: 30px;
        text-align: center;
        font-size: 16px;
        margin: 0;
        padding: 0;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
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
    /* Toast notification styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    .toast {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        padding: 16px 20px;
        margin-bottom: 10px;
        min-width: 300px;
        display: flex;
        align-items: center;
        gap: 12px;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }
    .toast.show {
        transform: translateX(0);
    }
    .toast.success {
        border-left: 4px solid #4CAF50;
    }
    .toast.error {
        border-left: 4px solid #f44336;
    }
    .toast.warning {
        border-left: 4px solid #ff9800;
    }
    .toast.info {
        border-left: 4px solid #2196F3;
    }
    .toast-icon {
        font-size: 20px;
    }
    .toast.success .toast-icon {
        color: #4CAF50;
    }
    .toast.error .toast-icon {
        color: #f44336;
    }
    .toast.warning .toast-icon {
        color: #ff9800;
    }
    .toast.info .toast-icon {
        color: #2196F3;
    }
    .toast-content {
        flex: 1;
    }
    .toast-title {
        font-weight: bold;
        margin-bottom: 4px;
        font-size: 14px;
    }
    .toast-message {
        font-size: 13px;
        color: #666;
    }
    .toast-close {
        background: none;
        border: none;
        cursor: pointer;
        color: #999;
        font-size: 16px;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .toast-close:hover {
        color: #333;
    }
    /* Select all checkbox styles */
    .select-all-container {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .select-all-container label {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
    }
    /* Loading spinner */
    .loading {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #8BC34A;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    /* Disabled state for buttons */
    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .checkout-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<div class="cart-container">
    <h1 style="text-align: center; font-size: 30px; font-weight: bold;padding-bottom: 20px;">Giỏ hàng của bạn</h1>
    
    <?php
    $sql = "SELECT g.*, c.soluong, c.chitietgiohangid, c.masanpham, c.sizeid, c.mausacid, s.tensanpham, s.gia, h.duongdan, sz.kichco, ms.tenmau, ms.mamau 
            FROM giohang g 
            JOIN chitietgiohang c ON g.giohangid = c.magiohang
            JOIN sanpham s ON c.masanpham = s.sanphamid 
            LEFT JOIN hinhanhsanpham h ON s.sanphamid = h.masanpham 
            LEFT JOIN size sz ON c.sizeid = sz.sizeid
            LEFT JOIN mausac ms ON c.mausacid = ms.mausacid
            WHERE g.manguoidung = ?
            GROUP BY c.chitietgiohangid";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0): ?>
        <!-- Select All Container -->
        <div class="select-all-container">
            <input type="checkbox" id="selectAll" style="width: 18px; height: 18px;">
            <label for="selectAll">Sản phẩm</label>
        </div>
        
        <table class="cart-table">
            <thead>
                <tr>
                    <th width="5%"></th>
                    <th width="30%">Sản phẩm</th>
                    <th width="15%">Phân loại</th>
                    <th width="15%">Giá</th>
                    <th width="15%">Số lượng</th>
                    <th width="10%">Tổng</th>
                    <th width="10%">Xóa</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;
            while ($row = $result->fetch_assoc()) {
                $promotion = getProductPromotion($conn, $row['masanpham']);
                if ($promotion) {
                    $discounted_price = calculateDiscountedPrice($row['gia'], $promotion);
                    $subtotal = $discounted_price * $row['soluong'];
                } else {
                    $subtotal = $row['gia'] * $row['soluong'];
                }
            ?>
                <tr>
                    <td>
                        <input type="checkbox" class="product-checkbox" value="<?php echo $row['chitietgiohangid']; ?>">
                    </td>
                    <td class="product-info">
                        <img src="picture/<?php echo $row['duongdan']; ?>" alt="<?php echo $row['tensanpham']; ?>">
                        <span><?php echo $row['tensanpham']; ?></span>
                    </td>
                    <td class="variant-info">
                        <span class="variant-badge" style="background:<?php echo $row['mamau']; ?>;color:#333;padding:2px 8px;border-radius:6px;display:inline-block;min-width:40px;">
                            <?php echo $row['tenmau'] ? htmlspecialchars($row['tenmau']) : 'Chưa chọn'; ?>
                        </span>
                        <span class="variant-badge" style="background:#eee;color:#333;padding:2px 8px;border-radius:6px;display:inline-block;min-width:30px;margin-left:6px;">
                            <?php echo $row['kichco'] ? htmlspecialchars($row['kichco']) : 'Chưa chọn'; ?>
                        </span>
                        <button class="edit-variant-btn" style="margin-left:8px;cursor:pointer;background:none;border:none;color:#8BC34A;font-size:16px;" onclick="openEditVariantModal(<?php echo $row['chitietgiohangid']; ?>)"><i class="fas fa-edit"></i></button>
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
                            <button class="quantity-btn minus" onclick="updateQuantity(<?php echo $row['chitietgiohangid']; ?>, 'decrease')" data-cart-id="<?php echo $row['chitietgiohangid']; ?>">-</button>
                            <span class="quantity" id="quantity-<?php echo $row['chitietgiohangid']; ?>"><?php echo $row['soluong']; ?></span>
                            <button class="quantity-btn plus" onclick="updateQuantity(<?php echo $row['chitietgiohangid']; ?>, 'increase')" data-cart-id="<?php echo $row['chitietgiohangid']; ?>">+</button>
                        </div>
                    </td>
                    <td class="subtotal" id="subtotal-<?php echo $row['chitietgiohangid']; ?>"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</td>
                    <td>
                        <button onclick="removeFromCart(<?php echo $row['chitietgiohangid']; ?>)" class="remove-btn" data-cart-id="<?php echo $row['chitietgiohangid']; ?>">
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
        
        <div class="cart-actions">
            <button onclick="processSelectedItems()" class="checkout-btn" id="checkoutBtn" disabled>
                <span class="btn-text">Tiến hành thanh toán</span>
                <span class="loading" style="display: none;"></span>
            </button>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 50px 20px;">
            <i class="fas fa-shopping-cart" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
            <p style="color: #666; font-size: 20px; margin-bottom: 20px;">Giỏ hàng của bạn đang trống</p>
            <a href="home.php" style="display: inline-block; background: #8BC34A; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-size: 18px;">Tiếp tục mua sắm</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
// Toast notification function
function showToast(type, title, message, duration = 3000) {
    const toastContainer = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const iconMap = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-circle',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    };
    
    toast.innerHTML = `
        <i class="toast-icon ${iconMap[type]}"></i>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Show animation
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Auto remove
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Select all functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const productCheckboxes = document.getElementsByClassName('product-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            for (let checkbox of productCheckboxes) {
                checkbox.checked = isChecked;
            }
            calculateTotal();
        });
    }
    
    // Individual checkbox change
    for (let checkbox of productCheckboxes) {
        checkbox.addEventListener('change', function() {
            calculateTotal();
            updateSelectAllState();
        });
    }
    
    // Initialize total
    calculateTotal();
});

function updateSelectAllState() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const productCheckboxes = document.getElementsByClassName('product-checkbox');
    const checkedBoxes = Array.from(productCheckboxes).filter(cb => cb.checked);
    
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = checkedBoxes.length === productCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < productCheckboxes.length;
    }
}

function calculateTotal() {
    let total = 0;
    const checkboxes = document.getElementsByClassName('product-checkbox');
    for(let checkbox of checkboxes) {
        if(checkbox.checked) {
            const row = checkbox.closest('tr');
            const subtotalText = row.querySelector('.subtotal').textContent;
            const subtotal = parseInt(subtotalText.replace(/[^\d]/g, ''));
            total += subtotal;
        }
    }
    document.querySelector('.total-amount').textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    
    // Enable/disable checkout button
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.disabled = total === 0;
    }
}

function updateQuantity(cartItemId, action) {
    const quantityElement = document.getElementById(`quantity-${cartItemId}`);
    const currentQuantity = parseInt(quantityElement.textContent);
    let newQuantity = currentQuantity;
    
    if (action === 'increase') {
        newQuantity = currentQuantity + 1;
    } else if (action === 'decrease') {
        newQuantity = Math.max(1, currentQuantity - 1);
    }
    
    if (newQuantity === currentQuantity) return;
    
    // Disable buttons during update
    const buttons = document.querySelectorAll(`[data-cart-id="${cartItemId}"]`);
    buttons.forEach(btn => btn.disabled = true);
    
    $.ajax({
        url: 'update_cart.php',
        type: 'POST',
        data: {
            cart_item_id: cartItemId,
            quantity: newQuantity
        },
        success: function(response) {
            if (response.success) {
                quantityElement.textContent = response.new_quantity;
                document.getElementById(`subtotal-${cartItemId}`).textContent = response.new_subtotal + 'đ';
                calculateTotal();
                showToast('success', 'Thành công', response.message);
            } else {
                showToast('error', 'Lỗi', response.message);
            }
        },
        error: function() {
            showToast('error', 'Lỗi', 'Có lỗi xảy ra khi cập nhật số lượng');
        },
        complete: function() {
            // Re-enable buttons
            buttons.forEach(btn => btn.disabled = false);
        }
    });
}

function removeFromCart(cartItemId) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        const button = document.querySelector(`[data-cart-id="${cartItemId}"]`);
        button.disabled = true;
        
        $.ajax({
            url: 'remove_cart_item.php',
            type: 'POST',
            data: {
                cart_item_id: cartItemId
            },
            success: function(response) {
                if (response.success) {
                    const row = button.closest('tr');
                    row.style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => {
                        row.remove();
                        calculateTotal();
                        updateSelectAllState();
                        
                        // Check if cart is empty
                        const remainingItems = document.querySelectorAll('.product-checkbox');
                        if (remainingItems.length === 0) {
                            location.reload(); // Reload to show empty cart message
                        }
                    }, 300);
                    
                    showToast('success', 'Thành công', response.message);
                } else {
                    showToast('error', 'Lỗi', response.message);
                }
            },
            error: function() {
                showToast('error', 'Lỗi', 'Có lỗi xảy ra khi xóa sản phẩm');
            },
            complete: function() {
                button.disabled = false;
            }
        });
    }
}

function processSelectedItems() {
    const checkboxes = document.getElementsByClassName('product-checkbox');
    const selectedItems = [];
    
    for(let checkbox of checkboxes) {
        if(checkbox.checked) {
            selectedItems.push(checkbox.value);
        }
    }
    
    if(selectedItems.length === 0) {
        showToast('warning', 'Cảnh báo', 'Vui lòng chọn sản phẩm để thanh toán');
        return;
    }
    
    const checkoutBtn = document.getElementById('checkoutBtn');
    const btnText = checkoutBtn.querySelector('.btn-text');
    const loading = checkoutBtn.querySelector('.loading');
    
    // Show loading state
    btnText.style.display = 'none';
    loading.style.display = 'inline-block';
    checkoutBtn.disabled = true;
    
    const formData = new FormData();
    formData.append('selected_items', JSON.stringify(selectedItems));
    
    fetch('checkout.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showToast('success', 'Thành công', 'Chuyển hướng đến trang thanh toán...');
            setTimeout(() => {
                window.location.href = 'checkout.php';
            }, 1000);
        } else {
            showToast('error', 'Lỗi', data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Lỗi', 'Có lỗi xảy ra khi xử lý thanh toán');
    })
    .finally(() => {
        // Reset button state
        btnText.style.display = 'inline';
        loading.style.display = 'none';
        checkoutBtn.disabled = false;
    });
}

// Add fadeOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(-100%); }
    }
`;
document.head.appendChild(style);
</script>
</body>
</html> 
