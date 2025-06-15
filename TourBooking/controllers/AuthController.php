<?php
// File: controllers/AuthController.php

require_once __DIR__ . '/../models/User.php';

class AuthController {

    public function showLoginForm() {
        require_once __DIR__ . '/../views/login_view.php';
    }

    public function handleLogin() {
    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email) || empty($password)) {
            $error_message = 'Vui lòng nhập đầy đủ email và mật khẩu!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Định dạng email không hợp lệ!';
        } else {
            $userModel = new User();
            $user = $userModel->findUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role_id'] = $user['role_id'];

                header('Location: /dashboard');
                exit();
            } else {
                $error_message = 'Email hoặc mật khẩu không chính xác.';
            }
        }
    }

    require_once __DIR__ . '/../views/login_view.php';
}


    public function logout() {
        session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit();
    }

    public function showRegisterForm() {
        require_once __DIR__ . '/../views/register_view.php';
    }

    public function handleRegistration() {
    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
            $error_message = 'Vui lòng nhập đầy đủ thông tin!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Định dạng email không hợp lệ!';
        } elseif (strlen($password) < 6) {
            $error_message = 'Mật khẩu phải có ít nhất 6 ký tự!';
        } elseif ($password !== $confirm_password) {
            $error_message = 'Mật khẩu không khớp!';
        } else {
            $userModel = new User();

            if ($userModel->findUserByEmail($email)) {
                $error_message = 'Email đã được sử dụng!';
            } elseif ($userModel->findUserByPhone($phone)) { // Kiểm tra trùng phone
                $error_message = 'Số điện thoại đã được sử dụng!';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                if ($userModel->createUser($name, $email, $hashed_password, 2, $phone)) { // Truyền thêm phone
                    header('Location: /login');
                    exit();
                } else {
                    $error_message = 'Đăng ký không thành công, vui lòng thử lại!';
                }
            }
        }
    }

    require_once __DIR__ . '/../views/register_view.php';
}

}
?>
