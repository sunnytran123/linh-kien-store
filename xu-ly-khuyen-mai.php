<?php
require_once 'connect.php';
session_start();

// Thêm logging
error_log("Request received: " . print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Lấy thông tin khuyến mãi
    $sql = "SELECT * FROM khuyenmai WHERE khuyenmaiid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $km = $result->fetch_assoc();

    // Lấy danh sách sản phẩm áp dụng
    $sql_sp = "SELECT sanpham_id FROM sanpham_khuyenmai WHERE khuyenmai_id = ?";
    $stmt_sp = $conn->prepare($sql_sp);
    $stmt_sp->bind_param("i", $id);
    $stmt_sp->execute();
    $result_sp = $stmt_sp->get_result();
    $sanpham_ids = [];
    while ($row_sp = $result_sp->fetch_assoc()) {
        $sanpham_ids[] = $row_sp['sanpham_id'];
    }

    $km['sanpham_ids'] = implode(',', $sanpham_ids);

    // Chuyển đổi định dạng ngày cho input datetime-local
    $km['ngaybatdau'] = date('Y-m-d\TH:i', strtotime($km['ngaybatdau']));
    $km['ngayketthuc'] = date('Y-m-d\TH:i', strtotime($km['ngayketthuc']));

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $km]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý xóa khuyến mãi
    if (isset($_POST['delete_khuyenmai'])) {
        try {
            $khuyenmaiid = $_POST['khuyenmaiid'];
            
            // Log thông tin xóa
            error_log("Deleting khuyenmai ID: " . $khuyenmaiid);
            
            $conn->begin_transaction();
            
            // Xóa các liên kết với sản phẩm trước
            $sql_delete_links = "DELETE FROM sanpham_khuyenmai WHERE khuyenmai_id = ?";
            $stmt_links = $conn->prepare($sql_delete_links);
            $stmt_links->bind_param("i", $khuyenmaiid);
            
            if (!$stmt_links->execute()) {
                throw new Exception("Không thể xóa liên kết sản phẩm: " . $stmt_links->error);
            }
            
            // Sau đó xóa khuyến mãi
            $sql_delete_khuyenmai = "DELETE FROM khuyenmai WHERE khuyenmaiid = ?";
            $stmt_khuyenmai = $conn->prepare($sql_delete_khuyenmai);
            $stmt_khuyenmai->bind_param("i", $khuyenmaiid);
            
            if (!$stmt_khuyenmai->execute()) {
                throw new Exception("Không thể xóa khuyến mãi: " . $stmt_khuyenmai->error);
            }
            
            $conn->commit();
            echo json_encode(['success' => true]);
            
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error deleting khuyenmai: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    // Xử lý thêm khuyến mãi mới
    if (isset($_POST['add_khuyenmai']) && !empty($_POST['khuyenmaiid'])) {
        try {
            $khuyenmaiid = intval($_POST['khuyenmaiid']);
            $tenkhuyenmai = $_POST['tenkhuyenmai'];
            $giatri = str_replace([',', '.'], '', $_POST['giatri']);
            $ngaybatdau = $_POST['ngaybatdau'];
            $ngayketthuc = $_POST['ngayketthuc'];
            $sanpham = isset($_POST['sanpham']) ? $_POST['sanpham'] : [];

            $conn->begin_transaction();

            // Cập nhật khuyến mãi
            $sql = "UPDATE khuyenmai SET tenkhuyenmai=?, giatri=?, ngaybatdau=?, ngayketthuc=? WHERE khuyenmaiid=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdssi", $tenkhuyenmai, $giatri, $ngaybatdau, $ngayketthuc, $khuyenmaiid);
            if (!$stmt->execute()) {
                throw new Exception("Không thể cập nhật khuyến mãi: " . $stmt->error);
            }

            // Xóa sản phẩm cũ
            $sql_del = "DELETE FROM sanpham_khuyenmai WHERE khuyenmai_id=?";
            $stmt_del = $conn->prepare($sql_del);
            $stmt_del->bind_param("i", $khuyenmaiid);
            $stmt_del->execute();

            // Thêm sản phẩm mới
            if (!empty($sanpham)) {
                $sql_sp = "INSERT INTO sanpham_khuyenmai (khuyenmai_id, sanpham_id) VALUES (?, ?)";
                $stmt_sp = $conn->prepare($sql_sp);
                foreach ($sanpham as $sp_id) {
                    $stmt_sp->bind_param("ii", $khuyenmaiid, $sp_id);
                    if (!$stmt_sp->execute()) {
                        throw new Exception("Không thể thêm sản phẩm khuyến mãi: " . $stmt_sp->error);
                    }
                }
            }

            $conn->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}

header('Content-Type: application/json');
?> 