document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll('.slider');
    let index = 0;

    function showSlide(i) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[i].classList.add('active');
    }

    function nextSlide() {
        index = (index + 1) % slides.length;
        showSlide(index);
    }

    showSlide(index);
    setInterval(nextSlide, 4000);
});
