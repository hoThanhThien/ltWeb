<?php
require_once __DIR__ . '/../models/TourModel.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<h3>Không tìm thấy tour. Vui lòng kiểm tra liên kết.</h3>";
    return;
}

$tourInfo = getTourById($_GET['id']);

if (!$tourInfo) {
    echo "<h3>Không tìm thấy tour. Vui lòng kiểm tra lại.</h3>";
    return;
}

$priceAfterDiscount = $tourInfo['price'] * (1 - $tourInfo['discount_percent'] / 100);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/database.php';
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Vui lòng đăng nhập để đặt tour.'); window.location.href='/Booking/ltWeb/TourBooking/public/login';</script>";
        return;
    }

    $people = (int)$_POST['quantity'];
    $days = (int)$_POST['days'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $totalPrice = $priceAfterDiscount * $people * $days;

    $db = connect_db();
    $query = $db->prepare("INSERT INTO bookings (user_id, tour_id, quantity, total_price, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
    $query->execute([
        $_SESSION['user_id'], $tourInfo['id'], $people, $totalPrice, $startDate, $endDate
    ]);

    echo "<script>alert('Đặt tour thành công!'); window.location.href='/Booking/ltWeb/TourBooking/public/my_bookings';</script>";
    return;
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="../public/css/tour_detail.css">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const quantity = document.getElementById('quantity');
    const totalPrice = document.getElementById('total-price');
    const daysInput = document.getElementById('days');
    const daysCount = document.getElementById('days-count');
    const startHidden = document.getElementById('start_date');
    const endHidden = document.getElementById('end_date');
    const base = <?= $priceAfterDiscount ?>;

    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
       
        onChange: (dates) => {
            if (dates.length === 2) {
                const [start, end] = dates;
                const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;

                startHidden.value = start.toISOString().split('T')[0];
                endHidden.value = end.toISOString().split('T')[0];
                daysInput.value = days;
                daysCount.textContent = days;

                updateTotal();
            }
        }
    });

    quantity.addEventListener('input', updateTotal);

    function updateTotal() {
        const qty = parseInt(quantity.value) || 0;
        const days = parseInt(daysInput.value) || 0;
        const total = base * qty * days;
        totalPrice.textContent = total.toLocaleString('vi-VN') + '₫';
    }

    const previews = document.querySelectorAll('.gallery-preview img');
    const mainImg = document.querySelector('.main-image');
    previews.forEach(img => {
        img.addEventListener('click', () => {
            mainImg.src = img.src;
        });
    });
});

function showTab(id) {
    document.querySelectorAll('.tab-content').forEach(e => e.style.display = 'none');
    document.querySelectorAll('.tab-list li').forEach(e => e.classList.remove('active'));
    document.getElementById(id).style.display = 'block';
    event.target.classList.add('active');
}
</script>

<div class="container">
  <div class="tour-hero">
      <div class="tour-gallery">
          <img class="main-image" src="/Booking/ltWeb/TourBooking/public/img/<?= $tourInfo['image'] ?>">
          <div class="gallery-preview">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                  <img src="/Booking/ltWeb/TourBooking/public/img/<?= $tourInfo['image'] ?>">
              <?php endfor; ?>
          </div>
      </div>
      <div class="tour-summary">
          <h1><?= $tourInfo['title'] ?></h1>
          <p class="address">📍 <?= $tourInfo['location'] ?></p>
          <p class="stars">⭐ <?= $tourInfo['stars'] ?>/5</p>
          <p class="desc"><?= $tourInfo['description'] ?></p>
          <ul class="features">
              <li><b>Thời gian:</b> <?= $tourInfo['duration_days'] ?> ngày</li>
              <li><b>Ngày:</b> <?= $tourInfo['start_date'] ?> đến <?= $tourInfo['end_date'] ?></li>
              <li><b>Loại tour:</b> <?= $tourInfo['loai_tour'] === 'trongnuoc' ? 'Trong nước' : 'Nước ngoài' ?></li>
          </ul>
          <div class="price-box">
              <?php if ($tourInfo['discount_percent'] > 0): ?>
                  <span class="old-price"><del><?= number_format($tourInfo['price']) ?>₫</del></span>
                  <span class="final-price text-danger fw-bold"><?= number_format($priceAfterDiscount) ?>₫ / người / ngày</span>
              <?php else: ?>
                  <span class="final-price fw-bold text-danger"><?= number_format($tourInfo['price']) ?>₫ / người / ngày</span>
              <?php endif; ?>
          </div>

          <form method="POST" class="tour-book-form">
              <label for="date_range">Chọn ngày:</label>
              <input type="text" id="date_range" placeholder="Chọn ngày bắt đầu và kết thúc" required>
              <input type="hidden" name="start_date" id="start_date">
              <input type="hidden" name="end_date" id="end_date">

              <label for="quantity">Số người:</label>
              <input type="number" name="quantity" id="quantity" min="1" value="1" required>

              <input type="hidden" name="days" id="days">
              <p>Số ngày: <strong id="days-count">0</strong></p>

              <div>Tổng giá: <strong id="total-price"><?= number_format($priceAfterDiscount) ?>₫</strong></div>
              <button type="submit">Đặt tour ngay</button>
          </form>
      </div>
  </div>

  <div class="tour-tabs">
      <ul class="tab-list">
          <li class="active" onclick="showTab('features')">✨ Điểm nổi bật</li>
          <li onclick="showTab('facilities')">🛎 Tiện ích</li>
          <li onclick="showTab('map')">📍 Vị trí</li>
          <li onclick="showTab('reviews')">🗣 Đánh giá</li>
      </ul>
      <div class="tab-wrap">
          <div class="tab-content" id="features">
              <ul>
                  <li>✔ Gần bãi biển</li>
                  <li>✔ Đưa đón sân bay</li>
                  <li>✔ Ngắm cảnh đẹp</li>
                  <li>✔ Trải nghiệm địa phương</li>
              </ul>
          </div>
          <div class="tab-content" id