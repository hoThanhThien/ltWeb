<?php
$pageTitle = "Chỉnh Sửa Người Dùng";
$current_page = 'users';
?>
<link href="/css/style-vanilla.css" rel="stylesheet" />
    <link href="/css/admin_style.css" rel="stylesheet" />
    <link href="/css/admin_dashboard.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="../img/logo.png">
<div class="content-card">
    <div class="card-header">
        <h3>Chỉnh Sửa Người Dùng</h3>
        <a href="/admin/users" class="btn btn-cancel">Quay lại Danh Sách</a>
    </div>
    
    <?php if (!empty($user)): ?>
        <form action="/admin/users/edit?id=<?= htmlspecialchars($user['id']) ?>" method="post" class="admin-form">
            
            <div class="form-group">
                <label for="name">Tên:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>
       

            <div class="form-actions">
                <button type="submit">Lưu Thay Đổi</button>
            </div>
        </form>
    <?php else: ?>
        <p>Không tìm thấy người dùng này.</p>
    <?php endif; ?>
</div>