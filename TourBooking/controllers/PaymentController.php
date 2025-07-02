<?php
// File: ltWeb/TourBooking/controllers/PaymentController.php

require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../models/Tour.php';

class PaymentController {
    private $conn;
    private $logFile = __DIR__ . '/../logs/payment_debug.log'; // Đường dẫn file log

    public function __construct() {
        $this->conn = connect_db();
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
    }

    private function _log($message) {
        file_put_contents($this->logFile, date('[Y-m-d H:i:s] ') . $message . "\n", FILE_APPEND);
    }

    public function showPaymentPage($bookingId) {
        $stmt = $this->conn->prepare("
            SELECT b.id, b.total_price, t.title 
            FROM bookings b
            JOIN tours t ON b.tour_id = t.id
            WHERE b.id = ? AND b.status = 'pending'
        ");
        $stmt->execute([$bookingId]);
        $bookingDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$bookingDetails) {
            $errorMessage = 'Lỗi: Không tìm thấy đơn hàng hoặc đơn hàng đã được xử lý.';
            http_response_code(404);
            require __DIR__ . '/../views/error_view.php';
            return;
        }

        require __DIR__ . '/../views/payment_view.php';
    }

    public function checkStatus() {
        header('Content-Type: application/json');
        if (!isset($_POST['booking_id']) || !is_numeric($_POST['booking_id'])) {
            echo json_encode(['payment_status' => 'invalid_request']);
            return;
        }

        $bookingId = $_POST['booking_id'];
        $stmt = $this->conn->prepare("SELECT status FROM bookings WHERE id = ?");
        $stmt->execute([$bookingId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['status'] === 'confirmed') {
            echo json_encode(['payment_status' => 'Paid']);
        } else {
            echo json_encode(['payment_status' => 'Unpaid']);
        }
    }

    public function handleWebhook() {
        $payload = file_get_contents('php://input');
        $this->_log("INSECURE Webhook Received: " . $payload);
        $data = json_decode($payload);

        if (!is_object($data) || !isset($data->content) || !isset($data->transferAmount)) {
            http_response_code(400);
            $this->_log("Webhook Error: Invalid data structure.");
            echo json_encode(['success' => false, 'message' => 'Invalid data structure']);
            return;
        }

        try {
            // === SỬA LỖI: DI CHUYỂN VIỆC GHI LOG GIAO DỊCH VÀO TRONG TRY...CATCH ===
            // Điều này đảm bảo nếu lưu log giao dịch thất bại, chương trình vẫn tiếp tục xử lý
            // và lỗi sẽ được ghi lại thay vì làm sập chương trình.
            $this->logTransaction($data);

            // Tách mã đơn hàng
            preg_match('/DH\s*(\d+)/i', $data->content, $matches);
            $bookingId = $matches[1] ?? null;

            if (!$bookingId) {
                $this->_log("Webhook Ignored: Booking ID not found in content '{$data->content}'.");
                echo json_encode(['success' => false, 'message' => 'Booking ID not found in content']);
                return;
            }
            $this->_log("Found Booking ID: {$bookingId}");

            // Chỉ xử lý nếu là giao dịch "nhận tiền"
            if ($data->transferType != "in") {
                $this->_log("Webhook Ignored: Not an incoming transaction for booking {$bookingId}.");
                echo json_encode(['success' => true, 'message' => 'Not an incoming transaction. Ignored.']);
                return;
            }

            $amountIn = (float) $data->transferAmount;
            $stmt = $this->conn->prepare("SELECT total_price FROM bookings WHERE id = ? AND status = 'pending'");
            $stmt->execute([$bookingId]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->_log("DB Check for Booking {$bookingId}: Found: " . ($booking ? 'Yes' : 'No') . ". Amount received: {$amountIn}");

            if ($booking && (float)$booking['total_price'] == $amountIn) {
                $updateStmt = $this->conn->prepare(
                    "UPDATE bookings SET status = 'confirmed', paid_at = NOW(), payment_method = 'sepay' WHERE id = ? AND status = 'pending'"
                );
                $updateStmt->execute([$bookingId]);

                $this->_log("SUCCESS: Booking ID {$bookingId} updated to confirmed.");
                echo json_encode(['success' => true, 'message' => 'Booking status updated']);
            } else {
                $reason = !$booking ? 'Booking not found or already processed' : 'Amount mismatch';
                $this->_log("Webhook Failed for Booking ID {$bookingId}: {$reason}");
                echo json_encode(['success' => false, 'message' => $reason]);
            }

        } catch (PDOException $e) {
            // Lỗi database sẽ được bắt và ghi lại ở đây
            $this->_log("DATABASE ERROR: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'A database error occurred.']);
        }
    }
    /**
 * Lưu lại toàn bộ giao dịch vào bảng tb_transition
 */
private function logTransaction($data) {
    // Câu lệnh SQL đã được cập nhật để trỏ đến bảng 'tb_transition'
    $sql = "INSERT INTO tb_transactions (
        gateway, transaction_date, account_number, sub_account, 
        amount_in, amount_out, accumulated, code, 
        transaction_content, reference_number, body
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);
    
    // Thực thi lệnh với dữ liệu từ webhook
    $stmt->execute([
        $data->gateway ?? null,
        $data->transactionDate ?? null,
        $data->accountNumber ?? null,
        $data->subAccount ?? null,
        ($data->transferType == "in") ? ($data->transferAmount ?? 0) : 0,
        ($data->transferType == "out") ? ($data->transferAmount ?? 0) : 0,
        $data->accumulated ?? 0,
        $data->code ?? null,
        $data->content ?? null,
        $data->referenceCode ?? null,
        json_encode($data) // Lưu toàn bộ payload dạng JSON vào cột 'body'
    ]);
}
}
?>