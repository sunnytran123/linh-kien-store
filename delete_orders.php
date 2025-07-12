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
            
            // Xóa chi tiết đơn hàng trước
            $sql_delete_details = "DELETE FROM chitietdonhang WHERE madonhang = ?";
            $stmt_details = $conn->prepare($sql_delete_details);
            $stmt_details->bind_param("i", $id);
            $stmt_details->execute();
            
            // Sau đó xóa đơn hàng
            $sql_delete_order = "DELETE FROM donhang WHERE donhangid = ?";
            $stmt_order = $conn->prepare($sql_delete_order);
            $stmt_order->bind_param("i", $id);
            
            if ($stmt_order->execute()) {
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