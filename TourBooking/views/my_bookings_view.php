<?php include_once __DIR__ . '/header.php'; ?>

<style>
    body { background-color: #f4f7f6; }
    .bookings-container { max-width: 1100px; margin: 50px auto; }
    .bookings-header {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0e0e0;
    }
    .bookings-header h1 {
        font-size: 28px;
        color: #333;
        font-weight: 600;
    }
    .bookings-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden; /* Để bo góc table hoạt động */
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .bookings-table th, .bookings-table td {
        padding: 18px 20px;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
    }
    .bookings-table th {
        background-color: #fafafa;
        font-weight: 600;
        color: #555;
        font-size: 14px;
        text-transform: uppercase;
    }
    .bookings-table td { color: #666; }
    .status-badge {
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        color: white;
        text-transform: capitalize;
    }
    .status-confirmed { background-color: #28a745; }
    .status-pending { background-color: #ffc107; color: #333; }
</style>

<div class="bookings-container">
    <div class="bookings-header">
        <h1>Lịch sử đặt hàng</h1>
    </div>

    <table class="bookings-table">
        <thead>
            <tr>
                <th>Mã Đơn</th>
                <th>Tên Tour</th>
                <th>Ngày Đặt</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($booking['booking_id']) ?></td>
                        <td><?= htmlspecialchars($booking['tour_name']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?></td>
                        <td><?= number_format($booking['total_price'], 0, ',', '.') ?> VNĐ</td>
                        <td>
                            <span class="status-badge status-<?= strtolower(htmlspecialchars($booking['status'])) ?>">
                                <?= ucfirst(htmlspecialchars($booking['status'])) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">Bạn chưa có đơn hàng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php include_once __DIR__ . '/pagination.php'; ?>
</div>
<footer>
<?php include_once __DIR__ . '/footer.php'; ?></footer>