<?php
// File: ltWeb/TourBooking/controllers/AdminController.php

require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/AuthTokenModel.php';
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
        $limit = 6; // 10 đơn hàng mỗi trang
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
        $limit = 6; // Số lượng tour hiển thị trên mỗi trang
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
    
    // Xử lý khi người dùng nhấn nút "Thêm mới"
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // --- LOGIC XỬ LÝ ẢNH (Ưu tiên: File > URL) ---
        $image_value = ''; // Mặc định là chuỗi rỗng

        // 1. Ưu tiên file tải lên
        if (isset($_FILES['new_image_file']) && $_FILES['new_image_file']['error'] == 0 && !empty($_FILES['new_image_file']['name'])) {
            $target_dir = __DIR__ . "/../public/img/";
            // Tạo tên file an toàn và duy nhất
            $file_extension = pathinfo($_FILES["new_image_file"]["name"], PATHINFO_EXTENSION);
            $file_name = "tour_" . time() . "." . $file_extension;
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($_FILES["new_image_file"]["tmp_name"], $target_file)) {
                $image_value = $file_name; // Nếu tải lên thành công, dùng tên file mới
            }
        // 2. Nếu không có file, kiểm tra có dán link URL không
        } elseif (!empty($_POST['image_url'])) {
            $image_value = $_POST['image_url']; // Dùng link URL
        }
        // --- KẾT THÚC LOGIC ẢNH ---

        // Gói tất cả dữ liệu vào MỘT MẢNG DUY NHẤT
        $data = [
            'title' => $_POST['title'],
            'location' => $_POST['location'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'duration_days' => $_POST['duration_days'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'available_slots' => $_POST['available_slots'],
            'stars' => $_POST['stars'],
            'loai_tour' => $_POST['loai_tour'],
            'image' => $image_value // Sử dụng giá trị ảnh đã được xử lý
        ];
        
        $tourModel = new Tour();
        // Gọi hàm addTour trong Model với chỉ một mảng dữ liệu
        if ($tourModel->addTour($data)) {
            header('Location: /admin/tours');
            exit();
        } else {
            // Xử lý lỗi nếu cần
            echo "Thêm tour mới thất bại!";
        }
    }

    // Hiển thị form thêm mới
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
    $id = $_GET['id'] ?? $_POST['id'] ?? null; // Lấy id từ GET hoặc POST

    if (!$id) {
        header('Location: /admin/tours');
        exit();
    }

    // Xử lý khi người dùng nhấn nút "Cập nhật" (phương thức POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // --- LOGIC XỬ LÝ ẢNH
        $image_value = $_POST['current_image']; 
        if (isset($_FILES['new_image_file']) && $_FILES['new_image_file']['error'] == 0 && !empty($_FILES['new_image_file']['name'])) {
            $target_dir = __DIR__ . "/../public/img/";
            $file_name = time() . '_' . basename($_FILES["new_image_file"]["name"]);
            $target_file = $target_dir . $file_name;
            if (move_uploaded_file($_FILES["new_image_file"]["tmp_name"], $target_file)) {
                $image_value = $file_name;
            }
        } elseif (!empty($_POST['image_url']) && $_POST['image_url'] != $_POST['current_image']) {
            $image_value = $_POST['image_url'];
        }

    
        // Gói tất cả dữ liệu từ form vào MỘT MẢNG DUY NHẤT
        $data = [
            'id' => $id,
            'title' => $_POST['title'],
            'location' => $_POST['location'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'duration_days' => $_POST['duration_days'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'available_slots' => $_POST['available_slots'],
            'stars' => $_POST['stars'],
            'loai_tour' => $_POST['loai_tour'],
            'image' => $image_value // Sử dụng giá trị ảnh đã được xử lý
        ];
        
        // Gọi hàm updateTour với CHỈ MỘT MẢNG DỮ LIỆU
        if ($tourModel->updateTour($data)) {
            header('Location: /admin/tours');
            exit();
        } else {
            echo "Cập nhật tour thất bại!";
        }

    // Hiển thị form edit (phương thức GET)
    } else {
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
}
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
        
        // 1. Lấy tham số lọc từ URL (nếu có)
        $filters = [
            'keyword' => $_GET['keyword'] ?? ''
        ];

        // 2. Logic phân trang
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 6; // Hoặc số lượng user bạn muốn hiển thị trên mỗi trang
        $offset = ($page - 1) * $limit;

        // 3. GỌI ĐÚNG PHƯƠNG THỨC LẤY USER  VÀ PHÂN TRANG
        // DÒNG NÀY LÀ QUAN TRỌNG NHẤT
        $users = $userModel->getFilteredUsers($filters, $limit, $offset);

        
        // 4. Đếm tổng số user đã lọc để tính toán phân trang
        $totalUsers = $userModel->countFilteredUsers($filters);
        $totalPages = max(1, ceil($totalUsers / $limit));

        // 5. Render view và truyền dữ liệu
        $this->renderView('user_management', [
            'users' => $users,
            'page' => $page,
            'totalPages' => $totalPages,
            'filters' => $filters,
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