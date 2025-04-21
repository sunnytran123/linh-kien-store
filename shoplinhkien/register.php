<?php
include("connect.php"); 
session_start();

function validateName($name) {
    // Kiểm tra tên có ít nhất 2 từ
    $words = explode(' ', trim($name));
    return count($words) >= 2;
}

function validatePhone($phone) {
    // Kiểm tra số điện thoại bắt đầu bằng 0 và có 10 số
    return preg_match('/^0\d{9,}$/', $phone);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tendangnhap = trim($_POST['txthoten']);
    $email = $_POST['txtemail'];
    $password = $_POST['txtmk'];
    $sdt = $_POST['txtsdt'];
    $loai = 3;
    $error = '';

    // Kiểm tra tên
    if (!validateName($tendangnhap)) {
        $error = "Họ tên phải có ít nhất 2 từ!";
    }
    // Kiểm tra số điện thoại
    elseif (!validatePhone($sdt)) {
        $error = "Số điện thoại phải bắt đầu bằng số 0 và có ít nhất 10 số!";
    }
    else {
        // Kiểm tra số điện thoại đã tồn tại chưa
        $check_phone = "SELECT * FROM nguoi_dung WHERE sdt = ?";
        $stmt = $conn->prepare($check_phone);
        $stmt->bind_param("s", $sdt);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Số điện thoại đã được đăng ký!";
        } else {
            // Thêm người dùng mới
            $insert_query = "INSERT INTO `nguoi_dung` (ten_dang_nhap, email, sdt, mat_khau, loai) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssssi", $tendangnhap, $email, $sdt, $password, $loai);
            
            if ($stmt->execute()) {
                echo '<script>alert("Đăng ký thành công!"); window.location.href="login.php";</script>';
            } else {
                $error = "Lỗi: " . $stmt->error;
            }
        }
    }
    
    if ($error) {
        echo '<script>alert("' . $error . '");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Phụ Kiện Giá Rẻ</title>
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
            padding: 15px 20px;
            border-radius: 50px 80px 50px 80px;
            max-width: 90%;
            height: 70px;
            margin: 10px auto;
            flex-wrap: nowrap;
        }

        .top-info, .top-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .top-info i {
            color: #FFD700;
            margin-right: 5px;
        }

        .top-links a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s;
            font-size: 16px;
        }

        .top-links a:hover {
            color: #FFD700;
        }

        .top-links i {
            font-size: 16px;
        }

        .auth-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }

        .auth-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .auth-box h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 16px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #8BC34A;
            outline: none;
        }

        .auth-button {
            width: 100%;
            padding: 12px;
            background: #8BC34A;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .auth-button:hover {
            background: #7CB342;
        }

        .auth-links {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        .auth-links a {
            color: #8BC34A;
            text-decoration: none;
            font-size: 14px;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Đăng ký tài khoản</h2>
            <form method = "post">
                <div class="form-row">
                    <div class="form-group">
                        <label>Họ Tên</label>
                        <input type="text" name="txthoten" placeholder="Nhập họ tên">
                    </div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="txtemail" placeholder="Nhập email của bạn">
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel" name="txtsdt" placeholder="Nhập số điện thoại">
                </div>
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name ="txtmk" placeholder="Nhập mật khẩu">
                </div>
                <button type="submit" class="auth-button">Đăng ký</button>
                <div class="auth-links">
                    <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>