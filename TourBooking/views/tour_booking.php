<?php
require_once __DIR__ . '/../models/TourModel.php';
$tourInfo = getTourById($_GET['id']);

if (!$tourInfo) {
    echo "<h3>Không tìm thấy tour. Vui lòng kiểm tra lại.</h3>";
    return;
}

$priceAfterDiscount = $tourInfo['price'] * (1 - $tourInfo['discount_percent'] / 100);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/database.php';
    if (session_status() === PHP_SESSION_NONE) session_start();
    $db = connect_db();

    $query = $db->prepare("INSERT INTO bookings (user_id, tour_id, quantity, total_price) VALUES (?, ?, ?, ?)");
    $query->execute([
        $_SESSION['user_id'], $tourInfo['id'], $_POST['quantity'], $priceAfterDiscount * $_POST['quantity']
    ]);
    echo "<script>alert('Đặt tour thành công!'); window.location.href='/Booking/ltWeb/TourBooking/public/my_bookings';</script>";
}
?>
<link rel="stylesheet" href="../public/css/booking_style.css">
<div class="booking-container">
    <div class="booking-card">
    <img src="/Booking/ltWeb/TourBooking/public/img/<?= $tour['image'] ?>" alt="<?= $tour['title'] ?>">
        <div class="booking-details">
            <h2><?= $tourInfo['title'] ?></h2>
            <p><?= $tourInfo['description'] ?></p>
            <p><strong>Thời gian:</strong> <?= $tourInfo['duration_days'] ?> ngày (<?= $tourInfo['start_date'] ?> - <?= $tourInfo['end_date'] ?>)</p>
            <p><strong>Địa điểm:</strong> <?= $tourInfo['location'] ?></p>
            <div class="pricing">
                <?php if ($tourInfo['discount_percent'] > 0): ?>
                    <span class="original-price"><del><?= number_format($tourInfo['price']) ?>₫</del></span>
                    <span class="discounted-price"><?= number_format($priceAfterDiscount) ?>₫ / người</span>
                <?php else: ?>
                    <span class="discounted-price"><?= number_format($tourInfo['price']) ?>₫ / người</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <form method="POST" class="booking-form">
        <h3>Thông tin đặt tour</h3>
        <input type="number" name="quantity" min="1" value="1" required placeholder="Số lượng người">
        <button type="submit">Xác nhận đặt</button>
    </form>
</div>
