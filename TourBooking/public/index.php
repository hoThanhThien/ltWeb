<?php
// File: public/index.php
// Front Controller - Trung tâm xử lý tất cả các request.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserManagementController.php';
// Lấy URI và loại bỏ query string
$request_uri = $_SERVER['REQUEST_URI'];
$request_uri = strtok($request_uri, '?');

// Điều hướng cơ bản
switch ($request_uri) {

    // Trang chủ (đăng nhập mới được vào)
    case '/':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        echo 'Chào mừng ' . htmlspecialchars($_SESSION['user_name']) . ' đến trang chủ! <a href="/dashboard">Vào Dashboard</a> | <a href="/logout">Đăng xuất</a>';
        break;
    case '/user_management':
        $userManagementController = new UserManagementController();
        $userManagementController->index();
        break;
    // Trang đăng ký
    case '/register':
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->handleRegistration();
        } else {
            $authController->showRegisterForm();
        }
        break;

    // Trang đăng nhập
    case '/login':
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->handleLogin();
        } else {
            $authController->showLoginForm();
        }
        break;

    // Đăng xuất
    case '/logout':
        $authController = new AuthController();
        $authController->logout();
        break;

    // Dashboard (cần đăng nhập)
    case '/dashboard':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        echo 'Đây là trang Dashboard! Xin chào ' . htmlspecialchars($_SESSION['user_name']) . '. <a href="/logout">Đăng xuất</a>';
        break;

    // Nếu route không tồn tại
    default:
        http_response_code(404);
        echo '<h1>404 - Không tìm thấy trang</h1>';
        echo '<a href="/">Quay về trang chủ</a>';
        break;
}
?>
