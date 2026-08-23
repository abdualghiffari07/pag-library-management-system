/* =========================================================
   SCROLL POSITION
   ========================================================= */

if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}


/*
 * Simpan posisi scroll sebelum form pengunjung dikirim.
 */
document.addEventListener('DOMContentLoaded', () => {

    const visitorForm =
        document.querySelector('#visitor form');


    if (visitorForm) {

        visitorForm.addEventListener('submit', () => {

            sessionStorage.setItem(
                'visitorScrollPosition',
                window.scrollY
            );

        });

    }

});


/*
 * Setelah halaman selesai dimuat kembali,
 * kembalikan posisi scroll sebelumnya.
 */
window.addEventListener('load', () => {

    const savedScrollPosition =
        sessionStorage.getItem('visitorScrollPosition');


    if (savedScrollPosition !== null) {

        window.scrollTo(
            0,
            parseInt(savedScrollPosition, 10)
        );


        sessionStorage.removeItem(
            'visitorScrollPosition'
        );

    } else {

        // Hanya kembali ke atas jika tidak ada
        // posisi scroll yang perlu dipulihkan.
        window.scrollTo(0, 0);

    }

});

/* =========================================================
   HERO IMAGE SLIDER
   ========================================================= */

const heroSlides =
    document.querySelectorAll('.hero-slide');

const sliderIndicators =
    document.querySelectorAll('.slider-indicator');

let currentSlide = 0;


/* =========================================================
   SHOW SLIDE
   ========================================================= */

function showSlide(index) {

    heroSlides.forEach((slide, i) => {

        slide.classList.toggle(
            'active',
            i === index
        );

    });


    sliderIndicators.forEach((indicator, i) => {

        indicator.classList.toggle(
            'active',
            i === index
        );

    });

}


/* =========================================================
   NEXT SLIDE
   ========================================================= */

function nextSlide() {

    currentSlide =
        (currentSlide + 1) % heroSlides.length;

    showSlide(currentSlide);

}


/* =========================================================
   AUTO SLIDER
   ========================================================= */

if (heroSlides.length > 0) {

    // Pastikan slide pertama tampil
    showSlide(0);


    setInterval(
        nextSlide,
        4000
    );

}


/* =========================================================
   SLIDER INDICATOR CLICK
   ========================================================= */

sliderIndicators.forEach((indicator, index) => {

    indicator.addEventListener(
        'click',
        () => {

            currentSlide = index;

            showSlide(currentSlide);

        }
    );

});

/* =========================================================
   MOBILE MENU
   ========================================================= */

const mobileMenuButton =
    document.getElementById('mobile-menu-button');

const mobileMenu =
    document.getElementById('mobile-menu');

const mobileMenuLinks =
    document.querySelectorAll('.mobile-menu-link');


/* =========================================================
   TOGGLE MOBILE MENU
   ========================================================= */

function toggleMobileMenu() {

    const isCurrentlyOpen =
        !mobileMenu.classList.contains('hidden');

    mobileMenu.classList.toggle('hidden');

    mobileMenuButton.setAttribute(
        'aria-expanded',
        String(!isCurrentlyOpen)
    );

}


/* =========================================================
   CLOSE MOBILE MENU
   ========================================================= */

function closeMobileMenu() {

    mobileMenu.classList.add('hidden');

    mobileMenuButton.setAttribute('aria-expanded', 'false');

}


if (mobileMenuButton && mobileMenu) {

    mobileMenuButton.addEventListener(
        'click',
        toggleMobileMenu
    );

    // Tutup menu otomatis saat salah satu link diklik
    mobileMenuLinks.forEach((link) => {

        link.addEventListener('click', closeMobileMenu);

    });

}

/* =========================================================
   LOGIN MODAL
   ========================================================= */

const loginTriggers =
    document.querySelectorAll('.login-trigger');

const loginModal =
    document.getElementById('login-modal');

const loginPanel =
    document.getElementById('login-panel');

const closeLogin =
    document.getElementById('close-login');


/* =========================================================
   OPEN LOGIN
   ========================================================= */

function openLogin() {

    // Tutup mobile menu jika sedang terbuka
    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {

        closeMobileMenu();

    }

    // Tampilkan modal
    loginModal.style.display = 'flex';

    // Pastikan panel berada di bawah
    loginPanel.classList.remove('login-panel-show');

    /*
     * Tunggu browser melakukan render,
     * kemudian jalankan animasi.
     */
    requestAnimationFrame(() => {

        requestAnimationFrame(() => {

            loginPanel.classList.add(
                'login-panel-show'
            );

        });

    });

}


/* =========================================================
   CLOSE LOGIN
   ========================================================= */

function closeLoginModal() {

    // Hapus class animasi
    loginPanel.classList.remove(
        'login-panel-show'
    );

    /*
     * Tunggu animasi selesai
     * sebelum menyembunyikan modal.
     */
    setTimeout(() => {

        loginModal.style.display = 'none';

    }, 700);

}


/* =========================================================
   LOGIN BUTTONS
   ========================================================= */

loginTriggers.forEach((button) => {

    button.addEventListener(
        'click',
        openLogin
    );

});


/* =========================================================
   CLOSE BUTTON
   ========================================================= */

if (closeLogin) {

    closeLogin.addEventListener(
        'click',
        closeLoginModal
    );

}


/* =========================================================
   CLICK OUTSIDE LOGIN PANEL
   ========================================================= */

if (loginModal) {

    loginModal.addEventListener(
        'click',
        (event) => {

            if (event.target === loginModal) {

                closeLoginModal();

            }

        }
    );

}

