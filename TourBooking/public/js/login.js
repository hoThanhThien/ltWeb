document.addEventListener('DOMContentLoaded', function() {
    
    // --- Phần 1: Chức năng "Hiển thị mật khẩu" ---
    const passwordInput = document.getElementById('password');
    const showPasswordToggle = document.getElementById('show_password_toggle');

    if (passwordInput && showPasswordToggle) {
        showPasswordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        });
    }

    // --- Phần 2: Xử lý link Đăng ký và Đăng nhập (Mã của bạn) ---

    var regLinks = document.querySelectorAll('.link-login, .link a[href="/register"]');
    regLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            window.parent.postMessage('open-register', '*');
        });
    });
    // Đăng nhập
    var loginLinks = document.querySelectorAll('.link-login[href="/login"], .form-footer a[href="/login"]');
    loginLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            window.parent.postMessage('open-login', '*');
        });
    });
});