<?php
require_once 'connect.php';
session_start();
// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
  header("Location: login.php");
  exit();
}
if(isset($_POST['id'])) {
    // Xử lý xóa người dùng
    if(isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id'];
        
        // Xóa người dùng có loại = 2 (nhân viên)
        $sql = "DELETE FROM nguoi_dung WHERE id = ? AND loai = 2";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit;
    }
    
    // Xử lý xóa nhiều nhân viên
    if(isset($_POST['action']) && $_POST['action'] == 'deleteMultiple') {
        $ids = $_POST['id']; // Mảng các ID
        
        $success_count = 0;
        $error_count = 0;
        
        foreach($ids as $id) {
            $sql = "DELETE FROM nguoi_dung WHERE id = ? AND loai = 2";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if($stmt->execute()) {
                $success_count++;
            } else {
                $error_count++;
            }
        }
        
        if($error_count == 0) {
            echo json_encode(['status' => 'success', 'message' => "Đã xóa thành công $success_count nhân viên"]);
        } else {
            echo json_encode(['status' => 'partial', 'message' => "Đã xóa thành công $success_count nhân viên, $error_count lỗi"]);
        }
        exit;
    }
    
    // Xử lý cập nhật người dùng
    if(isset($_POST['action']) && $_POST['action'] == 'update') {
        $id = $_POST['id'];
        $ten_dang_nhap = $_POST['ten_dang_nhap'];
        $email = $_POST['email'];
        $sodienthoai = $_POST['sodienthoai'];
        
        // Nếu có nhập mật khẩu mới
        if(!empty($_POST['mat_khau'])) {
            $mat_khau = password_hash($_POST['mat_khau'], PASSWORD_DEFAULT);
            $sql = "UPDATE nguoi_dung 
                    SET ten_dang_nhap = ?, email = ?, mat_khau = ?, sdt = ? 
                    WHERE id = ? AND loai = 2";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $ten_dang_nhap, $email, $mat_khau, $sodienthoai, $id);
        } else {
            // Nếu không thay đổi mật khẩu
            $sql = "UPDATE nguoi_dung 
                    SET ten_dang_nhap = ?, email = ?, sdt = ? 
                    WHERE id = ? AND loai = 2";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $ten_dang_nhap, $email, $sodienthoai, $id);
        }
        
        if($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit;
    }
}


// Lấy danh sách nhân viên
$sql = "SELECT * FROM nguoi_dung WHERE loai = 2";
$result = $conn->query($sql);
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

      
      <li><a class="app-menu__item active" href="table-data-table.php"><i class='app-menu__icon bx bx-id-card'></i>
          <span class="app-menu__label">Quản lý nhân viên</span></a></li>
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
      <li><a class="app-menu__item" href="quan-ly-bao-cao.php"><i
            class='app-menu__icon bx bx-pie-chart-alt-2'></i><span class="app-menu__label">Báo cáo doanh thu</span></a>
      </li>
      
    </ul>
  </aside>
  <main class="app-content">
    <div class="app-title">
      <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item active"><a href="#"><b>Danh sách nhân viên</b></a></li>
      </ul>
      <div id="clock"></div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <div class="tile-body">

            <div class="row element-button">
              <div class="col-sm-2">

                <a class="btn btn-add btn-sm" href="form-add-nhan-vien.php" title="Thêm"><i class="fas fa-plus"></i>
                  Tạo mới nhân viên</a>
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
                <button class="btn btn-delete btn-sm" type="button" title="Xóa" id="deleteSelected"><i
                    class="fas fa-trash-alt"></i> Xóa tất cả </button>
              </div>
            </div>
            <table class="table table-hover table-bordered js-copytextarea" cellpadding="0" cellspacing="0" border="0"
              id="sampleTable">
              <thead>
                <tr>
                  <th width="10"><input type="checkbox" id="selectAll"></th>
                  <th>ID nhân viên</th>
                  <th width="150">Họ và tên</th>
                  <th width="300">Email</th>
                  <th>Ngày tạo</th>
                  <th>SĐT</th>
                  <th>Chức vụ</th>
                  <th width="100">Tính năng</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td width="10"><input type="checkbox" name="check<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>"></td>
                        <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['ten_dang_nhap']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['ngay_tao'])); ?></td>
                        <td><?php echo htmlspecialchars($row['sdt']); ?></td>
                        <td>Nhân viên</td>
                        <td class="table-td-center">
                            <button class="btn btn-primary btn-sm trash" type="button" title="Xóa" 
                                onclick="deleteStaff(<?php echo $row['id']; ?>)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" 
                                data-toggle="modal" data-target="#ModalUP" 
                                data-id="<?php echo $row['id']; ?>"
                                data-name="<?php echo htmlspecialchars($row['ten_dang_nhap']); ?>"
                                data-email="<?php echo htmlspecialchars($row['email']); ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='10' class='text-center'>Không có nhân viên nào</td></tr>";
                }
                ?>
            </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!--
  MODAL
