<?php
// Đảm bảo file bắt đầu với PHP tag

// Kiểm tra xem function đã tồn tại chưa trước khi định nghĩa
if (!function_exists('getCartItemCount')) {
    function getCartItemCount($userId) {
        global $conn;
        $sql = "SELECT SUM(soluong) as total FROM giohang WHERE manguoidung = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ? $row['total'] : 0;
    }
}

if (!function_exists('getProductPromotion')) {
    function getProductPromotion($conn, $productId) {
        $sql = "SELECT k.* 
                FROM khuyenmai k 
                JOIN sanpham_khuyenmai sk ON k.khuyenmaiid = sk.khuyenmai_id 
                WHERE sk.sanpham_id = ? 
                AND k.ngaybatdau <= CURRENT_TIMESTAMP 
                AND k.ngayketthuc >= CURRENT_TIMESTAMP";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $promotion = $result->fetch_assoc();
            return [
                'khuyenmaiid' => $promotion['khuyenmaiid'],
                'tenkhuyenmai' => $promotion['tenkhuyenmai'],
                'giatrigiamgia' => floatval($promotion['giatri']),
                'loaigiamgia' => 'fixed',
                'ngaybatdau' => $promotion['ngaybatdau'],
                'ngayketthuc' => $promotion['ngayketthuc']
            ];
        }
        return null;
    }
}

if (!function_exists('calculateDiscountedPrice')) {
    function calculateDiscountedPrice($originalPrice, $promotion) {
        if (!$promotion) return $originalPrice;
        
        $originalPrice = floatval($originalPrice);
        $discountAmount = floatval($promotion['giatrigiamgia']);
        
        $finalPrice = $originalPrice - $discountAmount;
        
        return max($finalPrice, 1000);
    }
}
?>