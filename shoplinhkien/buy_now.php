<?php
session_start();
require_once 'connect.php';
require_once 'functions.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để mua hàng']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    if ($productId <= 0 || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }
    
    // Lấy thông tin sản phẩm
    $stmt = $conn->prepare("SELECT gia FROM sanpham WHERE sanphamid = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        exit;
    }

    // Chỉ lưu thông tin vào session, không tạo đơn hàng
    $_SESSION['buy_now'] = [
        'product_id' => $productId,
        'quantity' => $quantity,
        'price' => $product['gia']
    ];
    
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']); 