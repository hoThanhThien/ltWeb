<?php
require_once __DIR__ . '/../models/User.php';

class UserManagementController {
    public function index() {
        session_start();

        // Kiểm tra đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        // Chỉ cho phép admin truy cập
        if ($_SESSION['user_role'] !== 'admin') {
            echo '<h2>Bạn không có quyền truy cập trang này!</h2>';
            echo '<a href="/dashboard">Về Dashboard</a>';
            exit();
        }

        $userModel = new User();
        $users = $userModel->getAllUsers();

        require __DIR__ . '/../views/user_management.php';
    }
}
?>
