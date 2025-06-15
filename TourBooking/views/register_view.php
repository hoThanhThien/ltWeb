<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng kí</title>
    <link rel="stylesheet" href="../css/register.css">
    <script src="../js/register.js"></script>

</head>

<body>

    <form class="form-register" action="/register" method="POST">
        <h1 class="form-title">Đăng kí tài khoản</h1>

        <label for="userName">Họ và tên:</label>
        <input type="text" id="userName" name="name" class="input-field" required>

        <label for="userEmail">Email:</label>
        <input type="email" id="userEmail" name="email" class="input-field" required>

        
    <label for="phone">Số điện thoại:</label>
    <input type="text" name="phone" id="phone" required><br>


        <label for="userPassword">Mật khẩu:</label>
        <input type="password" id="userPassword" name="password" class="input-field" required>

        <label for="confirmPassword">Xác nhận mật khẩu:</label>
        <input type="password" id="confirmPassword" name="confirm_password" class="input-field" required>

        <div class="message-error" id="passwordError"></div>

        <button type="submit" class="btn-submit">Đăng ký</button>

        <p class="form-footer">Đã có tài khoản? <a class="link-login" href="/login">Đăng nhập</a></p>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
