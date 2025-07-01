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
    $sql = "SELECT id, title, location, price, discount_price, start_date, end_date, stars, image, loai_tour FROM tours";
    if ($type) {
        $sql .= " WHERE loai_tour = :type";
    }
    $sql .= " ORDER BY RAND() LIMIT :limit OFFSET :offset";
    $stmt = $this->conn->prepare($sql);

    if ($type) {
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function getCategories() {
        return $this->conn->query("SELECT * FROM categories")->fetchAll();
    }
}
