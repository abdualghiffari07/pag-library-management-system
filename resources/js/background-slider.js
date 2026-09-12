document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('[data-background-slider]');

    if (!slider) return;

    const slides = slider.querySelectorAll('.pag-background-slide');

    if (slides.length <= 1) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    let current = 0;

    setInterval(() => {
        slides[current].classList.remove('is-active');

        current = (current + 1) % slides.length;

        slides[current].classList.add('is-active');
    }, 5000);
});