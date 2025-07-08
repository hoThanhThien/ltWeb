<?php
$pageTitle = "Dashboard";
// Giả sử bạn có biến $stats chứa các số liệu thống kê
$stats = [
    'profit' => '1,783',
    'orders' => '15,830',
    'avg_price' => '6,780',
    'sold' => '6,784'
];
?>
<div class="stats-cards">
    <div class="card card-profit">
        <h4>Total Profit</h4>
        <p>$<?= $stats['profit'] ?></p>
        <span class="stat-change">+11%</span>
        <i class="fas fa-dollar-sign card-icon"></i>
    </div>
    <div class="card card-orders">
        <h4>Total Orders</h4>
        <p><?= $stats['orders'] ?></p>
        <span class="stat-change">+12%</span>
        <i class="fas fa-shopping-cart card-icon"></i>
    </div>
    <div class="card card-price">
        <h4>Average Price</h4>
        <p>$<?= $stats['avg_price'] ?></p>
        <span class="stat-change">+52%</span>
        <i class="fas fa-tag card-icon"></i>
    </div>
    <div class="card card-sold">
        <h4>Product Sold</h4>
        <p><?= $stats['sold'] ?></p>
        <span class="stat-change">+52%</span>
        <i class="fas fa-box card-icon"></i>
    </div>
</div>

<div class="content-card">
    <h3>Báo cáo gần đây</h3>
    <p>Nội dung báo cáo hoặc biểu đồ sẽ được hiển thị ở đây...</p>
</div>