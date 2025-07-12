<?php
require_once 'connect.php';
session_start();
// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
  header("Location: login.php");
  exit();
}
// Xử lý xóa sản phẩm
if(isset($_POST['delete_product'])) {
    $sanphamid = $_POST['sanphamid'];
    
    // Xóa hình ảnh liên quan trước
    $sql_delete_images = "DELETE FROM hinhanhsanpham WHERE masanpham = ?";
    $stmt_images = $conn->prepare($sql_delete_images);
    $stmt_images->bind_param("i", $sanphamid);
    $stmt_images->execute();
    
    // Sau đó xóa sản phẩm
    $sql_delete_product = "DELETE FROM sanpham WHERE sanphamid = ?";
    $stmt_product = $conn->prepare($sql_delete_product);
    $stmt_product->bind_param("i", $sanphamid);
    
    if($stmt_product->execute()) {
        echo json_encode(['status' => 'success']);
        exit;
    } else {
        echo json_encode(['status' => 'error']);
        exit;
    }
    echo '[status';
}

// Truy vấn lấy danh sách sản phẩm và hình ảnh với tổng số lượng từ sanpham_size
$sql = "SELECT sp.*, 
        (SELECT ha.duongdan FROM hinhanhsanpham ha WHERE ha.masanpham = sp.sanphamid LIMIT 1) as duongdan,
        dm.tendanhmuc, 
        COALESCE(SUM(ss.soluong), 0) as tong_ton_kho,
        GROUP_CONCAT(DISTINCT CONCAT(s.kichco, ':', ss.soluong) ORDER BY s.sizeid SEPARATOR ', ') as size_details,
        GROUP_CONCAT(DISTINCT ms.tenmau ORDER BY ms.tenmau SEPARATOR ', ') as color_details
        FROM sanpham sp 
        LEFT JOIN danhmuc dm ON sp.madanhmuc = dm.danhmucid
        LEFT JOIN sanpham_size ss ON sp.sanphamid = ss.sanphamid
        LEFT JOIN size s ON ss.sizeid = s.sizeid
        LEFT JOIN sanpham_mausac spm ON sp.sanphamid = spm.sanphamid
        LEFT JOIN mausac ms ON spm.mausacid = ms.mausacid
        GROUP BY sp.sanphamid, dm.tendanhmuc";
$result = $conn->query($sql);

// Thêm vào phần đầu file để xử lý cập nhật
if(isset($_POST['update_product'])) {
    $sanphamid = $_POST['sanphamid'];
    $tensanpham = $_POST['tensanpham'];
    $gia = str_replace(['.', ','], '', $_POST['gia']); // Chuyển đổi định dạng tiền về số
    $madanhmuc = $_POST['madanhmuc'];
    
    $sql = "UPDATE sanpham SET 
            tensanpham = ?, 
            gia = ?, 
            madanhmuc = ? 
            WHERE sanphamid = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdii", $tensanpham, $gia, $madanhmuc, $sanphamid);
    
    if($stmt->execute()) {
        echo "<script>
                swal('Thành công!', 'Đã cập nhật thông tin sản phẩm', 'success').then(function() {
                    window.location = 'table-data-product.php';
                });
              </script>";
    } else {
        echo "<script>
                swal('Thất bại!', 'Không thể cập nhật sản phẩm', 'error');
              </script>";
    }
    header("Refresh:0");
}

