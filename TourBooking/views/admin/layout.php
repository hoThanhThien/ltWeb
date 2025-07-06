<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Dashboard'; ?></title>
    <link rel="stylesheet" href="/public/css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .ck-editor__editable_inline { min-height: 250px; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>TOURADMIN</h3>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-section-title">Navigation</li>
                    <li class="nav-item">
                        <a href="/admin/dashboard" class="nav-link <?php echo ($current_page === 'dashboard') ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/tours" class="nav-link <?php echo ($current_page === 'tours') ? 'active' : ''; ?>">
                            <i class="fas fa-map-marked-alt"></i> <span>Quản lý Tour</span>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="/user_management" class="nav-link">
                            <i class="fas fa-users-cog"></i> <span>Quản lý User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/logout" class="nav-link">
                            <i class="fas fa-sign-out-alt"></i> <span>Đăng xuất</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="header-right">
                    <div class="user-profile">
                        <img src="https://i.pravatar.cc/40" alt="User Avatar">
                        <span><?php echo $_SESSION['username'] ?? 'Admin'; ?></span>
                        <i class="fas fa-caret-down"></i>
                    </div>
                </div>
            </header>

            <?php echo $content; ?>

        </main>
    </div>

    <script src="/public/js/admin_dashboard.js"></script>
    <?php if (isset($use_ckeditor) && $use_ckeditor): ?>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#description-editor' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
            })
            .catch( error => { console.error( error ); });
    </script>
    <?php endif; ?>
</body>
</html>