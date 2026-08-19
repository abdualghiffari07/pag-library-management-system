/* =========================================================
   PAG LIBRARY
   ADMIN DASHBOARD JAVASCRIPT
========================================================= */

'use strict';


document.addEventListener('DOMContentLoaded', () => {

    const sidebar =
        document.getElementById('sidebar');

    const toggle =
        document.getElementById('sidebar-toggle');

    const overlay =
        document.getElementById('sidebar-overlay');


    if (!sidebar || !toggle) {
        return;
    }


    /* =====================================================
       OPEN / CLOSE SIDEBAR
    ====================================================== */

    function openSidebar() {

        sidebar.classList.add('open');

        if (overlay) {
            overlay.classList.add('active');
        }

    }


    function closeSidebar() {

        sidebar.classList.remove('open');

        if (overlay) {
            overlay.classList.remove('active');
        }

    }


    /* =====================================================
       TOGGLE SIDEBAR
    ====================================================== */

    toggle.addEventListener('click', () => {

        if (sidebar.classList.contains('open')) {

            closeSidebar();

        } else {

            openSidebar();

        }

    });


    /* =====================================================
       OVERLAY
    ====================================================== */

    if (overlay) {

        overlay.addEventListener(
            'click',
            closeSidebar
        );

    }


    /* =====================================================
       CLOSE MOBILE SIDEBAR WHEN CLICKING MENU
    ====================================================== */

    const menuLinks =
        sidebar.querySelectorAll(
            '.menu-item:not(.menu-disabled)'
        );


    menuLinks.forEach((link) => {

        link.addEventListener('click', () => {

            if (window.innerWidth <= 900) {

                closeSidebar();

            }

        });

    });


    /* =====================================================
       RESIZE
    ====================================================== */

    window.addEventListener('resize', () => {

        if (window.innerWidth > 900) {

            closeSidebar();

        }

    });

});

/* =========================================================
   GLOBAL PAGE BACKGROUND
   PAG LIBRARY
========================================================= */

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
        '55% center',
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