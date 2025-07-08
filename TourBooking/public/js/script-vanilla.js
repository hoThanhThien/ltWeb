document.addEventListener('DOMContentLoaded', function() {
    
    // --- Chức năng bật/tắt Sidebar ---
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(event) {
            event.preventDefault();
            document.body.classList.toggle('sidenav-toggled');
        });
    }

    // --- Chức năng Menu xổ xuống ---
    const collapsibleToggles = document.querySelectorAll('.collapsible-toggle');
    collapsibleToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(event) {
            event.preventDefault(); // Ngăn trang nhảy lên đầu
            
            const targetId = toggle.getAttribute('data-target');
            const targetMenu = document.querySelector(targetId);

            if (targetMenu) {
                // Xoay mũi tên
                toggle.classList.toggle('collapsed');
                
                // Hiển thị hoặc ẩn menu con
                targetMenu.classList.toggle('show');
            }
        });
    });

});