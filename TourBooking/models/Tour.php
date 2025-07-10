<?php
require_once __DIR__ . '/../config/database.php';

class Tour {
    private $conn;

    public function __construct() {
        $this->conn = connect_db();
    }


/*
Đếm tổng số tour đang có.
 */
public function getTotalToursCount() {
     $stmt = $this->conn->query("SELECT COUNT(id) as total FROM tours");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}
public function getTours($type = null, $limit = 10, $offset = 0) {
    $limit = (int)$limit;
    $offset = (int)$offset;
// truy vấn cơ sở dữ liệu để lấy danh sách tour
    $sql = "SELECT id, title, location, price, discount_price,discount_percent, start_date, end_date, stars, image, loai_tour FROM tours";

    if ($type) {
        $sql .= " WHERE loai_tour = :type";
    }

    //  thêm SELECT và sắp xếp rõ ràng
    $sql .= " ORDER BY start_date DESC LIMIT :limit OFFSET :offset";

    $stmt = $this->conn->prepare($sql);

    if ($type) {
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getAllTours() {
    $stmt = $this->conn->prepare("SELECT * FROM tours");
    $stmt->execute();
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $tours;
}


public function countTours($type = null) {
    $sql = "SELECT COUNT(*) FROM tours";
    if ($type) {
        $sql .= " WHERE loai_tour = :type";
    }
    $stmt = $this->conn->prepare($sql);

    if ($type) {
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
    }
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getToursByCategory($categoryId) {
        $stmt = $this->conn->prepare("SELECT * FROM tours WHERE category_id = ?");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function getTourById($id) {
    $stmt = $this->conn->prepare("SELECT * FROM tours WHERE id = ?");
    $stmt->execute([$id]);
    // Trả về một mảng hoặc false nếu không tìm thấy
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//  hàm addTour để nhận một mảng
public function addTour(array $data) {
    $sql = "INSERT INTO tours (title, location, description, price, duration_days, start_date, end_date, available_slots, stars, image, loai_tour) 
            VALUES (:title, :location, :description, :price, :duration_days, :start_date, :end_date, :available_slots, :stars, :image, :loai_tour)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute($data);
}

//  hàm updateTour để nhận một mảng
public function updateTour(array $data) {
    $sql = "UPDATE tours SET 
                title = :title, 
                location = :location, 
                description = :description, 
                price = :price, 
                duration_days = :duration_days, 
                start_date = :start_date, 
                end_date = :end_date, 
                available_slots = :available_slots, 
                stars = :stars, 
                image = :image, 
                loai_tour = :loai_tour 
            WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute($data);
}

public function deleteTour($id) {
    $stmt = $this->conn->prepare("DELETE FROM tours WHERE id=?");
    $stmt->execute([$id]);
}

    public function getFilteredTours(array $filters = [], $limit = 12, $offset = 0) {
        $sql = "SELECT id, title, location, price, discount_price, discount_percent, start_date, end_date, stars, image, loai_tour FROM tours WHERE 1=1";
        $params = [];

        // Lọc theo loại tour (đã được dịch ở Controller)
        if (!empty($filters['loai_tour'])) {
            $sql .= " AND loai_tour = :loai_tour";
            $params[':loai_tour'] = $filters['loai_tour'];
        }

        // Lọc theo tour khuyến mãi
        if (!empty($filters['filter']) && $filters['filter'] === 'discount') {
            $sql .= " AND discount_percent > 0";
        }

        // Lọc theo khoảng giá
        if (!empty($filters['min_price'])) {
            $sql .= " AND price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        // Lọc theo khoảng ngày
        if (!empty($filters['start_date'])) {
            $sql .= " AND start_date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND end_date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }
        
        $sql .= " ORDER BY start_date DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        foreach ($params as $key => &$val) {
            $stmt->bindValue($key, $val);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countFilteredTours(array $filters = []) {
        $sql = "SELECT COUNT(*) FROM tours WHERE 1=1";
        $params = [];

        if (!empty($filters['loai_tour'])) {
            $sql .= " AND loai_tour = :loai_tour";
            $params[':loai_tour'] = $filters['loai_tour'];
        }

        if (!empty($filters['filter']) && $filters['filter'] === 'discount') {
            $sql .= " AND discount_percent > 0";
        }

        if (!empty($filters['min_price'])) {
            $sql .= " AND price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        if (!empty($filters['start_date'])) {
            $sql .= " AND start_date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND end_date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    public function getCategories() {
        return $this->conn->query("SELECT * FROM categories")->fetchAll();
    }
    
}
