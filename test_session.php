<?php
session_start();

echo "<h2>Test Session và Đăng nhập</h2>";

// Hiển thị toàn bộ session data
echo "<h3>1. Session Data</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Kiểm tra các key quan trọng
echo "<h3>2. Kiểm tra các key session</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "User ID (id): " . (isset($_SESSION['id']) ? $_SESSION['id'] : 'Not set') . "<br>";
echo "Email: " . (isset($_SESSION['email']) ? $_SESSION['email'] : 'Not set') . "<br>";
echo "Ten dang nhap: " . (isset($_SESSION['ten_dang_nhap']) ? $_SESSION['ten_dang_nhap'] : 'Not set') . "<br>";
echo "Loai: " . (isset($_SESSION['loai']) ? $_SESSION['loai'] : 'Not set') . "<br>";

// Kiểm tra trạng thái đăng nhập
echo "<h3>3. Trạng thái đăng nhập</h3>";
if (isset($_SESSION['id']) && $_SESSION['id'] > 0) {
    echo "✅ Đã đăng nhập với User ID: " . $_SESSION['id'] . "<br>";
    
    // Kiểm tra database
    try {
        $conn = new mysqli("localhost", "root", "", "shoplinhkien");
        if ($conn->connect_error) {
            echo "❌ Database connection failed: " . $conn->connect_error . "<br>";
        } else {
            echo "✅ Database connection successful<br>";
            
            // Kiểm tra user trong database
            $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                echo "✅ User found in database:<br>";
                echo "- ID: " . $user['id'] . "<br>";
                echo "- Ten dang nhap: " . $user['ten_dang_nhap'] . "<br>";
                echo "- Email: " . $user['email'] . "<br>";
                echo "- Loai: " . $user['loai'] . "<br>";
            } else {
                echo "❌ User not found in database<br>";
            }
            $stmt->close();
        }
        $conn->close();
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Chưa đăng nhập<br>";
    echo "Tạo guest_id: ";
    if (!isset($_SESSION['guest_id'])) {
        $_SESSION['guest_id'] = 'guest_' . session_id() . '_' . time();
    }
    echo $_SESSION['guest_id'] . "<br>";
}

// Kiểm tra file login.php
echo "<h3>4. Kiểm tra file login.php</h3>";
if (file_exists('login.php')) {
    echo "✅ File login.php tồn tại<br>";
    
    // Đọc một phần file để kiểm tra
    $login_content = file_get_contents('login.php');
    if (strpos($login_content, "\$_SESSION['id']") !== false) {
        echo "✅ File login.php sử dụng \$_SESSION['id'] đúng<br>";
    } else {
        echo "❌ File login.php không sử dụng \$_SESSION['id']<br>";
    }
} else {
    echo "❌ File login.php không tồn tại<br>";
}

// Hướng dẫn
echo "<h3>5. Hướng dẫn</h3>";
if (!isset($_SESSION['id']) || $_SESSION['id'] == 0) {
    echo "<p>Bạn chưa đăng nhập. Hãy:</p>";
    echo "<ol>";
    echo "<li>Đi đến trang <a href='login.php'>login.php</a></li>";
    echo "<li>Đăng nhập với tài khoản hợp lệ</li>";
    echo "<li>Quay lại trang này để kiểm tra</li>";
    echo "</ol>";
} else {
    echo "<p>✅ Bạn đã đăng nhập thành công!</p>";
    echo "<p>Bây giờ có thể test chatbot với user đã đăng nhập.</p>";
}
?> 