<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Nạp các file controller cần thiết
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/TourController.php';
require_once __DIR__ . '/../controllers/PaymentController.php';
require_once __DIR__ . '/../controllers/AdminController.php'; // Controller trung tâm cho admin
require_once __DIR__ . '/../controllers/BookingController.php';
// Lấy đường dẫn yêu cầu
$request_path = strtok($_SERVER['REQUEST_URI'], '?');
// Nếu người dùng là admin và đang không ở trang admin, chuyển hướng họ đi
if (isset($_SESSION['user_role_id']) && $_SESSION['user_role_id'] == 1) {
    $request_path = strtok($_SERVER['REQUEST_URI'], '?');
    
    if (!str_starts_with($request_path, '/admin') && $request_path !== '/logout') {
        echo "<script>window.parent.location.href = '/admin/dashboard';</script>";
        exit();
    }
}
// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['user_id'])) {
    // Nếu  vào trang login hoặc register -> Chuyển hướng họ đi
    if ($request_path === '/login' || $request_path === '/register') {
        
        // Nếu là admin, về trang admin
        if ($_SESSION['user_role_id'] == 1) {
            echo "<script>window.parent.location.href = '/admin/dashboard';</script>";
            exit();
        }
        // Nếu là user thường, về trang chủ
        else {
            echo "<script>window.parent.location.reload();</script>";
            exit();
        }
    }
    
}
// Ghi log để gỡ lỗi
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
      //  trang lịch sử đặt hàng
    case '/my-bookings':
        (new BookingController())->listUserBookings();
        break;

    // --- TRANG QUẢN TRỊ (ADMIN) ---
    case '/admin':
    case '/admin/dashboard':
        (new AdminController())->index();
        break;

    case '/admin/bookings/update_status':
        (new AdminController())->updateBookingStatus();
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

     case '/admin/users/edit': // Đường dẫn để hiển thị và xử lý form sửa user
        (new AdminController())->editUser();
        break;

    case '/admin/users/delete':
        (new AdminController())->deleteUser();
        break;

    // --- Trang mặc định khi không tìm thấy ---
    default:
        http_response_code(404);
        require __DIR__ . '/../views/404.php'; 
        break;
}