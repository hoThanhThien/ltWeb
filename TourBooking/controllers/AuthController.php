<?php


require_once __DIR__ . '/../models/User.php';

require_once __DIR__ . '/../models/AuthTokenModel.php';
class AuthController {
    private $userModel;
    private $authTokenModel;

    // Hàm này sẽ tự động chạy để tạo ra các model.
    public function __construct() {
        $conn = connect_db();
        $this->userModel = new User($conn);
        $this->authTokenModel = new AuthTokenModel($conn);
    }



    // Trong file: /controllers/AuthController.php

public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if (isset($_SESSION['user_id'])) {
            $this->authTokenModel->deleteTokensForUser($_SESSION['user_id']);
        }
        if (isset($_COOKIE['remember_me'])) {
            unset($_COOKIE['remember_me']);
            setcookie('remember_me', '', time() - 3600, '/');
        }

        $_SESSION = array();
        session_destroy();
        header('Location: /home');
        exit();
    }

public function showRegisterForm() {
    require_once __DIR__ . '/../views/register_view.php';
}




public function handleLogin() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Nếu người dùng đã đăng nhập rồi thì chuyển hướng họ đi ngay lập tức
    if (isset($_SESSION['user_id'])) {
        // Nếu là admin, về trang admin
        if ($_SESSION['user_role_id'] == 1) {
            header('Location: /admin/dashboard');
            exit();
        } 
        // Nếu là user thường, về trang chủ
        else {
            header('Location: /home');
            exit();
        }
    }
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
        $user = $this->userModel->findUserByEmail($email);
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
             //  Xử lý "Ghi nhớ đăng nhập"
            if (isset($_POST['remember_me'])) {
            $this->createRememberMeToken($user['id']);
        }
            if ($user['role_id'] == 1) {
        // Nếu là Admin: Yêu cầu cửa sổ  chuyển hướng đến trang admin
        echo "<script>window.parent.location.href = '/admin/dashboard';</script>";
    } else {
        // Nếu là User thường: Yêu cầu cửa sổ tải lại trang để cập nhật
        echo "<script>window.parent.location.reload();</script>";
    }
    exit();
        } else {
            // Nếu email hoặc mật khẩu không đúng
            $error_message = 'Email hoặc mật khẩu không chính xác.';
        }
    }
    
    // Nếu có bất kỳ lỗi nào, hiển thị lại form đăng nhập với thông báo lỗi
    require_once __DIR__ . '/../views/login_view.php';
}

// Tạo token "Ghi nhớ đăng nhập" và lưu vào cookie
private function createRememberMeToken($userId) {
    // 1. Tạo một chuỗi định danh (selector) ngẫu nhiên, an toàn dài 32 ký tự.
    //    Selector này được dùng để tìm token trong database một cách công khai.
    $selector = bin2hex(random_bytes(16));

    // 2. Tạo một chuỗi xác thực (validator) ngẫu nhiên, an toàn dài 64 ký tự.
    //    Validator là phần "mật khẩu" bí mật của token.
    $validator = bin2hex(random_bytes(32));

    // 3. Tạo một đối tượng ngày tháng để xác định thời điểm token hết hạn (30 ngày sau).
    $expires = new DateTime('+30 days');
    
    // 4. Gửi một cookie tên là 'remember_me' đến trình duyệt của người dùng.
    //    - Giá trị cookie chứa cả selector và validator GỐC, ngăn cách bởi dấu ':'.
    //    - $expires->getTimestamp(): Thời gian cookie hết hạn.
    //    - '/': Cookie có hiệu lực trên toàn bộ website.
    //    - true (httpOnly): Ngăn JavaScript truy cập vào cookie này, tăng cường bảo mật.
    setcookie('remember_me', $selector . ':' . $validator, $expires->getTimestamp(), '/', '', false, true);
    
    // 5. Băm (hash) chuỗi validator bằng thuật toán SHA256.
    $hashedValidator = hash('sha256', $validator);
    
    // 6. Gọi đến model để xóa tất cả các token "ghi nhớ" cũ của người dùng này.
    //   mỗi người dùng chỉ có một token hợp lệ tại một thời điểm.
    $this->authTokenModel->deleteTokensForUser($userId);
    
    // 7. Gọi đến model để chèn token mới vào database.
    //    - Lưu selector (công khai) và hashedValidator (đã mã hóa).
    //    - Gắn với userId và lưu ngày hết hạn đã được định dạng.
    $this->authTokenModel->insertToken($selector, $hashedValidator, $userId, $expires->format('Y-m-d H:i:s'));
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
