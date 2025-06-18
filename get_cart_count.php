<?php
session_start();
include 'connect.php';

header('Content-Type: application/json');

if  (!isset($_SESSION['id']) || !isset($_SESSION['loai'])) {
    echo json_encode(['count' => 0]);
    exit;
}

// Kiểm tra session
$sql = "SELECT COUNT(*) as count 
        FROM chitietgiohang cg 
        JOIN giohang g ON cg.magiohang = g.giohangid 
        WHERE g.manguoidung = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

echo json_encode(['count' => (int)$row['count']]);
?> 