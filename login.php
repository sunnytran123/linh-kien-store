<?php
session_start();
include 'connect.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ email và mật khẩu";
    } else {
        $user = checkLogin($email, $password);
        
        if ($user) {
            // Lưu thông tin user vào session
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['ten_dang_nhap'] = $user['ten_dang_nhap'];
            $_SESSION['loai'] = $user['loai'];
            if ($_SESSION['loai'] =="1" ||  $_SESSION['loai'] =="2")
            {
                header("Location: quan-ly-bao-cao.php");
                // echo $_SESSION['loai'];                                                                                                                      
            }
            else
            {
            // Chuyển hướng về trang chủ
            header("Location: home.php");
            }
            exit();
        } else {
            $error = "Email hoặc mật khẩu không đúng";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Phụ Kiện Giá Rẻ</title>
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
        }

        .top-info i {
            color: #FFD700;
            margin-right: 5px;
        }

        .top-links a {
            color: white;
            text-decoration: none;
        }

        header {
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #8BC34A;
            text-decoration: none;
        }

        .auth-container {
            max-width: 500px;
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
            font-size: 28px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #555;
            font-size: 16px;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
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
            padding: 15px;
            background: #8BC34A;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .auth-button:hover {
            background: #7CB342;
        }

        .auth-links {
            margin-top: 20px;
            text-align: center;
        }

        .auth-links a {
            color: #8BC34A;
            text-decoration: none;
            font-size: 16px;
            margin: 0 10px;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }

        .social-login {
            margin-top: 30px;
        }

        .social-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: opacity 0.3s;
        }

        .social-button:hover {
            opacity: 0.9;
        }

        .social-button i {
            margin-right: 10px;
            font-size: 18px;
        }

        .social-button.facebook {
            background: #4267B2;
        }

        .social-button.google {
            background: #DB4437;
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #ddd;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .divider span {
            background: white;
            padding: 0 10px;
            color: #666;
            font-size: 14px;
        }
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-box">
            <h2>Đăng nhập</h2>
            
            <?php if ($error): ?>
                <div class="error-message" style="color: red; margin-bottom: 15px; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Nhập email của bạn" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" placeholder="Nhập mật khẩu">
                </div>
                <button type="submit" class="auth-button">Đăng nhập</button>
                
                <div class="auth-links">
                    <a href="#">Quên mật khẩu?</a>
                    <a href="register.php">Đăng ký tài khoản mới</a>
                </div>

                <!-- <div class="divider">
                    <span>Hoặc đăng nhập với</span>
                </div>

                <div class="social-login">
                    <a href="#" class="social-button facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="#" class="social-button google">
                        <i class="fab fa-google"></i> Google
                    </a>
                </div> -->
            </form>
        </div>
    </div>
</body>
</html>