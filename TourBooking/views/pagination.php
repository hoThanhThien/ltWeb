<?php
// File này sử dụng các biến $totalPages, $page, và $filter

$range = 2; // Số lượng trang hiển thị ở bên trái và bên phải của trang hiện tại

// Bắt đầu hiển thị phân trang
// Dùng isset() để kiểm tra biến có tồn tại không, tránh lỗi
if (isset($totalPages) && $totalPages > 1) {
    
    // Lấy filter hiện tại (chỉ cần lấy một lần)
    $currentFilter = isset($filter) ? htmlspecialchars($filter) : '';
    
    echo '<div class="pagination">';

    // --- THÊM NÚT "TRƯỚC" (PREVIOUS) ---
    // Chỉ hiển thị link nếu trang hiện tại lớn hơn 1
    if (isset($page) && $page > 1) {
        echo '<a href="?filter=' . $currentFilter . '&page=' . ($page - 1) . '" class="nav-btn">&laquo; Previous</a>';
    } else {
        // Nếu đang ở trang 1, làm mờ nút đi
        echo '<span class="disabled nav-btn">&laquo; Previous</span>';
    }

    // --- VÒNG LẶP HIỂN THỊ SỐ TRANG (giữ nguyên) ---
    $last_page_printed = 0;
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == 1 || $i == $totalPages || ($i >= $page - $range && $i <= $page + $range)) {
            if ($last_page_printed > 0 && $i > $last_page_printed + 1) {
                echo '<span class="ellipsis">...</span>';
            }
            $isActive = (isset($page) && $i == $page) ? 'active' : '';
            echo '<a href="?filter=' . $currentFilter . '&page=' . $i . '" class="' . $isActive . '">' . $i . '</a>';
            $last_page_printed = $i;
        }
    }

    // --- THÊM NÚT "TIẾP" (NEXT) ---
    // Chỉ hiển thị link nếu trang hiện tại nhỏ hơn tổng số trang
    if (isset($page) && $page < $totalPages) {
        echo '<a href="?filter=' . $currentFilter . '&page=' . ($page + 1) . '" class="nav-btn">Next &raquo;</a>';
    } else {
        // Nếu đang ở trang cuối, làm mờ nút đi
        echo '<span class="disabled nav-btn">Next &raquo;</span>';
    }

    echo '</div>';
}
?>