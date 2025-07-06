document.addEventListener("DOMContentLoaded", function () {
    let index = 0;
const slides = document.querySelectorAll('.slider');

function showSlide(i) {
    slides.forEach(slide => slide.classList.remove('active'));
    slides[i].classList.add('active');
}

function nextSlide() {
    index = (index + 1) % slides.length;
    showSlide(index);
}

setInterval(nextSlide, 3000);

});
$(function() {
    // Mở modal khi bấm Đăng nhập / Đăng ký
    $('#open-auth-modal').click(function(e) {
        e.preventDefault();
        $('#auth-iframe').attr('src', '/login'); // hoặc '/register' nếu muốn mở đăng ký mặc định
        $('#iframe-modal-overlay, #iframe-modal').fadeIn(200);
    });

    // Đóng modal
    $('#close-iframe-modal, #iframe-modal-overlay').click(function() {
        $('#iframe-modal-overlay, #iframe-modal').fadeOut(200, function() {
            $('#auth-iframe').attr('src', '');
        });
    });

    // Cho phép chuyển qua lại giữa login/register trong iframe
    window.addEventListener('message', function(event) {
        if (event.data === 'open-register') {
            $('#auth-iframe').attr('src', '/register');
        }
        if (event.data === 'open-login') {
            $('#auth-iframe').attr('src', '/login');
        }
    });
});