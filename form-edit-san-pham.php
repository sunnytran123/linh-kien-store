<?php
session_start(); // Thêm dòng này lên đầu file

require_once 'connect.php';

// Debug để xem ID sản phẩm
echo "<!-- Debug ID: " . (isset($_GET['id']) ? $_GET['id'] : 'Không có ID') . " -->";

// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
    header("Location: login.php");
    exit();
}

// Lấy ID sản phẩm từ URL
$sanphamid = isset($_GET['id']) ? $_GET['id'] : null;

if ($sanphamid) {
    // Lấy thông tin sản phẩm
    $sql = "SELECT sp.*, ha.duongdan 
            FROM sanpham sp 
            LEFT JOIN hinhanhsanpham ha ON sp.sanphamid = ha.masanpham 
            WHERE sp.sanphamid = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sanphamid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $sanpham = $result->fetch_assoc();
        // Debug thông tin sản phẩm
        echo "<!-- Debug Sản phẩm: ";
        print_r($sanpham);
        echo " -->";
    } else {
        echo "<script>
                alert('Không tìm thấy thông tin sản phẩm!');
                window.location.href='table-data-product.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('Không có ID sản phẩm!');
            window.location.href='table-data-product.php';
          </script>";
    exit();
}

// Lấy danh sách danh mục
$sql_danhmuc = "SELECT * FROM danhmuc";
$result_danhmuc = $conn->query($sql_danhmuc);

// Lấy danh sách size hiện tại của sản phẩm
$sql_sizes = "SELECT ss.*, s.kichco 
              FROM sanpham_size ss 
              JOIN size s ON ss.sizeid = s.sizeid 
              WHERE ss.sanphamid = ? 
              ORDER BY s.sizeid";
$stmt_sizes = $conn->prepare($sql_sizes);
$stmt_sizes->bind_param("i", $sanphamid);
$stmt_sizes->execute();
$result_sizes = $stmt_sizes->get_result();

// Lấy danh sách tất cả size có sẵn
$sql_all_sizes = "SELECT * FROM size ORDER BY sizeid";
$result_all_sizes = $conn->query($sql_all_sizes);

