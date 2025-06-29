<?php
require_once __DIR__ . '/../guards/auth_guard.php';
?>

<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Dashboard</title>
    <link rel=\"stylesheet\" href=\"css/style.css\">
</head>
<body>
    <h1>Xin chào, <?php echo $_SESSION['user_name']; ?>!</h1>
    <p>Vai trò: <?php echo $_SESSION['user_role']; ?></p>
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <a href=\"user_management.php\">Quản lý tài khoản</a><br>
    <?php endif; ?>
    <a href=\"login.php\">Đăng xuất</a>
<?php
    include_once 'home.php';
    
 ?>
</body>
</html>