// Lấy danh sách danh mục
$sql_danhmuc = "SELECT * FROM danhmuc";
$result_danhmuc = $conn->query($sql_danhmuc);
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
        <style>
            .badge-info {
                background-color: #17a2b8;
                color: white;
                font-size: 11px;
                padding: 3px 6px;
                margin-right: 2px;
                border-radius: 4px;
            }
            .mr-1 {
                margin-right: 0.25rem;
            }
            .text-muted {
                color: #6c757d !important;
                font-style: italic;
            }
            .badge-secondary {
                background-color: #6c757d;
                color: white;
                font-size: 11px;
                padding: 3px 6px;
                margin-right: 2px;
                margin-bottom: 2px;
                border-radius: 4px;
                display: inline-block;
            }
            
            /* Chỉnh width cho các cột bảng */
            #sampleTable th:nth-child(1) { width: 50px; } /* Checkbox */
            #sampleTable th:nth-child(2) { width: 100px; } /* Mã sản phẩm */
            #sampleTable th:nth-child(3) { width: 200px; } /* Tên sản phẩm */
            #sampleTable th:nth-child(4) { width: 120px; } /* Ảnh */
            #sampleTable th:nth-child(5) { width: 150px; } /* Chi tiết size */
            #sampleTable th:nth-child(6) { width: 120px; } /* Màu sắc */
            #sampleTable th:nth-child(7) { width: 100px; } /* Tình trạng */
            #sampleTable th:nth-child(8) { width: 120px; } /* Giá tiền */
            #sampleTable th:nth-child(9) { width: 120px; } /* Danh mục */
            #sampleTable th:nth-child(10) { width: 100px; } /* Tính năng */
            
            /* Chỉnh width cho các ô dữ liệu */
            #sampleTable td:nth-child(1) { width: 50px; text-align: center; }
            #sampleTable td:nth-child(2) { width: 100px; text-align: center; }
            #sampleTable td:nth-child(3) { width: 200px; }
            #sampleTable td:nth-child(4) { width: 120px; text-align: center; }
            #sampleTable td:nth-child(5) { width: 150px; }
            #sampleTable td:nth-child(6) { width: 120px; }
            #sampleTable td:nth-child(7) { width: 100px; text-align: center; }
            #sampleTable td:nth-child(8) { width: 120px; text-align: right; }
            #sampleTable td:nth-child(9) { width: 120px; }
            #sampleTable td:nth-child(10) { width: 100px; text-align: center; }
        </style>
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

      <li><a class="app-menu__item " href="#"><i class='app-menu__icon bx bx-user-voice'></i><span
            class="app-menu__label">Quản lý khách hàng</span></a></li>
      <li><a class="app-menu__item active" href="table-data-product.php"><i
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
                <li class="breadcrumb-item active"><a href="#"><b>Danh sách sản phẩm</b></a></li>
            </ul>
            <div id="clock"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="row element-button">
                            <div class="col-sm-2">
              
                              <a class="btn btn-add btn-sm" href="form-add-san-pham.php" title="Thêm"><i class="fas fa-plus"></i>
                                Tạo mới sản phẩm</a>
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
                              <a class="btn btn-delete btn-sm" type="button" title="Xóa" onclick="deleteAllProducts()"><i
                                  class="fas fa-trash-alt"></i> Xóa tất cả </a>
                            </div>
                          </div>
                          <table class="table table-hover table-bordered" id="sampleTable">
                              <thead>
                                  <tr>
                                      <th width="10"><input type="checkbox" id="selectAll"></th>
                                      <th class="text-center">Mã sản phẩm</th>
                                      <th>sản phẩm</th>
                                      <th>Ảnh</th>
                                      <th>Chi tiết size</th>
                                      <th>Màu sắc</th>
                                      <th>Tình trạng</th>
                                      <th>Giá tiền</th>
                                      <th>Danh mục</th>
                                      <th>Tính năng</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                  if ($result->num_rows > 0) {
                                      while($row = $result->fetch_assoc()) {
                                          $tinhtrang = ($row['tong_ton_kho'] > 0) ? 
                                              '<span class="badge bg-success">Còn hàng</span>' : 
                                              '<span class="badge bg-danger">Hết hàng</span>';
                                          ?>
                                          <tr>
                                              <td width="10"><input type="checkbox" name="check<?php echo $row['sanphamid']; ?>" value="<?php echo $row['sanphamid']; ?>"></td>
                                              <td class="text-center"><?php echo $row['sanphamid']; ?></td>
                                              <td><?php echo $row['tensanpham']; ?></td>
                                              <td>
                                                  <?php if($row['duongdan']) { ?>
                                                      <img src="<?php echo 'picture/'.$row['duongdan']; ?>" alt="" width="100px;">
                                                  <?php } else { ?>
                                                      <img src="/img-sanpham/no-image.jpg" alt="" width="100px;">
                                                  <?php } ?>
                                              </td>
                                              <td>
                                                  <?php 
                                                  if($row['size_details']) {
                                                      $sizes = explode(', ', $row['size_details']);
                                                      foreach($sizes as $size) {
                                                          $size_parts = explode(':', $size);
                                                          if(count($size_parts) == 2 && $size_parts[1] > 0) {
                                                              echo '<span class="badge badge-info mr-1">' . $size_parts[0] . ': ' . $size_parts[1] . '</span>';
                                                          }
                                                      }
                                                  } else {
                                                      echo '<span class="text-muted">-</span>';
                                                  }
                                                  ?>
                                              </td>
                                              <td>
                                                  <?php 
                                                  if($row['color_details']) {
                                                      $colors = explode(', ', $row['color_details']);
                                                      foreach($colors as $color) {
                                                          echo '<span class="badge badge-secondary mr-1">' . trim($color) . '</span>';
                                                      }
                                                  } else {
                                                      echo '<span class="text-muted">Chưa có màu</span>';
                                                  }
                                                  ?>
                                              </td>
                                              <td><?php echo $tinhtrang; ?></td>
                                              <td><?php echo number_format($row['gia'], 0, ',', '.') . ' đ'; ?></td>
                                              <td data-madanhmuc="<?php echo $row['madanhmuc']; ?>">
                                                  <?php echo $row['tendanhmuc'] ?? 'Chưa phân loại'; ?>
                                              </td>
                                              <td>
                                                  <button class="btn btn-primary btn-sm trash" type="button" title="Xóa" 
                                                      onclick="deleteProduct(<?php echo $row['sanphamid']; ?>)">
                                                      <i class="fas fa-trash-alt"></i>
                                                  </button>
                                                  <a class="btn btn-primary btn-sm edit" href="form-edit-san-pham.php?id=<?php echo $row['sanphamid']; ?>" title="Sửa">
                                                      <i class="fas fa-edit"></i>
                                                  </a>
                                              </td>
                                          </tr>
                                          <?php
                                      }
                                  } else {
                                      echo "<tr><td colspan='11'>Không có sản phẩm nào</td></tr>";
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
<div class="modal fade" id="ModalUP" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
data-keyboard="false">
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
    <div class="modal-body">
      <div class="row">
        <div class="form-group col-md-12">
          <span class="thong-tin-thanh-toan">
            <h5>Chỉnh sửa thông tin sản phẩm cơ bản</h5>
          </span>
        </div>
      </div>
      <form method="POST" action="">
        <div class="row">
          <div class="form-group col-md-6">
              <label class="control-label">Mã sản phẩm </label>
              <input class="form-control" type="number" name="sanphamid" id="sanphamid" readonly>
          </div>
          <div class="form-group col-md-6">
              <label class="control-label">Tên sản phẩm</label>
              <input class="form-control" type="text" name="tensanpham" id="tensanpham" required>
          </div>
          <div class="form-group col-md-6">
              <label class="control-label">Số lượng</label>
              <input class="form-control" type="number" name="tong_ton_kho" id="tong_ton_kho" readonly>
              <small class="form-text text-muted">Số lượng được quản lý qua bảng sanpham_size</small>
          </div>
          <div class="form-group col-md-6">
              <label class="control-label">Giá bán</label>
              <input class="form-control" type="text" name="gia" id="gia">
          </div>
          <div class="form-group col-md-6">
              <label class="control-label">Danh mục</label>
              <select class="form-control" name="madanhmuc" id="madanhmuc">
                <?php while($danhmuc = $result_danhmuc->fetch_assoc()) { ?>
                  <option value="<?php echo $danhmuc['danhmucid']; ?>">
                    <?php echo $danhmuc['tendanhmuc']; ?>
                  </option>
                <?php } ?>
              </select>
          </div>
        </div>
        <BR>
        <button class="btn btn-save" type="submit" name="update_product">Lưu lại</button>
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
    <script type="text/javascript">
        $('#sampleTable').DataTable();
        
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

        // Hàm xóa tất cả sản phẩm đã chọn
        function deleteAllProducts() {
            var selectedIds = [];
            $('input[name^="check"]:checked').each(function() {
                selectedIds.push($(this).val());
            });
            
            if (selectedIds.length === 0) {
                swal("Thông báo", "Vui lòng chọn ít nhất một sản phẩm để xóa", "warning");
                return;
            }
            
            swal({
                title: "Cảnh báo",
                text: "Bạn có chắc chắn muốn xóa " + selectedIds.length + " sản phẩm đã chọn?",
                buttons: ["Hủy bỏ", "Đồng ý"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: 'delete_products.php',
                        type: 'POST',
                        data: {ids: selectedIds},
                        dataType: 'json',
                        success: function(response) {
                            if(response.status === 'success') {
                                swal("Thành công!", "Đã xóa " + response.deleted_count + " sản phẩm", "success")
                                .then(() => {
                                    location.reload();
                                });
                            } else {
                                swal("Thất bại!", response.message || "Không thể xóa sản phẩm", "error");
                            }
                        },
                        error: function() {
                            swal("Thất bại!", "Có lỗi xảy ra", "error");
                        }
                    });
                }
            });
        }
        
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
    </script>
   
    <script>
    function deleteProduct(sanphamid) {
        swal({
            title: "Cảnh báo",
            text: "Bạn có chắc chắn muốn xóa sản phẩm này?",
            buttons: ["Hủy bỏ", "Đồng ý"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'table-data-product.php',
                    type: 'POST',
                    data: {
                        delete_product: true,
                        sanphamid: sanphamid
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.status === 'success') {
                            swal("Thành công!", "Đã xóa sản phẩm", "success")
                            .then(() => {
                                location.reload();
                            });
                        } else {
                            swal("Thất bại!", "Không thể xóa sản phẩm", "error");
                        }
                    },
                    error: function() {
                        swal("Thất bại!", "Có lỗi xảy ra", "error");
                    }
                });
            }
        });
    }
    </script>
    <script>
    $(document).ready(function() {
        $('.edit').click(function() {
            var row = $(this).closest('tr');
            var sanphamid = row.find('td:eq(1)').text();
            var tensanpham = row.find('td:eq(2)').text();
            var tong_ton_kho = row.find('td:eq(4)').text();
            var gia = row.find('td:eq(6)').text().replace(/[^\d]/g, '');
            var madanhmuc = row.find('td:eq(7)').attr('data-madanhmuc');

            $('#sanphamid').val(sanphamid);
            $('#tensanpham').val(tensanpham);
            $('#tong_ton_kho').val(tong_ton_kho);
            $('#gia').val(gia);
            $('#madanhmuc').val(madanhmuc);
        });
    });
    </script>
</body>

</html>