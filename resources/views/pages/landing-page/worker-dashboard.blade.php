<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">

    <title>Informasi Buku - PAG Library</title>

    {{-- Theme --}}
    <script>
        (() => {
            const savedTheme = localStorage.getItem('theme');

            const prefersDark = window.matchMedia(
                '(prefers-color-scheme: dark)'
            ).matches;

            if (
                savedTheme === 'dark' ||
                (!savedTheme && prefersDark)
            ) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite([
        'resources/css/app.css',
        'resources/css/background-slider.css',
        'resources/js/app.js',
        'resources/js/background-slider.js'
    ])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 transition-colors duration-200 dark:bg-gray-950 dark:text-white">

    <x-background-slider />

    {{-- Overlay --}}
    <div
        class="pointer-events-none fixed inset-0 z-[1] bg-white/90 transition-colors duration-200 dark:bg-gray-950/90"
    ></div>

    @php
        $locations = $books
            ->map(fn ($book) => $book->location?->location_name)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $publishers = $books
            ->pluck('publisher')
            ->filter()
            ->unique()
            ->sort()
            ->values();
    @endphp

    <div class="relative z-10 min-h-screen">

        {{-- Header --}}
        <header
            class="sticky top-0 z-50 border-b border-gray-200 bg-white/95 backdrop-blur-xl transition-colors duration-200 dark:border-gray-800 dark:bg-gray-950/95"
        >
            <div
                class="mx-auto flex w-full max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-6 sm:py-4 lg:px-8"
            >

                {{-- Brand --}}
                <a
                    href="{{ route('worker.dashboard') }}"
                    class="flex min-w-0 items-center gap-2.5 sm:gap-3"
                >
                    <img
                        src="{{ asset('images/landing/logo-pertamina.png') }}"
                        alt="Logo Pertamina"
                        class="h-8 w-auto shrink-0 sm:h-10"
                    >

                    <div class="min-w-0">
                        <p
                            class="truncate text-xs font-semibold tracking-wide text-gray-900 dark:text-white sm:text-sm"
                        >
                            PERTA ARUN GAS
                        </p>

                        <p
                            class="hidden truncate text-[10px] tracking-wider text-gray-500 dark:text-gray-400 sm:block sm:text-[11px]"
                        >
                            LIBRARY MANAGEMENT SYSTEM
                        </p>
                    </div>
                </a>

                {{-- Actions --}}
                <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">

                    {{-- User Desktop --}}
                    <div class="mr-1 hidden items-center gap-2.5 lg:flex">

                        {{-- Header Avatar --}}
                        <div
                            class="relative h-9 w-9 shrink-0 overflow-hidden rounded-full border border-gray-200 bg-gray-100 dark:border-gray-700 dark:bg-gray-800"
                        >
                            <div
                                id="header-avatar-fallback"
                                class="absolute inset-0 flex items-center justify-center text-xs font-bold text-brand-600 dark:text-brand-400"
                            >
                                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                            </div>

                            <img
                                src="{{ route('worker.profile-photo') }}"
                                alt="Foto {{ $user->name }}"
                                class="relative z-10 h-full w-full object-cover"
                                onerror="
                                    this.style.display='none';
                                "
                            >
                        </div>

                        <div class="min-w-0 text-right">
                            <p
                                class="max-w-[160px] truncate text-sm font-semibold text-gray-800 dark:text-white"
                            >
                                {{ $user->name }}
                            </p>

                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Pekerja
                            </p>
                        </div>
                    </div>

                    {{-- Theme --}}
                    <button
                        type="button"
                        id="theme-toggle"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 sm:h-10 sm:w-10"
                        aria-label="Ganti tema"
                        title="Ganti tema"
                    >
                        <svg
                            id="theme-icon-dark"
                            class="hidden h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"
                            />
                        </svg>

                        <svg
                            id="theme-icon-light"
                            class="hidden h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m12.728 0-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                            />
                        </svg>
                    </button>

                    {{-- Change Password --}}
                    <a
                        href="{{ route('worker.password.edit') }}"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 sm:h-10 sm:w-auto sm:gap-2 sm:px-3"
                        title="Ubah Password"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H3v-4l5.257-5.257A6 6 0 1121 9z"
                            />
                        </svg>

                        <span class="hidden text-sm font-medium sm:inline">
                            Ubah Password
                        </span>
                    </a>

                    {{-- Logout --}}
                    <form
                        action="{{ route('logout') }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 sm:h-10 sm:w-auto sm:gap-2 sm:px-3"
                            title="Keluar"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"
                                />
                            </svg>

                            <span class="hidden text-sm font-medium sm:inline">
                                Keluar
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Main --}}
        <main
            class="mx-auto w-full max-w-7xl px-3 py-5 sm:px-6 sm:py-7 lg:px-8 lg:py-8"
        >

            {{-- Success --}}
            @if (session('success'))
                <div
                    class="mb-4 rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-600 dark:border-success-500/20 dark:bg-success-500/10 dark:text-success-400 sm:mb-6"
                >
                    {{ session('success') }}
                </div>
            @endif

            {{-- User Profile --}}
            <section
                class="mb-4 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-colors duration-200 dark:border-gray-800 dark:bg-white/[0.03] sm:mb-6"
            >
                <div class="p-4 sm:p-6">
                    <div
                        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                    >

                        <div class="flex min-w-0 items-center gap-3 sm:gap-4">

                            {{-- Profile Photo --}}
                            <div
                                class="relative h-14 w-14 shrink-0 overflow-hidden rounded-full border-2 border-white bg-gray-100 shadow-sm ring-1 ring-gray-200 dark:border-gray-900 dark:bg-gray-800 dark:ring-gray-700 sm:h-16 sm:w-16"
                            >
                                {{-- Fallback --}}
                                <div
                                    id="worker-avatar-fallback"
                                    class="absolute inset-0 flex items-center justify-center bg-brand-50 text-lg font-bold text-brand-600 dark:bg-brand-500/15 dark:text-brand-400"
                                >
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                </div>

                                {{-- Photo --}}
                                <img
                                    src="{{ route('worker.profile-photo') }}"
                                    alt="Foto profil {{ $user->name }}"
                                    class="relative z-10 h-full w-full object-cover"
                                    loading="eager"
                                    onerror="
                                        this.style.display='none';
                                    "
                                >
                            </div>

                            {{-- Identity --}}
                            <div class="min-w-0">
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400 sm:text-sm"
                                >
                                    Selamat datang,
                                </p>

                                <h1
                                    class="truncate text-lg font-bold text-gray-800 dark:text-white sm:text-2xl"
                                >
                                    {{ $user->name }}
                                </h1>

                                <div
                                    class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1"
                                >
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 sm:text-sm"
                                    >
                                        No. Pekerja:
                                        <span
                                            class="font-medium text-gray-700 dark:text-gray-300"
                                        >
                                            {{ $user->nopek }}
                                        </span>
                                    </p>

                                    <span
                                        class="hidden h-1 w-1 rounded-full bg-gray-300 dark:bg-gray-600 sm:block"
                                    ></span>

                                    <span
                                        class="inline-flex rounded-full bg-success-50 px-2 py-0.5 text-[10px] font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400 sm:text-xs"
                                    >
                                        Aktif
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div
                            class="min-w-0 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900 sm:max-w-xs"
                        >
                            <p
                                class="text-[10px] font-medium uppercase tracking-wider text-gray-400 sm:text-xs"
                            >
                                Email Login
                            </p>

                            <p
                                class="mt-1 truncate text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm"
                            >
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Notification --}}
            <section
                class="mb-4 rounded-xl border border-blue-200 bg-blue-50 p-4 transition-colors duration-200 dark:border-blue-500/20 dark:bg-blue-500/10 sm:mb-6"
            >
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400 sm:h-9 sm:w-9"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 3a9 9 0 110 18 9 9 0 010-18z"
                            />
                        </svg>
                    </div>

                    <div>
                        <p
                            class="text-sm font-semibold text-blue-800 dark:text-blue-300"
                        >
                            Informasi PAG Library
                        </p>

                        <p
                            class="mt-1 text-xs leading-5 text-blue-700 dark:text-blue-400 sm:text-sm sm:leading-6"
                        >
                            Gunakan halaman ini untuk melihat informasi
                            koleksi dan ketersediaan buku di PAG Library.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Books --}}
            <section
                class="relative isolate w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-colors duration-200 dark:border-gray-800 dark:bg-white/[0.03]"
            >

                {{-- Header --}}
                <div class="p-4 sm:p-5 lg:px-6">
                    <div
                        class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between"
                    >
                        <div>
                            <h2
                                class="text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg"
                            >
                                Data Buku
                            </h2>

                            <p
                                class="mt-1 text-xs text-gray-500 dark:text-gray-400 sm:text-sm"
                            >
                                Cari dan filter koleksi buku perpustakaan
                            </p>
                        </div>

                        <span
                            class="inline-flex w-fit items-center rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-medium text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            {{ $books->count() }} Judul Buku
                        </span>
                    </div>

                    {{-- Search & Filter --}}
                    <div
                        class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5"
                    >

                        {{-- Search --}}
                        <div
                            class="relative sm:col-span-2 xl:col-span-2"
                        >
                            <span
                                class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2"
                            >
                                <svg
                                    class="h-4 w-4 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"
                                    />
                                </svg>
                            </span>

                            <input
                                type="text"
                                id="book-search"
                                placeholder="Cari judul, penulis, Book No..."
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-9 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                            >

                            <button
                                type="button"
                                id="clear-book-search"
                                class="absolute right-3 top-1/2 hidden -translate-y-1/2 text-gray-400 transition hover:text-gray-600 dark:hover:text-gray-200"
                                aria-label="Hapus pencarian"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18 18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>

                        {{-- Status --}}
                        <div>
                            <select
                                id="filter-status"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                            >
                                <option value="">
                                    Semua Status
                                </option>

                                <option value="available">
                                    Tersedia
                                </option>

                                <option value="borrowed">
                                    Dipinjam
                                </option>
                            </select>
                        </div>

                        {{-- Location --}}
                        <div>
                            <select
                                id="filter-location"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                            >
                                <option value="">
                                    Semua Lokasi
                                </option>

                                @foreach ($locations as $location)
                                    <option
                                        value="{{ mb_strtolower($location) }}"
                                    >
                                        {{ $location }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Publisher --}}
                        <div>
                            <select
                                id="filter-publisher"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none transition focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                            >
                                <option value="">
                                    Semua Penerbit
                                </option>

                                @foreach ($publishers as $publisher)
                                    <option
                                        value="{{ mb_strtolower($publisher) }}"
                                    >
                                        {{ $publisher }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filter Bottom --}}
                    <div
                        class="mt-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <p
                            id="filter-description"
                            class="text-xs text-gray-400"
                        >
                            Gunakan pencarian atau filter untuk menemukan buku.
                        </p>

                        <button
                            type="button"
                            id="reset-book-filter"
                            class="hidden w-full items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 sm:w-auto"
                        >
                            <svg
                                class="h-3.5 w-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4v5h.582M20 20v-5h-.581M5.64 9A7 7 0 0118.36 6M18.36 15A7 7 0 015.64 18"
                                />
                            </svg>

                            Reset Filter
                        </button>
                    </div>
                </div>

                {{-- Mobile Cards --}}
                <div
                    class="border-t border-gray-100 p-3 dark:border-gray-800 lg:hidden"
                >
                    <div class="space-y-3">

                        @forelse ($books as $book)

                            @php
                                $authors = $book->authors
                                    ->pluck('author_name')
                                    ->filter()
                                    ->implode(', ');

                                $totalCopies =
                                    $book->copies->count();

                                $availableCopies =
                                    $book->copies
                                        ->where('status', 'Tersedia')
                                        ->count();

                                $locationName =
                                    $book->location?->location_name
                                    ?? '-';

                                $bookStatus =
                                    $availableCopies > 0
                                        ? 'available'
                                        : 'borrowed';

                                $searchValue = mb_strtolower(
                                    implode(' ', [
                                        $book->book_code ?? '',
                                        $book->cat_no ?? '',
                                        $book->title ?? '',
                                        $authors,
                                        $book->publisher ?? '',
                                        $locationName,
                                    ])
                                );
                            @endphp

                            <article
                                class="book-mobile-card overflow-hidden rounded-xl border border-gray-200 bg-white transition hover:border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-600"
                                data-search="{{ $searchValue }}"
                                data-status="{{ $bookStatus }}"
                                data-location="{{ mb_strtolower($locationName) }}"
                                data-publisher="{{ mb_strtolower($book->publisher ?? '') }}"
                            >
                                <div class="p-4">

                                    <div
                                        class="mb-3 flex items-start justify-between gap-3"
                                    >
                                        <div class="min-w-0 flex-1">
                                            <p
                                                class="text-[10px] font-medium uppercase tracking-wider text-gray-400"
                                            >
                                                Book No.
                                            </p>

                                            <p
                                                class="mt-0.5 truncate text-xs font-semibold text-gray-600 dark:text-gray-300"
                                            >
                                                {{ $book->book_code ?? '-' }}
                                            </p>
                                        </div>

                                        @if ($availableCopies > 0)
                                            <span
                                                class="shrink-0 rounded-full bg-success-50 px-2.5 py-1 text-[11px] font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400"
                                            >
                                                Tersedia
                                            </span>
                                        @else
                                            <span
                                                class="shrink-0 rounded-full bg-warning-50 px-2.5 py-1 text-[11px] font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-400"
                                            >
                                                Dipinjam
                                            </span>
                                        @endif
                                    </div>

                                    <h3
                                        class="text-sm font-semibold leading-5 text-gray-800 dark:text-white"
                                    >
                                        {{ $book->title }}
                                    </h3>

                                    <p
                                        class="mt-1.5 line-clamp-2 text-xs leading-5 text-gray-500 dark:text-gray-400"
                                    >
                                        {{ $authors !== '' ? $authors : 'Penulis tidak tersedia' }}
                                    </p>

                                    <div
                                        class="mt-4 grid grid-cols-2 gap-x-4 gap-y-3"
                                    >
                                        <div class="min-w-0">
                                            <p
                                                class="text-[10px] uppercase tracking-wide text-gray-400"
                                            >
                                                Cat. No.
                                            </p>

                                            <p
                                                class="mt-0.5 truncate text-xs font-medium text-gray-600 dark:text-gray-300"
                                            >
                                                {{ $book->cat_no ?? '-' }}
                                            </p>
                                        </div>

                                        <div class="min-w-0">
                                            <p
                                                class="text-[10px] uppercase tracking-wide text-gray-400"
                                            >
                                                Lokasi
                                            </p>

                                            <p
                                                class="mt-0.5 truncate text-xs font-medium text-gray-600 dark:text-gray-300"
                                            >
                                                {{ $locationName }}
                                            </p>
                                        </div>

                                        <div class="min-w-0">
                                            <p
                                                class="text-[10px] uppercase tracking-wide text-gray-400"
                                            >
                                                Penerbit
                                            </p>

                                            <p
                                                class="mt-0.5 truncate text-xs font-medium text-gray-600 dark:text-gray-300"
                                            >
                                                {{ $book->publisher ?? '-' }}
                                            </p>
                                        </div>

                                        <div>
                                            <p
                                                class="text-[10px] uppercase tracking-wide text-gray-400"
                                            >
                                                Eksemplar
                                            </p>

                                            <p
                                                class="mt-0.5 text-xs font-medium text-gray-600 dark:text-gray-300"
                                            >
                                                {{ $availableCopies }}
                                                tersedia dari
                                                {{ $totalCopies }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>

                        @empty
                            <div class="py-10 text-center">
                                <p
                                    class="text-sm font-medium text-gray-600 dark:text-gray-300"
                                >
                                    Belum ada data buku.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Desktop Table --}}
                <div
                    class="hidden w-full overflow-x-auto lg:block"
                >
                    <table class="w-full min-w-[1120px] table-fixed">
                        <thead>
                            <tr
                                class="border-y border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900"
                            >
                                <th
                                    class="w-[55px] px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400"
                                >
                                    NO.
                                </th>

                                <th
                                    class="w-[135px] px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400"
                                >
                                    BOOK NO.
                                </th>

                                <th
                                    class="w-[120px] px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400"
                                >
                                    CAT. NO.
                                </th>

                                <th
                                    class="w-[125px] px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400"
                                >
                                    LOCATION
                                </th>

                                <th
                                    class="w-[250px] px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400"
                                >
                                    TITLE
                                </th>

                                <th
                                    class="w-[200px] px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400"
                                >
                                    AUTHOR
                                </th>

                                <th
                                    class="w-[160px] px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400"
                                >
                                    PUBLISHER
                                </th>

                                <th
                                    class="w-[70px] px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400"
                                >
                                    QTY
                                </th>

                                <th
                                    class="w-[120px] px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400"
                                >
                                    STATUS
                                </th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-gray-100 dark:divide-gray-800"
                        >
                            @forelse ($books as $book)

                                @php
                                    $authors = $book->authors
                                        ->pluck('author_name')
                                        ->filter()
                                        ->implode(', ');

                                    $totalCopies =
                                        $book->copies->count();

                                    $availableCopies =
                                        $book->copies
                                            ->where('status', 'Tersedia')
                                            ->count();

                                    $locationName =
                                        $book->location?->location_name
                                        ?? '-';

                                    $bookStatus =
                                        $availableCopies > 0
                                            ? 'available'
                                            : 'borrowed';

                                    $searchValue = mb_strtolower(
                                        implode(' ', [
                                            $book->book_code ?? '',
                                            $book->cat_no ?? '',
                                            $book->title ?? '',
                                            $authors,
                                            $book->publisher ?? '',
                                            $locationName,
                                        ])
                                    );
                                @endphp

                                <tr
                                    class="book-desktop-row bg-white transition hover:bg-gray-50 dark:bg-transparent dark:hover:bg-white/[0.03]"
                                    data-search="{{ $searchValue }}"
                                    data-status="{{ $bookStatus }}"
                                    data-location="{{ mb_strtolower($locationName) }}"
                                    data-publisher="{{ mb_strtolower($book->publisher ?? '') }}"
                                >
                                    <td
                                        class="px-3 py-3 text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-3 py-3">
                                        <p
                                            class="truncate text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >
                                            {{ $book->book_code ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-3 py-3">
                                        <p
                                            class="truncate text-sm text-gray-600 dark:text-gray-400"
                                        >
                                            {{ $book->cat_no ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-3 py-3">
                                        <p
                                            class="truncate text-sm text-gray-600 dark:text-gray-400"
                                            title="{{ $locationName }}"
                                        >
                                            {{ $locationName }}
                                        </p>
                                    </td>

                                    <td class="px-3 py-3">
                                        <p
                                            class="truncate text-sm font-medium text-gray-800 dark:text-white/90"
                                            title="{{ $book->title }}"
                                        >
                                            {{ $book->title }}
                                        </p>
                                    </td>

                                    <td class="px-3 py-3">
                                        <p
                                            class="truncate text-sm text-gray-600 dark:text-gray-400"
                                            title="{{ $authors }}"
                                        >
                                            {{ $authors !== '' ? $authors : '-' }}
                                        </p>
                                    </td>

                                    <td class="px-3 py-3">
                                        <p
                                            class="truncate text-sm text-gray-600 dark:text-gray-400"
                                            title="{{ $book->publisher ?? '-' }}"
                                        >
                                            {{ $book->publisher ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-3 py-3 text-center">
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >
                                            {{ $totalCopies }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-3 text-center">
                                        @if ($availableCopies > 0)
                                            <span
                                                class="inline-flex rounded-full bg-success-50 px-2.5 py-1 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400"
                                            >
                                                Tersedia
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-warning-50 px-2.5 py-1 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-400"
                                            >
                                                Dipinjam
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td
                                        colspan="9"
                                        class="px-4 py-12 text-center"
                                    >
                                        <p
                                            class="text-sm font-medium text-gray-600 dark:text-gray-300"
                                        >
                                            Belum ada data buku.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Empty --}}
                <div
                    id="book-search-empty"
                    class="hidden border-t border-gray-100 px-5 py-12 text-center dark:border-gray-800"
                >
                    <div
                        class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                            />
                        </svg>
                    </div>

                    <p
                        class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Data buku tidak ditemukan
                    </p>

                    <p
                        class="mt-1 text-xs text-gray-400"
                    >
                        Coba ubah pencarian atau filter yang digunakan.
                    </p>
                </div>

                {{-- Footer --}}
                @if ($books->isNotEmpty())
                    <div
                        class="flex flex-col gap-1 border-t border-gray-100 bg-gray-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-5 dark:border-gray-800 dark:bg-gray-900/50"
                    >
                        <p
                            id="book-result-count"
                            class="text-xs text-gray-500 dark:text-gray-400"
                        >
                            Menampilkan {{ $books->count() }} data buku
                        </p>

                        <p
                            class="hidden text-xs text-gray-400 dark:text-gray-500 sm:block"
                        >
                            Informasi koleksi PAG Library
                        </p>
                    </div>
                @endif
            </section>

            {{-- Footer --}}
            <footer class="py-6 text-center sm:py-8">
                <p
                    class="text-[11px] text-gray-400 dark:text-gray-500 sm:text-xs"
                >
                    © {{ date('Y') }} PAG Library · Perta Arun Gas
                </p>
            </footer>
        </main>
    </div>

    {{-- Search & Filter --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput =
                document.getElementById('book-search');

            const clearSearchButton =
                document.getElementById('clear-book-search');

            const statusFilter =
                document.getElementById('filter-status');

            const locationFilter =
                document.getElementById('filter-location');

            const publisherFilter =
                document.getElementById('filter-publisher');

            const resetButton =
                document.getElementById('reset-book-filter');

            const filterDescription =
                document.getElementById('filter-description');

            const desktopRows =
                Array.from(
                    document.querySelectorAll(
                        '.book-desktop-row'
                    )
                );

            const mobileCards =
                Array.from(
                    document.querySelectorAll(
                        '.book-mobile-card'
                    )
                );

            const emptyState =
                document.getElementById('book-search-empty');

            const resultCount =
                document.getElementById('book-result-count');

            const matchItem = (
                item,
                keyword,
                status,
                location,
                publisher
            ) => {
                const itemSearch =
                    item.dataset.search || '';

                const itemStatus =
                    item.dataset.status || '';

                const itemLocation =
                    item.dataset.location || '';

                const itemPublisher =
                    item.dataset.publisher || '';

                const matchesSearch =
                    keyword === '' ||
                    itemSearch.includes(keyword);

                const matchesStatus =
                    status === '' ||
                    itemStatus === status;

                const matchesLocation =
                    location === '' ||
                    itemLocation === location;

                const matchesPublisher =
                    publisher === '' ||
                    itemPublisher === publisher;

                return (
                    matchesSearch &&
                    matchesStatus &&
                    matchesLocation &&
                    matchesPublisher
                );
            };

            const filterBooks = () => {
                const keyword =
                    searchInput
                        ? searchInput.value
                            .trim()
                            .toLowerCase()
                        : '';

                const status =
                    statusFilter
                        ? statusFilter.value
                        : '';

                const location =
                    locationFilter
                        ? locationFilter.value
                        : '';

                const publisher =
                    publisherFilter
                        ? publisherFilter.value
                        : '';

                let visible = 0;

                desktopRows.forEach((row) => {
                    const match = matchItem(
                        row,
                        keyword,
                        status,
                        location,
                        publisher
                    );

                    row.classList.toggle(
                        'hidden',
                        !match
                    );

                    if (match) {
                        visible++;
                    }
                });

                mobileCards.forEach((card) => {
                    const match = matchItem(
                        card,
                        keyword,
                        status,
                        location,
                        publisher
                    );

                    card.classList.toggle(
                        'hidden',
                        !match
                    );
                });

                const hasFilter =
                    keyword !== '' ||
                    status !== '' ||
                    location !== '' ||
                    publisher !== '';

                if (clearSearchButton) {
                    clearSearchButton.classList.toggle(
                        'hidden',
                        keyword === ''
                    );
                }

                if (resetButton) {
                    resetButton.classList.toggle(
                        'hidden',
                        !hasFilter
                    );

                    resetButton.classList.toggle(
                        'inline-flex',
                        hasFilter
                    );
                }

                if (emptyState) {
                    emptyState.classList.toggle(
                        'hidden',
                        visible !== 0
                    );
                }

                if (resultCount) {
                    resultCount.textContent =
                        `Menampilkan ${visible} dari ${desktopRows.length} data buku`;
                }

                if (filterDescription) {
                    if (hasFilter) {
                        filterDescription.textContent =
                            `${visible} buku sesuai dengan pencarian dan filter.`;
                    } else {
                        filterDescription.textContent =
                            'Gunakan pencarian atau filter untuk menemukan buku.';
                    }
                }
            };

            if (searchInput) {
                searchInput.addEventListener(
                    'input',
                    filterBooks
                );
            }

            if (statusFilter) {
                statusFilter.addEventListener(
                    'change',
                    filterBooks
                );
            }

            if (locationFilter) {
                locationFilter.addEventListener(
                    'change',
                    filterBooks
                );
            }

            if (publisherFilter) {
                publisherFilter.addEventListener(
                    'change',
                    filterBooks
                );
            }

            if (clearSearchButton) {
                clearSearchButton.addEventListener(
                    'click',
                    () => {
                        searchInput.value = '';
                        searchInput.focus();
                        filterBooks();
                    }
                );
            }

            if (resetButton) {
                resetButton.addEventListener(
                    'click',
                    () => {
                        if (searchInput) {
                            searchInput.value = '';
                        }

                        if (statusFilter) {
                            statusFilter.value = '';
                        }

                        if (locationFilter) {
                            locationFilter.value = '';
                        }

                        if (publisherFilter) {
                            publisherFilter.value = '';
                        }

                        filterBooks();
                    }
                );
            }

            filterBooks();
        });
    </script>

    {{-- Theme --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle =
                document.getElementById('theme-toggle');

            const darkIcon =
                document.getElementById('theme-icon-dark');

            const lightIcon =
                document.getElementById('theme-icon-light');

            const updateTheme = () => {
                const isDark =
                    document.documentElement
                        .classList
                        .contains('dark');

                if (darkIcon) {
                    darkIcon.classList.toggle(
                        'hidden',
                        isDark
                    );
                }

                if (lightIcon) {
                    lightIcon.classList.toggle(
                        'hidden',
                        !isDark
                    );
                }

                const metaTheme =
                    document.querySelector(
                        'meta[name="theme-color"]'
                    );

                if (metaTheme) {
                    metaTheme.setAttribute(
                        'content',
                        isDark
                            ? '#030712'
                            : '#ffffff'
                    );
                }
            };

            updateTheme();

            if (!toggle) {
                return;
            }

            toggle.addEventListener('click', () => {
                const isDark =
                    document.documentElement
                        .classList
                        .contains('dark');

                if (isDark) {
                    document.documentElement
                        .classList
                        .remove('dark');

                    localStorage.setItem(
                        'theme',
                        'light'
                    );
                } else {
                    document.documentElement
                        .classList
                        .add('dark');

                    localStorage.setItem(
                        'theme',
                        'dark'
                    );
                }

                updateTheme();
            });
        });
    </script>

</body>

</html>