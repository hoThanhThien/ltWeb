<?php $pageTitle = "Quản lý Người dùng"; ?>
<div class="content-card">
    <div class="card-header">
        <h3>Danh sách Khách hàng</h3>
        <a href="/admin/users/add" class="btn btn-add">
            <i class="fas fa-plus"></i> Thêm User mới
        </a>
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
                    <?php

                    // Kiểm tra xem người dùng có vai trò là khách hàng (role == 1) hay không
                    //dự kiến có thêm nhân viên nếu thời gian nhóm tụi mình cònđài nha anh em
                    if (isset($user['role_id'])  == 3):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td>
                            <td class="actions">
                                <a href="/admin/users/edit?id=<?= $user['id'] ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Sửa</a>
                                <a href="/admin/users/delete?id=<?= $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');"><i class="fas fa-trash"></i> Xóa</a>
                            </td>
                        </tr>
                    <?php
                    endif; // Kết thúc điều kiện if
                    ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Không có khách hàng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>