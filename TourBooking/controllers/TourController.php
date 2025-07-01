
<?php
require_once __DIR__ . '/../models/Tour.php';

class TourController {
    public function showByCategory($categoryId) {
        $tourModel = new Tour();
        $tours = $tourModel->getToursByCategory($categoryId);
        include __DIR__ . '/../views/tour_category.php';
    }


public function bookTour($tourId) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $tourModel = new Tour();
    $tour = $tourModel->getTourById($tourId);
    $error = '';
    $success = '';
    $priceAfterDiscount = $tour['price'] * (1 - $tour['discount_percent'] / 100);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../config/database.php';
        $conn = connect_db();

        if (!isset($_SESSION['user_id'])) {
            $error = "Vui lòng đăng nhập để đặt tour.";
        } else {
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $days = isset($_POST['days']) ? (int)$_POST['days'] : 1;
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $totalPrice = $priceAfterDiscount * $quantity * $days;

            if ($tour['available_slots'] <= 0) {
                $error = "Tour này đã hết vé. Vui lòng chọn tour khác!";
            } elseif ($quantity > $tour['available_slots']) {
                $error = "Số lượng vé không đủ! Chỉ còn {$tour['available_slots']} vé.";
            } elseif ($quantity <= 0) {
                $error = "Số lượng vé phải lớn hơn 0.";
            } else {
                $stmt = $conn->prepare("INSERT INTO bookings (user_id, tour_id, quantity, total_price, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $tourId, $quantity, $totalPrice, $startDate, $endDate]);
                $update = $conn->prepare("UPDATE tours SET available_slots = available_slots - ? WHERE id = ?");
                $update->execute([$quantity, $tourId]);
                if ($update->errorCode() !== '00000') {
                    $errInfo = $update->errorInfo();
                    $error = "Lỗi SQL khi cập nhật vé: " . $errInfo[2];
                } else {
                    $success = "Đặt tour thành công!";
                    // Reload lại số vé mới
                    $tour = $tourModel->getTourById($tourId);
                }
            }
        }
    }

    // Truyền biến sang view
    include __DIR__ . '/../views/tour_booking.php';
}
}
?>