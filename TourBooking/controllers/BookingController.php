<?php
require_once __DIR__ . '/../models/BookingModel.php';

class BookingController {

    public function listUserBookings() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /home');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $bookingModel = new BookingModel();

        // 1. Logic phân trang
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 8; // Hiển thị 5 đơn hàng mỗi trang
        $offset = ($page - 1) * $limit;

        // 2. Lấy đơn hàng đã phân trang và tổng số đơn hàng
        $bookings = $bookingModel->getBookingsByUserIdPaginated($userId, $limit, $offset);
        $totalBookings = $bookingModel->countUserBookings($userId);
        $totalPages = ceil($totalBookings / $limit);

        // 3. Render view và truyền tất cả dữ liệu cần thiết
        require __DIR__ . '/../views/my_bookings_view.php';
    }
}