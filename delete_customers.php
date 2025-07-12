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
            
            // Xóa khách hàng (loại = 3)
            $sql_delete_customer = "DELETE FROM nguoi_dung WHERE id = ? AND loai = 3";
            $stmt_customer = $conn->prepare($sql_delete_customer);
            $stmt_customer->bind_param("i", $id);
            
            if ($stmt_customer->execute()) {
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