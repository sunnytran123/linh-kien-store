<?php
session_start();
include 'connect.php';

// Kiểm tra đăng nhập
// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['id'];

// Lấy thông tin người dùng
$userQuery = "SELECT * FROM nguoi_dung WHERE id = ?";
$stmt = mysqli_prepare($conn, $userQuery);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$userResult = mysqli_stmt_get_result($stmt);
$userInfo = mysqli_fetch_assoc($userResult);

// Xử lý các filter
$timeFilter = isset($_GET['time_filter']) ? $_GET['time_filter'] : 'all';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
$sortFilter = isset($_GET['sort_filter']) ? $_GET['sort_filter'] : 'newest';

// Base query để lấy thông tin đơn hàng
$orderQuery = "SELECT DISTINCT dh.donhangid, dh.ngaydat, dh.trangthai, dh.tongtien, dh.phuongthuctt
               FROM donhang dh
               WHERE dh.manguoidung = ?";

// Thêm điều kiện thời gian
if ($timeFilter !== 'all') {
    switch($timeFilter) {
        case 'today':
            $orderQuery .= " AND DATE(dh.ngaydat) = CURDATE()";
            break;
        case 'week':
            $orderQuery .= " AND dh.ngaydat >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            break;
        case 'month':
            $orderQuery .= " AND dh.ngaydat >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            break;
    }
}

// Thêm điều kiện trạng thái
if ($statusFilter !== 'all') {
    $orderQuery .= " AND dh.trangthai = ?";
}

// Thêm sắp xếp
switch($sortFilter) {
    case 'oldest':
        $orderQuery .= " ORDER BY dh.donhangid ASC";
        break;
    case 'price_high':
        $orderQuery .= " ORDER BY ctdh.gia DESC";
        break;
    case 'price_low':
        $orderQuery .= " ORDER BY ctdh.gia ASC";
        break;
    default: // newest
        $orderQuery .= " ORDER BY dh.donhangid DESC";
}

// Thực thi query để lấy danh sách đơn hàng
$stmt = $conn->prepare($orderQuery);

if ($statusFilter !== 'all') {
    $stmt->bind_param("is", $userId, $statusFilter);
} else {
    $stmt->bind_param("i", $userId);
}

$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<?php
include 'header.php';
?>

