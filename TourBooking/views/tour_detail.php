<?php
// Kết nối CSDL
$conn = new mysqli('localhost', 'root', '', 'tours');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM tours WHERE id = $id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $tour = $result->fetch_assoc();
} else {
    echo "<p>Không tìm thấy tour.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết tour - <?php echo htmlspecialchars($tour['name']); ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .tour-detail-container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 32px;
        }
        .tour-detail-container img {
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            margin-bottom: 20px;
            object-fit: cover;
        }
        .tour-detail-container h2 {
            color: #1976d2;
        }
        .tour-detail-container p {
            font-size: 1.1rem;
        }
        .tour-detail-container .btn {
            display: inline-block;
            margin-top: 18px;
            background: #1976d2;
            color: #fff;
            padding: 10px 28px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        .tour-detail-container .btn:hover {
            background: #0d47a1;
        }
    </style>
</head>
<body>
    <div class="tour-detail-container">
        <img src="../public/img/<?php echo htmlspecialchars($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>">
        <h2><?php echo htmlspecialchars($tour['name']); ?></h2>
        <p><strong>Giá:</strong> <?php echo number_format($tour['price'], 0, ',', '.'); ?> VNĐ</p>
        <p><strong>Số sao:</strong> <?php for ($i = 0; $i < $tour['stars']; $i++) echo '⭐'; ?></p>
        <p><strong>Loại tour:</strong> <?php echo $tour['type'] == 'domestic' ? 'Trong nước' : 'Nước ngoài'; ?></p>
        <!-- Thêm các thông tin khác nếu có, ví dụ: mô tả, lịch trình,... -->
        <a href="booking.php?id=<?php echo $tour['id']; ?>" class="btn">Đặt Tour</a>
        <a href="home.php" class="btn" style="background:#888;margin-left:10px;">Quay lại</a>
    </div>
</body>
</html>