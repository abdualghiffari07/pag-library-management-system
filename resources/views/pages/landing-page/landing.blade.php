@php
    $visitorPath = route('visitors.register', [], false);

    $visitorUrl = request()->getSchemeAndHttpHost()
        . $visitorPath;

    $visitorRegisterUrl = $visitorUrl . '?tab=register';
    $visitorCheckinUrl = $visitorUrl . '?tab=checkin';
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>PAG Library</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/landing.js',
        'resources/js/visitor-qr.js'
    ])

    <style>
        /* =====================================================
           LANDING QR - ISOLATED STYLE
        ===================================================== */

        .pag-landing .pag-home-hero {
            position: relative;
            min-height: 100vh;
            min-height: 100svh;
            overflow: hidden;
        }

        .pag-landing .pag-home-inner {
            position: relative;
            z-index: 10;

            display: flex;
            align-items: center;
            justify-content: space-between;

            width: 100%;
            max-width: 1280px;
            min-height: 100vh;
            min-height: 100svh;

            margin: 0 auto;

            padding: 105px 32px 55px;

            gap: 52px;
        }

        /* Left */

        .pag-landing .pag-home-copy {
            flex: 1 1 auto;
            min-width: 0;
            max-width: 690px;
        }

        .pag-landing .pag-home-lines {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .pag-landing .pag-home-lines span {
            width: 48px;
            height: 4px;
            border-radius: 999px;
        }

        .pag-landing .pag-home-eyebrow {
            margin-bottom: 13px;

            color: rgba(255, 255, 255, 0.82);

            font-size: 14px;
            font-weight: 700;

            letter-spacing: 0.3em;
            text-transform: uppercase;
        }

        .pag-landing .pag-home-title {
            margin: 0;

            color: #ffffff;

            font-size: clamp(50px, 5vw, 70px);
            font-weight: 800;
            line-height: 1.07;

            letter-spacing: -0.04em;
        }

        .pag-landing .pag-home-title span {
            display: block;
            margin-top: 4px;
        }

        .pag-landing .pag-home-description {
            max-width: 640px;

            margin-top: 23px;

            color: rgba(255, 255, 255, 0.9);

            font-size: 18px;
            line-height: 1.75;
        }

        .pag-landing .pag-home-features {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            max-width: 630px;

            margin-top: 30px;
        }

        .pag-landing .pag-home-feature {
            padding: 0 18px;

            border-right:
                1px solid
                rgba(255, 255, 255, 0.22);
        }

        .pag-landing .pag-home-feature:first-child {
            padding-left: 0;
        }

        .pag-landing .pag-home-feature:last-child {
            border-right: 0;
        }

        .pag-landing .pag-home-feature strong {
            display: block;

            color: #ffffff;

            font-size: 13px;
            font-weight: 700;
        }

        .pag-landing .pag-home-feature span {
            display: block;

            margin-top: 5px;

            color: rgba(255, 255, 255, 0.64);

            font-size: 11px;
        }

        /* =====================================================
           QR CARD
        ===================================================== */

        .pag-landing .pag-home-visitor {
            flex: 0 0 390px;

            width: 390px;
            max-width: 390px;
        }

        .pag-landing .pag-home-qr-card {
            width: 100%;

            overflow: hidden;

            border:
                1px solid
                rgba(255, 255, 255, 0.6);

            border-radius: 24px;

            background: rgba(255, 255, 255, 0.97);

            box-shadow:
                0 24px 65px
                rgba(0, 31, 70, 0.28);
        }

        .pag-landing .pag-home-qr-head {
            padding: 17px 24px 10px;

            text-align: center;
        }

        .pag-landing .pag-home-qr-badge {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 7px;

            padding: 6px 13px;

            border-radius: 999px;

            background: #edf6ff;

            color: #005daa;

            font-size: 10px;
            font-weight: 700;
        }

        .pag-landing .pag-home-qr-badge svg {
            width: 14px;
            height: 14px;
        }

        .pag-landing .pag-home-qr-head h2 {
            margin-top: 10px;

            color: #102a43;

            font-size: 21px;
            font-weight: 800;
            line-height: 1.25;
        }

        .pag-landing .pag-home-qr-head p {
            max-width: 320px;

            margin: 5px auto 0;

            color: #667085;

            font-size: 11px;
            line-height: 1.5;
        }

        /* QR size is intentionally forced */

        .pag-landing .pag-home-qr-area {
            display: flex;

            align-items: center;
            justify-content: center;

            width: 100%;
        }

        .pag-landing .pag-home-qr-box {
            display: inline-flex;

            width: auto;
            height: auto;

            padding: 9px;

            border: 1px solid #e2e8f0;
            border-radius: 15px;

            background: #ffffff;

            box-shadow:
                0 7px 20px
                rgba(16, 42, 67, 0.12);
        }

        .pag-landing #visitor-qr-code {
            display: block !important;

            width: 165px !important;
            height: 165px !important;

            min-width: 165px !important;
            min-height: 165px !important;

            max-width: 165px !important;
            max-height: 165px !important;
        }

        /* URL */

        .pag-landing .pag-home-url-wrap {
            padding: 13px 22px 0;
        }

        .pag-landing .pag-home-url {
            display: flex;

            align-items: center;

            min-width: 0;

            gap: 8px;

            padding: 8px 10px;

            border: 1px solid #e2e8f0;
            border-radius: 10px;

            background: #f8fafc;
        }

        .pag-landing .pag-home-url > svg {
            flex: 0 0 auto;

            width: 15px;
            height: 15px;

            color: #005daa;
        }

        .pag-landing .pag-home-url span {
            min-width: 0;
            flex: 1;

            overflow: hidden;

            color: #667085;

            font-size: 10px;

            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .pag-landing .pag-home-url button {
            display: flex;

            flex: 0 0 auto;

            align-items: center;
            justify-content: center;

            width: 28px;
            height: 28px;

            padding: 0;

            border: 0;
            border-radius: 7px;

            background: transparent;

            color: #005daa;
        }

        .pag-landing .pag-home-url button:hover {
            background: #eaf4ff;
        }

        .pag-landing .pag-home-url button svg {
            width: 14px;
            height: 14px;
        }

        /* Buttons */

        .pag-landing .pag-home-actions {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 9px;

            padding: 12px 22px 17px;
        }

        .pag-landing .pag-home-actions a {
            display: flex;

            align-items: center;
            justify-content: center;

            min-width: 0;
            min-height: 52px;

            gap: 7px;

            padding: 8px;

            border-radius: 11px;

            text-decoration: none;

            transition:
                transform 0.2s ease,
                background-color 0.2s ease;
        }

        .pag-landing .pag-home-actions a:hover {
            transform: translateY(-2px);
        }

        .pag-landing .pag-home-actions svg {
            flex: 0 0 auto;

            width: 18px;
            height: 18px;
        }

        .pag-landing .pag-home-actions strong {
            display: block;

            font-size: 10px;
            font-weight: 700;
            line-height: 1.25;
        }

        .pag-landing .pag-home-actions small {
            display: block;

            margin-top: 2px;

            font-size: 8px;
            font-weight: 400;
        }

        .pag-landing .pag-home-register {
            border: 1px solid #005daa;

            background: #005daa;

            color: #ffffff;
        }

        .pag-landing .pag-home-register:hover {
            background: #004b89;
        }

        .pag-landing .pag-home-register small {
            color: rgba(255, 255, 255, 0.72);
        }

        .pag-landing .pag-home-checkin {
            border:
                1px solid
                rgba(0, 93, 170, 0.32);

            background: #ffffff;

            color: #005daa;
        }

        .pag-landing .pag-home-checkin:hover {
            background: #eef7ff;
        }

        .pag-landing .pag-home-checkin small {
            color: #667085;
        }

        .pag-landing .pag-home-qr-footer {
            padding: 7px 18px;

            border-top: 1px solid #f1f5f9;

            background: #fafcff;

            color: #98a2b3;

            font-size: 9px;

            text-align: center;
        }

        /* =====================================================
           LAPTOP 1366x768
        ===================================================== */

        @media (min-width: 1024px) and (max-height: 820px) {

            .pag-landing .pag-home-inner {
                padding-top: 88px;
                padding-bottom: 28px;

                gap: 42px;
            }

            .pag-landing .pag-home-title {
                font-size: 53px;
            }

            .pag-landing .pag-home-description {
                margin-top: 16px;

                font-size: 16px;
                line-height: 1.65;
            }

            .pag-landing .pag-home-features {
                margin-top: 20px;
            }

            .pag-landing .pag-home-visitor {
                flex-basis: 360px;

                width: 360px;
                max-width: 360px;
            }

            .pag-landing .pag-home-qr-head {
                padding-top: 12px;
                padding-bottom: 7px;
            }

            .pag-landing .pag-home-qr-head h2 {
                margin-top: 7px;

                font-size: 18px;
            }

            .pag-landing .pag-home-qr-head p {
                margin-top: 3px;

                font-size: 10px;
            }

            .pag-landing #visitor-qr-code {
                width: 140px !important;
                height: 140px !important;

                min-width: 140px !important;
                min-height: 140px !important;

                max-width: 140px !important;
                max-height: 140px !important;
            }

            .pag-landing .pag-home-qr-box {
                padding: 7px;
            }

            .pag-landing .pag-home-url-wrap {
                padding-top: 9px;
            }

            .pag-landing .pag-home-actions {
                padding-top: 9px;
                padding-bottom: 11px;
            }

            .pag-landing .pag-home-actions a {
                min-height: 45px;
            }

            .pag-landing .pag-home-qr-footer {
                padding-top: 5px;
                padding-bottom: 5px;
            }
        }

        /* =====================================================
           TABLET
        ===================================================== */

        @media (max-width: 1023px) {

            .pag-landing .pag-home-hero {
                min-height: auto;
            }

            .pag-landing .pag-home-inner {
                flex-direction: column;

                min-height: auto;

                padding:
                    110px
                    24px
                    75px;

                gap: 40px;
            }

            .pag-landing .pag-home-copy {
                width: 100%;
                max-width: 720px;

                text-align: center;
            }

            .pag-landing .pag-home-lines {
                justify-content: center;
            }

            .pag-landing .pag-home-features {
                display: none;
            }

            .pag-landing .pag-home-visitor {
                flex: none;

                width: 100%;
                max-width: 410px;
            }
        }

        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 639px) {

            .pag-landing .pag-home-inner {
                padding:
                    95px
                    18px
                    65px;

                gap: 30px;
            }

            .pag-landing .pag-home-title {
                font-size: 38px;
                line-height: 1.1;
            }

            .pag-landing .pag-home-description {
                margin-top: 15px;

                font-size: 14px;
                line-height: 1.6;
            }

            .pag-landing .pag-home-lines span {
                width: 32px;
                height: 3px;
            }

            .pag-landing .pag-home-visitor {
                max-width: 100%;
            }

            .pag-landing .pag-home-qr-card {
                border-radius: 18px;
            }

            .pag-landing #visitor-qr-code {
                width: 150px !important;
                height: 150px !important;

                min-width: 150px !important;
                min-height: 150px !important;

                max-width: 150px !important;
                max-height: 150px !important;
            }

            .pag-landing .pag-home-actions {
                grid-template-columns: 1fr;
            }

            .pag-landing .pag-home-actions strong {
                font-size: 11px;
            }
        }
    </style>
