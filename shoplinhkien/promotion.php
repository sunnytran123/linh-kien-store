<?php
session_start();
require_once 'connect.php';
require_once 'functions.php';

// Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kiểm tra các function có tồn tại không
if (!function_exists('getCartItemCount')) {
    die('Function getCartItemCount không tồn tại');
}

if (!function_exists('getProductPromotion')) {
    die('Function getProductPromotion không tồn tại');
}

if (!function_exists('calculateDiscountedPrice')) {
    die('Function calculateDiscountedPrice không tồn tại');
}

// Lấy thông tin người dùng nếu đã đăng nhập
if(isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $userQuery = "SELECT * FROM nguoi_dung WHERE id = ?";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $userInfo = $userResult->fetch_assoc();
}

// Lấy danh sách khuyến mãi đang diễn ra
$sql = "SELECT k.*, GROUP_CONCAT(s.tensanpham) as products 
        FROM khuyenmai k 
        LEFT JOIN sanpham_khuyenmai sk ON k.khuyenmaiid = sk.khuyenmai_id 
        LEFT JOIN sanpham s ON sk.sanpham_id = s.sanphamid 
        WHERE NOW() BETWEEN k.ngaybatdau AND k.ngayketthuc 
        GROUP BY k.khuyenmaiid";
$result = $conn->query($sql);

