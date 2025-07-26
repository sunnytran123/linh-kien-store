<?php
session_start();
require_once 'connect.php';
// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
    header("Location: login.php");
    exit();
}
// Lấy tổng số nhân viên
$sql_nhanvien = "SELECT COUNT(*) as total FROM nguoi_dung WHERE loai = '2'";
$result_nhanvien = $conn->query($sql_nhanvien);
$total_nhanvien = $result_nhanvien->fetch_assoc()['total'];

// Lấy tổng số sản phẩm và số sản phẩm hết hàng
$sql_sanpham = "
    SELECT 
        COUNT(*) as total, 
        SUM(CASE WHEN IFNULL(t.tonkho,0) = 0 THEN 1 ELSE 0 END) as hethang
    FROM sanpham sp
    LEFT JOIN (
        SELECT sanphamid, SUM(soluong) as tonkho
        FROM sanpham_size
        GROUP BY sanphamid
    ) t ON sp.sanphamid = t.sanphamid
";
$result_sanpham = $conn->query($sql_sanpham);
$sanpham_stats = $result_sanpham->fetch_assoc();

// Lấy thông tin đơn hàng
$sql_donhang = "SELECT 
    COUNT(*) as total_donhang,
    SUM(CASE WHEN trangthai = 'Đã hủy' THEN 1 ELSE 0 END) as donhang_huy,
    SUM(tongtien) as tong_doanhthu
FROM donhang";
$result_donhang = $conn->query($sql_donhang);
$donhang_stats = $result_donhang->fetch_assoc();

// Lấy sản phẩm bán chạy (từ bảng chitietgiohang)
$sql_banchay = "SELECT 
    sp.sanphamid, 
    sp.tensanpham, 
    sp.gia, 
    dm.tendanhmuc,
    SUM(ct.soluong) as total_sold
FROM sanpham sp

LEFT JOIN danhmuc dm ON sp.madanhmuc = dm.danhmucid
LEFT JOIN chitietdonhang ct ON sp.sanphamid = ct.masanpham
where ct.soluong > 0
GROUP BY sp.sanphamid, sp.tensanpham, sp.gia, dm.tendanhmuc
ORDER BY total_sold DESC
LIMIT 5";
$result_banchay = $conn->query($sql_banchay);

// Lấy tổng đơn hàng (từ bảng donhang)
$sql_tongdon = "SELECT 
    d.donhangid, 
    n.ten_dang_nhap, 
    d.ngaydat,
    d.trangthai,
    d.tongtien,
    d.sdt,
    d.phuongthuctt
FROM donhang d
JOIN nguoi_dung n ON d.manguoidung = n.id
ORDER BY d.ngaydat DESC
LIMIT 5";
$result_tongdon = $conn->query($sql_tongdon);

// Lấy sản phẩm hết hàng
$sql_hethang = "
    SELECT sp.sanphamid, sp.tensanpham, ha.duongdan, IFNULL(t.tonkho,0) as tonkho, sp.gia, dm.tendanhmuc
    FROM sanpham sp
    LEFT JOIN hinhanhsanpham ha ON sp.sanphamid = ha.masanpham
    LEFT JOIN danhmuc dm ON sp.madanhmuc = dm.danhmucid
    LEFT JOIN (
        SELECT sanphamid, SUM(soluong) as tonkho
        FROM sanpham_size
        GROUP BY sanphamid
    ) t ON sp.sanphamid = t.sanphamid
    WHERE IFNULL(t.tonkho,0) = 0
";
$result_hethang = $conn->query($sql_hethang);

// Lấy nhân viên mới
$sql_nhanvienmoi = "SELECT ten_dang_nhap, email, ngay_tao
                    FROM nguoi_dung
                    WHERE loai = 'nhanvien'
                    ORDER BY ngay_tao DESC
                    LIMIT 5";
