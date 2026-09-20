/* =========================================================
   SCROLL POSITION
========================================================= */

if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}

window.addEventListener('load', () => {
    const savedScrollPosition =
        sessionStorage.getItem(
            'visitorScrollPosition'
        );

    if (savedScrollPosition !== null) {
        window.scrollTo(
            0,
            parseInt(
                savedScrollPosition,
                10
            )
        );

        sessionStorage.removeItem(
            'visitorScrollPosition'
        );

        return;
    }

    window.scrollTo(0, 0);
});


/* =========================================================
   HERO SLIDER
========================================================= */

const heroSlides =
    document.querySelectorAll(
        '.hero-slide'
    );

const sliderIndicators =
    document.querySelectorAll(
        '.slider-indicator'
    );

let currentSlide = 0;
let sliderInterval = null;


function showSlide(index) {
    if (!heroSlides.length) {
        return;
    }

    heroSlides.forEach(
        (slide, i) => {
            slide.classList.toggle(
                'active',
                i === index
            );
        }
    );

    sliderIndicators.forEach(
        (indicator, i) => {
            indicator.classList.toggle(
                'active',
                i === index
            );
        }
    );
}


function nextSlide() {
    if (!heroSlides.length) {
        return;
    }

    currentSlide =
        (currentSlide + 1)
        % heroSlides.length;

    showSlide(currentSlide);
}


if (heroSlides.length > 0) {
    showSlide(0);

    sliderInterval =
        window.setInterval(
            nextSlide,
            4000
        );
}


sliderIndicators.forEach(
    (indicator, index) => {
        indicator.addEventListener(
            'click',
            () => {
                currentSlide = index;

                showSlide(
                    currentSlide
                );

                if (sliderInterval) {
                    window.clearInterval(
                        sliderInterval
                    );

                    sliderInterval =
                        window.setInterval(
                            nextSlide,
                            4000
                        );
                }
            }
        );
    }
);


/* =========================================================
   MOBILE MENU
========================================================= */

const mobileMenuButton =
    document.getElementById(
        'mobile-menu-button'
    );

const mobileMenu =
    document.getElementById(
        'mobile-menu'
    );

const mobileMenuLinks =
    document.querySelectorAll(
        '.mobile-menu-link'
    );


function toggleMobileMenu() {
    if (
        !mobileMenu
        || !mobileMenuButton
    ) {
        return;
    }

    const isOpen =
        !mobileMenu.classList
            .contains('hidden');

    mobileMenu.classList.toggle(
        'hidden'
    );

    mobileMenuButton.setAttribute(
        'aria-expanded',
        String(!isOpen)
    );
}


function closeMobileMenu() {
    if (
        !mobileMenu
        || !mobileMenuButton
    ) {
        return;
    }

    mobileMenu.classList.add(
        'hidden'
    );

    mobileMenuButton.setAttribute(
        'aria-expanded',
        'false'
    );
}


if (
    mobileMenuButton
    && mobileMenu
) {
    mobileMenuButton.addEventListener(
        'click',
        toggleMobileMenu
    );

    mobileMenuLinks.forEach(
        link => {
            link.addEventListener(
                'click',
                closeMobileMenu
            );
        }
    );
}


/* =========================================================
   LOGIN MODAL
========================================================= */

const loginTriggers =
    document.querySelectorAll(
        '.login-trigger'
    );

const loginModal =
    document.getElementById(
        'login-modal'
    );

const loginPanel =
    document.getElementById(
        'login-panel'
    );

const closeLogin =
    document.getElementById(
        'close-login'
    );


function openLogin() {
    if (
        !loginModal
        || !loginPanel
    ) {
        return;
    }

    if (
        mobileMenu
        && !mobileMenu.classList
            .contains('hidden')
    ) {
        closeMobileMenu();
    }

    loginModal.style.display =
        'flex';

    loginPanel.classList.remove(
        'login-panel-show'
    );

    requestAnimationFrame(
        () => {
            requestAnimationFrame(
                () => {
                    loginPanel.classList.add(
                        'login-panel-show'
                    );
                }
            );
        }
    );
}