// Phần hiển thị số lượng giỏ hàng
function displayCartCount($userId) {
    if(isset($userId)) {
        $count = getCartItemCount($userId);
        return "<span class='cart-count'>$count</span>";
    }
    return '';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khuyến Mãi Đang Diễn Ra</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
                * {
            margin: 0;
            padding: 0;
            font-size: 24px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        .promotion-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .promotion-card {
            background: linear-gradient(135deg, #8BC34A, #689F38);
            color: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(139, 195, 74, 0.2);
            transition: transform 0.3s ease;
        }

        .promotion-card:hover {
            transform: translateY(-5px);
        }

        .promotion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .promotion-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .promotion-period {
            font-size: 14px;
            opacity: 0.9;
            color: #fff;
        }

        .promotion-discount {
            font-size: 32px;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .promotion-products {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .promotion-products h4 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #fff;
        }

        .breadcrumb {
            max-width: 1200px;
            margin: 20px auto;
            padding: 10px 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #8BC34A;
        }

        .breadcrumb i {
            margin: 0 10px;
            color: #999;
        }
        .top-bar {
            background-color: #8BC34A;
            color: white;
            padding: 15px 20px;
            border-radius: 50px 80px 50px 80px;
            max-width: 95%;
            height: 70px;
            margin: 10px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                height: auto;
                padding: 10px;
                gap: 10px;
            }

            .top-info, .top-links {
                flex-wrap: wrap;
                justify-content: center;
                text-align: center;
            }
        }
         /* CSS cho header mới */
        header {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #8BC34A;
            text-decoration: none;
            transition: color 0.3s;
            margin-left: 30px;
        }

        .logo:hover {
            color: #689F38;
        }

        nav a {
            text-decoration: none;
            color: #333;
            margin: 0 15px;
        }
        nav a:hover{
            text-decoration: none;
            color: #8BC34A;
            margin: 0 15px;
        }
        /* CSS cho dropdown trong nav */
        nav .dropdown {
            position: relative;
        }

        nav .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px 0;
            min-width: 200px;
            display: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        nav .dropdown:hover .dropdown-menu {
            display: block;
        }

        nav .dropdown-menu li {
            list-style: none;
        }

        nav .dropdown-menu a {
            padding: 8px 16px;
            display: block;
            color: #333;
            text-decoration: none;
        }

        nav .dropdown-menu a:hover {
            background-color: #f5f5f5;
            color: #8BC34A;
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-icons a {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-icons i {
            color: #8BC34A;
        }


        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 240px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            border-radius: 4px;
            padding: 8px 0;
            z-index: 1000;
        }

        .dropdown-menu li {
            list-style: none;
        }

        .dropdown-menu li a {
            display: block;
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
                /* CSS cho footer */
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
            .top-bar {
                flex-direction: column;
                height: auto;
                padding: 10px;
                gap: 10px;
            }

            .top-info, .top-links {
                flex-wrap: wrap;
                justify-content: center;
                text-align: center;
            }

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


        /* CSS cho cart count */
        .cart-count {
            background: #8BC34A;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 12px;
            margin-left: 5px;
        }
        @media (max-width: 768px) {
            .top-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            header {
                padding: 10px;
            }

            .header-icons {
                gap: 10px;
            }
        }
         /* Thêm style cho trường hợp không có khuyến mãi */
         .no-promotion {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .no-promotion i {
            font-size: 48px;
            color: #999;
            margin-bottom: 20px;
        }

        .no-promotion p {
            color: #666;
            margin-bottom: 20px;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #8BC34A;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #7CB342;
        }

        @media (max-width: 768px) {
            .promotion-card {
                padding: 15px;
            }

            .promotion-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .promotion-title {
                font-size: 20px;
            }

            .promotion-discount {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<div class="top-bar">
        <div class="top-info">
            <span><i class="fas fa-map-marker-alt"></i>Trà Vinh</span>
            <span><i class="fas fa-envelope"></i>tuyenvt240384@sv-onuni.edu.vn</span>
        </div>
        <div class="top-links">
            <a href="#">Chính sách bảo mật</a> |
            <a href="#">Điều khoản sử dụng</a> |
            <a href="#">Hoàn trả & Đổi trả</a>
        </div>
    </div>
    
    <header>
        <a href="home.php" class="logo">TVeShop</a>
        <nav>
            <a href="home.php">Trang chủ</a>
            <div class="dropdown">
                <a href="#">Danh Mục</a>
                <ul class="dropdown-menu">
                    <?php
                        $categories = getCategoriesWithCount();
                        while($cat = mysqli_fetch_assoc($categories)) {
                            echo "<li><a href='?category={$cat['danhmucid']}'>{$cat['tendanhmuc']}</a></li>";
                        }
                    ?>
                </ul>
            </div>
            <a href="promotion.php">Khuyến mãi</a>
            <a href="https://www.facebook.com/messages/t/100053572991660">Liên hệ</a>
        </nav>
        
        <div class="header-icons">
            <a href="cart.php">
                <i class="fas fa-shopping-cart"></i> 
                Giỏ hàng
                <?php if(isset($_SESSION['id'])): ?>
                    <span class="cart-count"><?php echo getCartItemCount($_SESSION['id']); ?></span>
                <?php endif; ?>
            </a>
            <?php if(isset($_SESSION['id'])): ?>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-user"></i> <?php echo $_SESSION['ten_dang_nhap']; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="order_history.php"><i class="fas fa-history"></i> Lịch sử</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-user"></i> Đăng nhập</a>
            <?php endif; ?>
        </div>        
    </header>

    <div class="promotion-container">
        <h1 class="page-title">Khuyến Mãi Đang Diễn Ra</h1>
        
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="promotion-card">
                    <div class="promotion-header">
                        <div>
                            <div class="promotion-title"><?php echo $row['tenkhuyenmai']; ?></div>
                            <div class="promotion-period">
                                Từ: <?php echo date('d/m/Y', strtotime($row['ngaybatdau'])); ?> 
                                - Đến: <?php echo date('d/m/Y', strtotime($row['ngayketthuc'])); ?>
                            </div>
                        </div>
                        <div class="promotion-discount">-<?php echo number_format($row['giatri'], 0, ',', '.'); ?>đ</div>
                    </div>
                    
                    <div class="promotion-products">
                        <h4>Sản phẩm áp dụng:</h4>
                        <?php 
                        if($row['products']) {
                            echo str_replace(',', ', ', $row['products']);
                        } else {
                            echo "Chưa có sản phẩm áp dụng";
                        }
                        ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-promotion">
                <i class="fas fa-info-circle"></i>
                <p>Hiện tại không có chương trình khuyến mãi nào đang diễn ra.</p>
                <a href="home.php" class="back-btn">Quay lại trang chủ</a>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Liên hệ</h3>
                <p><i class="fas fa-map-marker-alt"></i>126 Nguyễn Thiện Thành</p>
                <p><i class="fas fa-phone"></i> 0123 456 789</p>
                <p><i class="fas fa-envelope"></i>tuyenvt@gmail.com</p>
            </div>
            
            <div class="footer-section">
                <h3>Liên kết nhanh</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-home"></i> Trang chủ</a></li>
                    <li><a href="#"><i class="fas fa-shopping-bag"></i> Sản phẩm</a></li>
                    <li><a href="#"><i class="fas fa-tags"></i> Khuyến mãi</a></li>
                    <li><a href="#"><i class="fas fa-shield-alt"></i> Chính sách bảo hành</a></li>
                </ul>
            </div>
    
            <div class="footer-section">
                <h3>Kết nối với chúng tôi</h3>
                <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
                <a href="#"><i class="fab fa-youtube"></i> YouTube</a>
                <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
        </div>
    
        <div class="footer-bottom">
            <p>&copy; 2025 Phụ Kiện Giá Rẻ. Mọi quyền được bảo lưu.</p>
        </div>
    </footer>
</body>
</html>