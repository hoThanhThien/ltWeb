
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
        <div class="logo"><a href="../home"><img src="../img/logo.png" alt="Logo"/></a></div>
        
            
        <div class="homeHeard" >
           
   <marquee width="100%"> <h5><img src="https://cdn-icons-png.flaticon.com/128/5373/5373330.png" width="20px" alt="Logo"/>&nbsp;&nbsp;"Vùng trời quê hương nào cũng là bầu trời Tổ quốc"</h5></marquee>
        </div>
        <div class="index_header">
            <a href="?filter=random-tours">Hot</a>
            
            <a href="/?filter=discount">Khuyến mãi</a>
            <a href="mailto:hothanhthien119@gmail.com">Liên hệ</a>
            
   
        <?php if (isset($_SESSION['user_id'])): ?>
    <a href="/my-bookings" class="user-profile-link">
        <img src="https://travel.com.vn/_next/static/media/user.6313d4b4.png"/>
        <span>Chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
    </a>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>
</html>