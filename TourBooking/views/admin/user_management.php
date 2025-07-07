<?php
// File: views/admin/user_management.php
$pageTitle = "Quản lý Người dùng";
$current_page = "users";
?>

<div class="content-card">
    <div class="card-header">
        <h3>Danh sách Người dùng</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    <td><?php echo ($user['role_id'] == 1) ? 'Admin' : 'Customer'; ?></td>
                    <td>
                        <?php if ($user['role_id'] != 1): // Không cho xóa admin ?>
                            <a href="/admin/users/delete?id=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">Xóa</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>