// Thêm vào đầu file
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Xử lý cập nhật sản phẩm
if(isset($_POST['update_product'])) {
    // Debug để xem dữ liệu gửi lên
    echo "<!-- Debug POST data: ";
    print_r($_POST);
    echo " -->";
    
    $tensanpham = $_POST['tensanpham'];
    $gia = str_replace(['.', ','], '', $_POST['gia']);
    $madanhmuc = $_POST['madanhmuc'];
    $mota = $_POST['mota'];
    
    // Debug các biến
    echo "<!-- Debug variables:
    tensanpham: $tensanpham
    tonkho: $tonkho
    gia: $gia
    madanhmuc: $madanhmuc
    mota: $mota
    -->";

    // Xử lý upload ảnh mới nếu có
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $target_dir = "picture/";
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Cập nhật đường dẫn ảnh mới
            $sql_update_image = "UPDATE hinhanhsanpham SET duongdan = ? WHERE masanpham = ?";
            $stmt_image = $conn->prepare($sql_update_image);
            $stmt_image->bind_param("si", $new_filename, $sanphamid);
            $stmt_image->execute();

            // Nếu chưa có ảnh, thêm mới
            if ($stmt_image->affected_rows == 0) {
                $sql_insert_image = "INSERT INTO hinhanhsanpham (masanpham, duongdan) VALUES (?, ?)";
                $stmt_insert = $conn->prepare($sql_insert_image);
                $stmt_insert->bind_param("is", $sanphamid, $new_filename);
                $stmt_insert->execute();
            }
        }
    }

    // Cập nhật thông tin sản phẩm
    $sql = "UPDATE sanpham SET 
            tensanpham = ?, 
            gia = ?, 
            madanhmuc = ?,
            mota = ?
            WHERE sanphamid = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdisi", $tensanpham, $gia, $madanhmuc, $mota, $sanphamid);
    
    if($stmt->execute()) {
        // Xử lý cập nhật size và số lượng
        if(isset($_POST['sizes']) && is_array($_POST['sizes'])) {
            // Xóa tất cả size cũ
            $sql_delete_sizes = "DELETE FROM sanpham_size WHERE sanphamid = ?";
            $stmt_delete = $conn->prepare($sql_delete_sizes);
            $stmt_delete->bind_param("i", $sanphamid);
            $stmt_delete->execute();
            
            // Thêm size mới
            foreach($_POST['sizes'] as $sizeid => $soluong) {
                if($soluong > 0) {
                    $sql_insert_size = "INSERT INTO sanpham_size (sanphamid, sizeid, soluong) VALUES (?, ?, ?)";
                    $stmt_insert_size = $conn->prepare($sql_insert_size);
                    $stmt_insert_size->bind_param("iii", $sanphamid, $sizeid, $soluong);
                    $stmt_insert_size->execute();
                }
            }
        }
        
        $update_success = true;
    } else {
        $update_error = $stmt->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
<title>Chỉnh sửa sản phẩm | Quản trị Admin</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <!-- Custom Admin CSS -->
  <link rel="stylesheet" type="text/css" href="css/admin-custom.css">
  <!-- Font-icon css-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <!-- or -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <link rel="stylesheet" type="text/css"
    href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
  <script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
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
    }
    
    .size-table {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 20px;
    }
    
    .size-table .table {
      margin-bottom: 0;
    }
    
    .size-table .table th {
      background: #28a745;
      color: white;
      border-color: #28a745;
    }
    
    .size-table .table td {
      vertical-align: middle;
    }
    
    .badge-info {
      background-color: #17a2b8;
      color: white;
      font-size: 12px;
      padding: 5px 10px;
    }
    
    .section-title {
      color: #28a745;
      border-bottom: 2px solid #28a745;
      padding-bottom: 8px;
      margin-bottom: 20px;
      font-weight: 600;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .size-table {
      max-width: 400px;
    }
    
    .size-table .table th {
      background: #28a745;
      color: white;
      border-color: #28a745;
      font-size: 14px;
    }
    
    .size-table .table td {
      vertical-align: middle;
      padding: 12px 8px;
    }
    
    #thumbbox {
      text-align: center;
      margin: 15px 0;
    }
    
    #thumbbox img {
      border: 2px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn {
      margin: 0 5px;
      padding: 8px 20px;
    }
    
    .btn-save {
      background-color: #28a745;
      border-color: #28a745;
    }
    
    .btn-cancel {
      background-color: #6c757d;
      border-color: #6c757d;
    }
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

      
      <li><a class="app-menu__item " href="table-data-table.php"><i class='app-menu__icon bx bx-id-card'></i>
          <span class="app-menu__label">Quản lý nhân viên</span></a></li>
      <li><a class="app-menu__item " href="#"><i class='app-menu__icon bx bx-user-voice'></i><span
            class="app-menu__label">Quản lý khách hàng</span></a></li>
      <li><a class="app-menu__item active" href="table-data-product.php"><i
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
                <li class="breadcrumb-item">Danh sách sản phẩm</li>
                <li class="breadcrumb-item"><a href="#">Chỉnh sửa sản phẩm</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Chỉnh sửa sản phẩm</h3>
                    <div class="tile-body">
                        <?php if(isset($sanpham)): ?>
                        <form class="row" method="POST" enctype="multipart/form-data">
                            <!-- Thông tin cơ bản -->
                            <div class="col-md-12">
                                <h5 class="section-title">Thông tin cơ bản</h5>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="control-label">Mã sản phẩm</label>
                                <input class="form-control" type="text" value="<?php echo $sanpham['sanphamid']; ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="tensanpham" 
                                       value="<?php echo htmlspecialchars($sanpham['tensanpham']); ?>" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="control-label">Giá bán</label>
                                <input class="form-control" type="text" name="gia" 
                                       value="<?php echo number_format($sanpham['gia'], 0, ',', '.'); ?>" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="control-label">Danh mục</label>
                                <select class="form-control" name="madanhmuc" required>
                                    <?php 
                                    while($danhmuc = $result_danhmuc->fetch_assoc()) {
                                        $selected = ($danhmuc['danhmucid'] == $sanpham['madanhmuc']) ? 'selected' : '';
                                        echo "<option value='" . $danhmuc['danhmucid'] . "' $selected>" . 
                                             htmlspecialchars($danhmuc['tendanhmuc']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <!-- Quản lý Size -->
                            <div class="col-md-12">
                                <h5 class="section-title">Quản lý Size và Số lượng</h5>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="table-responsive size-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="40%">Size</th>
                                                <th width="60%">Số lượng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            // Tạo mảng để lưu size hiện tại
                                            $current_sizes = array();
                                            while($size_row = $result_sizes->fetch_assoc()) {
                                                $current_sizes[$size_row['sizeid']] = $size_row['soluong'];
                                            }
                                            
                                            // Reset pointer để có thể fetch lại
                                            $result_all_sizes->data_seek(0);
                                            
                                            while($size = $result_all_sizes->fetch_assoc()) {
                                                $current_qty = isset($current_sizes[$size['sizeid']]) ? $current_sizes[$size['sizeid']] : 0;
                                                ?>
                                                <tr>
                                                    <td><strong><?php echo $size['kichco']; ?></strong></td>
                                                    <td>
                                                        <input type="number" 
                                                               class="form-control" 
                                                               name="sizes[<?php echo $size['sizeid']; ?>]" 
                                                               value="<?php echo $current_qty; ?>" 
                                                               min="0" 
                                                               style="width: 80px;">
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="form-text text-muted">
                                    Nhập số lượng cho từng size. Size có số lượng = 0 sẽ không được lưu.
                                </small>
                            </div>
                            
                            <!-- Ảnh sản phẩm -->
                            <div class="form-group col-md-6">
                                <label class="control-label">Ảnh sản phẩm</label>
                                <div id="myfileupload">
                                    <input type="file" id="uploadfile" name="image" onchange="readURL(this);" />
                                </div>
                                <div id="thumbbox">
                                    <img height="300" width="250" alt="Thumb image" id="thumbimage" 
                                         src="<?php echo $sanpham['duongdan'] ? 'picture/'.$sanpham['duongdan'] : ''; ?>" 
                                         style="display: <?php echo $sanpham['duongdan'] ? 'block' : 'none'; ?>; max-width: 100%;" />
                                    <a class="removeimg" href="javascript:"></a>
                                </div>
                                <div id="boxchoice">
                                    <a href="javascript:" class="Choicefile">
                                        <i class="fas fa-cloud-upload-alt"></i> Chọn ảnh
                                    </a>
                                    <p style="clear:both"></p>
                                </div>
                            </div>
                            
                            <!-- Mô tả sản phẩm -->
                            <div class="col-md-12">
                                <h5 class="section-title">Mô tả sản phẩm</h5>
                            </div>
                            <div class="form-group col-md-12">
                                <textarea class="form-control" name="mota" id="mota" rows="6">
                                    <?php echo htmlspecialchars($sanpham['mota']); ?>
                                </textarea>
                                <script>CKEDITOR.replace('mota');</script>
                            </div>
                            
                            <!-- Nút điều khiển -->
                            <div class="form-group col-md-12 text-center">
                                <button class="btn btn-save" type="submit" name="update_product">
                                    <i class="fas fa-save"></i> Lưu lại
                                </button>
                                <a class="btn btn-cancel" href="table-data-product.php">
                                    <i class="fas fa-times"></i> Hủy bỏ
                                </a>
                            </div>
                        </form>
                        <?php else: ?>
                            <div class="alert alert-danger">Không tìm thấy sản phẩm</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>





  <!--
  MODAL DANH MỤC

-->




  <!--

-->



  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
  <script src="js/plugins/pace.min.js"></script>
  <script>
    const inpFile = document.getElementById("inpFile");
    const loadFile = document.getElementById("loadFile");
    const previewContainer = document.getElementById("imagePreview");
    const previewContainer = document.getElementById("imagePreview");
    const previewImage = previewContainer.querySelector(".image-preview__image");
    const previewDefaultText = previewContainer.querySelector(".image-preview__default-text");
    inpFile.addEventListener("change", function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        previewDefaultText.style.display = "none";
        previewImage.style.display = "block";
        reader.addEventListener("load", function () {
          previewImage.setAttribute("src", this.result);
        });
        reader.readAsDataURL(file);
      }
    });

    // Thêm validation cho form size
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const sizeInputs = document.querySelectorAll('input[name^="sizes"]');
            let hasValidSize = false;
            
            sizeInputs.forEach(function(input) {
                if (parseInt(input.value) > 0) {
                    hasValidSize = true;
                }
            });
            
            if (!hasValidSize) {
                e.preventDefault();
                alert('Vui lòng nhập ít nhất một size với số lượng > 0');
                return false;
            }
        });
    });

  </script>

<!-- Đặt script này ở cuối file, ngay trước </body> -->
<?php if(isset($update_success) && $update_success): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        swal({
            title: 'Thành công!',
            text: 'Đã cập nhật thông tin sản phẩm',
            icon: 'success'
        }).then(function() {
            window.location = 'table-data-product.php';
        });
    });
</script>
<?php elseif(isset($update_error)): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        swal({
            title: 'Thất bại!',
            text: 'Không thể cập nhật sản phẩm: <?php echo $update_error; ?>',
            icon: 'error'
        });
    });
</script>
<?php endif; ?>
</body>

</html>