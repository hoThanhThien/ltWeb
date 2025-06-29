 <?php
    include_once 'header.php';
    include_once 'footer.php';
 ?>
 <!-- Home section -->
    <div id="home">
        <div class="banner">
            <img src="../public/img/bennerHeader.jpg" alt="Banner">
        </div>

        <div class="homeSearch">
            <div class="homeSearch-title">
                <div><a href="">Tour trọn gói</a></div>
                <div><a href="">Tour trong nước</a></div>
                <div><a href="">Tour nước ngoài</a></div>
            </div>
            <div class="search-section">
                <div class="search-item">
                    <label for="destination">Bạn muốn đi đâu?</label>
                    <input type="text" id="destination" placeholder="Khám phá cuộc phiêu lưu tiếp theo của bạn — tìm kiếm bất kỳ điểm đến nào">
                </div>
                <div class="search-item">
                    <label for="departure-date">Ngày đi</label>
                    <input type="text" id="departure-date" readonly>
                </div>
                <div class="search-item">
                    <label for="budget">Ngân sách</label>
                    <select id="budget">
                        <option value="">Chọn mức giá</option>
                    </select>
                </div>
                <button class="search-button">
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="home-banner">
            <div class="tour-categories">
                <div class="tour-category"><img src="../public/img/uudaihe.webp" alt=""><br><p>ƯU ĐÃI ONLINE HÈ</p></div>
                <div class="tour-category"><img src="../public/img/le.webp" alt=""><br><p>ƯU ĐÃI LỄ</p></div>
                <div class="tour-category"><img src="../public/img/caocap.webp" alt=""><br><p>TOUR CAO CẤP</p></div>
                <div class="tour-category"><img src="../public/img/tieuchuan.webp" alt=""><br><p>TOUR TIÊU CHUẨN</p></div>
            </div>
        </div>

       <div class="slideshow-container">
            <img class="slider active" src="../public/img/banner1.jpg" alt="Slide 1">
            <img class="slider" src="../public/img/banner2.webp" alt="Slide 2">
            <img class="slider" src="../public/img/banner3.jpg" alt="Slide 3">
            <img class="slider" src="../public/img/banner4.jpg" alt="Slide 4">
            <img class="slider" src="../public/img/banner5.jpg" alt="Slide 5">
        </div>


        <div class="homeHeard">
            <h2>CÙNG KHÁM PHÁ TRẢI NGHIỆM</h2>
            <b>Hãy chọn một điểm đến du lịch nổi tiếng dưới đây để khám phá các chuyến đi độc quyền của chúng tôi với mức giá vô cùng hợp lý.</b>
        </div>
    </div>
    <!-- Thêm vào vị trí bạn muốn hiển thị danh sách tour -->
    <div id="tour-list">
    <?php
        include_once
    // Kết nối CSDL (nếu chưa có)
    // $conn = new mysqli('localhost', 'root', '', 'ten_csdl');
    // if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);
    // Kết nối CSDL (sửa lại tên database, user, password nếu cần)
    error_reporting(E_ALL);// Hiển thị tất cả lỗi
    ini_set('display_errors', 1);// Hiển thị lỗi trên trình duyệt

    $conn = new mysqli('localhost', 'root', '', 'tours');
    if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);}
    

    $sql = "SELECT id, name, price, stars, image FROM tours ORDER BY RAND() LIMIT 10";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0):
        while($tour = $result->fetch_assoc()):
    ?>

    <div class="tour-item">
            <img src="../public/img/<?php echo htmlspecialchars($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" style="width:200px;height:130px;">
            <h4><?php echo htmlspecialchars($tour['name']); ?></h4>
            <p>Giá: <?php echo number_format($tour['price'], 0, ',', '.'); ?> VNĐ</p>
            <p>Số sao: <?php for ($i = 0; $i < $tour['stars']; $i++) echo '⭐'; ?></p>
            <a href="booking.php?id=<?php echo $tour['id']; ?>" class="btn" onclick="event.stopPropagation();">Đặt Tour</a>
        </div>
    <?php
        endwhile;
    else:
        echo "<p>Không có tour nào.</p>";
    endif;
    ?> 
    </div>
    
    <h3>CÁC TOUR TRONG NƯỚC</h3>
    <div id="tour-list">
    <?php
        $sql_domestic = "SELECT id, name, price, stars, image FROM tours WHERE type='domestic' ORDER BY RAND() lIMIT 15";
        $result_domestic = $conn->query($sql_domestic);
        if ($result_domestic && $result_domestic->num_rows > 0):
            while($tour = $result_domestic->fetch_assoc()):
    ?>
        <div class="tour-item">
            <img src="../public/img/<?php echo htmlspecialchars($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" style="width:200px;height:130px;">
            <h4><?php echo htmlspecialchars($tour['name']); ?></h4>
            <p>Giá: <?php echo number_format($tour['price'], 0, ',', '.'); ?> VNĐ</p>
            <p>Số sao: <?php for ($i = 0; $i < $tour['stars']; $i++) echo '⭐'; ?></p>
            <a href="booking.php?id=<?php echo $tour['id']; ?>" class="btn" onclick="event.stopPropagation();">Đặt Tour</a>
        </div>
    <?php
            endwhile;
        else:
            echo "<p>Không có tour trong nước.</p>";
        endif;
    ?>
    </div>

    <h3>CÁC TOUR NƯỚC NGOÀI</h3>
    <div id="tour-list">
    <?php
        $sql_international = "SELECT id, name, price, stars, image FROM tours WHERE type='international' ORDER BY RAND() LIMIT 10";
        $result_international = $conn->query($sql_international);
        if ($result_international && $result_international->num_rows > 0):
            while($tour = $result_international->fetch_assoc()):
    ?>
        <div class="tour-item">
            <img src="../public/img/<?php echo htmlspecialchars($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" style="width:200px;height:130px;">
            <h4><?php echo htmlspecialchars($tour['name']); ?></h4>
            <p>Giá: <?php echo number_format($tour['price'], 0, ',', '.'); ?> VNĐ</p>
            <p>Số sao: <?php for ($i = 0; $i < $tour['stars']; $i++) echo '⭐'; ?></p>
            <a href="booking.php?id=<?php echo $tour['id']; ?>" class="btn" onclick="event.stopPropagation();">Đặt Tour</a>
        </div>
    <?php
            endwhile;
        else:
            echo "<p>Không có tour nước ngoài.</p>";
        endif;
    ?>
    </div>

    <!-- Thêm vào cuối file home.php, sau các script khác -->
<script src="../public/js/main.js"></script>
  
    