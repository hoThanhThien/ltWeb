<?php
require_once __DIR__ . '/../models/Tour.php';
include_once 'header.php';

require_once '../controllers/HomeController.php';
require_once '../controllers/TourController.php';
function getImageSrc($image) {
    return (preg_match('/^https?:\/\//i', $image))
        ? htmlspecialchars($image)
        : '/Booking/ltWeb/TourBooking/public/img/' . htmlspecialchars($image);
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<h3>Không tìm thấy tour. Vui lòng kiểm tra liên kết.</h3>";
    return;
}
$tourModel = new Tour();
$tourInfo = $tourModel->getTourById($_GET['id']);

if (!$tourInfo) {
    echo "<h3>Không tìm thấy tour. Vui lòng kiểm tra lại.</h3>";
    return;
}
$priceAfterDiscount = $tourInfo['price'] * (1 - $tourInfo['discount_percent'] / 100);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/database.php';
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id'])) {
        $error = "Vui lòng đăng nhập để đặt tour.";
    } else {
        $people = (int)$_POST['quantity'];
        $days = (int)$_POST['days'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $totalPrice = $priceAfterDiscount * $people * $days;

        // Kiểm tra số lượng vé
        if ($tourInfo['available_slots'] <= 0) {
            $error = "Tour này đã hết vé. Vui lòng chọn tour khác!";
        } elseif ($people > $tourInfo['available_slots']) {
            $error = "Số lượng vé không đủ! Chỉ còn {$tourInfo['available_slots']} vé.";
        } elseif ($people <= 0) {
            $error = "Số lượng vé phải lớn hơn 0.";
        } else {
            $db = connect_db();
            $query = $db->prepare("INSERT INTO bookings (user_id, tour_id, quantity, total_price, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
            $query->execute([
                $_SESSION['user_id'], $tourInfo['id'], $people, $totalPrice, $startDate, $endDate
            ]);
            // Cập nhật lại số lượng vé còn lại
            $update = $db->prepare("UPDATE tours SET available_slots = available_slots - ? WHERE id = ?");
            $update->execute([$people, $tourInfo['id']]);
            if ($update->errorCode() !== '00000') {
                $errInfo = $update->errorInfo();
                $error = "Lỗi SQL khi cập nhật vé: " . $errInfo[2];
            } else {
                $success = "Đặt tour thành công!";
                // Reload lại số vé mới
                $tourInfo = $tourModel->getTourById($_GET['id']);
            }
        }
    }
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="../css/tour_detail.css">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="../js/tour_detail.js"></script>
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
      <img class="main-image" src="<?= getImageSrc($tourInfo['image']) ?>">
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
                <li><p class="desc"><?= $tourInfo['description'] ?></p></li>
            </ul>
          
        </div>
        <div class="tab-content" id="facilities" style="display: none;">
          <ul>
            <li>🛏️ Phòng nghỉ tiện nghi</li>
            <li>🍽️ Nhà hàng phục vụ 24/7</li>
            <li>🏊 Không khi vui vẻ</li>
            <li>💪 Phù hợp sức khỏe</li>
          </ul>
        </div>
        <div class="tab-content" id="map" style="display: none;">
          <ul>
           <p class="address">📍 <?= $tourInfo['location'] ?></p>
          </ul>
        </div>
         <div class="tab-content" id="reviews" style="display: none;">
          <ul>
           <p class="stars">⭐ <?= $tourInfo['stars'] ?>/5</p>
          </ul>
        </div>
      </div>
    </div>
    </div>
    <div class="tour-summary">
      <h1><?= $tourInfo['title'] ?></h1>
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

      <?php if (!empty($error)): ?>
      <div class="alert alert-danger" style="background:#ffeaea;color:#d8000c;padding:12px 18px;border-radius:8px;margin-bottom:16px;border:1px solid #f5c6cb;">
          <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
      <div class="alert alert-success" style="background:#eaffea;color:#008000;padding:12px 18px;border-radius:8px;margin-bottom:16px;border:1px solid #b2f5c6;">
          <?= htmlspecialchars($success) ?>
      </div>
      <?php endif; ?>

      <?php if ($tourInfo['available_slots'] <= 0): ?>
          <div class="alert alert-danger" style="background:#ffeaea;color:#d8000c;padding:12px 18px;border-radius:8px;margin-bottom:16px;border:1px solid #f5c6cb;">
              Tour này đã hết vé. Vui lòng chọn tour khác!
          </div>
      <?php elseif (empty($success)): ?>
          <form method="POST" class="tour-book-form">
            <label>Ngày khởi hành:</label>
            <input type="text" value="<?= htmlspecialchars($tourInfo['start_date']) ?> đến <?= htmlspecialchars($tourInfo['end_date']) ?>" readonly class="form-control-plaintext">
            <input type="hidden" name="start_date" value="<?= htmlspecialchars($tourInfo['start_date']) ?>">
            <input type="hidden" name="end_date" value="<?= htmlspecialchars($tourInfo['end_date']) ?>">

            <label for="quantity">Số người:</label>
            <input type="number" name="quantity" id="quantity" min="1" value="1" required>

            <input type="hidden" name="days" id="days" value="<?= $tourInfo['duration_days'] ?>">
            <p>Số ngày: <strong><?= $tourInfo['duration_days'] ?></strong></p>

            <div>Tổng giá: <strong id="total-price"><?= number_format($priceAfterDiscount * $tourInfo['duration_days']) ?>₫</strong></div>
            <button type="submit">Đặt tour ngay</button>
          </form>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include_once 'footer.php'; ?>