$result_nhanvienmoi = $conn->query($sql_nhanvienmoi);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Danh sách nhân viên | Quản trị Admin</title>
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
    <!-- Sidebar toggle button-->
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
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

      
    <?php if($_SESSION['loai'] == 1): ?>
            <li>
                <a class="app-menu__item" href="table-data-table.php">
                    <i class='app-menu__icon bx bx-id-card'></i>
                    <span class="app-menu__label">Quản lý nhân viên</span>
                </a>
            </li>
        <?php endif; ?>
      <li><a class="app-menu__item" href="table-data-customers.php"><i class='app-menu__icon bx bx-user-voice'></i><span
            class="app-menu__label">Quản lý khách hàng</span></a></li>
      <li><a class="app-menu__item" href="table-data-product.php"><i
            class='app-menu__icon bx bx-purchase-tag-alt'></i><span class="app-menu__label">Quản lý sản phẩm</span></a>
      </li>
      <li><a class="app-menu__item" href="table-data-oder.php"><i class='app-menu__icon bx bx-task'></i><span
            class="app-menu__label">Quản lý đơn hàng</span></a></li>
            <li>
                <a class="app-menu__item" href="table-data-khuyenmai.php">
                    <i class='app-menu__icon bx bx-gift'></i>
                    <span class="app-menu__label">Quản lý khuyến mãi</span>
                </a>
            </li>
      <li><a class="app-menu__item active" href="quan-ly-bao-cao.php"><i
            class='app-menu__icon bx bx-pie-chart-alt-2'></i><span class="app-menu__label">Báo cáo doanh thu</span></a>
      </li>
      
    </ul>
  </aside>
  <main class="app-content">
  <div class="row">
            <div class="col-md-12">
                <div class="app-title">
                    <ul class="app-breadcrumb breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><b>Báo cáo doanh thu</b></a></li>
                    </ul>
                    <div id="clock"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="widget-small primary coloured-icon">
                    <i class='icon bx bxs-user fa-3x'></i>
                    <div class="info">
                        <h4>Tổng nhân viên</h4>
                        <p><b><?php echo $total_nhanvien; ?> nhân viên</b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="widget-small info coloured-icon">
                    <i class='icon bx bxs-purchase-tag-alt fa-3x'></i>
                    <div class="info">
                        <h4>Tổng sản phẩm</h4>
                        <p><b><?php echo $sanpham_stats['total']; ?> sản phẩm</b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="widget-small warning coloured-icon">
                    <i class='icon fa-3x bx bxs-shopping-bag-alt'></i>
                    <div class="info">
                        <h4>Tổng đơn hàng</h4>
                        <p><b><?php echo $donhang_stats['total_donhang']; ?> đơn hàng</b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="widget-small primary coloured-icon">
                    <i class='icon fa-3x bx bxs-chart'></i>
                    <div class="info">
                        <h4>Tổng thu nhập</h4>
                        <p><b><?php echo number_format($donhang_stats['tong_doanhthu'], 0, ',', '.'); ?> đ</b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="widget-small warning coloured-icon">
                    <i class='icon fa-3x bx bxs-tag-x'></i>
                    <div class="info">
                        <h4>Hết hàng</h4>
                        <p><b><?php echo $sanpham_stats['hethang']; ?> sản phẩm</b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="widget-small danger coloured-icon">
                    <i class='icon fa-3x bx bxs-receipt'></i>
                    <div class="info">
                        <h4>Đơn hàng hủy</h4>
                        <p><b><?php echo $donhang_stats['donhang_huy']; ?> đơn hàng</b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="widget-small info coloured-icon">
                    <i class='icon bx bxs-gift fa-3x'></i>
                    <div class="info">
                        <h4>Khuyến mãi</h4>
                        <?php
                        $sql_km = "SELECT COUNT(*) as total_km 
                                  FROM khuyenmai 
                                  WHERE ngaybatdau <= NOW() AND ngayketthuc >= NOW()";
                        $result_km = $conn->query($sql_km);
                        $row_km = $result_km->fetch_assoc();
                        ?>
                        <p><b><?php echo $row_km['total_km'] ?> khuyến mãi</b></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div>
                        <h3 class="tile-title">SẢN PHẨM BÁN CHẠY</h3>
                    </div>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>Mã sản phẩm</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá tiền</th>
                                    <th>Danh mục</th>
                                    <th>Số lượng đã bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result_banchay->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['sanphamid']; ?></td>
                                    <td>
                                        <a href="form-edit-san-pham.php?id=<?php echo $row['sanphamid']; ?>" 
                                           class="text-primary" 
                                           style="text-decoration: none;">
                                            <?php echo htmlspecialchars($row['tensanpham']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo number_format($row['gia'], 0, ',', '.'); ?> đ</td>
                                    <td><?php echo htmlspecialchars($row['tendanhmuc']); ?></td>
                                    <td><?php echo $row['total_sold']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div>
                        <h3 class="tile-title">TỔNG ĐƠN HÀNG</h3>
                    </div>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>ID đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Trạng thái</th>
                                    <th>Số điện thoại</th>
                                    <th>Phương thức TT</th>
                                    <th>Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $tong_tien = 0;
                                while($row = $result_tongdon->fetch_assoc()): 
                                    $tong_tien += $row['tongtien'];
                                ?>
                                <tr>
                                    <td><?php echo $row['donhangid']; ?></td>
                                    <td><?php echo htmlspecialchars($row['ten_dang_nhap']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['ngaydat'])); ?></td>
                                    <td>
                                        <span class="badge <?php 
                                            switch($row['trangthai']) {
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
                                        ?>">
                                            <?php echo $row['trangthai']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $row['sdt']; ?></td>
                                    <td><?php echo $row['phuongthuctt']; ?></td>
                                    <td><?php echo number_format($row['tongtien'], 0, ',', '.'); ?> đ</td>
                                </tr>
                                <?php endwhile; ?>
                                <tr>
                                    <th colspan="6" class="text-right">Tổng cộng:</th>
                                    <td><?php echo number_format($tong_tien, 0, ',', '.'); ?> đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">DỮ LIỆU HÀNG THÁNG</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">THỐNG KÊ DOANH SỐ</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <canvas class="embed-responsive-item" id="barChartDemo"></canvas>
                    </div>
                </div>
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
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="js/plugins/chart.js"></script>
    <script type="text/javascript">
        <?php
        // Lấy dữ liệu doanh thu theo tháng
        $sql_doanhthu = "SELECT MONTH(ngaydat) as thang, SUM(tongtien) as doanhthu
                        FROM donhang
                        WHERE YEAR(ngaydat) = YEAR(CURRENT_DATE)
                        GROUP BY MONTH(ngaydat)
                        ORDER BY MONTH(ngaydat)";
        $result_doanhthu = $conn->query($sql_doanhthu);
        $doanhthu_data = array_fill(0, 12, 0);
        
        while($row = $result_doanhthu->fetch_assoc()) {
            $doanhthu_data[$row['thang']-1] = $row['doanhthu'];
        }
        ?>

        var data = {
            labels: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"],
            datasets: [{
                label: "Dữ liệu đầu tiên",
                fillColor: "rgba(255, 255, 255, 0.158)",
                strokeColor: "black",
                pointColor: "rgb(220, 64, 59)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "green",
                data: <?php echo json_encode($doanhthu_data); ?>
            }]
        };
        
        var ctxl = $("#lineChartDemo").get(0).getContext("2d");
        var lineChart = new Chart(ctxl).Line(data);
        
        var ctxb = $("#barChartDemo").get(0).getContext("2d");
        var barChart = new Chart(ctxb).Bar(data);
        </script>
    <!-- Google analytics script-->
    <script type="text/javascript">
        if (document.location.hostname == 'pratikborsadiya.in') {
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-72504830-1', 'auto');
            ga('send', 'pageview');
        }
    </script>
<?php include 'popupchatbotadmin.php'; ?>
</body>

</html>