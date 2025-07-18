<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();  // Bắt đầu session nếu chưa có
    }
    require_once 'connect.php';
    require_once 'functions.php';  // Thêm các file chức năng cần thiết
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Ẩn menu con mặc định */
        .dropdown-menu {
            display: none;
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            list-style: none;
            padding: 10px;
            min-width: 230px;
            z-index: 100;
            top: 100%;
            left: 0;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Hiển thị menu khi hover */
        .dropdown:hover .dropdown-menu {
            display: block;
        }

        /* Định dạng mục trong menu */
        .dropdown-menu li {
            padding: 5px 10px;
        }

        .dropdown-menu li a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 8px;
            transition: 0.3s;
        }

        .dropdown-menu li a:hover {
            color:#8BC34A;
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

    </style>
</head>
<body>

    <!-- Phần top-bar -->
    <div class="top-bar">
        <div class="top-info">
            <span><i class="fas fa-map-marker-alt"></i>Cà Mau</span>
            <span><i class="fas fa-envelope"></i>phuongthuy091203@gmail.com</span>
        </div>
    </div>

    <!-- Phần header -->
    <header>
        <a href="home.php" class="logo">Sunny Store</a>
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
            <a href="#" id="openContactModal">Liên hệ</a>
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
    <?php include 'popupchatbot.php'; ?>