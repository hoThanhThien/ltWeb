<?php
// File: ltWeb/TourBooking/controllers/AdminController.php

// Nạp các model cần thiết ở đầu file
require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/BookingModel.php';

class AdminController {

    private function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role_id']) || $_SESSION['user_role_id'] != 1) {
            header('Location: /login');
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

    /**
     * Hàm này sẽ hiển thị trang dashboard chính của admin.
     * Nó sẽ lấy dữ liệu thống kê và truyền ra view.
     */
    public function index() {
        $this->checkAdmin();
        
        // --- BẮT ĐẦU LOGIC LẤY DỮ LIỆU ---
        $bookingModel = new BookingModel();
        $tourModel = new Tour();

        // Lấy dữ liệu thống kê từ database
        $totalProfit = $bookingModel->calculateTotalProfit();
        $totalOrders = $bookingModel->countAllBookings();
        $averagePrice = $bookingModel->getAveragePrice();
        $totalTours = $tourModel->countTours(); // Giả sử hàm này đã có trong TourModel

        // Tạo mảng $stats với dữ liệu động
        $stats = [
            'profit' => number_format($totalProfit, 0, ',', '.'),
            'orders' => number_format($totalOrders, 0, ',', '.'),
            'avg_price' => number_format($averagePrice, 0, ',', '.'),
            'tours' => number_format($totalTours, 0, ',', '.')
        ];
        // Lấy danh sách tất cả đơn hàng
    $recentBookings = $bookingModel->getAllBookingsWithDetails();
  
        // Truyền mảng $stats và các dữ liệu khác sang view
        $this->renderView('dashboard', [
            'pageTitle' => 'Dashboard', // Thêm tiêu đề trang
            'stats' => $stats,
            'recentBookings' => $recentBookings
        ]);
        // --- KẾT THÚC LOGIC LẤY DỮ LIỆU ---
    }

    public function listTours() {
        $this->checkAdmin();
        $tourModel = new Tour();
        $tours = $tourModel->getAllTours();
        $this->renderView('tours_list', ['tours' => $tours]);
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
        $this->renderView('tour_add');
    }
    
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
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
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
        $this->renderView('edit_tour', ['tour' => $tour]);
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
        $users = $userModel->getAllUsers();
        $this->renderView('user_management', ['users' => $users]);
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