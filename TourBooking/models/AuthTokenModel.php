<?php
// File: models/AuthTokenModel.php
class AuthTokenModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertToken($selector, $hashedValidator, $userId, $expires) {
        $sql = "INSERT INTO auth_tokens (selector, hashed_validator, user_id, expires) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$selector, $hashedValidator, $userId, $expires]);
    }

    public function findTokenBySelector($selector) {
        $sql = "SELECT * FROM auth_tokens WHERE selector = ? AND expires >= NOW() LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$selector]);
        return $stmt->fetch();
    }

    public function deleteTokensForUser($userId) {
        $sql = "DELETE FROM auth_tokens WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
    }
}