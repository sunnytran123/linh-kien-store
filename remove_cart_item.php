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
    $cartItemId = isset($_POST['cart_item_id']) ? (int)$_POST['cart_item_id'] : 0;
    
    if ($cartItemId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }
    
    // Kiểm tra xem item có thuộc về user hiện tại không
    $checkSql = "SELECT cg.chitietgiohangid, g.giohangid 
                 FROM chitietgiohang cg 
                 JOIN giohang g ON cg.magiohang = g.giohangid 
                 WHERE g.manguoidung = ? AND cg.chitietgiohangid = ?";
                 
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "ii", $_SESSION['id'], $cartItemId);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
        exit;
    }
    
    $item = $result->fetch_assoc();
    
    // Xóa sản phẩm khỏi giỏ hàng
    $sql = "DELETE FROM chitietgiohang WHERE chitietgiohangid = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $cartItemId);
    
    if (mysqli_stmt_execute($stmt)) {
        // Kiểm tra và xóa giỏ hàng nếu không còn sản phẩm nào
        $checkEmpty = "DELETE g FROM giohang g 
                      LEFT JOIN chitietgiohang cg ON g.giohangid = cg.magiohang 
                      WHERE g.giohangid = ? AND cg.chitietgiohangid IS NULL";
        $stmtCheck = mysqli_prepare($conn, $checkEmpty);
        mysqli_stmt_bind_param($stmtCheck, "i", $item['giohangid']);
        mysqli_stmt_execute($stmtCheck);
        
        echo json_encode(['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa sản phẩm']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}
?> 