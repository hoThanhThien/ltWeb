<?php $pageTitle = "Quản lý Người dùng"; ?>
<div class="content-card">
    <div class="card-header">
        <h3>Danh sách Khách hàng</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td>
                        <td class="actions">
                            <a href="/admin/users/edit?id=<?= htmlspecialchars($user['id']) ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Sửa</a>
                            <a href="/admin/users/delete?id=<?= htmlspecialchars($user['id']) ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');"><i class="fas fa-trash"></i> Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Không có khách hàng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php include 'pagination.php'; ?>
</div>