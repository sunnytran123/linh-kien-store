<?php
session_start();
include 'connect.php';
// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
    header("Location: login.php");
    exit();
}
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    if ($productId <= 0 || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }
    
    // Cập nhật số lượng trong giỏ hàng
    $sql = "UPDATE chitietgiohang cg 
            JOIN giohang g ON cg.magiohang = g.giohangid 
            SET cg.soluong = ? 
            WHERE g.manguoidung = ? AND cg.masanpham = ?";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $quantity, $_SESSION['id'], $productId);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật số lượng']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}
?> 