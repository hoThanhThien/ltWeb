<link href="../css/pagination.css" rel="stylesheet" />
<div class="stats-cards">
    <div class="card card-profit">
        <h4>Total Profit</h4>
        <p><?= htmlspecialchars($stats['profit'] ?? 0) ?> VND</p>
        <span class="stat-change">+11%</span>
        <i class="fas fa-dollar-sign card-icon"></i>
    </div>
    <div class="card card-orders">
        <h4>Total Orders</h4>
        <p><?= htmlspecialchars($stats['orders'] ?? 0) ?></p>
        <span class="stat-change">+12%</span>
        <i class="fas fa-shopping-cart card-icon"></i>
    </div>
    <div class="card card-price">
        <h4>Average Price</h4>
        <p><?= htmlspecialchars($stats['avg_price'] ?? 0) ?> VND</p>
        <span class="stat-change">+52%</span>
        <i class="fas fa-tag card-icon"></i>
    </div>
    <div class="card card-sold">
        <h4>Total Tours</h4>
        <p><?= htmlspecialchars($stats['tours'] ?? 0) ?></p>
        <span class="stat-change">+52%</span>
        <i class="fas fa-box card-icon"></i>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>Tất cả đơn hàng</h3>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Khách Hàng</th>
                    <th>Tên Tour</th>
                    <th>Ngày Đặt</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentBookings)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Chưa có đơn hàng nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentBookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['booking_id']) ?></td>
                            <td><?= htmlspecialchars($booking['user_name']) ?></td>
                            <td><?= htmlspecialchars($booking['tour_name']) ?></td>
                            <td><?= date('d/m/Y', strtotime($booking['created_at'])) ?></td>
                            <td><?= number_format($booking['total_price'], 0, ',', '.') ?> VNĐ</td>
                            <td>
                                <?php
                                    $currentStatus = strtolower($booking['status']);
                                    // Xác định trạng thái mới khi click
                                    $newStatus = ($currentStatus == 'pending') ? 'confirmed' : 'pending';
                                    
                                    // Lấy đúng class CSS cho badge
                                    $badgeClass = ($currentStatus == 'pending') ? 'status-pending' : 'status-confirmed';
                                ?>
                                <a href="/admin/bookings/update_status?id=<?= $booking['booking_id'] ?>&status=<?= $newStatus ?>" class="status-badge <?= $badgeClass ?>">
                                    <?= ucfirst($currentStatus) ?>
                                </a>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php include 'pagination.php'; ?>
    </div>
</div>