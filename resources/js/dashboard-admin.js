'use strict';

// Sidebar
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebar-toggle');
    const overlay = document.getElementById('sidebar-overlay');

    if (!sidebar || !toggle) {
        return;
    }

    const openSidebar = () => {
        sidebar.classList.add('open');
        overlay?.classList.add('active');
    };

    const closeSidebar = () => {
        sidebar.classList.remove('open');
        overlay?.classList.remove('active');
    };

    toggle.addEventListener('click', () => {
        sidebar.classList.contains('open')
            ? closeSidebar()
            : openSidebar();
    });

    overlay?.addEventListener('click', closeSidebar);

    sidebar
        .querySelectorAll('.menu-item:not(.menu-disabled)')
        .forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 900) {
                    closeSidebar();
                }
            });
        });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) {
            closeSidebar();
        }
    });
});

// Background
document.addEventListener('DOMContentLoaded', () => {
    const main = document.querySelector('.main.dashboard-page');

    if (!main) {
        return;
    }

    const styles = {
        'background-image': 'url("/images/landing/pag-2.webp")',
        'background-size': 'cover',
        'background-position': '55% center',
        'background-repeat': 'no-repeat',
        'background-attachment': 'fixed'
    };

    Object.entries(styles).forEach(([property, value]) => {
        main.style.setProperty(property, value, 'important');
    });
});