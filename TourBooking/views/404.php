<?php
// Luôn bắt đầu session để có thể truy cập biến $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mặc định, link sẽ trỏ về trang chủ
$homeLink = '/home';

// Chỉ khi người dùng là Admin (role_id = 1), link mới trỏ về dashboard
if (isset($_SESSION['user_role_id']) && $_SESSION['user_role_id'] == 1) {
    $homeLink = '/admin/dashboard';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>404 - Không tìm thấy trang</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f1f1f1; color: #555; text-align: center; padding: 50px; }
        .container { max-width: 600px; margin: auto; }
        h1 { font-size: 8rem; margin: 0; color: #e74c3c; font-weight: 700; }
        h2 { font-size: 1.75rem; color: #333; }
        p { font-size: 1rem; }
        a { display: inline-block; margin-top: 20px; padding: 12px 30px; background-color: #3498db; color: white; text-decoration: none; border-radius: 50px; font-weight: 600; transition: background-color 0.2s; }
        a:hover { background-color: #2980b9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Oops! Không tìm thấy trang.</h2>
        <p>Trang bạn đang tìm kiếm có thể đã bị xóa, đổi tên hoặc không tồn tại.</p>
        
        <a href="<?= $homeLink ?>">Quay về Trang Chủ</a>
    </div>
</body>
</html>