<?php
include_once 'header.php';
require_once '../models/Tour.php';
require_once '../controllers/HomeController.php';
require_once '../controllers/TourController.php';
// Hàm gọn để xử lý ảnh
function getImageSrc($image) {
    return preg_match('/^https?:\/\//i', $image) ? htmlspecialchars($image) : '../img/' . htmlspecialchars($image);
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../css/home.css"/>
    <link rel="stylesheet" href="../css/chitiethome.css"/>
    <title>ĐẶT TOUR TRỌN GÓI</title>
    <script src="../js/main.js"></script>
</head>

<body>
    <div class="slideshow-container">
    <div class="slider-container">
        <img class="slider active" src="../img/banner1.jpg" alt="Slide 1">
        <img class="slider" src="../img/banner2.webp" alt="Slide 2">
        <img class="slider" src="../img/banner3.jpg" alt="Slide 3">
        <img class="slider" src="../img/banner4.jpg" alt="Slide 4">
        <img class="slider" src="../img/banner5.jpg" alt="Slide 5">
    </div>
    
       
</div>
</div> 
<form method="GET" action="" class="filter-form">
<div class="home-categories-wrapper">
    <div class="home-category-item">
        <a href="#">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Tour Biển Đảo">
        </a>
        <select name="filter">
    <option value="">-- Tất cả tour --</option>
    <option value="domestic" <?= ($_GET['filter'] ?? '') === 'domestic' ? 'selected' : '' ?>>Trong nước</option>
    <option value="international" <?= ($_GET['filter'] ?? '') === 'international' ? 'selected' : '' ?>>Nước ngoài</option>
    <option value="discount" <?= ($_GET['filter'] ?? '') === 'discount' ? 'selected' : '' ?>>Khuyến mãi</option>
</select>
    </div>
    <div class="home-category-item">
        <a href="#">
           
            <img src="https://cdn-icons-png.flaticon.com/512/2942/2942788.png" alt="Tour Ẩm Thực">
        </a>
        
    <input type="number" name="min_price" placeholder="Giá từ" value="<?= $_GET['min_price'] ?? '' ?>">
    <input type="number" name="max_price" placeholder="Đến" value="<?= $_GET['max_price'] ?? '' ?>">
    </div>
    <div class="home-category-item">
        <a href="#">
             <img src="https://cdn-icons-png.flaticon.com/512/2921/2921879.png" alt="Tour Leo Núi">
        </a>
            <input type="date" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>">
    <input type="date" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>">
    </div>

    <div class="home-category-item">
        <a href="#">
            <img src="https://cdn-icons-png.flaticon.com/512/817/817237.png" alt="lọc">
            <button type="submit">Lọc</button>
        </a>
    </div>
</div>

</form>

    <div class="main-content">
        <?php if (!empty($randomTours)): ?>
        
            <section class="random-tours">
             <h3>Tất Cả TOUR </h3>  
            
    <div id="tour-list">
        <?php foreach ($randomTours as $tour): ?>
            <div class="tour-item">
                <img src="<?php echo getImageSrc($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                
                <div class="tour-content">
                    <h3><?php echo htmlspecialchars($tour['title']); ?> - <?php echo htmlspecialchars($tour['location']); ?></h3>
                   <p><?php if (!empty($tour['discount_price']) && $tour['discount_price'] > 0): ?>
    <span><del><?= number_format($tour['price'], 0, ',', '.') ?>₫</del></span>
    <span><?= number_format($tour['discount_price'], 0, ',', '.') ?>₫ / người</span>
<?php else: ?>
    <span><?= number_format($tour['price'], 0, ',', '.') ?>₫ / người</span>
<?php endif; ?>
</p>
                    <p>Ngày khởi hành: <?php echo htmlspecialchars($tour['start_date']); ?> - <?php echo htmlspecialchars($tour['end_date']); ?></p>
                    <p class="stars"><?php for ($i=0; $i<$tour['stars']; $i++) echo '⭐'; ?></p>
                </div> <a href="/booking?id=<?php echo $tour['id']; ?>" class="btn">Đặt Tour</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
        <?php endif; ?>

        <?php if (!empty($domesticTours) && $filter === 'domestic'): ?>
        <section class="domestic-tours">
            <h3>CÁC TOUR TRONG NƯỚC</h3>
            <div id="tour-list">
                <?php foreach ($domesticTours as $tour): ?>
                    <div class="tour-item">
                        <img src="<?php echo getImageSrc($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                        <h3><?php echo htmlspecialchars($tour['title']); ?> - <?php echo htmlspecialchars($tour['location']); ?></h3>
                        <p><?php if (!empty($tour['discount_price']) && $tour['discount_price'] > 0): ?>
    <span><del><?= number_format($tour['price'], 0, ',', '.') ?>₫</del></span>
    <span><?= number_format($tour['discount_price'], 0, ',', '.') ?>₫ / người</span>
<?php else: ?>
    <span><?= number_format($tour['price'], 0, ',', '.') ?>₫ / người</span>
<?php endif; ?>
</p>
                        <p>Ngày khởi hành: <?php echo htmlspecialchars($tour['start_date']); ?> - <?php echo htmlspecialchars($tour['end_date']); ?></p>
                        <p class="stars"><?php for ($i=0; $i<$tour['stars']; $i++) echo '⭐'; ?></p>
                        <a href="/booking?id=<?php echo $tour['id']; ?>" class="btn">Đặt Tour</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($internationalTours) && $filter === 'international'): ?>
        <section class="international-tours">
            <h3>CÁC TOUR NƯỚC NGOÀI</h3>
            <div id="tour-list">
                <?php foreach ($internationalTours as $tour): ?>
                    <div class="tour-item">
                        <img src="<?php echo getImageSrc($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                        <h3><?php echo htmlspecialchars($tour['title']); ?> - <?php echo htmlspecialchars($tour['location']); ?></h3>
                        <p><?php if (!empty($tour['discount_price']) && $tour['discount_price'] > 0): ?>
    <span><del><?= number_format($tour['price'], 0, ',', '.') ?>₫</del></span>
    <span><?= number_format($tour['discount_price'], 0, ',', '.') ?>₫ / người</span>
<?php else: ?>
    <span><?= number_format($tour['price'], 0, ',', '.') ?>₫ / người</span>
<?php endif; ?>
</p>
<!-- Debugging output 
<pre><?php print_r($tour); ?></pre>
-->
                        <p>Ngày khởi hành: <?php echo htmlspecialchars($tour['start_date']); ?> - <?php echo htmlspecialchars($tour['end_date']); ?></p>
                        <p class="stars"><?php for ($i=0; $i<$tour['stars']; $i++) echo '⭐'; ?></p>
                        <a href="/booking?id=<?php echo $tour['id']; ?>" class="btn">Đặt Tour</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
                
        <?php include 'pagination.php'; ?>
    </div>

    <?php include_once 'footer.php'; ?>

</body>
</html>
