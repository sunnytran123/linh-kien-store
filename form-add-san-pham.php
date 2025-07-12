<?php
session_start(); // Thêm dòng này lên đầu file

require_once 'connect.php';
// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
  header("Location: login.php");
  exit();
}
// Xử lý thêm sản phẩm
if(isset($_POST['add_product'])) {
    $tensanpham = $_POST['tensanpham'];
    $mota = $_POST['mota'];
    $gia = str_replace(['.', ','], '', $_POST['gia']); // Chuyển đổi định dạng tiền về số
    $madanhmuc = $_POST['madanhmuc'];

    // Thêm sản phẩm vào database
    $sql = "INSERT INTO sanpham (tensanpham, mota, gia, madanhmuc) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $tensanpham, $mota, $gia, $madanhmuc);

    if($stmt->execute()) {
        $sanphamid = $stmt->insert_id;

        // Xử lý upload hình ảnh
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "picture/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = $sanphamid . '_' . time() . '.' . $file_extension;
            $target_file = $target_dir . $file_name;

            if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
               // Lưu đường dẫn vào database
            $sql_image = "INSERT INTO hinhanhsanpham (masanpham, duongdan) VALUES (?, ?)";
            $stmt_image = $conn->prepare($sql_image);
            // Lưu đường dẫn tương đối
            $duongdan = $file_name;
            $stmt_image->bind_param("is", $sanphamid, $duongdan);
            
            if(!$stmt_image->execute()) {
                echo "<script>
                    swal('Cảnh báo!', 'Thêm sản phẩm thành công nhưng không lưu được ảnh', 'warning');
                </script>";
            }
            }
        }
        
        // Xử lý thêm size và số lượng
        if(isset($_POST['sizes']) && is_array($_POST['sizes'])) {
            foreach($_POST['sizes'] as $sizeid => $soluong) {
                if($soluong > 0) {
                    $sql_insert_size = "INSERT INTO sanpham_size (sanphamid, sizeid, soluong) VALUES (?, ?, ?)";
                    $stmt_insert_size = $conn->prepare($sql_insert_size);
                    $stmt_insert_size->bind_param("iii", $sanphamid, $sizeid, $soluong);
                    $stmt_insert_size->execute();
                }
            }
        }

        // Xử lý thêm màu sắc
        if(isset($_POST['colors']) && is_array($_POST['colors'])) {
            foreach($_POST['colors'] as $mausacid) {
                if($mausacid > 0) {
                    $sql_insert_color = "INSERT INTO sanpham_mausac (sanphamid, mausacid) VALUES (?, ?)";
                    $stmt_insert_color = $conn->prepare($sql_insert_color);
                    $stmt_insert_color->bind_param("ii", $sanphamid, $mausacid);
                    $stmt_insert_color->execute();
                }
            }
        }

        echo "<script>
                swal('Thành công!', 'Đã thêm sản phẩm mới', 'success').then(function() {
                    window.location = 'table-data-product.php';
                });
              </script>";
    } else {
        echo "<script>
                swal('Thất bại!', 'Không thể thêm sản phẩm', 'error');
              </script>";
    }
}

// Lấy danh sách danh mục
$sql_danhmuc = "SELECT * FROM danhmuc";
$result_danhmuc = $conn->query($sql_danhmuc);

// Lấy danh sách tất cả size có sẵn
$sql_all_sizes = "SELECT * FROM size ORDER BY sizeid";
$result_all_sizes = $conn->query($sql_all_sizes);