<style>
/* --- Toàn bộ CSS riêng cho order_history như ban đầu --- */
.order-history-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
.page-title { font-size: 28px; color: #333; margin-bottom: 30px; text-align: center; }
.order-card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; overflow: hidden; }
.order-header { display: flex; justify-content: space-between; align-items: center; padding: 20px; background: #f9f9f9; border-bottom: 1px solid #eee; }
.order-info { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; width: 100%; text-align: center; }
.order-info p { margin: 0; color: #666; padding: 0 10px; border-right: 1px solid #eee; font-size: 18px; }
.order-info p:last-child { border-right: none; }
.order-info p:last-child span { background-color: #8BC34A; color: white; padding: 5px 10px; border-radius: 4px; display: inline-block; margin-left: 5px; }
.order-status { padding: 8px 15px; border-radius: 20px; font-size: 14px; font-weight: bold; }
.status-pending { background: #FFF3CD; color: #856404; }
.status-confirmed { background: #E3F2FD; color: #1565C0; }
.status-shipping { background: #E8F5E9; color: #2E7D32; }
.status-completed { background: #8BC34A; color: white; }
.status-cancelled { background: #FFEBEE; color: #C62828; }
.order-item { background: #fff; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
.order-header { display: flex; justify-content: space-between; padding: 15px 20px; background: #f8f9fa; border-bottom: 1px solid #eee; }
.order-content { padding: 20px; }
.product-info { display: flex; gap: 20px; align-items: center; padding-bottom: 15px; border-bottom: 1px solid #eee; }
.product-info img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; }
.product-details h3 { margin: 0 0 10px 0; font-size: 16px; color: #333; }
.product-details p { margin: 5px 0; color: #666; font-size: 18px; }
.order-total { text-align: right; padding-top: 15px; font-weight: bold; font-size: 16px; color: #333; }
.product-details .original-price { text-decoration: line-through; color: #999; font-size: 14px; }
.product-details .discount-price { color: #e53935; font-weight: bold; font-size: 14px; }
.order-status { padding: 4px 12px; border-radius: 4px; font-size: 14px; font-weight: 500; }
.status-pending { background-color: #fff3e0; color: #f57c00; }
.status-confirmed { background-color: #e3f2fd; color: #1976d2; }
.status-completed { background-color: #e8f5e9; color: #388e3c; }
.status-cancelled { background-color: #ffebee; color: #d32f2f; }
.order-footer { padding: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
.order-total { display: flex; align-items: center; gap: 10px; }
.total-label { font-size: 16px; color: #666; }
.total-value { font-size: 18px; color: #8BC34A; font-weight: bold; }
.delivery-info { padding: 15px 20px; background: #f9f9f9; border-bottom: 1px solid #eee; }
.delivery-info p { color: #666; margin: 5px 0; }
.delivery-info i { color: #8BC34A; margin-right: 10px; width: 20px; }
@media (max-width: 768px) { .order-info { grid-template-columns: 1fr; gap: 10px; } .order-info p { border-right: none; border-bottom: 1px solid #eee; padding: 10px 0; } .order-info p:last-child { border-bottom: none; } .order-header { flex-direction: column; align-items: flex-start; } .order-status { margin-top: 10px; } .order-item-details { flex-direction: column; align-items: flex-start; } .order-item-quantity { margin-left: 0; margin-top: 10px; } }
.continue-shopping { display: inline-flex; align-items: center; color: #666; text-decoration: none; margin-top: 30px; font-size: 18px; transition: color 0.3s ease; }
.continue-shopping i { margin-right: 8px; }
.continue-shopping:hover { color: #8BC34A; }
.filter-section { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
.filter-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; max-width: 1200px; margin: 0 auto; }
.filter-group { display: flex; flex-direction: column; gap: 8px; }
.filter-label { font-size: 18px; color: #666; font-weight: 500; margin-bottom: 5px; }
.filter-select { padding: 8px; border: 1px solid #ddd; border-radius: 5px; font-size: 18px; color: #333; background-color: #fff; cursor: pointer; outline: none; transition: border-color 0.3s; width: 100%; }
.filter-select option { font-size: 18px; }
.filter-select:hover { border-color: #8BC34A; }
.filter-select:focus { border-color: #8BC34A; box-shadow: 0 0 0 2px rgba(139, 195, 74, 0.2); }
@media (max-width: 768px) { .filter-grid { grid-template-columns: 1fr; gap: 15px; } }
.badge { font-size: 12px; padding: 4px 8px; border-radius: 4px; display: inline-block; }
</style>

<div class="order-history-container">
    <h1 class="page-title">Lịch Sử Đặt Hàng</h1>
    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Thời gian</label>
                    <select class="filter-select" name="time_filter">
                        <option value="all" <?php echo $timeFilter == 'all' ? 'selected' : ''; ?>>Tất cả</option>
                        <option value="today" <?php echo $timeFilter == 'today' ? 'selected' : ''; ?>>Hôm nay</option>
                        <option value="week" <?php echo $timeFilter == 'week' ? 'selected' : ''; ?>>7 ngày qua</option>
                        <option value="month" <?php echo $timeFilter == 'month' ? 'selected' : ''; ?>>30 ngày qua</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Trạng thái</label>
                    <select class="filter-select" name="status">
                        <option value="all" <?php echo $statusFilter == 'all' ? 'selected' : ''; ?>>Tất cả</option>
                        <option value="Chờ xác nhận" <?php echo $statusFilter == 'Chờ xác nhận' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                        <option value="Xác nhận đơn" <?php echo $statusFilter == 'Xác nhận đơn' ? 'selected' : ''; ?>>Xác nhận đơn</option>
                        <option value="Đang vận chuyển" <?php echo $statusFilter == 'Đang vận chuyển' ? 'selected' : ''; ?>>Đang vận chuyển</option>
                        <option value="Hoàn thành" <?php echo $statusFilter == 'Hoàn thành' ? 'selected' : ''; ?>>Hoàn thành</option>
                        <option value="Đã hủy" <?php echo $statusFilter == 'Đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Sắp xếp theo</label>
                    <select class="filter-select" name="sort_filter">
                        <option value="newest" <?php echo $sortFilter == 'newest' ? 'selected' : ''; ?>>Mới nhất</option>
                        <option value="oldest" <?php echo $sortFilter == 'oldest' ? 'selected' : ''; ?>>Cũ nhất</option>

                    </select>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <div class="order-info">
                        <p>Mã đơn hàng: #ORD<?php echo str_pad($order['donhangid'], 6, '0', STR_PAD_LEFT); ?></p>
                        <p>Thời gian: <?php echo date('d/m/Y H:i', strtotime($order['ngaydat'])); ?></p>
                        <p>Thanh toán: <?php echo $order['phuongthuctt']; ?></p>
                        <p>Trạng thái: <span class="badge <?php 
                            switch($order['trangthai']) {
                                case 'Chờ xác nhận':
                                    echo 'bg-warning';
                                    break;
                                case 'Xác nhận đơn':
                                    echo 'bg-primary';
                                    break;
                                case 'Đang vận chuyển':
                                    echo 'bg-info';
                                    break;
                                case 'Hoàn thành':
                                    echo 'bg-success';
                                    break;
                                case 'Đã hủy':
                                    echo 'bg-danger';
                                    break;
                                default:
                                    echo 'bg-secondary';
                            }
                        ?>"><?php echo $order['trangthai']; ?></span></p>
                    </div>
                </div>

                <div class="order-content">
                    <?php
                    // Lấy chi tiết sản phẩm trong đơn hàng
                    $detailQuery = "SELECT ctdh.*, sp.tensanpham, ha.duongdan
                                   FROM chitietdonhang ctdh
                                   JOIN sanpham sp ON ctdh.masanpham = sp.sanphamid
                                   LEFT JOIN hinhanhsanpham ha ON sp.sanphamid = ha.masanpham
                                   WHERE ctdh.madonhang = ?";
                    $detailStmt = $conn->prepare($detailQuery);
                    $detailStmt->bind_param("i", $order['donhangid']);
                    $detailStmt->execute();
                    $products = $detailStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    ?>
                    
                    <?php foreach ($products as $product): ?>
                    <div class="product-info">
                        <img src="picture/<?php echo $product['duongdan']; ?>" alt="<?php echo $product['tensanpham']; ?>">
                        <div class="product-details">
                            <h3><?php echo $product['tensanpham']; ?></h3>
                            <p>Số lượng: <?php echo $product['soluong']; ?></p>
                            <p>Đơn giá: <?php echo number_format($product['gia'], 0, ',', '.'); ?> VNĐ</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-footer">
                    <div class="order-total">
                        <span class="total-label">Tổng tiền:</span>
                        <span class="total-value"><?php echo number_format($order['tongtien'], 0, ',', '.'); ?> VNĐ</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <a href="home.php" class="continue-shopping">
            <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
        </a>
    </div>

<?php include 'footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeFilter = document.querySelector('select[name="time_filter"]');
        const statusFilter = document.querySelector('select[name="status"]');
        const sortFilter = document.querySelector('select[name="sort_filter"]');

        // Khôi phục trạng thái filter từ URL
        const params = new URLSearchParams(window.location.search);
        
        const time = params.get('time_filter');
        if (time) timeFilter.value = time;
        
        const status = params.get('status');
        if (status) statusFilter.value = status;
        
        const sort = params.get('sort_filter');
        if (sort) sortFilter.value = sort;

        // Xử lý sự kiện thay đổi
        function applyFilters() {
            let params = new URLSearchParams();

            if (timeFilter.value !== 'all') params.append('time_filter', timeFilter.value);
            if (statusFilter.value !== 'all') params.append('status', statusFilter.value);
            if (sortFilter.value !== 'newest') params.append('sort_filter', sortFilter.value);

            window.location.href = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        }

        timeFilter.addEventListener('change', applyFilters);
        statusFilter.addEventListener('change', applyFilters);
        sortFilter.addEventListener('change', applyFilters);
    });
    </script>
</body>
</html> 