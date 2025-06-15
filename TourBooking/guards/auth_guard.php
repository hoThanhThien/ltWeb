<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (!isset($_SESSION['user_id'])) {
        // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
        header('Location: /public/login');
        exit();
    }
?>