// Lấy danh sách tất cả màu sắc có sẵn
$sql_all_colors = "SELECT * FROM mausac ORDER BY mausacid";
$result_all_colors = $conn->query($sql_all_colors);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Thêm sản phẩm | Quản trị Admin</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" type="text/css" href="css/admin-custom.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
    <script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
    
    <style>
        .section-title {
            color: #28a745;
            border-bottom: 2px solid #28a745;
            padding-bottom: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 18px;
        }
        
        .subsection-title {
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 6px;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 16px;
        }
        
        .form-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #28a745;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .size-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .size-table .table {
            margin-bottom: 0;
        }
        
        .size-table .table th {
            background: #28a745;
            color: white;
            border: none;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 15px;
        }
        
        .size-table .table td {
            vertical-align: middle;
            padding: 12px 15px;
            border-color: #e9ecef;
        }
        
        .size-table .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .color-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 15px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .color-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .color-item:hover {
            border-color: #28a745;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .color-item input[type="checkbox"] {
            transform: scale(1.2);
        }
        
        .color-preview {
            width: 30px;
            height: 30px;
            border: 2px solid #ddd;
            border-radius: 6px;
            display: inline-block;
        }
        
        .image-section {
            text-align: center;
            background: white;
            border-radius: 8px;
            padding: 15px;
            border: 2px dashed #ddd;
            height: fit-content;
        }
        
        .image-section img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .Choicefile {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .Choicefile:hover {
            background: #218838;
            color: white;
            text-decoration: none;
        }
        
        .btn {
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 500;
            margin: 0 5px;
        }
        
        .btn-save {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .btn-save:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        
        .btn-cancel {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-cancel:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        
        .help-text {
            color: #6c757d;
            font-size: 13px;
            margin-top: 5px;
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
            content: '';
            background: red;
            border: 1px solid red;
            text-align: center;
            display: block;
            transform: rotate(-45deg);
            margin-top: -2px;
        }
        
        @media (max-width: 768px) {
            .color-grid {
                grid-template-columns: 1fr;
                max-height: none;
            }
            
            .form-section {
                padding: 15px;
            }
            
            .subsection-title {
                margin-top: 20px;
            }
            
            .image-section {
                margin-bottom: 20px;
            }
        }
    </style>
    
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#thumbimage").attr('src', e.target.result).show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        $(document).ready(function () {
            $(".Choicefile").click(function () {
                $("#uploadfile").click();
            });
            
            $(".removeimg").click(function () {
                $("#thumbimage").attr('src', '').hide();
                $("#uploadfile").val('');
                $(".removeimg").hide();
            });
        });
    </script>
</head>

<body class="app sidebar-mini rtl">
    <!-- Navbar-->
    <header class="app-header">
        <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
        <ul class="app-nav">
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
        <div class="app-sidebar__user">
            <img class="app-sidebar__user-avatar" src="/images/hay.jpg" width="50px" alt="User Image">
            <div>
                <p class="app-sidebar__user-name"><b><?php echo $_SESSION['ten_dang_nhap'];?></b></p>
                <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
            </div>
        </div>
        <hr>
        <ul class="app-menu">
            <li><a class="app-menu__item" href="table-data-table.php">
                <i class='app-menu__icon bx bx-id-card'></i>
                <span class="app-menu__label">Quản lý nhân viên</span>
            </a></li>
            <li><a class="app-menu__item" href="#">
                <i class='app-menu__icon bx bx-user-voice'></i>
                <span class="app-menu__label">Quản lý khách hàng</span>
            </a></li>
            <li><a class="app-menu__item active" href="table-data-product.php">
                <i class='app-menu__icon bx bx-purchase-tag-alt'></i>
                <span class="app-menu__label">Quản lý sản phẩm</span>
            </a></li>
            <li><a class="app-menu__item" href="table-data-oder.php">
                <i class='app-menu__icon bx bx-task'></i>
                <span class="app-menu__label">Quản lý đơn hàng</span>
            </a></li>
            <li><a class="app-menu__item" href="quan-ly-bao-cao.php">
                <i class='app-menu__icon bx bx-pie-chart-alt-2'></i>
                <span class="app-menu__label">Báo cáo doanh thu</span>
            </a></li>
        </ul>
    </aside>
    
    <main class="app-content">
        <div class="app-title">
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item">Danh sách sản phẩm</li>
                <li class="breadcrumb-item"><a href="#">Thêm sản phẩm</a></li>
            </ul>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">
                        <i class="fas fa-plus-circle"></i> Tạo mới sản phẩm
                    </h3>
                    
                    <div class="tile-body">
                        <form method="POST" enctype="multipart/form-data">
                            <!-- Thông tin cơ bản -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-info-circle"></i> Thông tin cơ bản
                                </h5>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="control-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="tensanpham" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label">Giá bán <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="gia" required>
                                        <div class="help-text">Nhập giá theo định dạng: 1,000,000</div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label">Danh mục <span class="text-danger">*</span></label>
                                        <select class="form-control" name="madanhmuc" required>
                                            <option value="">Chọn danh mục</option>
                                            <?php while($danhmuc = $result_danhmuc->fetch_assoc()) { ?>
                                                <option value="<?php echo $danhmuc['danhmucid']; ?>">
                                                    <?php echo $danhmuc['tendanhmuc']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quản lý Size và Màu sắc -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-cogs"></i> Quản lý Size và Màu sắc
                                </h5>
                                <div class="row">
                                    <!-- Size và Số lượng -->
                                    <div class="col-md-6">
                                        <h6 class="subsection-title">
                                            <i class="fas fa-ruler"></i> Size và Số lượng
                                        </h6>
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
                                                    while($size = $result_all_sizes->fetch_assoc()) {
                                                        ?>
                                                        <tr>
                                                            <td><strong><?php echo $size['kichco']; ?></strong></td>
                                                            <td>
                                                                <input type="number" 
                                                                       class="form-control" 
                                                                       name="sizes[<?php echo $size['sizeid']; ?>]" 
                                                                       value="0" 
                                                                       min="0" 
                                                                       style="width: 100px;">
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="help-text">
                                            <i class="fas fa-info-circle"></i> Nhập số lượng cho từng size. Size có số lượng = 0 sẽ không được lưu.
                                        </div>
                                    </div>
                                    
                                    <!-- Màu sắc -->
                                    <div class="col-md-6">
                                        <h6 class="subsection-title">
                                            <i class="fas fa-palette"></i> Màu sắc
                                        </h6>
                                        <div class="color-grid">
                                            <?php 
                                            while($color = $result_all_colors->fetch_assoc()) {
                                                ?>
                                                <div class="color-item">
                                                    <input type="checkbox" 
                                                           name="colors[]" 
                                                           value="<?php echo $color['mausacid']; ?>"
                                                           id="color_<?php echo $color['mausacid']; ?>">
                                                    <div class="color-preview" style="background-color: <?php echo $color['mamau']; ?>;"></div>
                                                    <label for="color_<?php echo $color['mausacid']; ?>" style="margin: 0; cursor: pointer;">
                                                        <strong><?php echo $color['tenmau']; ?></strong>
                                                    </label>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="help-text">
                                            <i class="fas fa-info-circle"></i> Chọn các màu sắc có sẵn cho sản phẩm.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ảnh sản phẩm và Mô tả -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-edit"></i> Ảnh sản phẩm và Mô tả
                                </h5>
                                <div class="row">
                                    <!-- Ảnh sản phẩm -->
                                    <div class="col-md-4">
                                        <h6 class="subsection-title">
                                            <i class="fas fa-image"></i> Ảnh sản phẩm
                                        </h6>
                                        <div class="image-section">
                                            <input type="file" id="uploadfile" name="image" onchange="readURL(this);" style="display: none;" />
                                            <div id="thumbbox">
                                                <img id="thumbimage" style="display: none;" />
                                                <a class="removeimg" href="javascript:" style="display: none;">×</a>
                                            </div>
                                            <div id="boxchoice">
                                                <a href="javascript:" class="Choicefile">
                                                    <i class="fas fa-cloud-upload-alt"></i> Chọn ảnh
                                                </a>
                                            </div>
                                            <div class="help-text">
                                                <i class="fas fa-info-circle"></i> Chọn ảnh cho sản phẩm mới.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Mô tả sản phẩm -->
                                    <div class="col-md-8">
                                        <h6 class="subsection-title">
                                            <i class="fas fa-align-left"></i> Mô tả sản phẩm
                                        </h6>
                                        <textarea class="form-control" name="mota" id="mota" rows="12"></textarea>
                                        <script>CKEDITOR.replace('mota');</script>
                                        <div class="help-text">
                                            <i class="fas fa-info-circle"></i> Mô tả chi tiết về sản phẩm, tính năng, chất liệu...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nút điều khiển -->
                            <div class="form-section text-center">
                                <button class="btn btn-save" type="submit" name="add_product">
                                    <i class="fas fa-save"></i> Lưu sản phẩm
                                </button>
                                <a class="btn btn-cancel" href="table-data-product.php">
                                    <i class="fas fa-times"></i> Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/plugins/pace.min.js"></script>
    
    <script>
        // Validation cho form
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const sizeInputs = document.querySelectorAll('input[name^="sizes"]');
                const colorInputs = document.querySelectorAll('input[name="colors[]"]:checked');
                let hasValidSize = false;
                
                sizeInputs.forEach(function(input) {
                    if (parseInt(input.value) > 0) {
                        hasValidSize = true;
                    }
                });
                
                if (!hasValidSize) {
                    e.preventDefault();
                    swal('Cảnh báo!', 'Vui lòng nhập ít nhất một size với số lượng > 0', 'warning');
                    return false;
                }
                
                if (colorInputs.length === 0) {
                    e.preventDefault();
                    swal('Cảnh báo!', 'Vui lòng chọn ít nhất một màu sắc cho sản phẩm', 'warning');
                    return false;
                }
            });
        });
    </script>
</body>
</html>