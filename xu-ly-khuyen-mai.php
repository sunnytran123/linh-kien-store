<?php
require_once 'connect.php';
session_start();

// Thêm logging
error_log("Request received: " . print_r($_POST, true));

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
    if (isset($_POST['add_khuyenmai'])) {
        try {
            $tenkhuyenmai = $_POST['tenkhuyenmai'];
            $giatri = str_replace([',', '.'], '', $_POST['giatri']); // Chuyển đổi định dạng tiền về số
            $ngaybatdau = $_POST['ngaybatdau'];
            $ngayketthuc = $_POST['ngayketthuc'];
            $sanpham = isset($_POST['sanpham']) ? $_POST['sanpham'] : [];

            // Log dữ liệu đầu vào
            error_log("Input data: " . print_r([
                'tenkhuyenmai' => $tenkhuyenmai,
                'giatri' => $giatri,
                'ngaybatdau' => $ngaybatdau,
                'ngayketthuc' => $ngayketthuc,
                'sanpham' => $sanpham
            ], true));

            $conn->begin_transaction();

            // Thêm khuyến mãi
            $sql = "INSERT INTO khuyenmai (tenkhuyenmai, giatri, ngaybatdau, ngayketthuc) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdss", $tenkhuyenmai, $giatri, $ngaybatdau, $ngayketthuc);
            
            if (!$stmt->execute()) {
                throw new Exception("Không thể thêm khuyến mãi: " . $stmt->error);
            }
            
            $khuyenmai_id = $conn->insert_id;
            error_log("Inserted khuyenmai_id: " . $khuyenmai_id);

            // Thêm sản phẩm áp dụng
            if (!empty($sanpham)) {
                $sql_sanpham = "INSERT INTO sanpham_khuyenmai (khuyenmai_id, sanpham_id) VALUES (?, ?)";
                $stmt_sanpham = $conn->prepare($sql_sanpham);
                
                foreach ($sanpham as $sp_id) {
                    $stmt_sanpham->bind_param("ii", $khuyenmai_id, $sp_id);
                    if (!$stmt_sanpham->execute()) {
                        throw new Exception("Không thể thêm sản phẩm khuyến mãi: " . $stmt_sanpham->error);
                    }
                }
            }

            $conn->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}

header('Content-Type: application/json');
?> 