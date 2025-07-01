<?php
if (session_status() == PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserManagementController.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/TourController.php';
// Xác định base URL (đường dẫn gốc đến thư mục public)
$basePath = '/projectPHP/ltWeb/TourBooking/public';

// Lấy URI và bỏ phần base path
$request_uri = strtok($_SERVER['REQUEST_URI'], '?');
$request_path = str_replace($basePath, '', $request_uri);

switch ($request_path) {
    case '/':
        header("Location: $basePath/home");
        exit();

    case '/home':
        $homeController = new HomeController();
        $homeController->index();
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
            header("Location: $basePath/login");
            exit();
        }
        echo '<h1>Dashboard</h1>';
        echo 'Xin chào ' . htmlspecialchars($_SESSION['user_name']) . '. ';
        echo '<a href="' . $basePath . '/logout">Đăng xuất</a>';
        break;
       
        case '/booking':
            if (isset($_GET['id'])) {
                $tourController = new TourController();
                $tourController->bookTour($_GET['id']);
            } else {
                echo '<h3>Thiếu ID tour</h3>';
            }
            break;
        case '/tour_detail':
                if (isset($_GET['id'])) {
                $tourController = new TourController();
                $tourController->bookTour($_GET['id']);
            } else {
                echo '<h3>Thiếu ID tour</h3>';
            }
                include __DIR__ . '/../views/tour_detail.php';
                break;
    default:
        http_response_code(404);
        echo '<h1>404 - Không tìm thấy trang</h1>';
        echo '<a href="' . $basePath . '/home">Quay về trang home</a>';
        break;
}
