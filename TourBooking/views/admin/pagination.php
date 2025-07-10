<?php
// Chỉ hiển thị khi có nhiều hơn 1 trang
if (isset($totalPages) && $totalPages > 1):
?>
<div class="pagination">
    <?php
    $path = strtok($_SERVER['REQUEST_URI'], '?');
    $queryParams = $_GET; // Lấy tất cả các tham số hiện tại (filter, sort,...)

    // --- Nút "Previous" ---
    if ($page > 1) {
        $queryParams['page'] = $page - 1;
        echo '<a href="' . htmlspecialchars($path . '?' . http_build_query($queryParams)) . '" class="page-link">&laquo; Previous</a>';
    } else {
        echo '<span class="page-link disabled">&laquo; Previous</span>';
    }

    // --- VÒNG LẶP HIỂN THỊ SỐ TRANG ---
    $range = 1; 
    $last_page_printed = 0;

    for ($i = 1; $i <= $totalPages; $i++) {
        // Điều kiện để hiển thị số trang hoặc dấu "..."
        if ($i == 1 || $i == $totalPages || ($i >= $page - $range && $i <= $page + $range)) {
            
            // In dấu "..." nếu có khoảng trống
            if ($last_page_printed > 0 && $i > $last_page_printed + 1) {
                echo '<span class="page-link ellipsis">...</span>';
            }
            
            // **CẢI TIẾN NHỎ TẠI ĐÂY**
            // Nếu là trang hiện tại, dùng <span>. Nếu không, dùng <a>
            if ($i == $page) {
                echo '<span class="page-link active">' . $i . '</span>';
            } else {
                $queryParams['page'] = $i;
                echo '<a href="' . htmlspecialchars($path . '?' . http_build_query($queryParams)) . '" class="page-link">' . $i . '</a>';
            }
            
            $last_page_printed = $i;
        }
    }

    // --- Nút "Next" ---
    if ($page < $totalPages) {
        $queryParams['page'] = $page + 1;
        echo '<a href="' . htmlspecialchars($path . '?' . http_build_query($queryParams)) . '" class="page-link">Next &raquo;</a>';
    } else {
        echo '<span class="page-link disabled">Next &raquo;</span>';
    }
    ?>
</div>
<?php endif; ?>