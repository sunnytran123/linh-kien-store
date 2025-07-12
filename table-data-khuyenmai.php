<?php
require_once 'connect.php';
session_start();

// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
    header("Location: login.php");
    exit();
}

// Xử lý xóa khuyến mãi
if(isset($_POST['delete_khuyenmai'])) {
    $khuyenmaiid = $_POST['khuyenmaiid'];
    
    // Bắt đầu transaction
    $conn->begin_transaction();
    
    try {
        // Xóa các liên kết với sản phẩm trước
        $sql_delete_links = "DELETE FROM sanpham_khuyenmai WHERE khuyenmai_id = ?";
        $stmt_links = $conn->prepare($sql_delete_links);
        $stmt_links->bind_param("i", $khuyenmaiid);
        $stmt_links->execute();
        
        // Sau đó xóa khuyến mãi
        $sql_delete_khuyenmai = "DELETE FROM khuyenmai WHERE khuyenmaiid = ?";
        $stmt_khuyenmai = $conn->prepare($sql_delete_khuyenmai);
        $stmt_khuyenmai->bind_param("i", $khuyenmaiid);
        $stmt_khuyenmai->execute();
        
        $conn->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// Truy vấn lấy danh sách khuyến mãi và sản phẩm áp dụng
$sql = "SELECT km.*, 
        GROUP_CONCAT(sp.tensanpham SEPARATOR ', ') as sanpham_ap_dung,
        COUNT(spkm.sanpham_id) as so_san_pham
        FROM khuyenmai km
        LEFT JOIN sanpham_khuyenmai spkm ON km.khuyenmaiid = spkm.khuyenmai_id
        LEFT JOIN sanpham sp ON spkm.sanpham_id = sp.sanphamid
        GROUP BY km.khuyenmaiid";
$result = $conn->query($sql);

// Lấy danh sách sản phẩm cho form thêm/sửa
$sql_sanpham = "SELECT sanphamid, tensanpham FROM sanpham";
$result_sanpham = $conn->query($sql_sanpham);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Quản lý khuyến mãi | Quản trị Admin</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" type="text/css" href="css/admin-custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
</head>

<body onload="time()" class="app sidebar-mini rtl">
    <!-- Thay thế phần header hiện tại -->
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

    <!-- Thay thế phần sidebar hiện tại -->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="app-sidebar__user">
            <img class="app-sidebar__user-avatar" src="/images/hay.jpg" width="50px" alt="User Image">
            <div>
                <p class="app-sidebar__user-name"><b><?php echo $_SESSION['ten_dang_nhap']; ?></b></p>
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
            <li>
                <a class="app-menu__item" href="#">
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
                <a class="app-menu__item" href="table-data-oder.php">
                    <i class='app-menu__icon bx bx-task'></i>
                    <span class="app-menu__label">Quản lý đơn hàng</span>
                </a>
            </li>
            <li>
                <a class="app-menu__item active" href="table-data-khuyenmai.php">
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
                <li class="breadcrumb-item active"><a href="#"><b>Danh sách khuyến mãi</b></a></li>
            </ul>
            <div id="clock"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="row element-button">
                            <div class="col-sm-2">
                                <a class="btn btn-add btn-sm" href="#" data-toggle="modal" data-target="#addKhuyenMaiModal" title="Thêm">
                                    <i class="fas fa-plus"></i> Tạo khuyến mãi mới
                                </a>
                            </div>
                        </div>
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox" id="selectAll"></th>
                                    <th class="text-center">Tên khuyến mãi</th>
                                    <th class="text-center">Giá trị</th>
                                    <th class="text-center">Ngày bắt đầu</th>
                                    <th class="text-center">Ngày kết thúc</th>
                                    <th class="text-center">SL áp dụng</th>
                                    <th class="text-center">Sản phẩm</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Tính năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $now = new DateTime();
                                        $start = new DateTime($row['ngaybatdau']);
                                        $end = new DateTime($row['ngayketthuc']);
                                        
                                        if ($now < $start) {
                                            $status = '<span class="badge bg-warning">Sắp diễn ra</span>';
                                        } elseif ($now > $end) {
                                            $status = '<span class="badge bg-danger">Đã kết thúc</span>';
                                        } else {
                                            $status = '<span class="badge bg-success">Đang diễn ra</span>';
                                        }
                                        ?>
                                        <tr>
                                            <td width="10"><input type="checkbox" name="check1" value="1"></td>
                                            <td><?php echo $row['tenkhuyenmai']; ?></td>
                                            <td><?php echo number_format($row['giatri'], 0, ',', '.'); ?> VNĐ</td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($row['ngaybatdau'])); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($row['ngayketthuc'])); ?></td>
                                            <td class="text-center"><?php echo $row['so_san_pham']; ?></td>
                                            <td><?php echo $row['sanpham_ap_dung']; ?></td>
                                            <td><?php echo $status; ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm trash" type="button" title="Xóa" 
                                                    onclick="deleteKhuyenMai(<?php echo $row['khuyenmaiid']; ?>)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <button class="btn btn-primary btn-sm edit" type="button" title="Sửa" 
                                                    onclick="editKhuyenMai(<?php echo $row['khuyenmaiid']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal thêm/sửa khuyến mãi -->
    <div class="modal fade" id="addKhuyenMaiModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <span class="thong-tin-thanh-toan">
                                <h5>Thêm khuyến mãi mới</h5>
                            </span>
                        </div>
                    </div>
                    <form id="addKhuyenMaiForm" method="POST">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="control-label">Tên khuyến mãi</label>
                                <input class="form-control" type="text" name="tenkhuyenmai" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Giá trị giảm (VNĐ)</label>
                                <input class="form-control" type="text" name="giatri" required
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Ngày bắt đầu</label>
                                <input class="form-control" type="datetime-local" name="ngaybatdau" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Ngày kết thúc</label>
                                <input class="form-control" type="datetime-local" name="ngayketthuc" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Sản phẩm áp dụng</label>
                                <select class="form-control" name="sanpham[]" multiple required>
                                    <?php
                                    $sql_sp = "SELECT sanphamid, tensanpham FROM sanpham";
                                    $result_sp = $conn->query($sql_sp);
                                    while($row_sp = $result_sp->fetch_assoc()) {
                                        echo "<option value='".$row_sp['sanphamid']."'>".$row_sp['tensanpham']."</option>";
                                    }
                                    ?>
                                </select>
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

    <!-- Essential javascripts -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/plugins/pace.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    
    <script>
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

        function deleteKhuyenMai(khuyenmaiid) {
            swal({
                title: "Cảnh báo",
                text: "Bạn có chắc chắn muốn xóa khuyến mãi này?",
                buttons: ["Hủy bỏ", "Đồng ý"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: 'xu-ly-khuyen-mai.php',
                        type: 'POST',
                        data: {
                            delete_khuyenmai: true,
                            khuyenmaiid: khuyenmaiid
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log('Response:', response);
                            if(response.success) {
                                swal("Thành công!", "Đã xóa khuyến mãi", "success")
                                .then(() => {
                                    location.reload();
                                });
                            } else {
                                swal("Thất bại!", response.message || "Không thể xóa khuyến mãi", "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error:", error);
                            console.error("Status:", status);
                            console.error("Response:", xhr.responseText);
                            swal("Thất bại!", "Có lỗi xảy ra khi xử lý yêu cầu", "error");
                        }
                    });
                }
            });
        }

        // Format số tiền khi nhập
        function formatMoney(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            input.value = parseInt(value).toLocaleString('vi-VN');
        }

        // Xử lý form thêm khuyến mãi
        $(document).ready(function() {
            $('#addKhuyenMaiForm').on('submit', function(e) {
                e.preventDefault();
                
                // Thêm logging để debug
                console.log('Form submitted');
                
                let formData = $(this).serialize() + '&add_khuyenmai=1';
                console.log('Form data:', formData);
                
                $.ajax({
                    url: 'xu-ly-khuyen-mai.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response:', response);
                        if(response.success) {
                            swal("Thành công!", "Đã thêm khuyến mãi mới", "success")
                            .then(() => {
                                location.reload();
                            });
                        } else {
                            swal("Thất bại!", response.message || "Có lỗi xảy ra", "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        console.error("Status:", status);
                        console.error("Response:", xhr.responseText);
                        swal("Thất bại!", "Có lỗi xảy ra khi xử lý yêu cầu", "error");
                    }
                });
            });
        });

        // Hàm sửa khuyến mãi
        function editKhuyenMai(id) {
            $.get('xu-ly-khuyen-mai.php', {id: id}, function(response) {
                if(response.success) {
                    let data = response.data;
                    $('#khuyenmaiid').val(data.khuyenmaiid);
                    $('#tenkhuyenmai').val(data.tenkhuyenmai);
                    $('#giatri').val(parseInt(data.giatri).toLocaleString('vi-VN'));
                    $('#ngaybatdau').val(data.ngaybatdau);
                    $('#ngayketthuc').val(data.ngayketthuc);
                    
                    // Cập nhật select multiple
                    let sanpham_ids = data.sanpham_ids.split(',');
                    $('#sanpham').val(sanpham_ids);
                    
                    $('#addKhuyenMaiModal').modal('show');
                }
            }, 'json');
        }
    </script>
</body>
</html> 