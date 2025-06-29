<?php
if (session_status() == PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserManagementController.php';
require_once __DIR__ . '/../controllers/HomeController.php';

$request_uri = strtok($_SERVER['REQUEST_URI'], '?');

switch ($request_uri) {
    case '/':
        header('Location: /home');
        exit();

    case '/home':
        $homeController = new HomeController();
        $homeController->index(); // Controller chịu trách nhiệm lấy data + gọi view
        break;

    case '/user_management':
        $userManagementController = new UserManagementController();
        $userManagementController->index();
        break;

    case '/register':
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->handleRegistration();
        } else {
            $authController->showRegisterForm();
        }
        break;

    case '/login':
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->handleLogin();
        } else {
            $authController->showLoginForm();
        }
        break;

    case '/logout':
        $authController = new AuthController();
        $authController->logout();
        break;

    case '/dashboard':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        echo '<h1>Dashboard</h1>';
        echo 'Xin chào ' . htmlspecialchars($_SESSION['user_name']) . '. ';
        echo '<a href="/logout">Đăng xuất</a>';
        break;

    default:
        http_response_code(404);
        echo '<h1>404 - Không tìm thấy trang</h1>';
        echo '<a href="/home">Quay về trang home</a>';
        break;
}
