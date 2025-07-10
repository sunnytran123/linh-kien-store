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
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    if ($cartItemId <= 0 || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }
    
    // Kiểm tra xem item có thuộc về user hiện tại không
    $checkSql = "SELECT cg.chitietgiohangid, cg.soluong, s.gia, s.tensanpham, ss.soluong as tonkho
                 FROM chitietgiohang cg 
                 JOIN giohang g ON cg.magiohang = g.giohangid 
                 JOIN sanpham s ON cg.masanpham = s.sanphamid
                 LEFT JOIN sanpham_size ss ON s.sanphamid = ss.sanphamid
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
    
    // Kiểm tra tồn kho
    if ($quantity > $item['tonkho']) {
        echo json_encode(['success' => false, 'message' => 'Số lượng vượt quá tồn kho (Tồn kho: ' . $item['tonkho'] . ')']);
        exit;
    }
    
    // Cập nhật số lượng trong giỏ hàng
    $sql = "UPDATE chitietgiohang SET soluong = ? WHERE chitietgiohangid = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $quantity, $cartItemId);
    
    if (mysqli_stmt_execute($stmt)) {
        // Tính tổng tiền mới
        $newSubtotal = $item['gia'] * $quantity;
        
        echo json_encode([
            'success' => true, 
            'new_quantity' => $quantity,
            'new_subtotal' => number_format($newSubtotal, 0, ',', '.'),
            'message' => 'Cập nhật số lượng thành công'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật số lượng']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}
?> 