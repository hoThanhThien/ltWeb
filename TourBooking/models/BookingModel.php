<?php
require_once __DIR__ . '/../config/database.php';

class BookingModel {
    private $conn;

    public function __construct() {
        $this->conn = connect_db();
    }

    // Đếm tổng số đơn hàng
    public function countAllBookings() {
        $stmt = $this->conn->query("SELECT COUNT(id) FROM bookings");
        return $stmt->fetchColumn();
    }

    // Tính tổng doanh thu
    public function calculateTotalProfit() {
        // Chỉ tính những đơn đã xác nhận 'confirmed'
        $stmt = $this->conn->query("SELECT SUM(total_price) FROM bookings WHERE status = 'confirmed'");
        return $stmt->fetchColumn() ?? 0;
    }

    // Tính giá trị trung bình mỗi đơn
    public function getAveragePrice() {
        $stmt = $this->conn->query("SELECT AVG(total_price) FROM bookings WHERE status = 'confirmed'");
        return $stmt->fetchColumn() ?? 0;
    }

/**
 * Lấy tất cả đơn hàng kèm thông tin chi tiết.
 */
public function getAllBookingsWithDetails() {
    $query = "
        SELECT 
            b.id AS booking_id,
            u.name AS user_name,
            t.title AS tour_name,
            b.created_at, 
            b.total_price,
            b.status
        FROM 
            bookings b
        JOIN 
            users u ON b.user_id = u.id
        JOIN 
            tours t ON b.tour_id = t.id
        ORDER BY 
            b.created_at DESC 
    ";
    $stmt = $this->conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>