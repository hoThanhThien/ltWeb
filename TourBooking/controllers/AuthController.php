<?php
// File: controllers/AuthController.php

require_once __DIR__ . '/../models/User.php';

class AuthController {

    public function showLoginForm() {
        require_once __DIR__ . '/../views/login_view.php';
    }



    public function logout() {
        session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: /home');
        exit();
    }

    public function showRegisterForm() {
        require_once __DIR__ . '/../views/home.php';
    }

    

    // Trong file: TourBooking/controllers/AuthController.php

public function handleLogin() {
    // Nếu không phải là yêu cầu POST, chỉ hiển thị form và dừng lại
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        require_once __DIR__ . '/../views/login_view.php';
        return;
    }

    //--- Từ đây trở đi, chúng ta chỉ xử lý yêu cầu POST ---
    $error_message = '';
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Kiểm tra dữ liệu đầu vào
    if (empty($email) || empty($password)) {
        $error_message = 'Vui lòng nhập đầy đủ email và mật khẩu!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Định dạng email không hợp lệ!';
    } else {
        // Tìm user trong database
        $userModel = new User();
        $user = $userModel->findUserByEmail($email);

        // Nếu tìm thấy user và mật khẩu khớp
        if ($user && password_verify($password, $user['password'])) {
            // Bắt đầu session nếu chưa có
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            // Lưu thông tin vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role_id'] = $user['role_id'];

            // DÒNG QUAN TRỌNG NHẤT LÀ ĐÂY
            // Ra lệnh cho trang cha (bên ngoài iframe) tải lại
            echo "<script>parent.location.reload();</script>";
            exit(); // Dừng thực thi ngay lập tức

        } else {
            // Nếu email hoặc mật khẩu không đúng
            $error_message = 'Email hoặc mật khẩu không chính xác.';
        }
    }
    
    // Nếu có bất kỳ lỗi nào, hiển thị lại form đăng nhập với thông báo lỗi
    require_once __DIR__ . '/../views/login_view.php';
}

    public function handleRegistration() {
        // Nếu không phải là yêu cầu POST, chỉ hiển thị form và dừng lại
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once __DIR__ . '/../views/register_view.php';
            return;
        }

        //--- Từ đây trở đi, chúng ta chỉ xử lý yêu cầu POST ---
        $error_message = '';
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        // 1. Kiểm tra validation
        if (empty($name) || empty($email) || empty($phone) || empty($password)) {
            $error_message = 'Vui lòng điền đầy đủ thông tin!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Định dạng email không hợp lệ!';
        } elseif (strlen($password) < 6) {
            $error_message = 'Mật khẩu phải có ít nhất 6 ký tự!';
        } elseif ($password !== $confirm_password) {
            $error_message = 'Mật khẩu xác nhận không khớp!';
        } else {
            // 2. Validation thành công, kiểm tra database
            $userModel = new User();

            if ($userModel->findUserByEmail($email)) {
                $error_message = 'Email đã được sử dụng!';
            } elseif ($userModel->findUserByPhone($phone)) {
                $error_message = 'Số điện thoại đã được sử dụng!';
            } else {
                // 3. Mọi thứ hợp lệ, tiến hành tạo user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                if ($userModel->createUser($name, $email, $hashed_password, 2, $phone)) {
                    // Đăng ký thành công -> Gửi script về cho iframe và KẾT THÚC
                    echo "<script>
                            alert('Đăng ký thành công! Vui lòng đăng nhập.');
                            parent.document.getElementById('auth-iframe').src = '/login';
                          </script>";
                    exit(); // Rất quan trọng: Dừng thực thi ngay tại đây
                } else {
                    $error_message = 'Đăng ký không thành công do lỗi hệ thống, vui lòng thử lại!';
                }
            }
        }
        
        // Nếu code chạy đến đây, có nghĩa là đã có lỗi xảy ra.
        // Hiển thị lại form đăng ký với thông báo lỗi.
        require_once __DIR__ . '/../views/register_view.php';
    }
}
?>
