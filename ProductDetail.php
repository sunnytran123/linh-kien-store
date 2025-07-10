<?php
session_start();
require_once 'connect.php';
require_once 'functions.php';
// Lấy ID sản phẩm từ URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin sản phẩm sử dụng hàm từ connect.php
$product = getProductDetail($productId);

// Nếu không tìm thấy sản phẩm, chuyển hướng về trang chủ
if (!$product) {
    header('Location: home.php');
    exit();
}

// Lấy tổng tồn kho từ bảng sanpham_size
$sql = "SELECT SUM(soluong) as tonkho FROM sanpham_size WHERE sanphamid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$product['tonkho'] = $row['tonkho'] ?? 0;

// Lấy tất cả ảnh sản phẩm
$images = [];
$sql = "SELECT duongdan FROM hinhanhsanpham WHERE masanpham = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $images[] = $row['duongdan'];
}

// Lấy danh sách size còn hàng cho sản phẩm
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

// Lấy danh sách màu cho sản phẩm
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

// Chuẩn bị dữ liệu màu: kiểm tra màu nào còn size còn hàng
$colorAvailable = [];
foreach ($colors as $cl) {
    $sql = "SELECT COUNT(*) as cnt FROM sanpham_size ss JOIN sanpham_mausac sm ON ss.sanphamid = sm.sanphamid WHERE ss.sanphamid = ? AND sm.mausacid = ? AND ss.soluong > 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $productId, $cl['mausacid']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $colorAvailable[$cl['mausacid']] = $row['cnt'] > 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm - Phụ Kiện Giá Rẻ</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-size: 24px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        .top-bar {
            display: flex;
            justify-content: center; /* căn giữa theo chiều ngang */
            align-items: center;     /* căn giữa theo chiều dọc */
            background-color: #8BC34A;
            color: white;
            padding: 15px 20px;
            border-radius: 50px 80px 50px 80px;
            max-width:95%;
            height: 70px;
            margin: 10px auto;
            flex-wrap: nowrap;
                }

        .top-info, .top-links {
            display: flex;
            gap: 15px;
        }
        .top-info i {
            color: #FFD700; 
            margin-right: 5px;
        }

        .top-links a {
            color: white;
            text-decoration: none;
        }

        .top-links a:hover {
            text-decoration: underline;
        }


        header {
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .header-icons {
            display: flex;
            gap: 15px;
        }
        .header-icons i {
            color: #8BC34A; /* Màu vàng */
            margin-right: 5px;
        }
        .header-icons a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #8BC34A;
            text-decoration: none;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            padding: 15px 50px;
            background: #f5f5f5;
            gap: 8px;
            font-size: 16px;
        }
        .breadcrumb a {
            color: #8BC34A;
            text-decoration: none;
            font-size: 16px;
        }
        .breadcrumb span {
            color: #666;
            font-size: 16px;
        }
        .breadcrumb-separator {
            color: #bbb;
            font-size: 18px;
            margin: 0 4px;
            display: flex;
            align-items: center;
            user-select: none;
        }

        .product-detail {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            display: flex;
            gap: 40px;
        }

        .product-gallery {
            flex: 1;
            /* max-width: 420px; */
        }
        #productCarousel {
            width: 100%;
            height: 550px;
            border-radius: 8px;
            overflow: hidden;
            background: #f5f5f5;
            margin-bottom: 20px;
        }
        #productCarousel .carousel-inner {
            width: 100%;
            height: 550px;
        }
        #productCarousel img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .thumbnail-images {
            display: flex;
            gap: 10px;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            overflow: hidden;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail:hover {
            border-color: #8BC34A;
        }

        .product-info {
            flex: 1;
        }

        .product-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
        }

        .product-code {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .product-price {
            font-size: 32px;
            color: #8BC34A;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .stock-status {
            color: #4CAF50;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .product-actions {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-selector button {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .quantity-selector input {
            width: 50px;
            height: 30px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .button-group {
            display: flex;
            gap: 15px;
        }

        .add-to-cart-btn, .buy-now-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn {
            background-color: #8BC34A;
            color: white;
        }

        .buy-now-btn {
            background-color: #ff4d4d;
            color: white;
        }

        .add-to-cart-btn:hover {
            background-color: #7cb342;
        }

        .buy-now-btn:hover {
            background-color: #ff3333;
        }

        .add-to-cart-btn:disabled, .buy-now-btn:disabled {
            background-color: #cccccc !important;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .stock-status {
            margin: 15px 0;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .product-price {
            font-size: 24px;
            color: #8BC34A;
            font-weight: bold;
            margin: 10px 0;
        }

        .product-code {
            color: #666;
            margin: 5px 0;
        }

        .product-description {
            margin: 15px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
            white-space: pre-line;
            line-height: 0.8;
            font-size: 40px;
        }

        .specs-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .specs-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            line-height: 1.6;
        }

        .specs-list li:last-child {
            border-bottom: none; 
        }

        .specs-list strong {
            color: #333;
            margin-right: 10px;
            min-width: 150px;
            display: inline-block;
        }

        .description-tabs {
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            padding-bottom: 20px;
        }

        .tab-button {
            padding: 10px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 24px;
            font-weight: bold;
            color: #666;
            margin-bottom: 10px;
        }

        .tab-button.active {
            color: #8BC34A;
            border-bottom: 2px solid #8BC34A;
        }

        .tab-content {
            font-size: 16px;
            line-height: 1.6;
            color: #666;
        }

        .shipping-info {
            margin-top: 20px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .shipping-info h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }

        .shipping-info p {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .product-features {
            margin-top: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 16px;
            color: #666;
        }

        .feature-item i {
            color: #8BC34A;
            margin-right: 10px;
        }
        footer {
            background-color: #333;
            color: white;
            padding: 40px 0 20px;
            margin-top: 50px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 0 20px;
        }

        .footer-section h3 {
            color: #8BC34A;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .footer-section p, 
        .footer-section a {
            color: #fff;
            text-decoration: none;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #8BC34A;
        }

        .footer-section i {
            color: #8BC34A;
            width: 20px;
            text-align: center;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            margin-top: 30px;
            border-top: 1px solid #444;
            color: #888;
        }

        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .footer-section {
                text-align: center;
            }

            .footer-section p, 
            .footer-section a {
                justify-content: center;
            }
        }

        button:disabled {
            background-color: #cccccc !important;
            cursor: not-allowed;
            opacity: 0.7;
        }

        input:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            background-color: #fff;
            min-width: 200px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            border-radius: 4px;
            padding: 8px;
            z-index: 1000;
        }

        .dropdown-menu li {
            list-style: none;
        }

        .dropdown-menu li a {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s;
        }

        .dropdown-menu li a:hover {
            background-color: #f5f5f5;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu i {
            margin-right: 8px;
            color: #8BC34A;
        }

        .cart-count {
            background-color: #8BC34A;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: relative;
            top: -10px;
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-icons a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-icons i {
            color: #8BC34A;
        }

        /* CSS cho thông báo khi hết hàng */
        .out-of-stock {
            color: #f44336;
            font-size: 16px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .out-of-stock i {
            font-size: 18px;
        }

        .price-container {
            margin: 10px 0;
        }

        .price-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .original-price {
            color: #999;
            text-decoration: line-through;
            font-size: 14px;
        }

        .discounted-price, .normal-price {
            color: #ff4d4d;
            font-size: 18px;
            font-weight: bold;
        }

        .discount-badge {
            background: #ff4d4d;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 12px;
            display: inline-block;
            margin-left: 5px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            margin: 0 15px;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #8BC34A;
        }

        nav .dropdown-menu a {
            text-decoration: none;
            padding: 8px 16px;
            display: block;
            color: #333;
            margin: 0;
        }

        nav .dropdown-menu a:hover {
            background: #f5f5f5;
            color: #8BC34A;
        }
        .color-options, .size-options {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 18px;
            align-items: center;
        }
        .color-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            border: 2px solid #eee;
            border-radius: 8px;
            background: #fff;
            padding: 8px 16px 8px 8px;
            cursor: pointer;
            font-size: 16px;
            transition: border 0.2s, box-shadow 0.2s;
            min-width: 80px;
            min-height: 40px;
            position: relative;
        }
        .color-btn .color-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 1.5px solid #ccc;
            display: inline-block;
            background: #eee;
        }
        .color-btn.active, .color-btn:hover {
            border: 2px solid #8BC34A;
            box-shadow: 0 2px 8px rgba(140,195,74,0.08);
        }
        .size-btn {
            border: 2px solid #eee;
            border-radius: 8px;
            background: #fff;
            padding: 8px 24px;
            font-size: 16px;
            cursor: pointer;
            min-width: 60px;
            min-height: 40px;
            transition: border 0.2s, box-shadow 0.2s;
            margin-right: 10px;
        }
        .size-btn.active, .size-btn:hover {
            border: 2px solid #8BC34A;
            box-shadow: 0 2px 8px rgba(140,195,74,0.08);
        }
        .size-btn:disabled, .color-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .product-options-label {
            min-width: 90px;
            display: inline-block;
            font-size: 16px;
            color: #444;
            margin-bottom: 6px;
        }
        .modal-overlay {
            position: fixed;
            z-index: 9999;
            left: 0; top: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: #fff;
            border-radius: 12px;
            max-width: 98vw;
            width: 600px;
            padding: 32px 28px 28px 28px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.18);
            position: relative;
            animation: modalShow 0.2s;
        }
        .modal-content h2 {
            text-align: center;
            color: #8BC34A;
            margin-bottom: 18px;
            font-size: 1.7rem;
            letter-spacing: 1px;
        }
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 18px;
            align-items: flex-start;
        }
        .contact-info-row {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #f4f8f3;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 1.05rem;
            color: #333;
            box-shadow: 0 1px 4px rgba(140,195,74,0.06);
            transition: background 0.2s;
        }
        .contact-info-row:hover {
            background: #e8f5e9;
        }
        .contact-info-row .icon {
            background: #8BC34A;
            color: #fff;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: background 0.2s;
        }
        .contact-info-row:hover .icon {
            background: #689f38;
        }
        @keyframes modalShow {
            from { transform: translateY(-40px); opacity: 0;}
            to { transform: translateY(0); opacity: 1;}
        }
        .close-modal {
            position: absolute;
            top: 10px; right: 16px;
            font-size: 28px;
            color: #888;
            cursor: pointer;
        }
        @media (max-width: 700px) {
            .modal-content {
                width: 98vw;
                padding: 12px 2vw 12px 2vw;
            }
            .footer-map iframe {
                min-height: 180px;
                height: 180px;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <div class="breadcrumb">
        <a href="home.php">Trang chủ</a>
        <span class="breadcrumb-separator">&gt;</span>
        <a href="?category=<?php echo $product['madanhmuc']; ?>"><?php echo $product['tendanhmuc']; ?></a>
        <span class="breadcrumb-separator">&gt;</span>
        <span><?php echo $product['tensanpham']; ?></span>
    </div>

    <div class="product-detail">
        <div class="product-gallery">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $idx => $img): ?>
                        <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                            <img src="picture/<?php echo $img; ?>" class="d-block w-100" alt="Ảnh sản phẩm">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($images) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-info">
            <h1 class="product-title"><?php echo $product['tensanpham']; ?></h1>
            <div class="product-code">Mã sản phẩm: <?php echo $product['sanphamid']; ?></div>
            <div class="price-container">
                <?php 
                $promotion = getProductPromotion($conn, $product['sanphamid']);
                if ($promotion): 
                    $discounted_price = calculateDiscountedPrice($product['gia'], $promotion);
                ?>
                    <div class="price-wrapper">
                        <span class="original-price"><?php echo number_format($product['gia'], 0, ',', '.'); ?>đ</span>
                        <span class="discounted-price"><?php echo number_format($discounted_price, 0, ',', '.'); ?>đ</span>
                    </div>
                    <span class="discount-badge">-<?php echo $promotion['giatrigiamgia']; ?>%</span>
                <?php else: ?>
                    <span class="normal-price"><?php echo number_format($product['gia'], 0, ',', '.'); ?>đ</span>
                <?php endif; ?>
            </div>
            
            <div class="stock-status">
                <?php if ($product['tonkho'] > 0): ?>
                    <i class="fas fa-check-circle" style="color: #4CAF50;"></i> Còn hàng
                <?php else: ?>
                    <i class="fas fa-times-circle" style="color: #f44336;"></i> Hết hàng
                <?php endif; ?>
            </div>

            <div class="product-actions">
                <div class="product-options" style="margin-bottom: 16px;">
                    <?php if (count($colors) > 0): ?>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span class="product-options-label">Màu Sắc</span>
                            <div class="color-options">
                                <?php foreach ($colors as $cl): ?>
                                    <button type="button" class="color-btn" data-color-id="<?php echo $cl['mausacid']; ?>" style="<?php if($cl['mamau']) echo 'background: #fff;'; ?>" <?php echo !$colorAvailable[$cl['mausacid']] ? 'disabled' : ''; ?>>
                                        <span class="color-dot" style="background: <?php echo htmlspecialchars($cl['mamau']); ?>;"></span>
                                        <?php echo htmlspecialchars($cl['tenmau']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (count($sizes) > 0): ?>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <span class="product-options-label">Size</span>
                            <div class="size-options">
                                <?php foreach ($sizes as $sz): ?>
                                    <button type="button" class="size-btn" data-size-id="<?php echo $sz['sizeid']; ?>" <?php echo $sz['soluong'] <= 0 ? 'disabled' : ''; ?>>
                                        <?php echo htmlspecialchars($sz['kichco']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="quantity-selector">
                    <button onclick="decreaseQuantity()" <?php echo $product['tonkho'] <= 0 ? 'disabled' : ''; ?>>-</button>
                    <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['tonkho']; ?>" 
                           <?php echo $product['tonkho'] <= 0 ? 'disabled' : ''; ?>>
                    <button onclick="increaseQuantity()" <?php echo $product['tonkho'] <= 0 ? 'disabled' : ''; ?>>+</button>
                </div>

                <div class="button-group">
                    <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product['sanphamid']; ?>)" 
                            <?php echo $product['tonkho'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>

                    <button class="buy-now-btn" onclick="buyNow(<?php echo $product['sanphamid']; ?>)"
                            <?php echo $product['tonkho'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-bolt"></i> Mua ngay
                    </button>
                </div>
            </div>

            <div class="product-features">
                <div class="feature-item">
                    <i class="fas fa-check"></i> Bảo hành chính hãng 12 tháng
                </div>
                <div class="feature-item">
                    <i class="fas fa-truck"></i> Giao hàng toàn quốc
                </div>
                <div class="feature-item">
                    <i class="fas fa-undo"></i> Đổi trả trong 7 ngày
                </div>
            </div>

            <div class="shipping-info">
                <h3>Thông tin vận chuyển</h3>
                <p>- Giao hàng nhanh trong 2h tại TP.HCM</p>
                <p>- Giao hàng tiêu chuẩn 2-3 ngày toàn quốc</p>
                <p>- Miễn phí giao hàng cho đơn từ 500.000đ</p>
            </div>
        </div>
    </div>

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="product-description">
            <div class="description-tabs">
                 <button class="tab-button active">Mô tả sản phẩm</button>
                <h5 style="margin-top: 10px;"><?php echo $product['tensanpham']; ?></h5>
                <?php 
                echo nl2br($product['mota']); 
                ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <div id="contactModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
      <span class="close-modal" id="closeContactModal">&times;</span>
      <h2>Liên hệ Sunny Store</h2>
      <div class="contact-info">
        <div class="contact-info-row"><span class="icon"><i class="fas fa-map-marker-alt"></i></span>164 Mỹ Tân, Đầm Dơi, Cà Mau</div>
        <div class="contact-info-row"><span class="icon"><i class="fas fa-phone"></i></span>0914090763</div>
        <div class="contact-info-row"><span class="icon"><i class="fas fa-envelope"></i></span>phuongthuy091203@gmail.com</div>
      </div>
      <div class="footer-map" style="margin-top: 15px;">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126076.99581562124!2d105.2371718!3d9.0723171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a1450040a46c09%3A0xbb0c457bde4f5702!2sCh%C3%AD%20Khanh!5e0!3m2!1svi!2s!4v1752059828388!5m2!1svi!2s" width="100%" height="320" style="border:0; border-radius:8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>

    <script>
        function decreaseQuantity() {
            var input = document.getElementById('quantity');
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function increaseQuantity() {
            var input = document.getElementById('quantity');
            var max = parseInt(input.getAttribute('max'));
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function addToCart(productId) {
            const quantity = parseInt(document.getElementById('quantity').value);
            // Lấy sizeid từ nút size đang active
            const activeSizeBtn = document.querySelector('.size-btn.active');
            if (!activeSizeBtn) {
                alert('Vui lòng chọn size');
                return;
            }
            const sizeId = activeSizeBtn.getAttribute('data-size-id');
            // Lấy colorid từ nút màu đang active
            const activeColorBtn = document.querySelector('.color-btn.active');
            if (!activeColorBtn) {
                alert('Vui lòng chọn màu sắc');
                return;
            }
            const colorId = activeColorBtn.getAttribute('data-color-id');
            if (isNaN(quantity) || quantity <= 0) {
                alert('Vui lòng nhập số lượng hợp lệ');
                return;
            }
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}&sizeid=${sizeId}&colorid=${colorId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã thêm sản phẩm vào giỏ hàng');
                    window.location.href = 'cart.php';
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi kết nối với server');
            });
        }

        function buyNow(productId) {
            const quantity = parseInt(document.getElementById('quantity').value);
            
            if (isNaN(quantity) || quantity <= 0) {
                alert('Vui lòng nhập số lượng hợp lệ');
                return;
            }
            
            // Gửi request đến buy_now.php
            fetch('buy_now.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Chuyển hướng đến trang thanh toán
                    window.location.href = 'checkout.php?buy_now=1';
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi kết nối với server');
            });
        }

        // Chọn màu
        const colorBtns = document.querySelectorAll('.color-btn');
        colorBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                colorBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
        // Chọn size
        const sizeBtns = document.querySelectorAll('.size-btn');
        sizeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                sizeBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.getElementById('openContactModal').onclick = function(e) {
      e.preventDefault();
      document.getElementById('contactModal').style.display = 'flex';
    };
    document.getElementById('closeContactModal').onclick = function() {
      document.getElementById('contactModal').style.display = 'none';
    };
    document.getElementById('contactModal').onclick = function(e) {
      if (e.target === this) this.style.display = 'none';
    };
    </script>
</body>
</html>