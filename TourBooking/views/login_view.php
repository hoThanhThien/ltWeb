
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
                <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="remember-me">
                    <input type="checkbox" id="remember-me" name="remember_me" <?php echo isset($remember_me) ? 'checked' : ''; ?>>
                    Ghi nhớ đăng nhập
                </label>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>

        <div class="link">
            <p>Chưa có tài khoản? <a href="/register">Đăng ký ngay</a></p>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Đăng ký
    var regLinks = document.querySelectorAll('.link-login, .link a[href="/register"]');
    regLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            window.parent.postMessage('open-register', '*');
        });
    });
    // Đăng nhập
    var loginLinks = document.querySelectorAll('.link-login[href="/login"], .form-footer a[href="/login"]');
    loginLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            window.parent.postMessage('open-login', '*');
        });
    });
});
</script>
</body>
</html>
