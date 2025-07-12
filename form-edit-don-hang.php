<?php
session_start();
require_once 'connect.php';

// Kiểm tra session
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra có ID đơn hàng được truyền vào không
if (!isset($_GET['id'])) {
    header("Location: table-data-oder.php");
    exit();
}

$order_id = $_GET['id'];

// Lấy thông tin đơn hàng
$sql = "SELECT d.*, n.ten_dang_nhap 
        FROM donhang d 
        JOIN nguoi_dung n ON d.manguoidung = n.id 
        WHERE d.donhangid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// Nếu không tìm thấy đơn hàng
if (!$order) {
    header("Location: table-data-oder.php");
    exit();
}

// Fetch order items - Updated column names to match the database structure
$sql_items = "SELECT c.*, s.tensanpham 
              FROM chitietdonhang c 
              JOIN sanpham s ON c.masanpham = s.sanphamid  -- Changed masanpham to sanphamid
              WHERE c.madonhang = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donhangid = (int)$_POST['donhangid'];
    $sdt = $_POST['sdt'];
    $diachigiao = $_POST['diachigiao'];
    $trangthai = $_POST['trangthai'];

    $sql = "UPDATE donhang 
            SET sdt = ?, 
                diachigiao = ?, 
                trangthai = ? 
            WHERE donhangid = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $sdt, $diachigiao, $trangthai, $donhangid);
    
    if ($stmt->execute()) {
        header("Location: table-data-oder.php?success=1");
    } else {
        header("Location: form-edit-don-hang.php?id=" . $donhangid . "&error=1");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Danh sách đơn hàng | Quản trị Admin</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <!-- Custom Admin CSS -->
  <link rel="stylesheet" type="text/css" href="css/admin-custom.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <!-- or -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

  <!-- Font-icon css-->
  <link rel="stylesheet" type="text/css"
    href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

</head>

<body onload="time()" class="app sidebar-mini rtl">
  <!-- Navbar-->
  <header class="app-header">
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"
      aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
        <!-- User Menu-->
        <li>
            <a class="app-nav__item" href="logout.php">
                <i class='bx bx-log-out bx-rotate-180'></i>
            </a>
        </li>
    </ul>
  </header>
  <!-- Sidebar menu-->
  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
  <aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="/images/hay.jpg" width="50px"
        alt="User Image">
      <div>
<p class="app-sidebar__user-name"><b><?php echo $_SESSION['ten_dang_nhap'];?></b></p>
        <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
      </div>
    </div>
    <hr>
    <ul class="app-menu">

      
      <li><a class="app-menu__item " href="table-data-table.php"><i class='app-menu__icon bx bx-id-card'></i>
          <span class="app-menu__label">Quản lý nhân viên</span></a></li>
      <li><a class="app-menu__item" href="table-data-customers.php"><i class='app-menu__icon bx bx-user-voice'></i><span
            class="app-menu__label">Quản lý khách hàng</span></a></li>
      <li><a class="app-menu__item" href="table-data-product.php"><i
            class='app-menu__icon bx bx-purchase-tag-alt'></i><span class="app-menu__label">Quản lý sản phẩm</span></a>
      </li>
      <li><a class="app-menu__item active" href="table-data-oder.php"><i class='app-menu__icon bx bx-task'></i><span
            class="app-menu__label">Quản lý đơn hàng</span></a></li>
     
      <li><a class="app-menu__item" href="quan-ly-bao-cao.php"><i
            class='app-menu__icon bx bx-pie-chart-alt-2'></i><span class="app-menu__label">Báo cáo doanh thu</span></a>
      </li>
      
    </ul>
  </aside>
    <main class="app-content">
    <div class="tile">
    <h3 class="tile-title">Chỉnh sửa đơn hàng</h3>
    <div class="tile-body">
        <?php if(isset($order)): ?>
        <form class="row" method="POST">
            <input type="hidden" name="donhangid" value="<?php echo $order_id; ?>">
            
            <div class="form-group col-md-4">
                <label class="control-label">ID đơn hàng</label>
                <input class="form-control" type="text" value="DH<?php echo str_pad($order['donhangid'], 5, '0', STR_PAD_LEFT); ?>" readonly>
            </div>

            <div class="form-group col-md-4">
                <label class="control-label">Tên khách hàng</label>
                <input class="form-control" type="text" value="<?php echo htmlspecialchars($order['ten_dang_nhap']); ?>" readonly>
            </div>

            <div class="form-group col-md-4">
                <label class="control-label">Số điện thoại khách hàng</label>
                <input class="form-control" type="text" name="sdt" value="<?php echo htmlspecialchars($order['sdt']); ?>"readonly>
            </div>

            <div class="form-group col-md-4">
                <label class="control-label">Địa chỉ khách hàng</label>
                <input class="form-control" type="text" name="diachigiao" value="<?php echo htmlspecialchars($order['diachigiao']); ?>"readonly>
            </div>

            <div class="form-group col-md-4">
                <label for="trangthai" class="control-label">Tình trạng đơn hàng</label>
                <select class="form-control" id="trangthai" name="trangthai">
                    <?php
                    // Định nghĩa mảng trạng thái theo thứ tự ưu tiên
                    $trangthaiList = [
                        'Chờ xác nhận',
                        'Xác nhận đơn',
                        'Đang vận chuyển',
                        'Hoàn thành',
                        'Đã hủy'
                    ];

                    // Tạo class màu cho từng trạng thái
                    $trangthaiColors = [
                        'Chờ xác nhận' => 'text-warning',
                        'Xác nhận đơn' => 'text-primary',
                        'Đang vận chuyển' => 'text-info',
                        'Hoàn thành' => 'text-success',
                        'Đã hủy' => 'text-danger'
                    ];

                    foreach ($trangthaiList as $trangthai) {
                        $selected = ($order['trangthai'] == $trangthai) ? 'selected' : '';
                        $colorClass = $trangthaiColors[$trangthai];
                        echo "<option value='" . htmlspecialchars($trangthai) . "' class='" . $colorClass . "' " . $selected . ">" . 
                             htmlspecialchars($trangthai) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Chi tiết sản phẩm -->
            <div class="form-group col-md-12">
                <label class="control-label">Danh sách sản phẩm</label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $tongtien = 0;
                        while($item = $items->fetch_assoc()): 
                            $thanhtien = $item['soluong'] * $item['gia'];
                            $tongtien += $thanhtien;
                        ?>
                        <tr>
                            <td>
                                <a href="form-edit-san-pham.php?id=<?php echo $item['masanpham']; ?>" 
                                class="text-primary" 
                                style="text-decoration: none;">
                                    <?php echo htmlspecialchars($item['tensanpham']); ?>
                                </a>
                            </td>
                            <td><?php echo $item['soluong']; ?></td>
                            <td><?php echo number_format($item['gia'], 0, ',', '.') . ' đ'; ?></td>
                            <td><?php echo number_format($thanhtien, 0, ',', '.') . ' đ'; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Tổng tiền:</strong></td>
                            <td><strong><?php echo number_format($tongtien, 0, ',', '.') . ' đ'; ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="form-group col-md-12">
                <button class="btn btn-save" type="submit">Lưu lại</button>
                <a class="btn btn-cancel" href="table-data-oder.php">Hủy bỏ</a>
            </div>
        </form>
        <?php else: ?>
            <div class="alert alert-danger">Không tìm thấy đơn hàng</div>
        <?php endif; ?>
    </div>
</div>
    </main>
   <!-- Essential javascripts for application to work-->
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
  <!-- The javascript plugin to display page loading on top-->
  <script src="js/plugins/pace.min.js"></script>
  <!-- Thêm style để hiển thị màu cho các option -->
  <style>
    .text-warning { color: #ffc107 !important; }
    .text-primary { color: #007bff !important; }
    .text-info { color: #17a2b8 !important; }
    .text-success { color: #28a745 !important; }
    .text-danger { color: #dc3545 !important; }

    /* Tạo hiệu ứng hover cho options */
    #trangthai option:hover {
        background-color: #f8f9fa;
    }
  </style>
  <!-- Thêm script để hiển thị badge với màu tương ứng -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const trangthai = document.getElementById('trangthai');
      
      // Cập nhật màu khi thay đổi trạng thái
      trangthai.addEventListener('change', function() {
          const selectedOption = this.options[this.selectedIndex];
          const colorClass = selectedOption.className;
          this.className = `form-control ${colorClass}`;
      });

      // Set màu ban đầu
      const initialOption = trangthai.options[trangthai.selectedIndex];
      if (initialOption) {
          trangthai.className = `form-control ${initialOption.className}`;
      }
  });
  </script>
  </body>
</html>