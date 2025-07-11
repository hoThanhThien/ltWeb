<?php $pageTitle = "Quản lý Tour"; ?>
<div class="content-card">
    <div class="card-header">
        <h3>Danh sách Tour du lịch</h3>
        <a href="/admin/tours/add" class="btn btn-add">
            <i class="fas fa-plus"></i> Thêm Tour mới
        </a>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tên Tour</th>
                <th>Địa điểm</th>
                <th>Giá</th>
                <th>Ngày BĐ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tours)): ?>
                <?php foreach ($tours as $tour): ?>
                    <tr>
                        <td><?= htmlspecialchars($tour['id']) ?></td>
                        <td>
                       
    <?php
    // ko dùng hàm getImageSrc
        // Lấy giá trị ảnh từ database
        $image_source = $tour['image'];
        $image_url = '';

        // Kiểm tra xem giá trị có phải là một URL đầy đủ không
        if (filter_var($image_source, FILTER_VALIDATE_URL)) {
            // Nếu đúng là URL, dùng trực tiếp
            $image_url = htmlspecialchars($image_source);
        } else {
            // Nếu chỉ là tên file, thêm đường dẫn /img/ vào trước
            $image_url = "../img/" . htmlspecialchars($image_source);
        }
    ?>
    <img src="<?= $image_url ?>" alt="Ảnh tour" class="tour-image-thumb">
</td>
                        <td><?= htmlspecialchars($tour['title']) ?></td>
                        <td><?= htmlspecialchars($tour['location']) ?></td>
                        <td><?= number_format($tour['price']) ?> VNĐ</td>
                        <td><?= date('d/m/Y', strtotime($tour['start_date'])) ?></td>
                        <td class="actions">
                            <a href="/admin/tours/edit?id=<?= $tour['id'] ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Sửa</a>
                            <a href="/admin/tours/delete?id=<?= $tour['id'] ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tour này?');"><i class="fas fa-trash"></i> Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Chưa có tour nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
       
    </table>
    <?php include 'pagination.php'; ?>
</div>