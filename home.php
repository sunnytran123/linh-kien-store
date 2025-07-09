<?php
    session_start();  // Thêm dòng này ở đầu file
    require_once 'connect.php';
    require_once 'functions.php';  // Thêm dòng này
    // Kiểm tra session
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
            justify-content: space-between;
            align-items: center;
            background-color: #8BC34A;
            color: white;
            padding: 15px 20px; /* Tăng padding để tăng chiều cao */
            border-radius: 50px 80px 50px 80px;
            max-width:95%;
            height: 70px; 
            margin: 10px auto; /* Căn giữa */
            flex-wrap: nowrap; /* Không xuống dòng */
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
        .breadcrumb {
            padding: 10px 50px;
            background: #f5f5f5;
        }

        .breadcrumb a {
            color: #8BC34A;
            text-decoration: none;
        }

        .main-content {
            display: flex;
            padding: 30px 50px;
        }

        .sidebar {
            width: 250px;
            margin-right: 30px;
        }

        .categories h2, .price h2 {
            margin-bottom: 15px;
            color: #333;
        }

        .categories ul {
            list-style: none;
        }

        .categories li {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }

        .categories a {
            color: #666;
            text-decoration: none;
        }

        .count {
            color: #999;
        }

        .products {
            flex: 1;
        }

        .products-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            align-items: center;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
            flex-grow: 1;
            margin-bottom: 20px;
        }

        .search-box input {
            padding: 8px;
            padding-right: 35px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 250px;
        }

        .search-box a {
            position: absolute;
            right: 10px;
            color: #666;
            text-decoration: none;
        }

        .search-box a:hover {
            color: #8BC34A;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .product-card {
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
        }

        .product-image {
            height: 300px;
            background: #f5f5f5;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
        }

        .product-category {
            color: white;
            background: #8BC34A;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .product-title {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .product-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .product-price {
            color: #8BC34A;
            font-weight: bold;
        }
    .hero {
        background-image: url('picture/nen.jpg');
        background-size: contain; /* Giữ tỷ lệ hình ảnh và đảm bảo toàn bộ ảnh được hiển thị */
        background-position: center; /* Căn giữa ảnh */
        background-repeat: no-repeat; /* Ngừng lặp lại ảnh nền */
        width: 100%;
        height: 50vh; /* Chiều cao có thể điều chỉnh theo ý muốn */
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto; /* Căn giữa */
    }

        h1 {
            text-align: center;
            margin: 20px 0;
            font-size: 32px;
            color: #333;
            font-weight: bold;
        }

        .main-content {
            background-color: #f9f9f9;
            border-radius: 10px;
            display: flex;
            gap: 20px;
            margin-left: 80px;
            margin-right: 80px;
        }
        .sorting {
            margin-left: auto;
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


        /* Định dạng dropdown */
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
        /* .dropdown-menu li a {
            font-size: 14px;  
            padding: 6px 10px;  
        }
        */
        .pagination .active {
            background-color: #8BC34A;
            color: white !important;
        }
        .pagination a:hover {
            background-color: #f0f0f0;
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
            width: ;
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
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="top-info">
            <span><i class="fas fa-map-marker-alt"></i>Cà Mau</span>
            <span><i class="fas fa-envelope"></i>phuongthuy091203@gmail.com</span>
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

    <div class="hero">
    </div>

    <!-- <H1>Sản Phẩm Nổi Bật</H1> -->
    <div class="main-content">
        <aside class="sidebar">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm kiếm" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <a href="#" onclick="performSearch(); return false;"><i class="fas fa-search"></i></a>
            </div>
            <div class="categories">
                <h2>Danh mục sản phẩm</h2>
                <ul>
                    <?php
                        $categories = getCategoriesWithCount();
                        while($cat = mysqli_fetch_assoc($categories)) {
                            echo "<li><a href='#'>{$cat['tendanhmuc']}</a> <span class='count'>({$cat['count']})</span></li>";
                        }
                    ?>
                </ul>
            </div>
 
        </aside>

        <main class="products">
            <div class="products-header">
                <div class="sorting">
                    <select onchange="window.location.href='?sort='+this.value">
                        <option value="">Thứ tự mặc định</option>
                        <option value="price_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : ''; ?>>Giá thấp đến cao</option>
                        <option value="price_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : ''; ?>>Giá cao đến thấp</option>
                    </select>
                </div>
            </div>

            <div class="product-grid">
                <?php
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
                    $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
                    $productsPerPage = 6;
                    
                    // Xử lý tìm kiếm
                    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

                    // Query cơ bản
                    $query = "SELECT sp.*, dm.tendanhmuc, ha.duongdan, 
                              COALESCE(SUM(ss.soluong), 0) AS tong_ton_kho
                              FROM sanpham sp
                              LEFT JOIN danhmuc dm ON sp.madanhmuc = dm.danhmucid
                              LEFT JOIN hinhanhsanpham ha ON sp.sanphamid = ha.masanpham
                              LEFT JOIN sanpham_size ss ON sp.sanphamid = ss.sanphamid
                              WHERE 1=1";

                    // Thêm điều kiện tìm kiếm
                    if ($search) {
                        $query .= " AND (sp.tensanpham LIKE ? OR dm.tendanhmuc LIKE ?)";
                    }

                    // Thêm điều kiện category nếu có
                    if (isset($_GET['category'])) {
                        $query .= " AND sp.madanhmuc = ?";
                    }

                    // Thêm GROUP BY
                    $query .= " GROUP BY sp.sanphamid";

                    // Thêm sắp xếp
                    if (isset($_GET['sort'])) {
                        switch ($_GET['sort']) {
                            case 'price_asc':
                                $query .= " ORDER BY sp.gia ASC";
                                break;
                            case 'price_desc':
                                $query .= " ORDER BY sp.gia DESC";
                                break;
                            default:
                                $query .= " ORDER BY sp.sanphamid DESC";
                        }
                    } else {
                        $query .= " ORDER BY sp.sanphamid DESC";
                    }

                    // Thêm phân trang
                    $query .= " LIMIT ? OFFSET ?";

                    // Chuẩn bị và thực thi query
                    $stmt = mysqli_prepare($conn, $query);

                    // Bind các tham số
                    $searchParam = "%$search%";
                    if ($search && isset($_GET['category'])) {
                        $offset = ($page - 1) * $productsPerPage;
                        mysqli_stmt_bind_param($stmt, "ssiii", 
                            $searchParam, 
                            $searchParam, 
                            $_GET['category'],
                            $productsPerPage, 
                            $offset
                        );
                    } elseif ($search) {
                        $offset = ($page - 1) * $productsPerPage;
                        mysqli_stmt_bind_param($stmt, "ssii", 
                            $searchParam, 
                            $searchParam,
                            $productsPerPage, 
                            $offset
                        );
                    } elseif (isset($_GET['category'])) {
                        $offset = ($page - 1) * $productsPerPage;
                        mysqli_stmt_bind_param($stmt, "iii", 
                            $_GET['category'],
                            $productsPerPage, 
                            $offset
                        );
                    } else {
                        $offset = ($page - 1) * $productsPerPage;
                        mysqli_stmt_bind_param($stmt, "ii", 
                            $productsPerPage, 
                            $offset
                        );
                    }

                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    // Cập nhật đếm tổng số sản phẩm cho phân trang
                    $countQuery = "SELECT COUNT(DISTINCT sp.sanphamid) as total 
                                   FROM sanpham sp 
                                   LEFT JOIN danhmuc dm ON sp.madanhmuc = dm.danhmucid 
                                   WHERE 1=1";

                    if ($search) {
                        $countQuery .= " AND (sp.tensanpham LIKE ? OR dm.tendanhmuc LIKE ?)";
                    }
                    if (isset($_GET['category'])) {
                        $countQuery .= " AND sp.madanhmuc = ?";
                    }

                    $countStmt = mysqli_prepare($conn, $countQuery);
                    if ($search && isset($_GET['category'])) {
                        mysqli_stmt_bind_param($countStmt, "ssi", 
                            $searchParam, 
                            $searchParam,
                            $_GET['category']
                        );
                    } elseif ($search) {
                        mysqli_stmt_bind_param($countStmt, "ss", 
                            $searchParam, 
                            $searchParam
                        );
                    } elseif (isset($_GET['category'])) {
                        mysqli_stmt_bind_param($countStmt, "i", $_GET['category']);
                    }
                    mysqli_stmt_execute($countStmt);
                    $totalResult = mysqli_stmt_get_result($countStmt);
                    $totalRow = mysqli_fetch_assoc($totalResult);
                    $totalProducts = $totalRow['total'];
                    $totalPages = ceil($totalProducts / $productsPerPage);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <div class="product-card">
                                <a href="ProductDetail.php?id=<?php echo $row['sanphamid']; ?>" style="text-decoration: none; color: inherit;">
                                    <div class="product-image">
                                        <img src="picture/<?php echo $row['duongdan']; ?>" 
                                             alt="<?php echo $row['tensanpham']; ?>">
                                    </div>
                                    <div class="product-info">
                                        <span class="product-category"><?php echo $row['tendanhmuc']; ?></span>
                                        <h3 class="product-title"><?php echo $row['tensanpham']; ?></h3>
                                        <div class="price-container">
                                            <?php 
                                            $promotion = getProductPromotion($conn, $row['sanphamid']);
                                            if ($promotion) {
                                                $discounted_price = calculateDiscountedPrice($row['gia'], $promotion);
                                                echo '<div class="price">';
                                                echo '<span class="original-price">' . number_format($row['gia'], 0, ',', '.') . 'đ</span>';
                                                echo '<span class="discounted-price">' . number_format(abs($discounted_price), 0, ',', '.') . 'đ</span>';
                                                echo '</div>';
                                                if ($promotion['loaigiamgia'] == 'percentage') {
                                                    echo '<div class="discount-tag">' . abs($promotion['giatrigiamgia']) . '%</div>';
                                                }
                                            } else {
                                                echo '<div class="price">';
                                                echo '<span class="normal-price">' . number_format($row['gia'], 0, ',', '.') . 'đ</span>';
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>Không có sản phẩm nào trong danh mục này.</p>";
                    }
                ?>
            </div>

            <!-- Phân trang -->
            <div class="pagination" style="text-align: center; margin-top: 20px;">
                <?php
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $activeClass = ($i == $page) ? 'active' : '';
                        // Thêm tham số sort vào URL phân trang
                        $sortParam = isset($_GET['sort']) ? "&sort=".$_GET['sort'] : '';
                        echo "<a href='?page=$i$sortParam' class='page-link $activeClass' style='margin: 0 5px; padding: 5px 10px; text-decoration: none; color: #333; border: 1px solid #ddd;'>$i</a>";
                    }
                ?>
            </div>
        </main>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy elements
        const searchInput = document.getElementById('searchInput');
        
        // Thêm event listener cho phím Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    });

    function performSearch() {
        const searchTerm = document.getElementById('searchInput').value.trim();
        
        // Lấy các tham số URL hiện tại
        const urlParams = new URLSearchParams(window.location.search);
        
        if (searchTerm) {
            urlParams.set('search', searchTerm);
        } else {
            urlParams.delete('search');
        }
        
        // Giữ lại các tham số lọc khác nếu có
        if (urlParams.toString()) {
            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        } else {
            window.location.href = window.location.pathname;
        }
    }
    </script>
</body>
</html>