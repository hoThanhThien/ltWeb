<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Tour</title>
     <link href="/css/style-vanilla.css" rel="stylesheet" />
    <link href="/css/admin_style.css" rel="stylesheet" />
    <link href="/css/admin_dashboard.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="../img/logo.png">
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    
    <style>
        /* Tùy chỉnh chiều cao cho CKEditor */
        .ck-editor__editable_inline {
            min-height: 250px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Sửa thông tin Tour</h1>
        <form action="/admin/tours/edit?id=<?php echo $tour['id']; ?>" method="post" enctype="multipart/form-data" class="admin-form">
            <div class="form-group">
                <label>Tên Tour:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($tour['title']); ?>" required>
            </div>
            <div class="form-group">
                <label>Địa điểm:</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($tour['location']); ?>" required>
            </div>
            <div class="form-group">
                <label>Giá:</label>
                <input type="number" name="price" value="<?php echo htmlspecialchars($tour['price']); ?>" required>
            </div>
            <div class="form-group">
                <label>Số ngày:</label>
                <input type="number" name="duration_days" value="<?php echo htmlspecialchars($tour['duration_days']); ?>" required>
            </div>
            <div class="form-group">
                <label>Ngày bắt đầu:</label>
                <input type="date" name="start_date" value="<?php echo htmlspecialchars($tour['start_date']); ?>" required>
            </div>
            <div class="form-group">
                <label>Ngày kết thúc:</label>
                <input type="date" name="end_date" value="<?php echo htmlspecialchars($tour['end_date']); ?>" required>
            </div>
            <div class="form-group">
                <label>Số chỗ:</label>
                <input type="number" name="available_slots" value="<?php echo htmlspecialchars($tour['available_slots']); ?>" required>
            </div>
            <div class="form-group">
                <label>Số sao (1-5):</label>
                <input type="number" name="stars" min="1" max="5" value="<?php echo htmlspecialchars($tour['stars']); ?>" required>
            </div>
            <div class="form-group">
                <label>Loại tour:</label>
                <select name="loai_tour">
                    <option value="trongnuoc" <?php if ($tour['loai_tour'] == 'trongnuoc') echo 'selected'; ?>>Trong nước</option>
                    <option value="nuocngoai" <?php if ($tour['loai_tour'] == 'nuocngoai') echo 'selected'; ?>>Nước ngoài</option>
                </select>
            </div>
             <div class="form-group">
                <label>Chọn ảnh mới (để thay đổi):</label>
                <input type="file" name="image">
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($tour['image']); ?>">
            </div>
            <div class="form-group full-width">
                <label>Mô tả chi tiết:</label>
                <textarea name="description" id="description-editor"><?php echo htmlspecialchars($tour['description']); ?></textarea>
            </div>
            <div class="form-group full-width image-preview">
                <label>Ảnh hiện tại:</label>
                <img src="/public/img/<?php echo htmlspecialchars($tour['image']); ?>" alt="Ảnh tour hiện tại">
            </div>
            <div class="form-group full-width">
                <button type="submit">Cập nhật Tour</button>
                 <a href="/admin/tours" class="btn" style="background-color: #7f8c8d; margin-left: 10px;">Hủy</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create( document.querySelector( '#description-editor' ), {
                // Cấu hình CKEditor (tùy chọn)
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
            } )
            .then( editor => {
                console.log( 'Editor was initialized', editor );
            } )
            .catch( error => {
                console.error( error );
            } );
    </script>
</body>
</html>