<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>PAG Library</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="page-load-animation overflow-x-hidden bg-white text-slate-800">

    {{-- =====================================================
        NAVBAR
    ====================================================== --}}
    <header
        class="fixed left-0 top-0 z-50 w-full border-b border-white/20 bg-white/90 backdrop-blur-md">

        <div
            class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:h-20 sm:px-6 lg:px-8">

            {{-- Logo --}}
            <a
                href="#home"
                class="flex min-w-0 items-center gap-2 sm:gap-3">

        <div
            class="flex h-11 w-12 shrink-0 items-center justify-center overflow-hidden rounded-lg sm:h-16 sm:w-16 sm:rounded-xl">
            <img
                src="{{ asset('images/landing/logo-pertamina.png') }}"
                alt="Logo PAG"
                class="h-full w-full object-contain p-1"
            >   
        </div>

                <div class="min-w-0">

                    <h1
                        class="truncate text-sm font-bold leading-none sm:text-lg font-['Inter',sans-serif]">
                        Perta Arun Gas Library
                    </h1>

                    <p
                        class="mt-1 hidden text-xs text-slate-500 sm:block">
                        Library Management System
                    </p>

                </div>

            </a>


            {{-- Desktop Navigation --}}
            <nav class="hidden items-center gap-6 md:flex lg:gap-8">

                <a
                    href="#home"
                    class="text-sm font-medium text-slate-700 transition hover:text-[#E31E24]">
                    Home
                </a>

                <a
                    href="#about"
                    class="text-sm font-medium text-slate-700 transition hover:text-[#E31E24]">
                    About
                </a>

                <a
                    href="#library"
                    class="text-sm font-medium text-slate-700 transition hover:text-[#00A651]">
                    Library
                </a>

            <button
                type="button"
                class="login-trigger rounded-full bg-[#E31E24] px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-[#c91820] lg:px-6">
                Login / Masuk
            </button>

            </nav>


            {{-- Mobile Menu Button --}}
            <button
                id="mobile-menu-button"
                type="button"
                class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-700 transition hover:bg-slate-100 md:hidden"
                aria-label="Buka menu"
                aria-expanded="false"
                aria-controls="mobile-menu">

                <svg
                    id="menu-icon"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    class="h-6 w-6">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16" />

                </svg>

            </button>

        </div>


        {{-- Mobile Navigation --}}
        <div
            id="mobile-menu"
            class="hidden border-t border-slate-200 bg-white shadow-lg md:hidden">

            <nav class="mx-auto flex max-w-7xl flex-col px-4 py-4 sm:px-6">

                <a
                    href="#home"
                    class="mobile-menu-link rounded-lg px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-[#E31E24]">
                    Home
                </a>

                <a
                    href="#about"
                    class="mobile-menu-link rounded-lg px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-[#E31E24]">
                    About
                </a>

                <a
                    href="#library"
                    class="mobile-menu-link rounded-lg px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-[#00A651]">
                    Library
                </a>

            <button
                type="button"
                class="login-trigger mt-2 rounded-lg bg-[#E31E24] px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-[#c91820]">
                Login / Masuk
            </button>

            </nav>

        </div>

    </header>


    {{-- =====================================================
        MAIN
    ====================================================== --}}
    <main id="home">


        {{-- =====================================================
            HERO / IMAGE SLIDER
        ====================================================== --}}
        <section
            class="hero-section">

            {{-- Background 1 --}}
            <div
                class="hero-slide active"
                style="background-image: url('/images/landing/pag-4.png');">
            </div>


            {{-- Background 2 --}}
            <div
                class="hero-slide"
                style="background-image: url('/images/landing/pag-2.webp');">
            </div>


            {{-- Background 3 --}}
            <div
                class="hero-slide"
                style="background-image: url('/images/landing/pag-3.webp');">
            </div>


            {{-- Overlay --}}
            <div
                class="hero-overlay">
            </div>


            {{-- Hero Content --}}
            <div
                class="relative z-10 mx-auto flex min-h-[680px] max-w-7xl items-center px-5 pb-20 pt-28 sm:min-h-[720px] sm:px-6 sm:pt-32 lg:h-full lg:min-h-0 lg:px-8 lg:pb-0">

                <div
                    class="w-full max-w-3xl text-center sm:text-left">

                    {{-- Accent --}}
                    <div
                        class="mb-5 flex items-center justify-center gap-2 sm:justify-start sm:gap-3">

                        <span
                            class="h-1 w-8 rounded-full bg-[#E31E24] sm:w-12">
                        </span>

                        <span
                            class="h-1 w-8 rounded-full bg-[#00A651] sm:w-12">
                        </span>

                        <span
                            class="h-1 w-8 rounded-full bg-white sm:w-12">
                        </span>

                    </div>


                    {{-- Subtitle --}}
                    <p
                        class="mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-white/80 sm:mb-4 sm:text-sm sm:tracking-[0.3em]">
                        Perta Arun Gas
                    </p>


                    {{-- Title --}}
                    <h1
                        class="text-4xl font-bold leading-tight tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">

                         Perpustakaan

                        <span class="">
                            PAG
                        </span>

                    </h1>


                    {{-- Description --}}
                    <p
                        class="mx-auto mt-5 max-w-2xl text-base leading-7 text-white/90 sm:mt-6 sm:text-lg sm:leading-relaxed md:text-xl lg:mx-0">

                        Pusat informasi dan perpustakaan digital untuk
                        mendukung budaya membaca, pembelajaran, dan
                        berbagi pengetahuan.

                    </p>


                    {{-- Buttons --}}
                    <div
                        class="mt-7 flex flex-col justify-center gap-3 sm:mt-9 sm:flex-row sm:justify-start">

                        <a
                            href="#library"
                            class="rounded-full bg-white px-6 py-3.5 text-center text-sm font-semibold text-[#005DAA] shadow-lg transition hover:-translate-y-1 hover:shadow-xl sm:px-7">

                            Explore Library

                        </a>

                    </div>

                </div>

            </div>


            {{-- Slider Indicators --}}
            <div
                class="absolute bottom-7 left-1/2 z-20 flex -translate-x-1/2 gap-2 sm:bottom-8">

                <button
                    class="slider-indicator active"
                    data-slide="0"
                    aria-label="Slide 1">
                </button>

                <button
                    class="slider-indicator"
                    data-slide="1"
                    aria-label="Slide 2">
                </button>

                <button
                    class="slider-indicator"
                    data-slide="2"
                    aria-label="Slide 3">
                </button>

            </div>

        </section>


        {{-- =====================================================
            ABOUT PAG LIBRARY
        ====================================================== --}}
        <section
            id="about"
            class="bg-white px-5 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24">

            <div class="mx-auto max-w-7xl">

                <div
                    class="grid items-center gap-10 md:gap-12 lg:grid-cols-2 lg:gap-16">

                    <div>

                        <div
                            class="mb-4 flex items-center justify-center gap-2 sm:justify-start sm:gap-3">

                            <span
                                class="h-1 w-8 rounded-full bg-[#E31E24] sm:w-10">
                            </span>

                            <span
                                class="h-1 w-8 rounded-full bg-[#005DAA] sm:w-10">
                            </span>

                            <span
                                class="h-1 w-8 rounded-full bg-[#00A651] sm:w-10">
                            </span>

                        </div>


                        <p
                            class="text-center text-xs font-semibold uppercase tracking-[0.2em] text-[#005DAA] sm:text-left sm:text-sm sm:tracking-[0.25em]">
                            About PAG Library
                        </p>


                        <h2
                            class="mt-3 text-center text-3xl font-bold leading-tight text-[#102A43] sm:text-4xl md:text-5xl lg:text-left">

                            Knowledge that

                            <span class="text-[#E31E24]">
                                empowers
                            </span>

                            people.

                        </h2>

                    </div>


                    <div>

                        <p
                            class="text-center text-base leading-7 text-slate-600 sm:text-lg sm:leading-8 lg:text-left">

                            PAG Library merupakan sistem manajemen
                            perpustakaan yang dirancang untuk membantu
                            pengelolaan koleksi buku, eksemplar,
                            pengguna, serta proses peminjaman dan
                            pengembalian secara terstruktur.

                        </p>


                        <p
                            class="mt-4 text-center text-base leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8 lg:text-left">

                            Sistem ini membantu menciptakan proses
                            pengelolaan perpustakaan yang lebih
                            terorganisir, mudah diakses, dan efisien.

                        </p>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
            LIBRARY FEATURES
        ====================================================== --}}
        <section
            id="library"
            class="bg-[#F5F7FA] px-5 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24">

            <div class="mx-auto max-w-7xl">

                <div class="text-center">

                    <p
                        class="text-xs font-semibold uppercase tracking-[0.2em] text-[#005DAA] sm:text-sm sm:tracking-[0.25em]">
                        Our Library
                    </p>


                    <h2
                        class="mt-3 text-3xl font-bold text-[#102A43] sm:text-4xl">
                        Everything in one place
                    </h2>


                    <p
                        class="mx-auto mt-4 max-w-2xl text-sm leading-6 text-slate-600 sm:mt-5 sm:text-base sm:leading-7">
                        Berbagai fitur untuk membantu pengelolaan
                        perpustakaan secara lebih mudah dan terstruktur.
                    </p>

                </div>


                {{-- Feature Cards --}}
                <div
                    class="mt-10 grid gap-5 sm:mt-12 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">


                    {{-- Books --}}
                    <div class="library-card">

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-2xl sm:h-14 sm:w-14">
                            📚
                        </div>


                        <h3
                            class="mt-5 text-lg font-bold text-[#102A43] sm:mt-6 sm:text-xl">
                            Koleksi Buku
                        </h3>


                        <p
                            class="mt-2 text-sm leading-6 text-slate-600 sm:mt-3 sm:text-base sm:leading-7">
                            Kelola informasi buku dan berbagai eksemplar
                            yang tersedia di perpustakaan.
                        </p>

                    </div>


                    {{-- Users --}}
                    <div class="library-card">

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-2xl sm:h-14 sm:w-14">
                            👥
                        </div>


                        <h3
                            class="mt-5 text-lg font-bold text-[#102A43] sm:mt-6 sm:text-xl">
                            Pengguna
                        </h3>


                        <p
                            class="mt-2 text-sm leading-6 text-slate-600 sm:mt-3 sm:text-base sm:leading-7">
                            Pengelolaan pengguna berdasarkan peran
                            Admin, Librarian, dan Member.
                        </p>

                    </div>


                    {{-- Loans --}}
                    <div class="library-card md:col-span-2 lg:col-span-1">

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-2xl sm:h-14 sm:w-14">
                            🔄
                        </div>


                        <h3
                            class="mt-5 text-lg font-bold text-[#102A43] sm:mt-6 sm:text-xl">
                            Peminjaman
                        </h3>


                        <p
                            class="mt-2 text-sm leading-6 text-slate-600 sm:mt-3 sm:text-base sm:leading-7">
                            Kelola proses peminjaman, pengembalian,
                            status buku, hingga keterlambatan.
                        </p>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
            CTA
        ====================================================== --}}
        <section
            class="relative overflow-hidden bg-[#005DAA] px-5 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24">

            <div
                class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-[#E31E24]/30 blur-3xl sm:h-72 sm:w-72">
            </div>


            <div
                class="absolute -bottom-20 -left-20 h-56 w-56 rounded-full bg-[#00A651]/30 blur-3xl sm:h-72 sm:w-72">
            </div>


            <div
                class="relative mx-auto max-w-3xl text-center text-white">

                <h2
                    class="text-3xl font-bold sm:text-4xl md:text-5xl">
                    Ready to explore?
                </h2>


                <p
                    class="mt-4 text-base leading-7 text-white/80 sm:mt-5 sm:text-lg sm:leading-8">
                    Masuk ke PAG Library untuk mengakses sistem
                    perpustakaan.
                </p>


                <button
                    type="button"
                    class="login-trigger mt-7 inline-flex rounded-full bg-[#E31E24] px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-[#c91820] hover:shadow-xl sm:mt-8 sm:px-8 sm:py-4 sm:text-base">
                    Login / Masuk
                </button>

            </div>

        </section>


        {{-- =====================================================
            FOOTER
        ====================================================== --}}
        <footer
            class="bg-[#102A43] px-5 py-8 text-white sm:px-6 sm:py-10 lg:px-8">

            <div
                class="mx-auto flex max-w-7xl flex-col items-center gap-4 text-center sm:flex-row sm:justify-between sm:text-left">

                <div>

                    <h3 class="font-bold">
                        PAG Library
                    </h3>

                    <p class="mt-1 text-xs text-white/60 sm:text-sm">
                        Library Management System
                    </p>

                </div>


                <p class="text-xs text-white/60 sm:text-sm">
                    © {{ date('Y') }} PAG Library
                </p>

            </div>

        </footer>

    </main>

    {{-- =====================================================
        LOGIN MODAL
        Catatan: styling sepenuhnya dikontrol oleh #login-modal
        dan #login-panel di landing.css (Separation of Concerns).
        Class "hidden" di sini hanya menentukan state awal.
    ====================================================== --}}
    <div id="login-modal" class="hidden">

        <div id="login-panel">

            {{-- Header --}}
            <div class="mb-6 text-center">

                {{-- Logo --}}
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center overflow-hidden rounded-xl bg-[#005DAA]">

                    <img
                        src="{{ asset('images/landing/logo-pertamina.png') }}"
                        alt="PAG Library"
                        class="h-full w-full object-contain p-2">

                </div>


                <h2 class="text-2xl font-bold text-[#102A43]">
                    Login
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Masuk ke PAG Library
                </p>

            </div>


            {{-- Form Login --}}
            <form
                method="POST"
                action="{{ route('login.process') }}"
                class="space-y-5">

                @csrf


                {{-- Email --}}
                <div>

                    <label
                        for="email"
                        class="mb-2 block text-sm font-semibold text-[#102A43]">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="Masukkan email"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-[#005DAA] focus:ring-2 focus:ring-[#005DAA]/20">

                </div>


                {{-- Password --}}
                <div>

                    <label
                        for="password"
                        class="mb-2 block text-sm font-semibold text-[#102A43]">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-[#005DAA] focus:ring-2 focus:ring-[#005DAA]/20">

                </div>


                {{-- Error --}}
                @if ($errors->any())

                    <div
                        class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-600">

                        {{ $errors->first() }}

                    </div>

                @endif


                {{-- Login Button --}}
                <button
                    type="submit"
                    class="w-full rounded-xl bg-[#E31E24] px-5 py-3.5 text-sm font-semibold text-white shadow-md transition hover:bg-[#c91820] hover:shadow-lg">

                    Login / Masuk

                </button>

            </form>


            {{-- Close --}}
            <button
                id="close-login"
                type="button"
                class="mt-5 w-full text-center text-sm font-medium text-slate-500 transition hover:text-[#E31E24]">

                Kembali ke Home

            </button>

        </div>

    </div>


</body>

</html>