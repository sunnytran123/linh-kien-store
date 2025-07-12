<?php
require_once 'connect.php';
session_start();
// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
  header("Location: login.php");
  exit();
}


$sql = "SELECT d.donhangid, n.ten_dang_nhap, d.ngaydat, d.diachigiao,
        (SELECT SUM(soluong) FROM chitietdonhang WHERE madonhang = d.donhangid) as total_quantity,
        d.tongtien, d.trangthai 
        FROM donhang d 
        JOIN nguoi_dung n ON d.manguoidung = n.id 
        ORDER BY d.ngaydat DESC";
$result = $conn->query($sql);
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
        <?php if($_SESSION['loai'] == 1): ?>  <!-- Chỉ hiển thị cho admin (loại = 1) -->
            <li>
                <a class="app-menu__item" href="table-data-table.php">
                    <i class='app-menu__icon bx bx-id-card'></i>
                    <span class="app-menu__label">Quản lý nhân viên</span>
                </a>
            </li>
        <?php endif; ?>

        <li>
            <a class="app-menu__item" href="table-data-customers.php">
                <i class='app-menu__icon bx bx-user-voice'></i>
                <span class="app-menu__label">Quản lý khách hàng</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="table-data-product.php">
                <i class='app-menu__icon bx bx-purchase-tag-alt'></i>
                <span class="app-menu__label">Quản lý sản phẩm</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item active" href="table-data-oder.php">
                <i class='app-menu__icon bx bx-task'></i>
                <span class="app-menu__label">Quản lý đơn hàng</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="table-data-khuyenmai.php">
                <i class='app-menu__icon bx bx-gift'></i>
                <span class="app-menu__label">Quản lý khuyến mãi</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item" href="quan-ly-bao-cao.php">
                <i class='app-menu__icon bx bx-pie-chart-alt-2'></i>
                <span class="app-menu__label">Báo cáo doanh thu</span>
            </a>
        </li>
    </ul>
  </aside>
    <main class="app-content">
      <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item active"><a href="#"><b>Danh sách đơn hàng</b></a></li>
        </ul>
        <div id="clock"></div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <div class="row element-button">
                <div class="col-sm-2">
  
                  <a class="btn btn-add btn-sm" href="form-add-don-hang.html" title="Thêm"><i class="fas fa-plus"></i>
                    Tạo mới đơn hàng</a>
                </div>
                <div class="col-sm-2">
                  <a class="btn btn-delete btn-sm nhap-tu-file" type="button" title="Nhập" onclick="myFunction(this)"><i
                      class="fas fa-file-upload"></i> Tải từ file</a>
                </div>
  
                <div class="col-sm-2">
                  <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()"><i
                      class="fas fa-print"></i> In dữ liệu</a>
                </div>
                <div class="col-sm-2">
                  <a class="btn btn-delete btn-sm print-file js-textareacopybtn" type="button" title="Sao chép"><i
                      class="fas fa-copy"></i> Sao chép</a>
                </div>
  
                <div class="col-sm-2">
                  <a class="btn btn-excel btn-sm" href="" title="In"><i class="fas fa-file-excel"></i> Xuất Excel</a>
                </div>
                <div class="col-sm-2">
                  <a class="btn btn-delete btn-sm pdf-file" type="button" title="In" onclick="myFunction(this)"><i
                      class="fas fa-file-pdf"></i> Xuất PDF</a>
                </div>
                <div class="col-sm-2">
                  <a class="btn btn-delete btn-sm" type="button" title="Xóa" onclick="deleteAllOrders()"><i
                      class="fas fa-trash-alt"></i> Xóa tất cả </a>
                </div>
              </div>
              <table class="table table-hover table-bordered" id="sampleTable">
                <thead>
                  <tr>
                    <th width="10"><input type="checkbox" id="selectAll"></th>
                    <th>ID đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt hàng</th>
                    <th>Địa chỉ</th>
                    <th>Tổng tiền</th>
                    <th>Tình trạng</th>
                    <th>Tính năng</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $status_badge = '';
                            switch($row['trangthai']) {
                                case 'Đã hủy':
                                    $status_badge = 'bg-danger';
                                    break;
                                case 'Đang xử lý':
                                    $status_badge = 'bg-warning';
                                    break;
                                case 'Đã hoàn thành':
                                    $status_badge = 'bg-success';
                                    break;
                                default:
                                    $status_badge = 'bg-info';
                            }
                    ?>
                            <tr>
                                <td width="10"><input type="checkbox" name="check<?php echo $row['donhangid']; ?>" value="<?php echo $row['donhangid']; ?>"></td>
                                <td>DH<?php echo str_pad($row['donhangid'], 5, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($row['ten_dang_nhap']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['ngaydat'])); ?></td>
                                <td><?php echo htmlspecialchars($row['diachigiao']); ?></td>
                                <td><?php echo number_format($row['tongtien'], 0, ',', '.') . ' đ'; ?></td>
                                <td><span class="badge <?php echo $status_badge; ?>"><?php echo $row['trangthai']; ?></span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm trash" type="button" title="Xóa" 
                                            data-id="<?php echo $row['donhangid']; ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>                                    
                                    <?php if($row['trangthai'] != 'Đã hủy'): ?>
                                    <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" 
                                            onclick="window.location.href='form-edit-don-hang.php?id=<?php echo $row['donhangid']; ?>'">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>Không có đơn hàng nào</td></tr>";
                    }
                    ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  <!-- Essential javascripts for application to work-->
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="src/jquery.table2excel.js"></script>
  <script src="js/main.js"></script>
  <!-- The javascript plugin to display page loading on top-->
  <script src="js/plugins/pace.min.js"></script>
  <!-- Page specific javascripts-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <!-- Data table plugin-->
  <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript">$('#sampleTable').DataTable();</script>
  <script>
    // Xử lý Select All
    $(document).ready(function() {
        // Xử lý checkbox Select All
        $('#selectAll').change(function() {
            var isChecked = $(this).is(':checked');
            $('input[name^="check"]').prop('checked', isChecked);
        });
        
        // Xử lý khi checkbox riêng lẻ thay đổi
        $(document).on('change', 'input[name^="check"]', function() {
            var totalCheckboxes = $('input[name^="check"]').length;
            var checkedCheckboxes = $('input[name^="check"]:checked').length;
            
            if (checkedCheckboxes === totalCheckboxes) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });
    });

    // Hàm xóa tất cả đơn hàng đã chọn
    function deleteAllOrders() {
        var selectedIds = [];
        $('input[name^="check"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            swal("Thông báo", "Vui lòng chọn ít nhất một đơn hàng để xóa", "warning");
            return;
        }
        
        swal({
            title: "Cảnh báo",
            text: "Bạn có chắc chắn muốn xóa " + selectedIds.length + " đơn hàng đã chọn?",
            buttons: ["Hủy bỏ", "Đồng ý"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'delete_orders.php',
                    type: 'POST',
                    data: {ids: selectedIds},
                    dataType: 'json',
                    success: function(response) {
                        if(response.status === 'success') {
                            swal("Thành công!", "Đã xóa " + response.deleted_count + " đơn hàng", "success")
                            .then(() => {
                                location.reload();
                            });
                        } else {
                            swal("Thất bại!", response.message || "Không thể xóa đơn hàng", "error");
                        }
                    },
                    error: function() {
                        swal("Thất bại!", "Có lỗi xảy ra", "error");
                    }
                });
            }
        });
    }
    // function deleteRow(r) {
    //   var i = r.parentNode.parentNode.rowIndex;
    //   document.getElementById("myTable").deleteRow(i);
    // }
    // jQuery(function () {
    //   jQuery(".trash").click(function () {
    //     swal({
    //       title: "Cảnh báo",
         
    //       text: "Bạn có chắc chắn là muốn xóa đơn hàng này?",
    //       buttons: ["Hủy bỏ", "Đồng ý"],
    //     })
    //       .then((willDelete) => {
    //         if (willDelete) {
    //           swal("Đã xóa thành công.!", {
                
    //           });
    //         }
    //       });
    //   });
    // });
    // oTable = $('#sampleTable').dataTable();
    // $('#all').click(function (e) {
    //   $('#sampleTable tbody :checkbox').prop('checked', $(this).is(':checked'));
    //   e.stopImmediatePropagation();
    // });


    jQuery(function () {
    jQuery(".trash").click(function () {
        var id = $(this).data('id');
        swal({
            title: "Cảnh báo",
            text: "Bạn có chắc chắn là muốn xóa đơn hàng này?",
            buttons: ["Hủy bỏ", "Đồng ý"],
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'delete_order.php',
                    type: 'POST',
                    data: {id: id},
                    success: function(response) {
                        swal("Đã xóa thành công!", {
                            icon: "success",
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        swal("Có lỗi xảy ra!", {
                            icon: "error",
                        });
                    }
                });
            }
        });
    });
});

    //Thời Gian
    function time() {
      var today = new Date();
      var weekday = new Array(7);
      weekday[0] = "Chủ Nhật";
      weekday[1] = "Thứ Hai";
      weekday[2] = "Thứ Ba";
      weekday[3] = "Thứ Tư";
      weekday[4] = "Thứ Năm";
      weekday[5] = "Thứ Sáu";
      weekday[6] = "Thứ Bảy";
      var day = weekday[today.getDay()];
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();
      var h = today.getHours();
      var m = today.getMinutes();
      var s = today.getSeconds();
      m = checkTime(m);
      s = checkTime(s);
      nowTime = h + " giờ " + m + " phút " + s + " giây";
      if (dd < 10) {
        dd = '0' + dd
      }
      if (mm < 10) {
        mm = '0' + mm
      }
      today = day + ', ' + dd + '/' + mm + '/' + yyyy;
      tmp = '<span class="date"> ' + today + ' - ' + nowTime +
        '</span>';
      document.getElementById("clock").innerHTML = tmp;
      clocktime = setTimeout("time()", "1000", "Javascript");

      function checkTime(i) {
        if (i < 10) {
          i = "0" + i;
        }
        return i;
      }
    }
    //In dữ liệu
    var myApp = new function () {
      this.printTable = function () {
        var tab = document.getElementById('sampleTable');
        var win = window.open('', '', 'height=700,width=700');
        win.document.write(tab.outerHTML);
        win.document.close();
        win.print();
      }
    }


    //Modal
    $("#show-emp").on("click", function () {
      $("#ModalUP").modal({ backdrop: false, keyboard: false })
    });
  </script>
</body>

</html>