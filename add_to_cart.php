<?php
session_start();

// Kiểm tra session
if (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
    header("Location: login.php");
    exit();
}
include 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $sizeId = isset($_POST['sizeid']) ? (int)$_POST['sizeid'] : 0;
    $colorId = isset($_POST['colorid']) ? (int)$_POST['colorid'] : 0;
    
    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
        exit;
    }
    
    if ($quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Số lượng không hợp lệ']);
        exit;
    }
    if ($sizeId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn size']);
        exit;
    }
    if ($colorId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn màu sắc']);
        exit;
    }
    
    $result = addToCart($_SESSION['id'], $productId, $quantity, $sizeId, $colorId);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}
?> 