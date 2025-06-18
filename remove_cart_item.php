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
    
    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }
    
    // Xóa sản phẩm khỏi giỏ hàng
    $sql = "DELETE cg FROM chitietgiohang cg 
            JOIN giohang g ON cg.magiohang = g.giohangid 
            WHERE g.manguoidung = ? AND cg.masanpham = ?";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION['id'], $productId);
    
    if (mysqli_stmt_execute($stmt)) {
        // Kiểm tra và xóa giỏ hàng nếu không còn sản phẩm nào
        $checkEmpty = "DELETE g FROM giohang g 
                      LEFT JOIN chitietgiohang cg ON g.giohangid = cg.magiohang 
                      WHERE g.manguoidung = ? AND cg.chitietgiohangid IS NULL";
        $stmtCheck = mysqli_prepare($conn, $checkEmpty);
        mysqli_stmt_bind_param($stmtCheck, "i", $_SESSION['id']);
        mysqli_stmt_execute($stmtCheck);
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa sản phẩm']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}
?> 