<?php
require_once 'connect.php';
session_start();

// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
    echo json_encode(['status' => 'error', 'message' => 'Không có quyền truy cập']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids'];
    
    if (empty($ids)) {
        echo json_encode(['status' => 'error', 'message' => 'Không có ID nào được chọn']);
        exit();
    }
    
    // Bắt đầu transaction
    $conn->begin_transaction();
    
    try {
        $deleted_count = 0;
        
        foreach ($ids as $id) {
            $id = intval($id);
            
            // Xóa các liên kết với sản phẩm trước
            $sql_delete_links = "DELETE FROM sanpham_khuyenmai WHERE khuyenmai_id = ?";
            $stmt_links = $conn->prepare($sql_delete_links);
            $stmt_links->bind_param("i", $id);
            $stmt_links->execute();
            
            // Sau đó xóa khuyến mãi
            $sql_delete_khuyenmai = "DELETE FROM khuyenmai WHERE khuyenmaiid = ?";
            $stmt_khuyenmai = $conn->prepare($sql_delete_khuyenmai);
            $stmt_khuyenmai->bind_param("i", $id);
            
            if ($stmt_khuyenmai->execute()) {
                $deleted_count++;
            }
        }
        
        $conn->commit();
        echo json_encode(['status' => 'success', 'deleted_count' => $deleted_count]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
}
?> 