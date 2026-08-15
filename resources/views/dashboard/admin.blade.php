<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Admin - PAG Library</title>

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

        {{-- BRAND --}}
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


        {{-- NAVIGATION --}}
        <nav class="menu">

            <div class="menu-section">
                KOLEKSI
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


            <div class="menu-section">
                PENGUNJUNG
            </div>


            {{-- PENGUNJUNG --}}
            {{-- Route akan dibuat pada tahap berikutnya --}}
            <div class="menu-item menu-disabled">

                <span class="menu-icon">
                    ♙
                </span>

                <span>
                    Daftar Pengunjung
                </span>

            </div>


            <div class="menu-section">
                LAPORAN
            </div>


            {{-- LAPORAN --}}
            {{-- Route akan dibuat pada tahap berikutnya --}}
            <a href="{{ route('reports.index') }}"
            class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">

                <span class="menu-icon">▥</span>

                <span>
                    Laporan
                </span>

            </a>

        </nav>


        {{-- LOGOUT --}}
        <div class="logout-wrapper">

            <form method="POST"
                  action="{{ route('logout') }}">

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


    {{-- MOBILE OVERLAY --}}
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
                        class="sidebar-toggle"
                        aria-label="Buka menu">

                    ☰

                </button>


                <div>

                    <div class="header-title">
                        Dashboard
                    </div>

                    <div class="header-line"></div>

                </div>

            </div>


            {{-- HEADER RIGHT --}}
            <div class="header-right">

                <button class="header-button"
                        type="button"
                        aria-label="Search">

                    ⌕

                </button>


                <button class="header-button"
                        type="button"
                        aria-label="Notifikasi">

                    ♧

                    <span class="notification-badge">
                        4
                    </span>

                </button>


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


            {{-- WELCOME --}}
            <div class="welcome-card">

                <div class="welcome-content">

                    <div class="welcome-small">
                        Selamat datang kembali,
                    </div>

                    <h1>
                        {{ $user->name }}
                    </h1>

                    <p>
                        Berikut ringkasan data perpustakaan
                        PAG Library.
                    </p>

                </div>


                <div class="welcome-decoration">

                    <div class="book-decoration">
                        📚
                    </div>

                </div>

            </div>


            {{-- =================================================
                 STATISTICS
            ================================================== --}}

            <div class="stats-grid">


                {{-- TOTAL BUKU --}}
                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            📖
                        </div>

                        <button class="card-more">
                            ⋯
                        </button>

                    </div>


                    <div class="stat-label">
                        Total Judul Buku
                    </div>

                    <div class="stat-value">
                        {{ $totalBooks }}
                    </div>

                    <div class="stat-description">
                        Judul seluruh koleksi buku
                    </div>

                </div>


                {{-- EKSEMPLAR --}}
                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            ▣
                        </div>

                        <button class="card-more">
                            ⋯
                        </button>

                    </div>


                    <div class="stat-label">
                        Total Eksemplar
                    </div>

                    <div class="stat-value">
                        {{ $totalCopies }}
                    </div>

                    <div class="stat-description">
                        Seluruh salinan buku
                    </div>

                </div>


                {{-- DIPINJAM --}}
                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            ↗
                        </div>

                        <button class="card-more">
                            ⋯
                        </button>

                    </div>


                    <div class="stat-label">
                        Sedang Dipinjam
                    </div>

                    <div class="stat-value">
                        {{ $borrowedCopies }}
                    </div>

                    <div class="stat-description">
                        Eksemplar sedang dipinjam
                    </div>

                </div>


                {{-- PENGUNJUNG --}}
                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            ♙
                        </div>

                        <button class="card-more">
                            ⋯
                        </button>

                    </div>


                    <div class="stat-label">
                        Total Pengunjung
                    </div>

                    <div class="stat-value">
                        —
                    </div>

                    <div class="stat-description">
                        Data pengunjung
                    </div>

                </div>


                {{-- PEMINJAMAN --}}
                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            ▣
                        </div>

                        <button class="card-more">
                            ⋯
                        </button>

                    </div>


                    <div class="stat-label">
                        Peminjaman Hari Ini
                    </div>

                    <div class="stat-value">
                        —
                    </div>

                    <div class="stat-description">
                        Transaksi hari ini
                    </div>

                </div>

            </div>


            {{-- =================================================
                 ANALYTICS
            ================================================== --}}

            <div class="analytics-grid">


                {{-- PEMINJAMAN --}}
                <div class="panel loan-chart-panel">

                    <div class="panel-header">

                        <div>

                            <h2>
                                Statistik Peminjaman
                            </h2>

                            <span>
                                6 Bulan Terakhir
                            </span>

                        </div>


                        <button class="period-button">
                            6 Bulan
                            <span>⌄</span>
                        </button>

                    </div>


                    <div class="chart">

                        <div class="chart-y">
                            <span>100</span>
                            <span>80</span>
                            <span>60</span>
                            <span>40</span>
                            <span>20</span>
                            <span>0</span>
                        </div>


                        <div class="chart-area">

                            <div class="chart-grid-line line-1"></div>
                            <div class="chart-grid-line line-2"></div>
                            <div class="chart-grid-line line-3"></div>
                            <div class="chart-grid-line line-4"></div>
                            <div class="chart-grid-line line-5"></div>


                            <svg viewBox="0 0 700 250"
                                 preserveAspectRatio="none"
                                 class="loan-svg">

                                <defs>

                                    <linearGradient
                                        id="loanGradient"
                                        x1="0"
                                        x2="0"
                                        y1="0"
                                        y2="1">

                                        <stop
                                            offset="0%"
                                            stop-color="#e31e24"
                                            stop-opacity=".35"/>

                                        <stop
                                            offset="100%"
                                            stop-color="#e31e24"
                                            stop-opacity="0"/>

                                    </linearGradient>

                                </defs>


                                <path
                                    class="chart-fill"
                                    d="M0,175
                                       C80,165 100,160 140,145
                                       C190,128 210,150 250,125
                                       C300,100 330,110 370,120
                                       C420,130 430,95 480,90
                                       C530,85 560,70 600,60
                                       C640,55 670,70 700,80
                                       L700,250
                                       L0,250 Z"/>


                                <path
                                    class="chart-line"
                                    d="M0,175
                                       C80,165 100,160 140,145
                                       C190,128 210,150 250,125
                                       C300,100 330,110 370,120
                                       C420,130 430,95 480,90
                                       C530,85 560,70 600,60
                                       C640,55 670,70 700,80"/>

                            </svg>


                            <div class="chart-x">

                                <span>Mar</span>
                                <span>Apr</span>
                                <span>Mei</span>
                                <span>Jun</span>
                                <span>Jul</span>
                                <span>Agu</span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- STATUS EKSEMPLAR --}}
                <div class="panel status-panel">

                    <div class="panel-header">

                        <div>

                            <h2>
                                Eksemplar Berdasarkan Status
                            </h2>

                        </div>

                    </div>


                    <div class="status-content">


                        <div class="donut">

                            <div class="donut-hole">

                                <strong>
                                    {{ $totalCopies }}
                                </strong>

                                <span>
                                    Total
                                </span>

                            </div>

                        </div>


                        <div class="status-list">

                            <div class="status-row">

                                <span>
                                    <i class="dot available"></i>
                                    Tersedia
                                </span>

                                <strong>
                                    {{ $availableCopies }}
                                </strong>

                            </div>


                            <div class="status-row">

                                <span>
                                    <i class="dot borrowed"></i>
                                    Dipinjam
                                </span>

                                <strong>
                                    {{ $borrowedCopies }}
                                </strong>

                            </div>


                            <div class="status-row">

                                <span>
                                    <i class="dot lost"></i>
                                    Hilang
                                </span>

                                <strong>
                                    0
                                </strong>

                            </div>


                            <div class="status-row">

                                <span>
                                    <i class="dot damaged"></i>
                                    Rusak
                                </span>

                                <strong>
                                    0
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RECENT DATA
            ================================================== --}}

            <div class="recent-grid">


                {{-- PEMINJAMAN TERBARU --}}
                <div class="panel table-panel">

                    <div class="panel-header">

                        <div>

                            <h2>
                                Peminjaman Terbaru
                            </h2>

                        </div>


                        <a href="{{ route('books.index') }}"
                           class="view-all">
                            Lihat Semua
                        </a>

                    </div>


                    <div class="table-wrapper">

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        Nama Pekerja
                                    </th>

                                    <th>
                                        No. Pekerja
                                    </th>

                                    <th>
                                        Buku
                                    </th>

                                    <th>
                                        Waktu Peminjaman
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <tr>

                                    <td>
                                        <div class="person">
                                            <span>BS</span>
                                            Budi Santoso
                                        </div>
                                    </td>

                                    <td>
                                        12345
                                    </td>

                                    <td>
                                        Clean Code
                                    </td>

                                    <td>
                                        15/08/2026 09:15
                                    </td>

                                    <td>
                                        <span class="badge">
                                            Dipinjam
                                        </span>
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <div class="person">
                                            <span>AS</span>
                                            Andi Saputra
                                        </div>
                                    </td>

                                    <td>
                                        12346
                                    </td>

                                    <td>
                                        Design Patterns
                                    </td>

                                    <td>
                                        15/08/2026 10:30
                                    </td>

                                    <td>
                                        <span class="badge">
                                            Dipinjam
                                        </span>
                                    </td>

                                </tr>


                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- PENGUNJUNG TERBARU --}}
                <div class="panel table-panel">

                    <div class="panel-header">

                        <div>

                            <h2>
                                Pengunjung Terbaru
                            </h2>

                        </div>


                        <div class="view-all disabled">
                            Lihat Semua
                        </div>

                    </div>


                    <div class="table-wrapper">

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        Nama Pekerja
                                    </th>

                                    <th>
                                        No. Pekerja
                                    </th>

                                    <th>
                                        Waktu Kunjungan
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <tr>

                                    <td>

                                        <div class="person">

                                            <span>
                                                DW
                                            </span>

                                            Dewi Lestari

                                        </div>

                                    </td>

                                    <td>
                                        12348
                                    </td>

                                    <td>
                                        15/08/2026 14:20
                                    </td>

                                </tr>


                                <tr>

                                    <td>

                                        <div class="person">

                                            <span>
                                                AF
                                            </span>

                                            Ahmad Fauzi

                                        </div>

                                    </td>

                                    <td>
                                        12349
                                    </td>

                                    <td>
                                        15/08/2026 13:10
                                    </td>

                                </tr>


                                <tr>

                                    <td>

                                        <div class="person">

                                            <span>
                                                MR
                                            </span>

                                            M. Ridhwan

                                        </div>

                                    </td>

                                    <td>
                                        12350
                                    </td>

                                    <td>
                                        15/08/2026 12:45
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </section>

    </main>

</div>

</body>

</html>