-->
<!-- Cập nhật Modal -->
<div class="modal fade" id="ModalUP" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <span class="thong-tin-thanh-toan">
                            <h5>Chỉnh sửa thông tin nhân viên</h5>
                        </span>
                    </div>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Tên đăng nhập</label>
                            <input class="form-control" type="text" name="ten_dang_nhap" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Email</label>
                            <input class="form-control" type="email" name="email" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Số điện thoại</label>
                            <input class="form-control" type="text" name="sodienthoai">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Mật khẩu mới</label>
                            <input class="form-control" type="password" name="mat_khau">
                        </div>
                    </div>
                    <BR>
                    <button class="btn btn-save" type="submit">Lưu lại</button>
                    <a class="btn btn-cancel" data-dismiss="modal" href="#">Hủy bỏ</a>
                </form>
            </div>
        </div>
    </div>
</div>
  <!--
  MODAL
-->

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
    
    // Xử lý nút "Xóa tất cả"
    $('#deleteSelected').click(function() {
        var checkedIds = [];
        $('input[name^="check"]:checked').each(function() {
            checkedIds.push($(this).val());
        });
        
        if (checkedIds.length === 0) {
            swal("Thông báo", "Vui lòng chọn ít nhất một nhân viên để xóa", "warning");
            return;
        }
        
        swal({
            title: "Cảnh báo",
            text: "Bạn có chắc chắn muốn xóa " + checkedIds.length + " nhân viên đã chọn?",
            buttons: ["Hủy bỏ", "Đồng ý"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'table-data-table.php',
                    type: 'POST',
                    data: { 
                        id: checkedIds,
                        action: 'deleteMultiple'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.status === 'success' || response.status === 'partial') {
                            swal("Thành công!", response.message, "success")
                            .then(() => {
                                location.reload();
                            });
                        } else {
                            swal("Thất bại!", "Không thể xóa nhân viên", "error");
                        }
                    },
                    error: function() {
                        swal("Thất bại!", "Có lỗi xảy ra", "error");
                    }
                });
            }
        });
    });
});

// Xử lý xóa người dùng
function deleteStaff(id) {
    swal({
        title: "Cảnh báo",
        text: "Bạn có chắc chắn muốn xóa nhân viên này?",
        buttons: ["Hủy bỏ", "Đồng ý"],
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: 'table-data-table.php',
                type: 'POST',
                data: { 
                    id: id,
                    action: 'delete'
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        swal("Thành công!", "Đã xóa nhân viên", "success")
                        .then(() => {
                            location.reload();
                        });
                    } else {
                        swal("Thất bại!", "Không thể xóa nhân viên", "error");
                    }
                },
                error: function() {
                    swal("Thất bại!", "Có lỗi xảy ra", "error");
                }
            });
        }
    });
}

// Xử lý cập nhật thông tin
$(document).ready(function() {
    // Khi mở modal sửa
    $('.edit').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var email = $(this).data('email');
        var phone = $(this).data('phone');
        
        $('#ModalUP input[name="id"]').val(id);
        $('#ModalUP input[name="ten_dang_nhap"]').val(name);
        $('#ModalUP input[name="email"]').val(email);
        $('#ModalUP input[name="sodienthoai"]').val(phone);
    });

    // Khi submit form cập nhật
    $('#ModalUP form').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize() + '&action=update';
        
        $.ajax({
            url: 'table-data-table.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    swal("Thành công!", "Đã cập nhật thông tin nhân viên", "success")
                    .then(() => {
                        location.reload();
                    });
                } else {
                    swal("Thất bại!", "Không thể cập nhật thông tin", "error");
                }
            },
            error: function() {
                swal("Thất bại!", "Có lỗi xảy ra", "error");
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
    //     //Sao chép dữ liệu
    //     var copyTextareaBtn = document.querySelector('.js-textareacopybtn');

    // copyTextareaBtn.addEventListener('click', function(event) {
    //   var copyTextarea = document.querySelector('.js-copytextarea');
    //   copyTextarea.focus();
    //   copyTextarea.select();

    //   try {
    //     var successful = document.execCommand('copy');
    //     var msg = successful ? 'successful' : 'unsuccessful';
    //     console.log('Copying text command was ' + msg);
    //   } catch (err) {
    //     console.log('Oops, unable to copy');
    //   }
    // });


    //Modal
    $("#show-emp").on("click", function () {
      $("#ModalUP").modal({ backdrop: false, keyboard: false })
    });
  </script>
</body>

</html>