<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Tour Booking</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập tài khoản</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <input type="checkbox" id="show_password_toggle">
                <label for="show_password_toggle">Hiển thị mật khẩu</label>
            </div>
            
            <div>
                <input type="checkbox" id="remember_me" name="remember_me" value="1">
                <label for="remember_me">Ghi nhớ đăng nhập</label>
            </div>

            <button type="submit">Đăng nhập</button>
        </form>

        <div class="link">
            <p>Chưa có tài khoản? <a href="/register">Đăng ký ngay</a></p>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/login.js"></script>
</body>
</html>