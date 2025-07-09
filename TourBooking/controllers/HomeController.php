<?php

require_once __DIR__ . '/../models/Tour.php';

class HomeController {
    public function index() {
        $tourModel = new Tour();

        // Lấy tất cả các tham số lọc từ URL một cách an toàn
        $filters = [
            'filter' => $_GET['filter'] ?? null,
            'min_price' => $_GET['min_price'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null,
        ];

        // Loại bỏ các bộ lọc không có giá trị
        $activeFilters = array_filter($filters);
        
        // Chuẩn bị một mảng riêng để truy vấn CSDL
        $dbFilters = $activeFilters;

        // Dịch giá trị 'filter' sang 'loai_tour' để khớp với CSDL
        if (isset($dbFilters['filter'])) {
            if ($dbFilters['filter'] === 'domestic') {
                $dbFilters['loai_tour'] = 'trongnuoc';
            } elseif ($dbFilters['filter'] === 'international') {
                $dbFilters['loai_tour'] = 'nuocngoai';
            }
        }

        // Logic phân trang
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 8;
        $offset = ($page - 1) * $limit;

        // Lấy các tour đã lọc
        $tours = $tourModel->getFilteredTours($dbFilters, $limit, $offset);
        $this->applyDiscounts($tours);
        
        // Đếm tổng số tour đã lọc để phân trang chính xác
        $totalTours = $tourModel->countFilteredTours($dbFilters);
        $totalPages = max(1, ceil($totalTours / $limit));

        // Chuẩn bị các biến cho view
        $filter = $_GET['filter'] ?? 'random-tours';
        $randomTours = [];
        $domesticTours = [];
        $internationalTours = [];
        
        // Phân loại tour vào đúng mảng để view hiển thị
        if ($filter === 'domestic') {
            $domesticTours = $tours;
        } elseif ($filter === 'international') {
            $internationalTours = $tours;
        } else {
            // Xử lý các trường hợp còn lại: tour khuyến mãi, lọc theo giá/ngày, hoặc không lọc
            $randomTours = $tours;
        }

        require __DIR__ . '/../views/home.php';
    }

    /**
     * Tính toán và áp dụng giá khuyến mãi cho danh sách tour.
     */
    private function applyDiscounts(&$tours) {
        if (!is_array($tours)) return;

        foreach ($tours as &$tour) {
            if (!empty($tour['discount_percent']) && $tour['discount_percent'] > 0) {
                $tour['discount_price'] = $tour['price'] * (1 - $tour['discount_percent'] / 100);
            } else {
                $tour['discount_price'] = 0;
            }
        }
    }
}