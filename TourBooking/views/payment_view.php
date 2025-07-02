<?php
include_once 'header.php';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanh toán đơn hàng - TourBooking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .payment-container {
            max-width: 900px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .order-summary {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
        }
        .paid-success-box { display: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="payment-container">
        <div id="checkout_box">
            <h2 class="mb-1"><span > <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-check-circle text-success" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"></path>
            <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"></path>
        </svg></span> Đặt hàng thành công</h2>
            <p class="text-muted">Mã đơn hàng #DH<?= htmlspecialchars($bookingDetails['id']); ?></p>
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <p class="text-center text-muted">Hướng dẫn thanh toán qua chuyển khoản ngân hàng</p>
                    <div class="row border rounded p-3">
                        <div class="col-md-6 text-center border-end">
                            <h6>Cách 1: Mở app ngân hàng và quét mã QR</h6>
    <!----- Mã QR sẽ tự động cập nhật số tiền và nội dung chuyển khoản ---->
                            <img src="https://qr.sepay.vn/img?bank=<?= htmlspecialchars(PAYMENT_BANK_CODE); ?>&acc=<?= htmlspecialchars(PAYMENT_ACCOUNT_NUMBER); ?>&template=compact2&amount=<?= floatval($bookingDetails['total_price']); ?>&des=DH<?= htmlspecialchars($bookingDetails['id']); ?>" class="img-fluid my-2" alt="Mã QR thanh toán cho đơn hàng #DH<?= htmlspecialchars($bookingDetails['id']); ?>">
                            <div id="payment-status" class="mt-2">
                                <span>Trạng thái: </span><span class="fw-bold text-warning">Chờ thanh toán...</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-center">Cách 2: Chuyển khoản thủ công</h6>

                        
                            <div class="text-center my-2"><img src="https://upload.wikimedia.org/wikipedia/commons/2/25/Logo_MB_new.png" style="height:30px"></div>
                            <table class="table table-sm">
    <tr>
        <td>Chủ tài khoản:</td>
        <td class="text-end"><b><?= htmlspecialchars(PAYMENT_ACCOUNT_NAME); ?></b></td>
    </tr>
    <tr>
        <td>Số TK:</td>
        <td class="text-end"><b><?= htmlspecialchars(PAYMENT_ACCOUNT_NUMBER); ?></b></td>
    </tr>
    <tr>
        <td>Số tiền:</td>
        <td class="text-end text-danger"><b><?= number_format($bookingDetails['total_price']); ?>đ</b></td>
    </tr>
    <tr>
        <td>Nội dung:</td>
        <td class="text-end"><b>DH<?= htmlspecialchars($bookingDetails['id']); ?></b></td>
    </tr>
</table>
                            <p class="small bg-light p-2 rounded"><b>Lưu ý:</b> Vui lòng giữ nguyên nội dung chuyển khoản để hệ thống tự động xác nhận.</p>
                        </div>
                    </div>
                     <a href="/home" class="btn btn-link mt-3">&laquo; Quay lại</a>
                </div>

                <div class="col-md-4">
                    <div class="order-summary">
                        <h5 class="mb-3">Thông tin đơn hàng</h5>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><?= htmlspecialchars($bookingDetails['title']); ?></td>
                                    <td class="text-end"><?= number_format($bookingDetails['total_price']); ?>đ</td>
                                </tr>
                                 <tr>
                                    <td>Khuyến mãi</td>
                                    <td class="text-end">-</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="fw-bold">Tổng</td>
                                    <td class="text-end fw-bold"><?= number_format($bookingDetails['total_price']); ?>đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="success_pay_box" class="paid-success-box text-center p-5">
            <h2 class="text-success mb-3"><span class="check-icon">✓</span> Thanh toán thành công</h2>
            <p>Chúng tôi đã nhận được thanh toán cho đơn hàng #DH<?= htmlspecialchars($bookingDetails['id']); ?>. <br>Chúc bạn có một chuyến đi vui vẻ!</p>
            <a href="/home" class="btn btn-primary mt-3">Về trang chủ</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    var pay_status = 'Unpaid';
    var booking_id = <?= $bookingDetails['id']; ?>;

    function check_payment_status() {
        if (pay_status === 'Unpaid') {
            $.ajax({
                type: "POST",
                data: { booking_id: booking_id },
                url: "/payment/check_status", // Route đã được cấu hình trong index.php
                dataType: "json",
                success: function(data) {
                    if (data.payment_status === "Paid") {
                        pay_status = 'Paid';
                        $("#checkout_box").fadeOut(function() {
                            $("#success_pay_box").fadeIn();
                        });
                    }
                },
                error: function() {
                    console.log("Lỗi khi kiểm tra trạng thái thanh toán.");
                }
            });
        }
    }
    // Bắt đầu kiểm tra trạng thái sau 2 giây, và lặp lại mỗi 3 giây
    setTimeout(function() {
        check_payment_status(); // Check lần đầu
        setInterval(check_payment_status, 3000); // Check lặp lại
    }, 2000);
</script>
</body>
   
</html>
<?php include_once 'footer.php'; ?>