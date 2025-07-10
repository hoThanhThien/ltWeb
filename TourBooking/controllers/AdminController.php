<?php
// File: ltWeb/TourBooking/controllers/AdminController.php

require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/BookingModel.php';

class AdminController {

    private function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role_id']) || $_SESSION['user_role_id'] != 1) {
            header('Location: /home'); // Chuyển hướng về trang home nếu không phải admin
            exit();
        }
    }

    private function renderView($viewName, $data = []) {
        extract($data);
        ob_start();
        require_once __DIR__ . "/../views/admin/{$viewName}.php";
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/admin/layout.php';
    }

     public function index() {
        $this->checkAdmin();
        $bookingModel = new BookingModel();
        $tourModel = new Tour();

        // --- PHẦN 1: LẤY DỮ LIỆU THỐNG KÊ 
        $stats = [
            'profit' => number_format($bookingModel->calculateTotalProfit(), 0, ',', '.'),
            'orders' => number_format($bookingModel->countAllBookings(), 0, ',', '.'),
            'avg_price' => number_format($bookingModel->getAveragePrice(), 0, ',', '.'),
            'tours' => number_format($tourModel->getTotalToursCount(), 0, ',', '.')
        ];

        // --- PHẦN 2: LOGIC PHÂN TRANG CHO DANH SÁCH ĐƠN HÀNG ---
        
        // 2.1. Xác định trang hiện tại và giới hạn mỗi trang
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // 10 đơn hàng mỗi trang
        $offset = ($page - 1) * $limit;

        // 2.2. Lấy đơn hàng đã phân trang từ Model
        $recentBookings = $bookingModel->getBookingsPaginated($limit, $offset);
        
        // 2.3. Tính tổng số trang
        $totalBookings = $bookingModel->countAllBookings();
        $totalPages = ceil($totalBookings / $limit);

        // --- PHẦN 3: RENDER VIEW (thêm biến phân trang) ---
        $this->renderView('dashboard', [
            'pageTitle' => 'Dashboard',
            'current_page' => 'dashboard',
            'stats' => $stats,
            'recentBookings' => $recentBookings, // Dữ liệu đã được phân trang
            'page' => $page,                       // Truyền trang hiện tại
            'totalPages' => $totalPages            // Truyền tổng số trang
        ]);
    }
    
    public function listTours() {
        $this->checkAdmin();
        $tourModel = new Tour();
        
        // 1. Lấy trang hiện tại từ URL, mặc định là trang 1
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Số lượng tour hiển thị trên mỗi trang
        $offset = ($page - 1) * $limit;

        // 2. Lấy đúng số lượng tour cho trang hiện tại
        $tours = $tourModel->getTours(null, $limit, $offset);
        
        // 3. Đếm tổng số tour để tính toán phân trang
        $totalTours = $tourModel->getTotalToursCount();
        $totalPages = ceil($totalTours / $limit);

        // 4. Truyền đầy đủ các biến cần thiết sang view
        $this->renderView('tours_list', [
            'tours' => $tours,
            'page' => $page,
            'totalPages' => $totalPages,
            'pageTitle' => 'Quản lý Tour',
            'current_page' => 'tours'
        ]);
    }
      public function listBookings() {
        $this->checkAdmin(); //  có hàm kiểm tra đăng nhập admin
        $bookingModel = new BookingModel();

        // 1. Xác định trang hiện tại và số lượng mục mỗi trang
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // 10 đơn hàng mỗi trang
        $offset = ($page - 1) * $limit;

        // 2. Lấy dữ liệu từ Model
        $bookings = $bookingModel->getBookingsPaginated($limit, $offset);
        $totalBookings = $bookingModel->countAllBookings();
        $totalPages = ceil($totalBookings / $limit);

        // 3. Render view và truyền dữ liệu qua
        $this->renderView('admin/bookings_list', [
            'bookings' => $bookings,
            'page' => $page,
            'totalPages' => $totalPages,
            // 'pageTitle' => 'Quản lý Đơn hàng' // Tùy chọn
        ]);
    }
    public function addTour() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../public/img/";
                $image = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $image;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            }

            $tourModel = new Tour();
            $tourModel->addTour(
                $_POST['title'], $_POST['location'], $_POST['description'], $_POST['price'],
                $_POST['duration_days'], $_POST['start_date'], $_POST['end_date'],
                $_POST['available_slots'], $_POST['stars'], $image, $_POST['loai_tour']
            );
            header('Location: /admin/tours');
            exit();
        }
        $this->renderView('tour_add', [
            'pageTitle' => 'Thêm Tour Mới',
            'current_page' => 'tours',
            'use_ckeditor' => true
        ]);
    }
    // Sửa tour
    public function editTour() {
        $this->checkAdmin();
        $tourModel = new Tour();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/tours');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = $_POST['current_image'];
            if (isset($_FILES['image']) && !empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
                $target_dir = __DIR__ . "/../public/img/";
                $image = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $image;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            }

            $tourModel->updateTour(
                $id, $_POST['title'], $_POST['location'], $_POST['description'], $_POST['price'],
                $_POST['duration_days'], $_POST['start_date'], $_POST['end_date'],
                $_POST['available_slots'], $_POST['stars'], $image, $_POST['loai_tour']
            );
            header('Location: /admin/tours');
            exit();
        }

        $tour = $tourModel->getTourById($id);
        if (!$tour) {
            header('Location: /admin/tours');
            exit();
        }
        $this->renderView('edit_tour', [
            'tour' => $tour,
            'pageTitle' => 'Chỉnh Sửa Tour',
            'current_page' => 'tours',
            'use_ckeditor' => true
        ]);
    }
    // sửa thông tin
    public function editUser() {
        $this->checkAdmin();
        $userModel = new User();
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /admin/users');
            exit();
        }

        // Xử lý khi người dùng gửi form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone']; // Lấy dữ liệu phone
            $password = $_POST['password'];

            // Cập nhật người dùng
            $userModel->updateUser($id, $name, $email, $phone, $password);

            header('Location: /admin/users');
            exit();
        }

        // Hiển thị form với dữ liệu có sẵn
        $user = $userModel->getUserById($id);
        if (!$user) {
            header('Location: /admin/users');
            exit();
        }

        $this->renderView('edit_user', [
            'user' => $user,
            'pageTitle' => 'Chỉnh Sửa Người Dùng',
            'current_page' => 'users'
        ]);
    }
    public function deleteTour() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $tourModel = new Tour();
            $tourModel->deleteTour($id);
        }
        header('Location: /admin/tours');
        exit();
    }


    public function listUsers() {
        $this->checkAdmin();
        $userModel = new User();
        
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5; // Số lượng user trên mỗi trang
        $offset = ($page - 1) * $limit;

        $users = $userModel->getAllUsers($limit, $offset);
        $totalUsers = $userModel->getUsersCount();
        $totalPages = ceil($totalUsers / $limit);

        $this->renderView('user_management', [
            'users' => $users,
            'page' => $page,
            'totalPages' => $totalPages,
            'pageTitle' => 'Quản lý User',
            'current_page' => 'users'
        ]);
    }
    

    /**
     * Xử lý yêu cầu cập nhật trạng thái đơn hàng.
     */
    public function updateBookingStatus() {
        $this->checkAdmin(); // Luôn kiểm tra quyền admin

        $bookingId = $_GET['id'] ?? null;
        $newStatus = $_GET['status'] ?? null;

        // Chỉ xử lý nếu có đủ thông tin và trạng thái hợp lệ
        $validStatuses = ['confirmed', 'pending', 'cancelled'];
        if ($bookingId && $newStatus && in_array($newStatus, $validStatuses)) {
            $bookingModel = new BookingModel();
            $bookingModel->updateBookingStatus($bookingId, $newStatus);
        }

        // Chuyển hướng người dùng trở lại trang dashboard
        header('Location: /admin/dashboard');
        exit();
    }


    public function deleteUser() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($id == $_SESSION['user_id']) {
                echo "<script>alert('Bạn không thể xóa chính mình!'); window.location.href='/admin/users';</script>";
                exit();
            }
            $userModel = new User();
            $userModel->deleteUser($id);
        }
        header('Location: /admin/users');
        exit();
    }
}