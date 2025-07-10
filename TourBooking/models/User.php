<?php
// File: models/User.php

require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct($db = null) {
        // Cho phép truyền PDO từ bên ngoài để dễ dàng kiểm soát và test.
        $this->db = $db ?? connect_db();
    }

    /**
     * Tìm người dùng theo email.
     */
    public function findUserByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Tìm người dùng theo ID.
     */
    public function findUserById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Tạo người dùng mới.
     */
    public function createUser($name, $email, $password, $role_id = 2, $phone) { 
    try {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role_id, phone) VALUES (:name, :email, :password, :role_id, :phone)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->bindParam(':phone', $phone);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo 'Lỗi: ' . $e->getMessage();
        return false;
    }
}

    /**
 * Tìm người dùng theo số điện thoại.
 */
public function findUserByPhone($phone) {
    try {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE phone = :phone");
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    } catch (PDOException $e) {
        return false;
    }
}
public function getUsersCount() {
    $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['total'] : 0;
}
public function getUserById($id) {
    try {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    } catch (PDOException $e) {
        return false;
    }
}
    /**
     * Lấy danh sách tất cả người dùng.
     */
    public function getAllUsers() {
        try {
            $stmt = $this->db->query("SELECT * FROM users WHERE role_id = 3 ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Đảm bảo trả về mảng
        } catch (PDOException $e) {
            return []; // Nếu lỗi thì trả về mảng rỗng
        }
    }

    /**
     * Cập nhật thông tin người dùng.
     */
    public function updateUser($id, $name, $email, $phone, $password) {
        // Câu SQL cơ bản
        $sql = "UPDATE users SET name = :name, email = :email, phone = :phone";

        // Nếu có nhập mật khẩu mới thì mới thêm vào câu SQL
        if (!empty($password)) {
            $sql .= ", password = :password";
        }

        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        // Gán các giá trị
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);

        // Nếu có mật khẩu mới thì gán giá trị
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    /**
     * Xóa người dùng.
     */
    public function deleteUser($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
