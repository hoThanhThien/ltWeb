<?php
if (session_status() == PHP_SESSION_NONE) session_start();
/// Nếu session chưa được khởi tạo, hãy khởi tạo nó
// lưu trạng thái đăng nhập của người dùng

// Nạp các file cần thiết
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserManagementController.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/TourController.php';
require_once __DIR__ . '/../controllers/PaymentController.php'; 

// --- Lưu ý ---
// 1. dùng  Virtual Host.


// 2. Lấy đường dẫn trực tiếp, không cần cắt chuỗi.
$request_path = strtok($_SERVER['REQUEST_URI'], '?');

// Dòng log gỡ lỗi
file_put_contents(
    __DIR__ . '/../logs/router_debug.log', 
    "Timestamp: " . date('Y-m-d H:i:s') . " | Path: [{$request_path}]" . "\n", 
    FILE_APPEND
);

switch ($request_path) {
    case '/':
        header("Location: /home");
        exit();

    case '/home':
        $homeController = new HomeController();
        $homeController->index();
        break;
    
    case '/user_management':
    // Bước 1: Kiểm tra xem người dùng đã đăng nhập chưa
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login");
        exit();
    }

    // Bước 2: Kiểm tra xem người dùng có phải là admin không
    // Lưu ý: Dựa theo code trước, bạn dùng 'user_role_id'. Giả sử 1 là admin.
    if ($_SESSION['user_role_id'] != 1) { 
        // Nếu không phải admin, chuyển về trang chủ và dừng lại
        header("Location: /home");
        exit();
    }

    // Nếu code chạy được đến đây, có nghĩa người dùng LÀ ADMIN
    // -> Cho phép họ truy cập và hiển thị trang.
    $userManagementController = new UserManagementController();
    $userManagementController->index();
    break; // Kết thúc case

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
            header("Location: /login");
            exit();
        }
        echo '<h1>Dashboard</h1>';
        echo 'Xin chào ' . htmlspecialchars($_SESSION['user_name']) . '. ';
        echo '<a href="/logout">Đăng xuất</a>';
        break;
        
    case '/booking':
        if (isset($_GET['id'])) {
            $tourController = new TourController();
            $tourController->bookTour($_GET['id']);
        } else {
            echo '<h3>Thiếu ID tour</h3>';
        }
        break;
        
    // --- CHO THANH TOÁN ---
    case '/payment':
        if (isset($_GET['id'])) {
            $paymentController = new PaymentController();
            $paymentController->showPaymentPage($_GET['id']);
        } else {
            echo '<h3>Thiếu ID đơn hàng</h3>';
        }
        break;

    case '/payment/check_status':
        $paymentController = new PaymentController();
        $paymentController->checkStatus();
        break;

    case '/payment/webhook':
        $paymentController = new PaymentController();
        $paymentController->handleWebhook();
        break;

    default:
        http_response_code(404);
        echo '<h1>404 - Không tìm thấy trang</h1>';
        echo '<a href="/home">Quay về trang home</a>';
        break;
}
?>