<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h2>Debug Chat Data</h2>";

// Kết nối database
$conn = new mysqli("localhost", "root", "", "shoplinhkien");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
echo "<p><strong>User ID:</strong> " . $user_id . "</p>";

if ($user_id > 0) {
    // Kiểm tra bảng conversations
    echo "<h3>Bảng conversations:</h3>";
    $query_conversations = "SELECT * FROM conversations WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query_conversations);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result_conversations = $stmt->get_result();
    
    if ($result_conversations->num_rows > 0) {
        echo "<p>Có " . $result_conversations->num_rows . " cuộc trò chuyện:</p>";
        while ($row = $result_conversations->fetch_assoc()) {
            echo "<p>Conversation ID: " . $row['id'] . " - Created: " . $row['created_at'] . "</p>";
            
            // Kiểm tra messages cho conversation này
            $query_messages = "SELECT * FROM messages WHERE conversation_id = ? ORDER BY created_at ASC";
            $stmt_messages = $conn->prepare($query_messages);
            $stmt_messages->bind_param("i", $row['id']);
            $stmt_messages->execute();
            $result_messages = $stmt_messages->get_result();
            
            if ($result_messages->num_rows > 0) {
                echo "<p>Có " . $result_messages->num_rows . " tin nhắn:</p>";
                while ($msg = $result_messages->fetch_assoc()) {
                    echo "<p>- " . $msg['role'] . ": " . substr($msg['content'], 0, 50) . "...</p>";
                }
            } else {
                echo "<p>Không có tin nhắn nào</p>";
            }
            $stmt_messages->close();
        }
    } else {
        echo "<p>Không có cuộc trò chuyện nào cho user này</p>";
    }
    $stmt->close();
    
    // Kiểm tra tất cả conversations
    echo "<h3>Tất cả conversations trong database:</h3>";
    $query_all = "SELECT * FROM conversations ORDER BY created_at DESC LIMIT 10";
    $result_all = $conn->query($query_all);
    if ($result_all->num_rows > 0) {
        while ($row = $result_all->fetch_assoc()) {
            echo "<p>ID: " . $row['id'] . " - User: " . $row['user_id'] . " - Created: " . $row['created_at'] . "</p>";
        }
    } else {
        echo "<p>Không có conversations nào trong database</p>";
    }
    
    // Kiểm tra tất cả messages
    echo "<h3>Tất cả messages trong database:</h3>";
    $query_all_messages = "SELECT * FROM messages ORDER BY created_at DESC LIMIT 10";
    $result_all_messages = $conn->query($query_all_messages);
    if ($result_all_messages->num_rows > 0) {
        while ($row = $result_all_messages->fetch_assoc()) {
            echo "<p>Conversation: " . $row['conversation_id'] . " - Role: " . $row['role'] . " - Content: " . substr($row['content'], 0, 30) . "...</p>";
        }
    } else {
        echo "<p>Không có messages nào trong database</p>";
    }
    
} else {
    echo "<p><strong>Lỗi:</strong> User chưa đăng nhập (user_id = 0)</p>";
}

$conn->close();
?> 