<?php
// File: public/test_write.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Kiểm tra quyền ghi...</h1>";
$logDir = __DIR__ . '/../logs'; // Đường dẫn đến thư mục logs

echo "<p>Đang kiểm tra thư mục: <strong>" . realpath($logDir) . "</strong></p><hr>";

if (!is_dir($logDir)) {
    echo "<p style='color:red; font-weight:bold;'>LỖI: Thư mục 'logs' không tồn tại! Bạn hãy tạo nó bằng tay trong 'ltWeb/TourBooking/'.</p>";
} elseif (!is_writable($logDir)) {
    echo "<p style='color:red; font-weight:bold;'>LỖI: Không có quyền ghi vào thư mục 'logs'! Vấn đề 100% nằm ở việc cấp quyền cho thư mục trên Windows. Hãy làm lại thật kỹ bước cấp quyền 'Write' cho 'Users'.</p>";
} else {
    echo "<p style='color:green; font-weight:bold;'>TIN TỐT: PHP có quyền ghi vào thư mục 'logs'!</p>";
    $logFile = $logDir . '/payment_debug.log';
    $testContent = "Test write from test_write.php OK at " . date('Y-m-d H:i:s') . "\n";

    if (file_put_contents($logFile, $testContent, FILE_APPEND) !== false) {
         echo "<p style='color:green; font-weight:bold;'>THÀNH CÔNG: Đã ghi thử thành công vào file 'payment_debug.log'.</p>";
         echo "<p>Điều này có nghĩa là lỗi không phải do quyền ghi. Vấn đề nằm ở chỗ khác (có thể hàm handleWebhook của bạn không được gọi). </p>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>LỖI: Ghi file thất bại dù có quyền! Lỗi này rất lạ, hãy kiểm tra Apache error log.</p>";
    }
}
?>