function closeLoginModal() {
    if (
        !loginModal
        || !loginPanel
    ) {
        return;
    }

    loginPanel.classList.remove(
        'login-panel-show'
    );

    setTimeout(
        () => {
            loginModal.style.display =
                'none';
        },
        700
    );
}


loginTriggers.forEach(
    button => {
        button.addEventListener(
            'click',
            openLogin
        );
    }
);


if (closeLogin) {
    closeLogin.addEventListener(
        'click',
        closeLoginModal
    );
}


if (loginModal) {
    loginModal.addEventListener(
        'click',
        event => {
            if (
                event.target
                === loginModal
            ) {
                closeLoginModal();
            }
        }
    );
}


document.addEventListener(
    'keydown',
    event => {
        if (
            event.key === 'Escape'
            && loginModal
            && loginModal.style.display
                === 'flex'
        ) {
            closeLoginModal();
        }
    }
);


/* =========================================================
   COUNTER
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    () => {
        const counters =
            document.querySelectorAll(
                '[data-counter]'
            );

        if (!counters.length) {
            return;
        }

        const animateCounter =
            counter => {
                const target =
                    Number(
                        counter.dataset
                            .counter
                    );

                if (
                    Number.isNaN(target)
                ) {
                    return;
                }

                const duration = 1800;

                const startTime =
                    performance.now();

                const updateCounter =
                    currentTime => {
                        const elapsed =
                            currentTime
                            - startTime;

                        const progress =
                            Math.min(
                                elapsed
                                / duration,
                                1
                            );

                        const easedProgress =
                            1
                            - Math.pow(
                                1 - progress,
                                3
                            );

                        const currentValue =
                            Math.floor(
                                target
                                * easedProgress
                            );

                        counter.textContent =
                            currentValue
                                .toLocaleString(
                                    'id-ID'
                                );

                        if (
                            progress < 1
                        ) {
                            requestAnimationFrame(
                                updateCounter
                            );
                        } else {
                            counter.textContent =
                                target
                                    .toLocaleString(
                                        'id-ID'
                                    );
                        }
                    };

                requestAnimationFrame(
                    updateCounter
                );
            };

        const counterObserver =
            new IntersectionObserver(
                (
                    entries,
                    observer
                ) => {
                    entries.forEach(
                        entry => {
                            if (
                                !entry
                                    .isIntersecting
                            ) {
                                return;
                            }

                            animateCounter(
                                entry.target
                            );

                            observer.unobserve(
                                entry.target
                            );
                        }
                    );
                },
                {
                    threshold: 0.5
                }
            );

        counters.forEach(
            counter => {
                counterObserver.observe(
                    counter
                );
            }
        );
    }
);


/* =========================================================
   COPY VISITOR URL
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    () => {
        const copyButton =
            document.getElementById(
                'copy-visitor-url'
            );

        const copyFeedback =
            document.getElementById(
                'copy-visitor-feedback'
            );

        if (!copyButton) {
            return;
        }

        copyButton.addEventListener(
            'click',
            async () => {
                const url =
                    copyButton
                        .dataset
                        .url
                        ?.trim();

                if (!url) {
                    return;
                }

                let copied = false;

                try {
                    if (
                        navigator.clipboard
                        && window
                            .isSecureContext
                    ) {
                        await navigator
                            .clipboard
                            .writeText(url);

                        copied = true;
                    }
                } catch (error) {
                    copied = false;
                }

                if (!copied) {
                    const textarea =
                        document
                            .createElement(
                                'textarea'
                            );

                    textarea.value = url;

                    textarea.setAttribute(
                        'readonly',
                        ''
                    );

                    textarea.style.position =
                        'fixed';

                    textarea.style.opacity =
                        '0';

                    document.body.appendChild(
                        textarea
                    );

                    textarea.select();

                    try {
                        copied =
                            document
                                .execCommand(
                                    'copy'
                                );
                    } catch (error) {
                        copied = false;
                    }

                    textarea.remove();
                }

                if (
                    copied
                    && copyFeedback
                ) {
                    copyFeedback
                        .classList
                        .remove('hidden');

                    setTimeout(
                        () => {
                            copyFeedback
                                .classList
                                .add(
                                    'hidden'
                                );
                        },
                        2000
                    );
                }
            }
        );
    }
);