</head>

<body class="pag-landing page-load-animation overflow-x-hidden bg-white text-slate-800">

    {{-- Navbar --}}
    <header
        class="fixed left-0 top-0 z-50 w-full border-b border-white/20 bg-white/90 backdrop-blur-md"
    >
        <div
            class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:h-20 sm:px-6 lg:px-8"
        >
            <a
                href="#home"
                class="flex min-w-0 items-center gap-2 sm:gap-3"
            >
                <div
                    class="flex h-11 w-12 shrink-0 items-center justify-center sm:h-16 sm:w-16"
                >
                    <img
                        src="{{ asset('images/landing/logo-pertamina.png') }}"
                        alt="Logo PAG"
                        class="h-full w-full object-contain p-1"
                    >
                </div>

                <div class="min-w-0">
                    <h1
                        class="truncate text-sm font-bold leading-none sm:text-lg"
                    >
                        Perta Arun Gas Library
                    </h1>

                    <p
                        class="mt-1 hidden text-xs text-slate-500 sm:block"
                    >
                        Library Management System
                    </p>
                </div>
            </a>

            <nav
                class="hidden items-center gap-5 md:flex lg:gap-7"
            >
                <a
                    href="#home"
                    class="text-sm font-medium text-slate-700"
                >
                    Home
                </a>

                <a
                    href="#about"
                    class="text-sm font-medium text-slate-700"
                >
                    About
                </a>

                <a
                    href="#library"
                    class="text-sm font-medium text-slate-700"
                >
                    Library
                </a>

                <a
                    href="{{ $visitorCheckinUrl }}"
                    class="rounded-full bg-[#005DAA] px-6 py-2.5 text-sm font-semibold text-white shadow-md"
                >
                    Pengunjung
                </a>

                <button
                    type="button"
                    class="login-trigger rounded-full bg-[#E31E24] px-6 py-2.5 text-sm font-semibold text-white shadow-md"
                >
                    Login / Masuk
                </button>
            </nav>

            <button
                id="mobile-menu-button"
                type="button"
                class="flex h-10 w-10 items-center justify-center md:hidden"
                aria-label="Buka menu"
                aria-expanded="false"
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    class="h-6 w-6"
                >
                    <path
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>
        </div>

        <div
            id="mobile-menu"
            class="hidden border-t border-slate-200 bg-white shadow-lg md:hidden"
        >
            <nav class="flex flex-col px-4 py-4">

                <a
                    href="#home"
                    class="mobile-menu-link px-4 py-3"
                >
                    Home
                </a>

                <a
                    href="#about"
                    class="mobile-menu-link px-4 py-3"
                >
                    About
                </a>

                <a
                    href="#library"
                    class="mobile-menu-link px-4 py-3"
                >
                    Library
                </a>

                <a
                    href="{{ $visitorCheckinUrl }}"
                    class="mt-2 rounded-lg bg-[#005DAA] px-4 py-3 text-center text-white"
                >
                    Pengunjung
                </a>

                <button
                    type="button"
                    class="login-trigger mt-2 rounded-lg bg-[#E31E24] px-4 py-3 text-white"
                >
                    Login / Masuk
                </button>
            </nav>
        </div>
    </header>

    <main id="home">

        {{-- Hero --}}
        <section class="pag-home-hero hero-section">

            <div
                class="hero-slide active"
                style="background-image: url('/images/landing/pag-4.png');"
            ></div>

            <div
                class="hero-slide"
                style="background-image: url('/images/landing/pag-2.webp');"
            ></div>

            <div
                class="hero-slide"
                style="background-image: url('/images/landing/pag-3.webp');"
            ></div>

            <div class="hero-overlay"></div>

            <div class="pag-home-inner">

                {{-- Left --}}
                <div class="pag-home-copy">

                    <div class="pag-home-lines">
                        <span class="bg-[#E31E24]"></span>
                        <span class="bg-[#00A651]"></span>
                        <span class="bg-white"></span>
                    </div>

                    <p class="pag-home-eyebrow">
                        Perta Arun Gas
                    </p>

                    <h1 class="pag-home-title">
                        Perpustakaan

                        <span>
                            PertaArunGas
                        </span>
                    </h1>

                    <p class="pag-home-description">
                        Pusat informasi dan perpustakaan digital untuk
                        mendukung budaya membaca, pembelajaran, dan
                        berbagi pengetahuan.
                    </p>

                    <div class="pag-home-features">

                        <div class="pag-home-feature">
                            <strong>
                                Koleksi Digital
                            </strong>

                            <span>
                                Informasi buku terpusat
                            </span>
                        </div>

                        <div class="pag-home-feature">
                            <strong>
                                Mudah Diakses
                            </strong>

                            <span>
                                Akses cepat dan praktis
                            </span>
                        </div>

                        <div class="pag-home-feature">
                            <strong>
                                Untuk Semua
                            </strong>

                            <span>
                                Mendukung budaya literasi
                            </span>
                        </div>

                    </div>
                </div>

                {{-- QR --}}
                <div class="pag-home-visitor">

                    <div class="pag-home-qr-card">

                        <div class="pag-home-qr-head">

                            <div class="pag-home-qr-badge">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        d="M4 4h5v5H4V4Zm11 0h5v5h-5V4ZM4 15h5v5H4v-5Zm11 0h2v2h-2v-2Zm3 0h2v5h-5v-2"
                                    />
                                </svg>

                                Akses Cepat Pengunjung
                            </div>

                            <h2>
                                Scan QR Pengunjung
                            </h2>

                            <p>
                                Scan menggunakan kamera HP untuk membuka
                                halaman pendaftaran atau check-in.
                            </p>
                        </div>

                        <div class="pag-home-qr-area">

                            <div class="pag-home-qr-box">

                                <canvas
                                    id="visitor-qr-code"
                                    data-url="{{ $visitorUrl }}"
                                    data-size="165"
                                    aria-label="QR Code Pengunjung"
                                ></canvas>

                            </div>

                        </div>

                        <p
                            id="visitor-qr-error"
                            class="hidden text-center text-xs text-red-600"
                        >
                            QR Code gagal dibuat.
                        </p>

                        <div class="pag-home-url-wrap">

                            <div class="pag-home-url">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        d="M10 13a5 5 0 0 0 7.1.1l2-2a5 5 0 0 0-7.1-7.1l-1.1 1.1"
                                    />

                                    <path
                                        d="M14 11a5 5 0 0 0-7.1-.1l-2 2A5 5 0 0 0 12 20l1.1-1.1"
                                    />
                                </svg>

                                <span>
                                    {{ $visitorUrl }}
                                </span>

                                <button
                                    id="copy-visitor-url"
                                    type="button"
                                    data-url="{{ $visitorUrl }}"
                                    aria-label="Salin tautan"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <rect
                                            x="9"
                                            y="9"
                                            width="11"
                                            height="11"
                                            rx="2"
                                        />

                                        <path
                                            d="M15 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h3"
                                        />
                                    </svg>
                                </button>

                            </div>

                            <p
                                id="copy-visitor-feedback"
                                class="hidden"
                            >
                                Tautan berhasil disalin.
                            </p>

                        </div>

                        <div class="pag-home-actions">

                            <a
                                href="{{ $visitorRegisterUrl }}"
                                class="pag-home-register"
                            >
                                <span>
                                    <strong>
                                        Daftar Pengunjung
                                    </strong>

                                    <small>
                                        Pengunjung baru
                                    </small>
                                </span>
                            </a>

                            <a
                                href="{{ $visitorCheckinUrl }}"
                                class="pag-home-checkin"
                            >
                                <span>
                                    <strong>
                                        Masuk Pengunjung
                                    </strong>

                                    <small>
                                        Sudah terdaftar
                                    </small>
                                </span>
                            </a>

                        </div>

                        <div class="pag-home-qr-footer">
                            PAG Library · Akses pengunjung cepat dan mudah
                        </div>

                    </div>
                </div>

            </div>

            {{-- Slider --}}
            <div
                class="absolute bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-2"
            >
                <button
                    class="slider-indicator active"
                    data-slide="0"
                    aria-label="Slide 1"
                ></button>

                <button
                    class="slider-indicator"
                    data-slide="1"
                    aria-label="Slide 2"
                ></button>

                <button
                    class="slider-indicator"
                    data-slide="2"
                    aria-label="Slide 3"
                ></button>
            </div>

        </section>

        {{-- About --}}
        <section
            id="about"
            class="bg-white px-5 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24"
        >
            <div class="mx-auto max-w-7xl">

                <div
                    class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16"
                >

                    <div>

                        <p
                            class="text-sm font-semibold uppercase tracking-[0.25em] text-[#005DAA]"
                        >
                            About PAG Library
                        </p>

                        <h2
                            class="mt-3 text-3xl font-bold text-[#102A43] sm:text-4xl"
                        >
                            Knowledge that

                            <span class="text-[#E31E24]">
                                empowers
                            </span>

                            people.
                        </h2>

                    </div>

                    <div>

                        <p
                            class="text-base leading-8 text-slate-600"
                        >
                            PAG Library merupakan sistem manajemen
                            perpustakaan yang dirancang untuk membantu
                            pengelolaan koleksi buku, eksemplar,
                            pengguna, serta proses peminjaman dan
                            pengembalian secara terstruktur.
                        </p>

                        <p
                            class="mt-4 text-base leading-8 text-slate-600"
                        >
                            Sistem ini membantu menciptakan proses
                            pengelolaan perpustakaan yang lebih
                            terorganisir, mudah diakses, dan efisien.
                        </p>

                    </div>

                </div>

            </div>
        </section>

        {{-- Library --}}
        <section
            id="library"
            class="bg-[#F5F7FA] px-5 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24"
        >
            <div class="mx-auto max-w-7xl">

                <div class="text-center">

                    <p
                        class="text-sm font-semibold uppercase tracking-[0.25em] text-[#005DAA]"
                    >
                        Our Library
                    </p>

                    <h2
                        class="mt-3 text-3xl font-bold text-[#102A43] sm:text-4xl"
                    >
                        Everything in one place
                    </h2>

                </div>

                <div
                    class="mt-10 grid gap-6 md:grid-cols-2"
                >

                    <div class="library-card">

                        <p class="text-sm text-slate-500">
                            Koleksi Buku
                        </p>

                        <p
                            class="mt-2 text-4xl font-bold text-[#102A43]"
                            data-counter="{{ $totalBooks }}"
                        >
                            0
                        </p>

                        <p class="mt-2 text-sm text-slate-500">
                            Buku tersedia di perpustakaan
                        </p>

                    </div>

                    <div class="library-card">

                        <p class="text-sm text-slate-500">
                            Pengunjung
                        </p>

                        <p
                            class="mt-2 text-4xl font-bold text-[#102A43]"
                            data-counter="{{ $totalVisitors }}"
                        >
                            0
                        </p>

                        <p class="mt-2 text-sm text-slate-500">
                            Pengunjung terdaftar
                        </p>

                    </div>

                </div>

            </div>
        </section>

        <footer
            class="bg-[#102A43] px-5 py-8 text-white"
        >
            <div
                class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 sm:flex-row"
            >
                <div>
                    <strong>
                        PAG Library
                    </strong>

                    <p class="text-sm text-white/60">
                        Library Management System
                    </p>
                </div>

                <p class="text-sm text-white/60">
                    © {{ date('Y') }} PAG Library
                </p>
            </div>
        </footer>

    </main>

    @include('pages.landing-page.login-form')

</body>

</html>