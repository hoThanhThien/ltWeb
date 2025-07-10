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
     * Lấy danh sách đơn hàng của người dùng với phân trang.
     */
    public function getBookingsByUserIdPaginated($userId, $limit, $offset) {
        $query = "
            SELECT b.id AS booking_id, t.title AS tour_name, b.created_at, b.total_price, b.status
            FROM bookings b
            JOIN tours t ON b.tour_id = t.id
            WHERE b.user_id = :user_id
            ORDER BY b.created_at DESC
            LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm tổng số đơn hàng của một người dùng.
     */
    public function countUserBookings($userId) {
        $stmt = $this->conn->prepare("SELECT COUNT(id) FROM bookings WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
 /**
     * Lấy tất cả đơn hàng của một người dùng cụ thể.
     */
    public function getBookingsByUserId($userId) {
        $query = "
            SELECT 
                b.id AS booking_id,
                t.title AS tour_name,
                b.created_at,
                b.total_price,
                b.status
            FROM 
                bookings b
            JOIN 
                tours t ON b.tour_id = t.id
            WHERE 
                b.user_id = :user_id
            ORDER BY 
                b.created_at DESC
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

/**
     * Cập nhật trạng thái cho một đơn hàng cụ thể.
     * @param int $bookingId ID của đơn hàng
     * @param string $newStatus Trạng thái mới (ví dụ: 'confirmed', 'pending')
     * @return bool
     */
    public function updateBookingStatus($bookingId, $newStatus) {
        try {
            $sql = "UPDATE bookings SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
            $stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

public function getBookingsPaginated($limit, $offset) {
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
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>