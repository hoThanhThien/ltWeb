<?php
// Chỉ hiển thị khi có nhiều hơn 1 trang
if (isset($totalPages) && $totalPages > 1):
?>
<div class="pagination">
    <?php
    $path = strtok($_SERVER['REQUEST_URI'], '?');
    $queryParams = $_GET;

    // Nút "Previous"
    if ($page > 1) {
        $queryParams['page'] = $page - 1;
        echo '<a href="' . htmlspecialchars($path . '?' . http_build_query($queryParams)) . '">&laquo; Previous</a>';
    } else {
        // Sử dụng thẻ span và class 'disabled' khi ở trang đầu
        echo '<span class="disabled">&laquo; Previous</span>';
    }

    // Các nút số trang
    for ($i = 1; $i <= $totalPages; $i++) {
        $queryParams['page'] = $i;
        $isActive = ($i == $page) ? 'active' : '';
        echo '<a href="' . htmlspecialchars($path . '?' . http_build_query($queryParams)) . '" class="' . $isActive . '">' . $i . '</a>';
    }

    // Nút "Next"
    if ($page < $totalPages) {
        $queryParams['page'] = $page + 1;
        echo '<a href="' . htmlspecialchars($path . '?' . http_build_query($queryParams)) . '">Next &raquo;</a>';
    } else {
        // Sử dụng thẻ span và class 'disabled' khi ở trang cuối
        echo '<span class="disabled">Next &raquo;</span>';
    }
    ?>
</div>
<?php endif; ?>