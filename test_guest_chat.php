<?php
// File test để kiểm tra hệ thống guest chat
session_start();

echo "<h2>Test Guest Chat System</h2>";

// Test 1: Kiểm tra session
echo "<h3>1. Session Test</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "User ID from session: " . (isset($_SESSION['id']) ? $_SESSION['id'] : 'Not set') . "<br>";
echo "Session data: <pre>" . print_r($_SESSION, true) . "</pre><br>";

// Test 2: Tạo guest_id
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
if ($user_id == 0) {
    if (!isset($_SESSION['guest_id'])) {
        $_SESSION['guest_id'] = 'guest_' . session_id() . '_' . time();
    }
    $user_id = $_SESSION['guest_id'];
}
echo "Guest ID: " . (isset($_SESSION['guest_id']) ? $_SESSION['guest_id'] : 'Not set') . "<br>";
echo "Final User ID: " . $user_id . "<br>";

// Test 3: Kiểm tra database connection
echo "<h3>2. Database Connection Test</h3>";
try {
    $conn = new mysqli("localhost", "root", "", "shoplinhkien");
    if ($conn->connect_error) {
        echo "❌ Database connection failed: " . $conn->connect_error . "<br>";
    } else {
        echo "✅ Database connection successful<br>";
        
        // Test 4: Kiểm tra cấu trúc bảng
        echo "<h3>3. Table Structure Test</h3>";
        $result = $conn->query("DESCRIBE conversations");
        if ($result) {
            echo "✅ Conversations table structure:<br>";
            while ($row = $result->fetch_assoc()) {
                if ($row['Field'] == 'user_id') {
                    echo "user_id type: " . $row['Type'] . "<br>";
                }
            }
        }
        
        $result = $conn->query("DESCRIBE messages");
        if ($result) {
            echo "✅ Messages table structure:<br>";
            while ($row = $result->fetch_assoc()) {
                if ($row['Field'] == 'user_id') {
                    echo "user_id type: " . $row['Type'] . "<br>";
                }
            }
        }
        
        // Test 5: Thử tạo conversation
        echo "<h3>4. Create Conversation Test</h3>";
        $stmt = $conn->prepare("INSERT INTO conversations (user_id, status) VALUES (?, 'waiting_for_admin')");
        $stmt->bind_param("s", $user_id);
        if ($stmt->execute()) {
            $conversation_id = $conn->insert_id;
            echo "✅ Conversation created with ID: " . $conversation_id . "<br>";
            
            // Test 6: Thử lưu message
            echo "<h3>5. Save Message Test</h3>";
            $stmt_msg = $conn->prepare("INSERT INTO messages (user_id, conversation_id, role, content) VALUES (?, ?, ?, ?)");
            $role = 'user';
            $content = 'Test message from guest';
            $stmt_msg->bind_param("siss", $user_id, $conversation_id, $role, $content);
            if ($stmt_msg->execute()) {
                echo "✅ Message saved successfully<br>";
            } else {
                echo "❌ Failed to save message: " . $stmt_msg->error . "<br>";
            }
            $stmt_msg->close();
        } else {
            echo "❌ Failed to create conversation: " . $stmt->error . "<br>";
        }
        $stmt->close();
    }
    $conn->close();
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test 7: Kiểm tra get_chat_history.php
echo "<h3>6. Chat History API Test</h3>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/shoplinhkien/get_chat_history.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=" . session_id());
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "<br>";
echo "Response: " . $response . "<br>";

echo "<h3>7. Instructions</h3>";
echo "<p>Để hoàn tất việc sửa lỗi:</p>";
echo "<ol>";
echo "<li>Chạy script SQL: <code>update_database_structure.sql</code></li>";
echo "<li>Khởi động lại server Python: <code>python serverlinhkien.py</code></li>";
echo "<li>Test chatbot với user chưa đăng nhập</li>";
echo "</ol>";
?> 