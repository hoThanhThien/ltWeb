<?php
include_once 'header.php';
require_once '../models/Tour.php';
require_once '../controllers/HomeController.php';
// Hàm gọn để xử lý ảnh
function getImageSrc($image) {
    return preg_match('/^https?:\/\//i', $image) ? htmlspecialchars($image) : '../public/img/' . htmlspecialchars($image);
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
    <div id="home">
        <div class="homeSearch">
            <div class="homeSearch-title">
                <div><a href="?filter=random-tours">Tất cả</a></div>
                <div><a href="?filter=domestic">Tour trong nước</a></div>
                <div><a href="?filter=international">Tour nước ngoài</a></div>
            </div>
            <div class="search-section">
                <div class="search-item">
                    <label for="destination">Bạn muốn đi đâu?</label>
                    <input type="text" id="destination" placeholder="Khám phá cuộc phiêu lưu tiếp theo của bạn — tìm kiếm bất kỳ điểm đến nào">
                </div>
                <div class="search-item">
                    <label for="departure-date">Địa điểm</label>
                    <input type="text" id="departure-date" readonly>
                </div>
                <div class="search-item">
                    <button class="search-button">
                    <i class="fa fa-search"></i>
                    Tìm kiếm
                </button>

                </div>
                
            </div>
        </div>

        <div class="slideshow-container">
            <img class="slider active" src="../img/banner1.jpg" alt="Slide 1">
            <img class="slider" src="../img/banner2.webp" alt="Slide 2">
            <img class="slider" src="../img/banner3.jpg" alt="Slide 3">
            <img class="slider" src="../img/banner4.jpg" alt="Slide 4">
            <img class="slider" src="../img/banner5.jpg" alt="Slide 5">
        </div>
    </div>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
    <div class="main-content">
        <?php if (!empty($randomTours) && ($filter === 'random-tours' || !$filter)): ?>
        <section class="random-tours">
            <h3>TOUR NGẪU NHIÊN</h3>
            <div id="tour-list">
                <?php foreach ($randomTours as $tour): ?>
                    <div class="tour-item">
                        <img src="<?php echo getImageSrc($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                        <h3><?php echo htmlspecialchars($tour['title']); ?> - <?php echo htmlspecialchars($tour['location']); ?></h3>
                        <p>Giá: <?php echo number_format($tour['discount_price'] ?: $tour['price'], 0, ',', '.'); ?> VNĐ</p>
                        <p>Ngày khởi hành: <?php echo htmlspecialchars($tour['start_date']); ?> - <?php echo htmlspecialchars($tour['end_date']); ?></p>
                        <p class="stars"><?php for ($i=0; $i<$tour['stars']; $i++) echo '⭐'; ?></p>
                        <a href="/booking?id=<?php echo $tour['id']; ?>" class="btn">Đặt Tour</a>
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
                        <p>Giá: <?php echo number_format($tour['discount_price'] ?: $tour['price'], 0, ',', '.'); ?> VNĐ</p>
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
                        <p>Giá: <?php echo number_format($tour['discount_price'] ?: $tour['price'], 0, ',', '.'); ?> VNĐ</p>
                        <p>Ngày khởi hành: <?php echo htmlspecialchars($tour['start_date']); ?> - <?php echo htmlspecialchars($tour['end_date']); ?></p>
                        <p class="stars"><?php for ($i=0; $i<$tour['stars']; $i++) echo '⭐'; ?></p>
                        <a href="/booking?id=<?php echo $tour['id']; ?>" class="btn">Đặt Tour</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

         <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?filter=<?php echo htmlspecialchars($filter); ?>&page=<?php echo $i; ?>"
                   class="<?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
    </div>

    <?php include_once 'footer.php'; ?>
</body>
</html>
