<?php
require_once __DIR__ . '/../guards/admin_guard.php';
require_once __DIR__ . '/../controllers/UserController.php';

$userController = new UserController();
$users = $userController->listUsers();
if (isset($_GET['delete'])) {
    $userController->deleteUser($_GET['delete']);
    header('Location: user_management.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Quản lý tài khoản</title>
    <link rel=\"stylesheet\" href=\"css/style.css\">
</head>
<body>
    <h1>Quản lý tài khoản</h1>
    <a href=\"dashboard.php\">Quay lại Dashboard</a>
    <table border=\"1\" cellpadding=\"10\">
        <tr>
            <th>ID</th><th>Tên</th><th>Email</th><th>Vai trò</th><th>Hành động</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                    <?php if ($user['role'] !== 'admin'): ?>
                        <a href=\"?delete=<?= $user['id'] ?>\" onclick=\"return confirm('Bạn có chắc chắn muốn xóa?');\">Xóa</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
