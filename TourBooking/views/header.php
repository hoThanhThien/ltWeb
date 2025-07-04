
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/home.css" />
    <link rel="stylesheet" href="../css/chitiethome.css"/>
    <link rel="stylesheet" href="../css/pagination.css" />
    <title>ĐẶT TOUR TRỌN GÓI</title>
    <link rel="icon" type="image/png" href="../img/logo.png">

    <script src="/public/js/main.js"></script>
</head>

<body>
    <script src="../public/js/main.js"></script>
    <div class="Header">
        <div class="logo"><a href="../home"><img src="../img/logo.png" alt="Logo"></a></div>
         <div class="homeHeard">
            <h2>CÙNG KHÁ PHÁ TRẢI NGHIỆM</h2>
        </div>
        <div class="index_header">
            <a href="?filter=random-tours">Hot</a>
            <a href="">Tour</a>
            <a href="">Khuyến mãi</a>
            <a href="">Liên hệ</a>
            
   
        <?php if (isset($_SESSION['user_id'])): ?>
            <img src="https://travel.com.vn/_next/static/media/user.6313d4b4.png"/><span>Chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="/logout">Đăng xuất</a>
        <?php else: ?>
            <a href="#" id="open-auth-modal">Đăng nhập / Đăng ký</a>
        <?php endif; ?>

            
        </div>
    </div>

<div id="iframe-modal-overlay"></div>
<div id="iframe-modal">
    <button id="close-iframe-modal">&times;</button>
    <iframe id="auth-iframe" src="" name="auth-iframe" frameborder="0"></iframe>
</div>

</body>
</html>