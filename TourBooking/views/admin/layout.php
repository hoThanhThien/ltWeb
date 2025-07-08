<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Dashboard - Admin</title>

    <link href="../css/style-vanilla.css" rel="stylesheet" />
    
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
</head>
<body> <nav class="topnav">
        <a class="navbar-brand" href="/admin">Admin Panel</a>
        <button class="sidebar-toggle" id="sidebarToggle">
            <svg class="icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/></svg>
        </button>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sidenav">
                <div class="sidenav-menu">
                    <div class="nav-items">
                        <div class="sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="/admin">Dashboard</a>
                        
                        <div class="sidenav-menu-heading">Quản lý</div>

                        <a class="nav-link collapsible-toggle" href="#" data-target="#collapseTour">
                            Quản lý Tour
                            <div class="sidenav-collapse-arrow">▼</div>
                        </a>
                        <div class="collapse-menu" id="collapseTour">
                            <a class="nav-link" href="../admin/tours">Danh sách Tour</a>
                            <a class="nav-link" href="../admin/add-tour">Thêm Tour</a>
                        </div>

                        <a class="nav-link collapsible-toggle" href="#" data-target="#collapseUser">
                            Quản lý User
                            <div class="sidenav-collapse-arrow">▼</div>
                        </a>
                        <div class="collapse-menu" id="collapseUser">
                            <a class="nav-link" href="../admin/users">Danh sách User</a>
                        </div>
                    </div>
                </div>
                <div class="sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <?php echo $content; ?>
            </main>
            <footer class="page-footer">
                <div class="footer-content">
                    <div class="text-muted">Copyright &copy; Your Website 2024</div>
                </div>
            </footer>
        </div>
    </div>

    <script src="../js/script-vanilla.js"></script>
</body>
</html>