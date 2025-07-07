<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Nạp các file controller cần thiết
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/TourController.php';
require_once __DIR__ . '/../controllers/PaymentController.php';
require_once __DIR__ . '/../controllers/AdminController.php'; // Controller trung tâm cho admin

// Lấy đường dẫn yêu cầu
$request_path = strtok($_SERVER['REQUEST_URI'], '?');

// Ghi log để gỡ lỗi (tùy chọn)
file_put_contents(
    __DIR__ . '/../logs/router_debug.log',
    "Timestamp: " . date('Y-m-d H:i:s') . " | Path: [{$request_path}]" . "\n",
    FILE_APPEND
);

// Điều hướng dựa trên đường dẫn
switch ($request_path) {
    // --- Trang người dùng ---
    case '/':
    case '/home':
        (new HomeController())->index();
        break;
    
    case '/booking':
        (new TourController())->bookTour($_GET['id']);
        break;

    // --- Xác thực ---
    case '/login':
        (new AuthController())->handleLogin();
        break;

    case '/register':
        (new AuthController())->handleRegistration();
        break;

    case '/logout':
        (new AuthController())->logout();
        break;

    // --- Thanh toán ---
    case '/payment':
        (new PaymentController())->showPaymentPage($_GET['id']);
        break;

    case '/payment/check_status':
        (new PaymentController())->checkStatus();
        break;
    
    case '/payment/webhook':
        (new PaymentController())->handleWebhook();
        break;

    // --- TRANG QUẢN TRỊ (ADMIN) ---
    case '/admin':
    case '/admin/dashboard':
        (new AdminController())->index();
        break;

    case '/admin/tours':
        (new AdminController())->listTours();
        break;

    case '/admin/tours/add':
        (new AdminController())->addTour();
        break;

    case '/admin/tours/edit':
        (new AdminController())->editTour();
        break;

    case '/admin/tours/delete':
        (new AdminController())->deleteTour();
        break;
    
    case '/admin/users': // Đường dẫn mới cho quản lý người dùng
        (new AdminController())->listUsers();
        break;

    case '/admin/users/delete': // Đường dẫn mới để xóa người dùng
        (new AdminController())->deleteUser();
        break;

    // --- Trang mặc định khi không tìm thấy ---
    default:
        http_response_code(404);
        require __DIR__ . '/../views/404.php'; 
        break;
}