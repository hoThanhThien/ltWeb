<?php

require_once __DIR__ . '/../models/Tour.php';

class HomeController {
    public function index() {
        $tourModel = new Tour();

        $filter = $_GET['filter'] ?? null;

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 6;
        $offset = ($page - 1) * $limit;

        switch ($filter) {
            case 'domestic':
                $randomTours = [];
                $domesticTours = $tourModel->getTours('trongnuoc', $limit, $offset);
                $this->applyDiscounts($domesticTours);

                $internationalTours = [];
                $totalTours = $tourModel->countTours('trongnuoc');
                break;

            case 'international':
                $randomTours = [];
                $domesticTours = [];
                $internationalTours = $tourModel->getTours('nuocngoai', $limit, $offset);
                $this->applyDiscounts($internationalTours);

                $totalTours = $tourModel->countTours('nuocngoai');
                break;

            case 'random-tours':
            default:
                $randomTours = $tourModel->getTours(null, $limit, $offset);
                $this->applyDiscounts($randomTours);

                $domesticTours = [];
                $internationalTours = [];
                $totalTours = $tourModel->countTours(null);
                break;
        }

        $totalPages = max(1, ceil($totalTours / $limit));

        require __DIR__ . '/../views/home.php';
    }

    private function applyDiscounts(&$tours) {
        if (!is_array($tours)) return; // Đảm bảo không lỗi nếu null

        foreach ($tours as &$tour) {
            if (!empty($tour['discount_percent']) && $tour['discount_percent'] > 0) {
                $tour['discount_price'] = $tour['price'] * (1 - $tour['discount_percent'] / 100);
            } else {
                $tour['discount_price'] = 0;
            }
        }
    }
}
