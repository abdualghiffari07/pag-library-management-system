<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - PAG Library</title>

    @vite(['resources/css/dashboard-admin.css', 'resources/js/dashboard-admin.js'])
</head>

<body>

<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="brand">
            <div class="brand-title">
                PAG LIBRARY
            </div>

            <div class="brand-subtitle">
                Sistem Manajemen Perpustakaan
            </div>
        </div>

        <nav class="menu">

            <div class="menu-title">
                Utama
            </div>

            <a href="{{ route('dashboard') }}"
               class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="menu-icon">⌂</span>
                Dashboard
            </a>


            <div class="menu-title">
                Koleksi
            </div>

            <a href="{{ route('books.index') }}"
               class="menu-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
                <span class="menu-icon">▣</span>
                Buku
            </a>

            <a href="{{ route('book-copies.index') }}"
               class="menu-item {{ request()->routeIs('book-copies.*') ? 'active' : '' }}">
                <span class="menu-icon">▤</span>
                Eksemplar Buku
            </a>

            <a href="{{ route('authors.index') }}"
               class="menu-item {{ request()->routeIs('authors.*') ? 'active' : '' }}">
                <span class="menu-icon">♙</span>
                Penulis
            </a>

            <a href="{{ route('categories.index') }}"
               class="menu-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <span class="menu-icon">▦</span>
                Kategori
            </a>

            <a href="{{ route('locations.index') }}"
               class="menu-item {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                <span class="menu-icon">⌖</span>
                Lokasi
            </a>


            <div class="menu-title">
                Transaksi
            </div>

            <a href="{{ route('loans.index') }}"
               class="menu-item {{ request()->routeIs('loans.*') ? 'active' : '' }}">
                <span class="menu-icon">↔</span>
                Peminjaman
            </a>


            <div class="menu-title">
                Laporan
            </div>

            {{-- TODO: route belum tersedia, ganti <div> ke <a> setelah halaman dibuat --}}
            <div class="menu-item">
                <span class="menu-icon">▤</span>
                Laporan Koleksi
            </div>

            {{-- TODO: route belum tersedia, ganti <div> ke <a> setelah halaman dibuat --}}
            <div class="menu-item">
                <span class="menu-icon">▥</span>
                Laporan Peminjaman
            </div>


            <div class="menu-title">
                Arsip
            </div>

            {{-- TODO: route belum tersedia, ganti <div> ke <a> setelah halaman dibuat --}}
            <div class="menu-item">
                <span class="menu-icon">▧</span>
                File Buku
            </div>


            <div class="menu-title">
                Sistem
            </div>

            {{-- TODO: route belum tersedia, ganti <div> ke <a> setelah halaman dibuat --}}
            <div class="menu-item">
                <span class="menu-icon">♙</span>
                Pengguna
            </div>

        </nav>

        <div class="logout-wrapper">

            <form method="POST"
                  action="{{ route('logout') }}">

                @csrf

                <button type="submit"
                        class="logout-button">
                    ⇥ &nbsp; Keluar
                </button>

            </form>

        </div>

    </aside>

    <div id="sidebar-overlay" class="sidebar-overlay"></div>


    <!-- MAIN -->
    <main class="main">

        <!-- HEADER -->
    <header class="header">

        <div class="header-left">

            <button
                type="button"
                id="sidebar-toggle"
                class="sidebar-toggle"
                aria-label="Buka menu">
                ☰
            </button>

            <div class="header-title">
                Dashboard Administrator
            </div>

        </div>


        <div class="admin-profile">

            <div class="admin-info">

                <div class="admin-name">
                    {{ $user->name }}
                </div>

                <div class="admin-role">
                    Administrator
                </div>

            </div>

            <div class="avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

        </div>

    </header>


        <!-- CONTENT -->
        <section class="content">

            <!-- WELCOME -->
            <div class="welcome">

                <h1>
                    Dashboard
                </h1>

                <p>
                    Selamat datang kembali,
                    <strong>{{ $user->name }}</strong>.
                    Berikut ringkasan perpustakaan PAG.
                </p>

            </div>


            <!-- PRIMARY STATISTICS -->
            <div class="stats-grid">

                <div class="stat-card">

                    <div class="stat-label">
                        Total Judul Buku
                    </div>

                    <div class="stat-value">
                        {{ $totalBooks }}
                    </div>

                    <div class="stat-description">
                        Jumlah seluruh judul buku
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-label">
                        Total Eksemplar
                    </div>

                    <div class="stat-value">
                        {{ $totalCopies }}
                    </div>

                    <div class="stat-description">
                        Jumlah seluruh eksemplar
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-label">
                        Sedang Dipinjam
                    </div>

                    <div class="stat-value">
                        {{ $borrowedCopies }}
                    </div>

                    <div class="stat-description">
                        Eksemplar yang sedang dipinjam
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-label">
                        Terlambat
                    </div>

                    <div class="stat-value">
                        {{ $overdueLoans }}
                    </div>

                    <div class="stat-description">
                        Peminjaman yang melewati batas waktu
                    </div>

                </div>

            </div>


            <!-- SECONDARY STATISTICS -->
            <div class="secondary-grid">

                <div class="secondary-card">

                    <div class="secondary-label">
                        Eksemplar Tersedia
                    </div>

                    <div class="secondary-value">
                        {{ $availableCopies }}
                    </div>

                </div>


                <div class="secondary-card">

                    <div class="secondary-label">
                        Total Peminjaman
                    </div>

                    <div class="secondary-value">
                        {{ $totalLoans }}
                    </div>

                </div>


                <div class="secondary-card">

                    <div class="secondary-label">
                        Peminjaman Aktif
                    </div>

                    <div class="secondary-value">
                        {{ $activeLoans }}
                    </div>

                </div>

            </div>


            <!-- QUICK ACTIONS -->
            <h2 class="section-title">
                Akses Cepat
            </h2>

            <div class="quick-actions">

                <a href="{{ route('books.index') }}"
                   class="action">

                    <div class="action-title">
                        Kelola Buku
                    </div>

                    <div class="action-description">
                        Kelola data buku perpustakaan
                    </div>

                </a>


                <a href="{{ route('book-copies.index') }}"
                   class="action">

                    <div class="action-title">
                        Kelola Eksemplar
                    </div>

                    <div class="action-description">
                        Kelola eksemplar buku
                    </div>

                </a>


                <a href="{{ route('loans.index') }}"
                   class="action">

                    <div class="action-title">
                        Kelola Peminjaman
                    </div>

                    <div class="action-description">
                        Kelola transaksi peminjaman
                    </div>

                </a>


                {{-- TODO: route belum tersedia, ganti <div> ke <a> setelah halaman laporan dibuat --}}
                <div class="action">

                    <div class="action-title">
                        Laporan
                    </div>

                    <div class="action-description">
                        Lihat laporan perpustakaan
                    </div>

                </div>

            </div>

        </section>

    </main>

</div>

</body>

</html>