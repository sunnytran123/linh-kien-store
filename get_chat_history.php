<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kết nối database
$conn = new mysqli("localhost", "root", "", "shoplinhkien");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy user_id hoặc guest_id
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

// Nếu chưa đăng nhập, sử dụng guest_id
if ($user_id == 0) {
    if (!isset($_SESSION['guest_id'])) {
        $_SESSION['guest_id'] = 'guest_' . session_id() . '_' . time();
    }
    $user_id = $_SESSION['guest_id'];
}

// Kiểm tra xem user_id có phải là số (user đã đăng nhập) hay string (guest)
$is_guest = !is_numeric($user_id);

if ($is_guest) {
    // Xử lý guest - tìm conversation theo guest_id
    $query_conversation = "SELECT id FROM conversations WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
    $stmt = $conn->prepare($query_conversation);
    $stmt->bind_param("s", $user_id); // Sử dụng string parameter
} else {
    // Xử lý user đã đăng nhập
    $query_conversation = "SELECT id FROM conversations WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
    $stmt = $conn->prepare($query_conversation);
    $stmt->bind_param("i", $user_id); // Sử dụng integer parameter
}

$stmt->execute();
$result_conversation = $stmt->get_result();

if ($result_conversation->num_rows > 0) {
    $conversation = $result_conversation->fetch_assoc();
    $conversation_id = $conversation['id'];
    
    // Lấy toàn bộ tin nhắn của conversation này
    $query_messages = "SELECT * FROM messages WHERE conversation_id = ? ORDER BY created_at ASC";
    $stmt_messages = $conn->prepare($query_messages);
    $stmt_messages->bind_param("i", $conversation_id);
    $stmt_messages->execute();
    $result_messages = $stmt_messages->get_result();
    
    $messages = array();
    while ($row = $result_messages->fetch_assoc()) {
        $messages[] = array(
            'content' => $row['content'],
            'role' => $row['role'],
            'created_at' => $row['created_at']
        );
    }
    
    echo json_encode(array(
        'status' => 'success',
        'conversation_id' => $conversation_id,
        'messages' => $messages
    ));
} else {
    echo json_encode(array(
        'status' => 'no_conversation',
        'message' => 'Không có cuộc trò chuyện nào'
    ));
}

$stmt->close();
if (isset($stmt_messages)) $stmt_messages->close();
$conn->close();
?> 