<?php
// File: views/admin/tour_add.php
$pageTitle = "Thêm Tour mới";
$current_page = "tours";
$use_ckeditor = true; // Báo cho layout nạp thư viện CKEditor
?>

<div class="content-card">
    <div class="card-header">
        <h3>Thông tin Tour mới</h3>
    </div>
    <form action="/admin/tours/add" method="post" enctype="multipart/form-data" class="admin-form">
        <div class="form-group">
            <label>Tên Tour:</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Địa điểm:</label>
            <input type="text" name="location" required>
        </div>
        <div class="form-group">
            <label>Giá:</label>
            <input type="number" step="1000" name="price" required>
        </div>
        <div class="form-group">
            <label>Số ngày:</label>
            <input type="number" name="duration_days" required>
        </div>
        <div class="form-group">
            <label>Ngày bắt đầu:</label>
            <input type="date" name="start_date" required>
        </div>
        <div class="form-group">
            <label>Ngày kết thúc:</label>
            <input type="date" name="end_date" required>
        </div>
        <div class="form-group">
            <label>Số chỗ:</label>
            <input type="number" name="available_slots" required>
        </div>
        <div class="form-group">
            <label>Số sao (1-5):</label>
            <input type="number" name="stars" min="1" max="5" required>
        </div>
        <div class="form-group">
            <label>Loại tour:</label>
            <select name="loai_tour">
                <option value="trongnuoc">Trong nước</option>
                <option value="nuocngoai">Nước ngoài</option>
            </select>
        </div>
        <div class="form-group">
            <label>Ảnh đại diện:</label>
            <input type="file" name="image" required>
        </div>
        <div class="form-group full-width">
            <label>Mô tả chi tiết:</label>
            <textarea name="description" id="description-editor"></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-add"><i class="fas fa-check"></i> Thêm Tour</button>
            <a href="/admin/tours" class="btn btn-cancel">Hủy</a>
        </div>
    </form>
</div>