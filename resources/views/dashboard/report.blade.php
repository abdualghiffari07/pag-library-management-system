<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Laporan - PAG Library</title>

    @vite([
        'resources/css/dashboard-admin.css',
        'resources/js/dashboard-admin.js'
    ])

</head>


<body>

<div class="app">

    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    <aside class="sidebar" id="sidebar">

        <div class="brand">

            <div class="brand-mark">
                📚
            </div>

            <div class="brand-text">

                <div class="brand-title">
                    PAG <span>LIBRARY</span>
                </div>

                <div class="brand-subtitle">
                    Sistem Perpustakaan
                </div>

            </div>

        </div>


<nav class="menu">

    <div class="menu-section">
        ADMIN
    </div>


    {{-- DATA BUKU --}}

    <a href="{{ route('books.index') }}"
       class="menu-item {{ request()->routeIs('books.*') ? 'active' : '' }}">

        <span class="menu-icon">
            ▣
        </span>

        <span>
            Data Buku
        </span>

    </a>


    {{-- EKSEMPLAR BUKU --}}

    <a href="{{ route('book-copies.index') }}"
       class="menu-item {{ request()->routeIs('book-copies.*') ? 'active' : '' }}">

        <span class="menu-icon">
            ▤
        </span>

        <span>
            Eksemplar Buku
        </span>

    </a>


    {{-- DAFTAR PENGUNJUNG --}}

    <div class="menu-item menu-disabled">

        <span class="menu-icon">
            ♙
        </span>

        <span>
            Daftar Pengunjung
        </span>

    </div>


    {{-- LAPORAN --}}

    <a href="{{ route('reports.index') }}"
       class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">

        <span class="menu-icon">
            ▥
        </span>

        <span>
            Laporan
        </span>

    </a>

</nav>


        {{-- LOGOUT --}}

        <div class="logout-wrapper">

        <form method="POST"
            action="{{ route('logout') }}"
            onsubmit="return confirm('Apakah Anda yakin ingin keluar?');">

            @csrf

            <button type="submit"
                    class="logout-button">

                <span class="logout-icon">
                    ⇥
                </span>

                <span>
                    Keluar
                </span>

            </button>

        </form>

        </div>

    </aside>


    <div id="sidebar-overlay"
         class="sidebar-overlay">
    </div>


    {{-- =====================================================
         MAIN
    ====================================================== --}}

    <main class="main">


        {{-- HEADER --}}

        <header class="header">

            <div class="header-left">

                <button type="button"
                        id="sidebar-toggle"
                        class="sidebar-toggle">

                    ☰

                </button>


                <div>

                    <div class="header-title">
                        Laporan
                    </div>

                    <div class="header-line"></div>

                </div>

            </div>


            <div class="header-right">

                <div class="admin-profile">

                    <div class="avatar">

                        {{ strtoupper(substr($user->name, 0, 1)) }}

                    </div>


                    <div class="admin-info">

                        <div class="admin-name">
                            {{ $user->name }}
                        </div>

                        <div class="admin-role">
                            Administrator
                        </div>

                    </div>

                </div>

            </div>

        </header>


        {{-- =================================================
             CONTENT
        ================================================== --}}

        <section class="content">


            {{-- TITLE --}}

            <div class="welcome-card">

                <div class="welcome-content">

                    <div class="welcome-small">
                        PAG Library
                    </div>

                    <h1>
                        Laporan Perpustakaan
                    </h1>

                    <p>
                        Ringkasan dan statistik data
                        perpustakaan.
                    </p>

                </div>


                <div class="welcome-decoration">

                    <div class="book-decoration">
                        📊
                    </div>

                </div>

            </div>


            {{-- =================================================
                 STATISTICS
            ================================================== --}}

            <div class="stats-grid">


                {{-- TOTAL JUDUL --}}

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            📖
                        </div>

                    </div>

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


                {{-- TOTAL EKSEMPLAR --}}

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            ▣
                        </div>

                    </div>

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


                {{-- TERSEDIA --}}

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            ✓
                        </div>

                    </div>

                    <div class="stat-label">
                        Eksemplar Tersedia
                    </div>

                    <div class="stat-value">
                        {{ $availableCopies }}
                    </div>

                    <div class="stat-description">
                        Siap untuk dipinjam
                    </div>

                </div>


                {{-- DIPINJAM --}}

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            ↗
                        </div>

                    </div>

                    <div class="stat-label">
                        Sedang Dipinjam
                    </div>

                    <div class="stat-value">
                        {{ $borrowedCopies }}
                    </div>

                    <div class="stat-description">
                        Sedang dipinjam pekerja
                    </div>

                </div>


                {{-- TOTAL PEMINJAMAN --}}

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            ↔
                        </div>

                    </div>

                    <div class="stat-label">
                        Total Peminjaman
                    </div>

                    <div class="stat-value">
                        {{ $totalLoans }}
                    </div>

                    <div class="stat-description">
                        Seluruh transaksi
                    </div>

                </div>

            </div>


            {{-- =================================================
                 SECONDARY STATISTICS
            ================================================== --}}

            <div class="secondary-grid">


                <div class="secondary-card">

                    <div class="secondary-label">
                        Peminjaman Aktif
                    </div>

                    <div class="secondary-value">
                        {{ $activeLoans }}
                    </div>

                </div>


                <div class="secondary-card">

                    <div class="secondary-label">
                        Peminjaman Terlambat
                    </div>

                    <div class="secondary-value">
                        {{ $overdueLoans }}
                    </div>

                </div>


            </div>


            {{-- =================================================
                 REPORT SECTIONS
            ================================================== --}}

            <h2 class="section-title">
                Ringkasan Laporan
            </h2>


            <div class="quick-actions">


                <div class="action">

                    <div class="action-title">
                        Laporan Peminjaman
                    </div>

                    <div class="action-description">
                        Laporan transaksi peminjaman
                        perpustakaan per bulan.
                    </div>

                </div>


                <div class="action">

                    <div class="action-title">
                        Statistik Pengunjung
                    </div>

                    <div class="action-description">
                        Statistik kunjungan pekerja
                        ke perpustakaan.
                    </div>

                </div>


                <div class="action">

                    <div class="action-title">
                        Statistik Koleksi
                    </div>

                    <div class="action-description">
                        Statistik buku dan
                        eksemplar perpustakaan.
                    </div>

                </div>

            </div>

        </section>

    </main>

</div>

</body>

</html>