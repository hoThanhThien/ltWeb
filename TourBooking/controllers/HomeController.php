<?php
require_once __DIR__ . '/../models/Tour.php';

class HomeController {
    public function index() {
        $tourModel = new Tour();

        // Xử lý filter
        $filter = isset($_GET['filter']) ? $_GET['filter'] : null;

        // Xử lý phân trang
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit =2;
        $offset = ($page - 1) * $limit;

        // Đếm tổng tour theo filter
        $totalTours = $tourModel->countTours($filter);
        $totalPages = ceil($totalTours / $limit);

        // Lấy tour cho trang hiện tại
        $tours = $tourModel->getTours($filter, $limit, $offset);

        // Lấy thêm data nếu cần (ví dụ: tour ngẫu nhiên, trong nước, nước ngoài cho trang "Tất cả")
        $randomTours = $tourModel->getTours(null, 10);
        $domesticTours = $tourModel->getTours('trongnuoc', 15);
        $internationalTours = $tourModel->getTours('nuocngoai', 10);

        // Truyền tất cả biến qua view
        require __DIR__ . '/../views/home.php';
    }
}
