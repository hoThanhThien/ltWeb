<?php
// File: views/admin/tours_list.php
$pageTitle = "Quản lý Tour";
$current_page = "tours";
?>

<div class="content-card">
    <div class="card-header">
        <h3>Danh sách Tour du lịch</h3>
        <a href="/admin/tours/add" class="btn btn-add" style="background-color: #00c853; color: white; text-decoration: none;">
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
            <?php foreach ($tours as $tour): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tour['id']); ?></td>
                    <td><img src="/public/img/<?php echo htmlspecialchars($tour['image']); ?>" alt="Ảnh tour" style="width: 100px; border-radius: 5px;"></td>
                    <td><?php echo htmlspecialchars($tour['title']); ?></td>
                    <td><?php echo htmlspecialchars($tour['location']); ?></td>
                    <td><?php echo number_format($tour['price']); ?> VNĐ</td>
                    <td><?php echo date("d/m/Y", strtotime($tour['start_date'])); ?></td>
                    <td class="actions">
                        <a href="/admin/tours/edit?id=<?php echo $tour['id']; ?>" class="btn">Sửa</a>
                        <a href="/admin/tours/delete?id=<?php echo $tour['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tour này?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>