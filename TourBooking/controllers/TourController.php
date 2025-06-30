<?php
require_once __DIR__ . '/../models/TourModel.php';

class TourController {
    public function showByCategory($categoryId) {
        $tours = getToursByCategory($categoryId);
        include __DIR__ . '/../views/tour_category.php';
    }

    public function bookTour($tourId) {
        if (session_status() === PHP_SESSION_NONE) session_start(); // Bắt đầu session nếu chưa có

        // Gọi hàm lấy tour
        $tour = getTourById($tourId);
        if (!$tour) {
            echo "<h3>Không tìm thấy tour</h3>";
            return;
        }
        
        $final_price = $tour['price'] * (1 - $tour['discount_percent'] / 100);
        
        $conn = connect_db();
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, tour_id, quantity, total_price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $tourId, 1, $final_price]);
        

        // Khi người dùng submit form đặt tour
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../config/database.php';
            $conn = connect_db(); // ✅ Gọi hàm kết nối CSDL đúng cách

            $stmt = $conn->prepare("INSERT INTO orders (user_id, tour_id, fullname, phone, total_price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_SESSION['user_id'], $tourId, $_POST['fullname'], $_POST['phone'], $final_price
            ]);

            // Điều hướng về home
            $basePath = '/Booking/ltWeb/TourBooking/public'; //Đảm bảo $basePath có
            echo "<script>alert('Đặt tour thành công!'); window.location.href='$basePath/home';</script>";
            return;
        }

        include __DIR__ . '/../views/tour_booking.php';
    }
    public function showByType($type = null) {
        if ($type && in_array($type, ['trongnuoc', 'nuocngoai'])) {
            $tours = getToursByType($type);
        } else {
            $tours = getAllTours(); // fallback nếu thiếu type
        }
        include __DIR__ . '/../views/tour_category.php';
    }
    
}
