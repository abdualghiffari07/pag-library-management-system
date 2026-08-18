document.addEventListener('DOMContentLoaded', function () {

    const main = document.querySelector('.main.dashboard-page');

    if (!main) {
        return;
    }

    main.style.setProperty(
        'background-image',
        'url("/images/landing/pag-2.webp")',
        'important'
    );

    main.style.setProperty(
        'background-size',
        'cover',
        'important'
    );

    main.style.setProperty(
        'background-position',
        '55% 45%',
        'important'
    );

    main.style.setProperty(
        'background-repeat',
        'no-repeat',
        'important'
    );

    main.style.setProperty(
        'background-attachment',
        'fixed',
        'important'
    );

});