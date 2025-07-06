// File: public/js/admin_dashboard.js
document.addEventListener("DOMContentLoaded", function() {
    const menuToggle = document.querySelector('.main-header .fa-bars');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    // Chỉ thực hiện nếu các thành phần tồn tại
    if (menuToggle && sidebar && mainContent) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });
    }
});