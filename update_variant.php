<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit();
}

if (!isset($_POST['cart_item_id']) || !isset($_POST['color_id']) || !isset($_POST['size_id'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin cần thiết']);
    exit();
}

$cartItemId = (int)$_POST['cart_item_id'];
$colorId = (int)$_POST['color_id'];
$sizeId = (int)$_POST['size_id'];
$userId = $_SESSION['id'];

// Kiểm tra xem cart item có thuộc về user này không
$sql = "SELECT c.masanpham, c.soluong 
        FROM chitietgiohang c 
        JOIN giohang g ON c.magiohang = g.giohangid 
        WHERE c.chitietgiohangid = ? AND g.manguoidung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cartItemId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng']);
    exit();
}

$cartItem = $result->fetch_assoc();
$productId = $cartItem['masanpham'];
$quantity = $cartItem['soluong'];

// Kiểm tra xem màu sắc có tồn tại cho sản phẩm này không
$sql = "SELECT COUNT(*) as count FROM sanpham_mausac WHERE sanphamid = ? AND mausacid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $productId, $colorId);
$stmt->execute();
$result = $stmt->get_result();
$colorExists = $result->fetch_assoc()['count'] > 0;

if (!$colorExists) {
    echo json_encode(['success' => false, 'message' => 'Màu sắc không có sẵn cho sản phẩm này']);
    exit();
}

// Kiểm tra xem size có tồn tại và còn hàng không
$sql = "SELECT ss.soluong FROM sanpham_size ss WHERE ss.sanphamid = ? AND ss.sizeid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $productId, $sizeId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Kích thước không có sẵn cho sản phẩm này']);
    exit();
}

$sizeInfo = $result->fetch_assoc();
if ($sizeInfo['soluong'] < $quantity) {
    echo json_encode(['success' => false, 'message' => 'Số lượng trong kho không đủ cho kích thước này']);
    exit();
}

// Cập nhật phân loại sản phẩm
$sql = "UPDATE chitietgiohang SET sizeid = ?, mausacid = ? WHERE chitietgiohangid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $sizeId, $colorId, $cartItemId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Đã cập nhật phân loại sản phẩm thành công']);
} else {
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật']);
}
?> 