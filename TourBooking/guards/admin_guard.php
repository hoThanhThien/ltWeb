<?php
require_once 'auth_guard.php';

if ($_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    die('<h1>403 Forbidden</h1><p>Bạn không có quyền truy cập trang này.</p>');
}
?>
