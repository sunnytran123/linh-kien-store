<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "shoplinhkien";
    
    $conn = mysqli_connect($servername, $username, $password, $dbname);



    // Hàm lấy tất cả sản phẩm có sorting
    function getAllProducts($page = 1, $productsPerPage = 6, $sort = '', $categoryId = null) {
        global $conn;
        
        $start = ($page - 1) * $productsPerPage;
        
        $sql = "SELECT s.*, h.duongdan, d.tendanhmuc 
                FROM sanpham s
                LEFT JOIN hinhanhsanpham h ON s.sanphamid = h.masanpham
                LEFT JOIN danhmuc d ON s.madanhmuc = d.danhmucid";

        // Thêm điều kiện lọc theo danh mục nếu có
        if ($categoryId) {
            $sql .= " WHERE s.madanhmuc = " . intval($categoryId);
        }

        // Thêm điều kiện sắp xếp
        if ($sort) {
            switch($sort) {
                case 'price_asc':
                    $sql .= " ORDER BY s.gia ASC";
                    break;
                case 'price_desc':
                    $sql .= " ORDER BY s.gia DESC";
                    break;
                default:
                    $sql .= " ORDER BY s.sanphamid ASC"; // Sắp xếp theo ID tăng dần
            }
        } else {
            // Mặc định sắp xếp theo ID sản phẩm
            $sql .= " ORDER BY s.sanphamid ASC";
        }
        
        $sql .= " LIMIT $start, $productsPerPage";
        return mysqli_query($conn, $sql);
    }

    function getTotalProducts() {
        global $conn;
        $sql = "SELECT COUNT(*) as total FROM sanpham";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }

    // Hàm lấy danh mục và số lượng sản phẩm
    function getCategoriesWithCount() {
        global $conn;
        $sql = "SELECT d.danhmucid, d.tendanhmuc, COUNT(s.sanphamid) as count 
                FROM danhmuc d 
                LEFT JOIN sanpham s ON d.danhmucid = s.madanhmuc 
                GROUP BY d.danhmucid, d.tendanhmuc";
        return mysqli_query($conn, $sql);
    }

    // Hàm lấy chi tiết sản phẩm theo ID
    function getProductDetail($productId) {
        global $conn;
        $sql = "SELECT s.*, h.duongdan, d.tendanhmuc 
                FROM sanpham s
                LEFT JOIN hinhanhsanpham h ON s.sanphamid = h.masanpham
                LEFT JOIN danhmuc d ON s.madanhmuc = d.danhmucid
                WHERE s.sanphamid = " . intval($productId);
        $result = mysqli_query($conn, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Hàm kiểm tra đăng nhập
    function checkLogin($email, $pass) {
        global $conn;
        
        // Sử dụng prepared statement để tránh SQL injection
        $sql = "SELECT * FROM nguoi_dung WHERE email = ? AND mat_khau = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $pass);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            // Nếu đăng nhập thành công, trả về thông tin người dùng
            return $user;
        }
        return false;
    }

    // Hàm lấy thông tin giỏ hàng
    function getCartItems($userId) {
        global $conn;
        
        $sql = "SELECT cg.*, s.tensanpham, s.gia, s.tonkho, h.duongdan 
                FROM chitietgiohang cg 
                JOIN giohang g ON cg.magiohang = g.giohangid
                JOIN sanpham s ON cg.masanpham = s.sanphamid 
                LEFT JOIN hinhanhsanpham h ON s.sanphamid = h.masanpham
                WHERE g.manguoidung = ?";
                
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        
        return mysqli_stmt_get_result($stmt);
    }

    // Hàm lấy tổng số lượng sản phẩm trong giỏ hàng
    function getCartItemCount($userId) {
        global $conn;
        
        $sql = "SELECT COUNT(*) as total 
                FROM chitietgiohang cg 
                JOIN giohang g ON cg.magiohang = g.giohangid 
                WHERE g.manguoidung = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return $row['total'];
    }

    // Hàm tính tổng tiền giỏ hàng
    function getCartTotal($userId) {
        global $conn;
        
        $sql = "SELECT SUM(g.soluong * s.gia) as total 
                FROM giohang g 
                JOIN sanpham s ON g.sanphamid = s.sanphamid 
                WHERE g.manguoidung = ?";
                
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return $row['total'] ?? 0;
    }

    // Hàm cập nhật số lượng sản phẩm trong giỏ hàng
    function updateCartQuantity($userId, $productId, $change) {
        global $conn;
        
        // Lấy tổng tồn kho từ bảng sanpham_size
        $sql = "SELECT SUM(soluong) as tonkho FROM sanpham_size WHERE sanphamid = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $product = mysqli_fetch_assoc($result);
        $tonkho = $product['tonkho'] ?? 0;
        
        // Lấy số lượng hiện tại trong giỏ hàng
        $sql = "SELECT soluong FROM giohang WHERE manguoidung = ? AND sanphamid = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $currentItem = mysqli_fetch_assoc($result);
        
        $newQuantity = ($currentItem ? $currentItem['soluong'] : 0) + $change;
        
        // Kiểm tra số lượng mới có hợp lệ không
        if ($newQuantity <= 0) {
            return removeCartItem($userId, $productId);
        }
        
        if ($newQuantity > $tonkho) {
            return ['success' => false, 'message' => 'Số lượng vượt quá tồn kho'];
        }
        
        // Cập nhật số lượng
        if ($currentItem) {
            $sql = "UPDATE giohang SET soluong = ? WHERE manguoidung = ? AND sanphamid = ?";
        } else {
            $sql = "INSERT INTO giohang (soluong, manguoidung, sanphamid) VALUES (?, ?, ?)";
        }
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $newQuantity, $userId, $productId);
        
        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true];
        }
        return ['success' => false, 'message' => 'Lỗi cập nhật giỏ hàng'];
    }

    // Hàm xóa sản phẩm khỏi giỏ hàng
    function removeCartItem($userId, $productId) {
        global $conn;
        
        $sql = "DELETE FROM giohang WHERE manguoidung = ? AND sanphamid = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
        
        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true];
        }
        return ['success' => false, 'message' => 'Lỗi xóa sản phẩm'];
    }

    // Hàm kiểm tra sản phẩm có trong giỏ hàng không
    function isProductInCart($userId, $productId) {
        global $conn;
        
        $sql = "SELECT COUNT(*) as count FROM giohang WHERE manguoidung = ? AND sanphamid = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return $row['count'] > 0;
    }

    // Hàm thêm sản phẩm vào giỏ hàng
    function addToCart($userId, $productId, $quantity = 1, $sizeId = 0, $colorId = 0) {
        global $conn;
        try {
            // Lấy tổng tồn kho từ bảng sanpham_size cho size cụ thể
            $sql = "SELECT soluong FROM sanpham_size WHERE sanphamid = ? AND sizeid = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $productId, $sizeId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $product = mysqli_fetch_assoc($result);
            $tonkho = $product['soluong'] ?? 0;
            if ($tonkho === null) {
                return ['success' => false, 'message' => 'Không tìm thấy sản phẩm hoặc tồn kho size này'];
            }
            // Kiểm tra giỏ hàng hiện tại của user với sản phẩm, size, màu
            $sql = "SELECT g.giohangid, cg.chitietgiohangid, cg.soluong 
                    FROM giohang g 
                    LEFT JOIN chitietgiohang cg ON g.giohangid = cg.magiohang AND cg.masanpham = ? AND cg.sizeid = ? AND cg.mausacid = ?
                    WHERE g.manguoidung = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iiii", $productId, $sizeId, $colorId, $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $cartInfo = mysqli_fetch_assoc($result);
            // Nếu chưa có giỏ hàng, tạo mới
            if (!$cartInfo || !$cartInfo['giohangid']) {
                $sql = "INSERT INTO giohang (manguoidung) VALUES (?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $userId);
                mysqli_stmt_execute($stmt);
                $cartId = mysqli_insert_id($conn);
            } else {
                $cartId = $cartInfo['giohangid'];
            }
            // Tính toán số lượng mới
            $newQuantity = $quantity;
            if ($cartInfo && $cartInfo['chitietgiohangid']) {
                $newQuantity += $cartInfo['soluong'];
            }
            // Kiểm tra tồn kho với số lượng mới
            if ($newQuantity > $tonkho) {
                return ['success' => false, 'message' => 'Số lượng vượt quá tồn kho size này'];
            }
            // Cập nhật hoặc thêm mới chi tiết giỏ hàng
            if ($cartInfo && $cartInfo['chitietgiohangid']) {
                $sql = "UPDATE chitietgiohang SET soluong = ? WHERE chitietgiohangid = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ii", $newQuantity, $cartInfo['chitietgiohangid']);
            } else {
                $sql = "INSERT INTO chitietgiohang (magiohang, masanpham, sizeid, mausacid, soluong) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "iiiii", $cartId, $productId, $sizeId, $colorId, $newQuantity);
            }
            if (mysqli_stmt_execute($stmt)) {
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'Lỗi khi cập nhật giỏ hàng'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    // Hàm cập nhật tổng tiền giỏ hàng
    function updateCartTotal($cartId) {
        global $conn;
        
        $sql = "UPDATE giohang g 
                SET tongtien = (
                    SELECT SUM(cg.soluong * cg.gia) 
                    FROM chitietgiohang cg 
                    WHERE cg.magiohang = g.giohangid
                ) 
                WHERE giohangid = ?";
                
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $cartId);
        return mysqli_stmt_execute($stmt);
    }
?>