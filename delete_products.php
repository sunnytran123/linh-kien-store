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
            
            // Xóa hình ảnh liên quan trước
            $sql_delete_images = "DELETE FROM hinhanhsanpham WHERE masanpham = ?";
            $stmt_images = $conn->prepare($sql_delete_images);
            $stmt_images->bind_param("i", $id);
            $stmt_images->execute();
            
            // Xóa liên kết size
            $sql_delete_size = "DELETE FROM sanpham_size WHERE sanphamid = ?";
            $stmt_size = $conn->prepare($sql_delete_size);
            $stmt_size->bind_param("i", $id);
            $stmt_size->execute();
            
            // Xóa liên kết màu sắc
            $sql_delete_color = "DELETE FROM sanpham_mausac WHERE sanphamid = ?";
            $stmt_color = $conn->prepare($sql_delete_color);
            $stmt_color->bind_param("i", $id);
            $stmt_color->execute();
            
            // Xóa liên kết khuyến mãi
            $sql_delete_promo = "DELETE FROM sanpham_khuyenmai WHERE sanpham_id = ?";
            $stmt_promo = $conn->prepare($sql_delete_promo);
            $stmt_promo->bind_param("i", $id);
            $stmt_promo->execute();
            
            // Sau đó xóa sản phẩm
            $sql_delete_product = "DELETE FROM sanpham WHERE sanphamid = ?";
            $stmt_product = $conn->prepare($sql_delete_product);
            $stmt_product->bind_param("i", $id);
            
            if ($stmt_product->execute()) {
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