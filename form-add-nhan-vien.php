<?php
// Thêm phần xử lý PHP ở đầu file, sau phần require header
require_once 'connect.php';
session_start();

// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
  header("Location: login.php");
  exit();
} 
if(isset($_POST['them_nhanvien'])) {
    $ten_dang_nhap = $_POST['ten_dang_nhap'];
    $email = $_POST['email'];
    $sodienthoai = $_POST['sodienthoai'];
    $mat_khau = password_hash($_POST['mat_khau'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
    $loai = '2'; // Mặc định là nhân viên

    try {
        // Kiểm tra email đã tồn tại chưa
        $check_sql = "SELECT * FROM nguoi_dung WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if($result->num_rows > 0) {
            echo "<script>
                swal('Thất bại!', 'Email đã tồn tại trong hệ thống!', 'error');
            </script>";
        } else {
            // Thêm nhân viên mới
            $sql = "INSERT INTO nguoi_dung (ten_dang_nhap, email, mat_khau, sdt, loai) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $ten_dang_nhap, $email, $mat_khau, $sodienthoai, $loai);

            if($stmt->execute()) {
                echo "<script>
                    swal('Thành công!', 'Đã thêm nhân viên mới!', 'success').then(function() {
                        window.location = 'table-data-table.php';
                    });
                </script>";
            } else {
                throw new Exception("Lỗi khi thêm nhân viên");
            }
        }
    } catch (Exception $e) {
        echo "<script>
            swal('Thất bại!', 'Có lỗi xảy ra: " . $e->getMessage() . "', 'error');
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Thêm nhân viên | Quản trị Admin</title>
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
  <script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <script>

    function readURL(input, thumbimage) {
      if (input.files && input.files[0]) { //Sử dụng  cho Firefox - chrome
        var reader = new FileReader();
        reader.onload = function (e) {
          $("#thumbimage").attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
      else { // Sử dụng cho IE
        $("#thumbimage").attr('src', input.value);

      }
      $("#thumbimage").show();
      $('.filename').text($("#uploadfile").val());
      $('.Choicefile').css('background', '#14142B');
      $('.Choicefile').css('cursor', 'default');
      $(".removeimg").show();
      $(".Choicefile").unbind('click');

    }
    $(document).ready(function () {
      $(".Choicefile").bind('click', function () {
        $("#uploadfile").click();

      });
      $(".removeimg").click(function () {
        $("#thumbimage").attr('src', '').hide();
        $("#myfileupload").php('<input type="file" id="uploadfile"  onchange="readURL(this);" />');
        $(".removeimg").hide();
        $(".Choicefile").bind('click', function () {
          $("#uploadfile").click();
        });
        $('.Choicefile').css('background', '#14142B');
        $('.Choicefile').css('cursor', 'pointer');
        $(".filename").text("");
      });
    })
  </script>
</head>

<body class="app sidebar-mini rtl">
  <style>
    .Choicefile {
      display: block;
      background: #14142B;
      border: 1px solid #fff;
      color: #fff;
      width: 150px;
      text-align: center;
      text-decoration: none;
      cursor: pointer;
      padding: 5px 0px;
      border-radius: 5px;
      font-weight: 500;
      align-items: center;
      justify-content: center;
    }

    .Choicefile:hover {
      text-decoration: none;
      color: white;
    }

    #uploadfile,
    .removeimg {
      display: none;
    }

    #thumbbox {
      position: relative;
      width: 100%;
      margin-bottom: 20px;
    }

    .removeimg {
      height: 25px;
      position: absolute;
      background-repeat: no-repeat;
      top: 5px;
      left: 5px;
      background-size: 25px;
      width: 25px;
      /* border: 3px solid red; */
      border-radius: 50%;

    }

    .removeimg::before {
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      content: '';
      border: 1px solid red;
      background: red;
      text-align: center;
      display: block;
      margin-top: 11px;
      transform: rotate(45deg);
    }

    .removeimg::after {
      /* color: #FFF; */
      /* background-color: #DC403B; */
      content: '';
      background: red;
      border: 1px solid red;
      text-align: center;
      display: block;
      transform: rotate(-45deg);
      margin-top: -2px;
    }
  </style>
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
     
      <li><a class="app-menu__item" href="quan-ly-bao-cao.php"><i
            class='app-menu__icon bx bx-pie-chart-alt-2'></i><span class="app-menu__label">Báo cáo doanh thu</span></a>
      </li>
      
    </ul>
  </aside>
  <main class="app-content">
    <div class="app-title">
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">Danh sách nhân viên</li>
        <li class="breadcrumb-item"><a href="#">Thêm nhân viên</a></li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">

        <div class="tile">
          <h3 class="tile-title">Tạo mới nhân viên</h3>
          <div class="tile-body">
            <form class="row" method="POST" action="">
              <div class="form-group col-md-4">
                <label class="control-label">Họ và tên</label>
                <input class="form-control" type="text" name="ten_dang_nhap" required>
              </div>
              <div class="form-group col-md-4">
                <label class="control-label">Địa chỉ email</label>
                <input class="form-control" type="email" name="email" required>
              </div>              
              <div class="form-group col-md-4">
                <label class="control-label">Số điện thoại</label>
                <input class="form-control" type="text" name="sodienthoai" required>
              </div>      
              <div class="form-group col-md-4">
                <label class="control-label">Mật khẩu</label>
                <input class="form-control" type="password" name="mat_khau" required>
              </div>
              
              <div class="form-group col-md-12">
                <button class="btn btn-save" type="submit" name="them_nhanvien">Lưu lại</button>
                <a class="btn btn-cancel" href="table-data-table.php">Hủy bỏ</a>
              </div>
            </form>
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

</body>

</html>