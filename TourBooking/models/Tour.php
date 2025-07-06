<?php
require_once __DIR__ . '/../config/database.php';

class Tour {
    private $conn;

    public function __construct() {
        $this->conn = connect_db();
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
        return $stmt->fetch();
        
    }

    public function addTour($title, $location, $description, $price, $duration_days, $start_date, $end_date, $available_slots, $stars, $image, $loai_tour) {
        $stmt =$this->conn->prepare("INSERT INTO tours (title, location, description, price, duration_days, start_date, end_date, available_slots, stars, image, loai_tour) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $location, $description, $price, $duration_days, $start_date, $end_date, $available_slots, $stars, $image, $loai_tour]);
    }
    public function updateTour($id, $title, $location, $description, $price, $duration_days, $start_date, $end_date, $available_slots, $stars, $image, $loai_tour) {
    $stmt = $this->conn->prepare("UPDATE tours SET title=?, location=?, description=?, price=?, duration_days=?, start_date=?, end_date=?, available_slots=?, stars=?, image=?, loai_tour=? WHERE id=?");
    $stmt->execute([$title, $location, $description, $price, $duration_days, $start_date, $end_date, $available_slots, $stars, $image, $loai_tour, $id]);
}

public function deleteTour($id) {
    $stmt = $this->conn->prepare("DELETE FROM tours WHERE id=?");
    $stmt->execute([$id]);
}

    public function getCategories() {
        return $this->conn->query("SELECT * FROM categories")->fetchAll();
    }
    
}
