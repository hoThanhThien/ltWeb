<?php

require_once __DIR__ . '/../models/Tour.php';

class HomeController {
    public function index() {
        $tourModel = new Tour();

        // Xử lý filter
        $filter = $_GET['filter'] ?? null;

        // Xử lý phân trang
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 8;
        $offset = ($page - 1) * $limit;

        // Lấy dữ liệu theo filter
        switch ($filter) {
            case 'domestic':
                $randomTours = [];
                $domesticTours = $tourModel->getTours('trongnuoc', $limit, $offset);
                $internationalTours = [];
                $totalTours = $tourModel->countTours('trongnuoc');
                break;
            case 'international':
                $randomTours = [];
                $domesticTours = [];
                $internationalTours = $tourModel->getTours('nuocngoai', $limit, $offset);
                $totalTours = $tourModel->countTours('nuocngoai');
                break;
            case 'random-tours':
            default:
                $randomTours = $tourModel->getTours(null, $limit, $offset);
                $domesticTours = [];
                $internationalTours = [];
                $totalTours = $tourModel->countTours(null);
                break;
        }
        $totalPages = max(1, ceil($totalTours / $limit));

        // Truyền biến sang view
        require __DIR__ . '/../views/home.php';
    }
}
?>