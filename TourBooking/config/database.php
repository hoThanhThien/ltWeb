<?php
// File: config/database.php
// Chứa thông tin kết nối đến cơ sở dữ liệu

define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Thay bằng username của bạn
define('DB_PASS', ''); // Thay bằng password của bạn
define('DB_NAME', 'tour_booking_db'); // Thay bằng tên CSDL của bạn
define('PAYMENT_BANK_CODE', 'MB');
define('PAYMENT_ACCOUNT_NUMBER', '0375227764');
define('PAYMENT_ACCOUNT_NAME', 'CTTNHH DIDI');

function connect_db() {
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        // Thiết lập chế độ lỗi PDO thành exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("set names utf8");
        return $conn;
    } catch(PDOException $e) {
        die("Kết nối thất bại: " . $e->getMessage());
    }
}
?>