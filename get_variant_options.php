<?php
session_start();
require_once 'connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit();
}

if (!isset($_POST['cart_item_id'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm']);
    exit();
}

$cartItemId = (int)$_POST['cart_item_id'];
$userId = $_SESSION['id'];

// Lấy thông tin sản phẩm từ giỏ hàng
$sql = "SELECT c.masanpham, c.sizeid, c.mausacid, s.tensanpham 
        FROM chitietgiohang c 
        JOIN giohang g ON c.magiohang = g.giohangid 
        JOIN sanpham s ON c.masanpham = s.sanphamid 
        WHERE c.chitietgiohangid = ? AND g.manguoidung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cartItemId, $userId);
$stmt->execute();
$result = $stmt->get_result();

// Debug: Log the query parameters
error_log("Cart Item ID: " . $cartItemId . ", User ID: " . $userId);
error_log("Query result rows: " . $result->num_rows);

// Debug: Check if there are any cart items for this user
$debugSql = "SELECT COUNT(*) as count FROM chitietgiohang c JOIN giohang g ON c.magiohang = g.giohangid WHERE g.manguoidung = ?";
$debugStmt = $conn->prepare($debugSql);
$debugStmt->bind_param("i", $userId);
$debugStmt->execute();
$debugResult = $debugStmt->get_result();
$debugRow = $debugResult->fetch_assoc();
error_log("Total cart items for user: " . $debugRow['count']);

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng']);
    exit();
}

$cartItem = $result->fetch_assoc();
$productId = $cartItem['masanpham'];
$currentSizeId = $cartItem['sizeid'];
$currentColorId = $cartItem['mausacid'];

// Lấy danh sách màu sắc có sẵn cho sản phẩm
$colors = [];
$sql = "SELECT ms.mausacid, ms.tenmau, ms.mamau 
        FROM sanpham_mausac sm 
        JOIN mausac ms ON sm.mausacid = ms.mausacid 
        WHERE sm.sanphamid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $colors[] = $row;
}

// Lấy danh sách size có sẵn cho sản phẩm
$sizes = [];
$sql = "SELECT sz.sizeid, sz.kichco, ss.soluong 
        FROM sanpham_size ss 
        JOIN size sz ON ss.sizeid = sz.sizeid 
        WHERE ss.sanphamid = ? AND ss.soluong > 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $sizes[] = $row;
}

// Tạo HTML cho modal
$html = '';
// Không hiển thị tên sản phẩm hoặc tiêu đề
// Màu sắc
$html .= '<div style="margin-bottom: 20px;">';
$html .= '<label style="display: block; margin-bottom: 10px; color: #333;">Màu sắc:</label>';
$html .= '<div style="display: flex; flex-wrap: wrap; gap: 10px;">';

foreach ($colors as $color) {
    $checked = ($color['mausacid'] == $currentColorId) ? 'checked' : '';
    $html .= '<label style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; border: 2px solid #eee; border-radius: 6px; cursor: pointer; background: #fff;">';
    $html .= '<input type="radio" name="color" value="' . $color['mausacid'] . '" ' . $checked . ' style="margin: 0;">';
    $html .= '<span style="width: 20px; height: 20px; border-radius: 50%; background: ' . htmlspecialchars($color['mamau']) . '; border: 1px solid #ccc;"></span>';
    $html .= '<span style="font-size: 14px;">' . htmlspecialchars($color['tenmau']) . '</span>';
    $html .= '</label>';
}

$html .= '</div></div>';

// Kích thước
$html .= '<div style="margin-bottom: 20px;">';
$html .= '<label style="display: block;  margin-bottom: 10px; color: #333;">Kích thước:</label>';
$html .= '<div style="display: flex; flex-wrap: wrap; gap: 10px;">';

foreach ($sizes as $size) {
    $checked = ($size['sizeid'] == $currentSizeId) ? 'checked' : '';
    $html .= '<label style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; border: 2px solid #eee; border-radius: 6px; cursor: pointer; background: #fff;">';
    $html .= '<input type="radio" name="size" value="' . $size['sizeid'] . '" ' . $checked . ' style="margin: 0;">';
    $html .= '<span style="font-size: 14px;">' . htmlspecialchars($size['kichco']) . '</span>';
    $html .= '<span style="font-size: 12px; color: #666;">(' . $size['soluong'] . ' có sẵn)</span>';
    $html .= '</label>';
}

$html .= '</div></div>';

// Buttons
$html .= '<div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">';
$html .= '<button onclick="closeEditVariantModal()" style="padding: 10px 20px; border: 1px solid #ddd; background: #fff; border-radius: 6px; cursor: pointer; font-size: 14px;">Hủy</button>';
$html .= '<button onclick="updateVariant()" style="padding: 10px 20px; border: none; background: #8BC34A; color: white; border-radius: 6px; cursor: pointer; font-size: 14px;">Cập nhật</button>';
$html .= '</div></div>';

// Debug: Log the HTML length
error_log("Generated HTML length: " . strlen($html));

echo json_encode(['success' => true, 'html' => $html]);
?> 