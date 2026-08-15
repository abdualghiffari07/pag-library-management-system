<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Data Buku - PAG Library</title>

    @vite([
        'resources/css/dashboard-admin.css',
        'resources/css/books.css',
        'resources/js/dashboard-admin.js'
    ])

</head>


<body>

<div class="app">

    {{-- =====================================================
         SIDEBAR
         (diselaraskan dengan report.blade.php: brand-mark,
         menu-section, pola menu-disabled, active state dinamis)
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


        {{-- HEADER
             (header-line ditambahkan, urutan avatar/nama
             disamakan dengan report.blade.php) --}}

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
                        Data Buku
                    </div>

                    <div class="header-line"></div>

                </div>

            </div>


            <div class="header-right">

                <div class="admin-profile">

                    <div class="avatar">

                        {{ strtoupper(
                            substr(auth()->user()->name, 0, 1)
                        ) }}

                    </div>


                    <div class="admin-info">

                        <div class="admin-name">
                            {{ auth()->user()->name }}
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


            {{-- PAGE TITLE
                 (pola welcome-card, sama seperti report.blade.php,
                 tombol "Tambah Buku" diletakkan di slot
                 welcome-decoration) --}}

            <div class="welcome-card">

                <div class="welcome-content">

                    <div class="welcome-small">
                        KOLEKSI PERPUSTAKAAN
                    </div>

                    <h1>
                        Data Buku
                    </h1>

                    <p>
                        Kelola seluruh data buku yang tersedia
                        di perpustakaan PAG.
                    </p>

                </div>


                <div class="welcome-decoration">

                    <a href="{{ route('books.create') }}"
                       class="add-book-button">

                        <span class="add-icon">
                            +
                        </span>

                        Tambah Buku

                    </a>

                </div>

            </div>


            {{-- =================================================
                 SUCCESS MESSAGE (tidak diubah)
            ================================================== --}}

            @if (session('success'))

                <div class="alert-success">

                    <span class="alert-icon">
                        ✓
                    </span>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            @endif


            {{-- =================================================
                 BOOK CARD (isi & logika tidak diubah)
            ================================================== --}}

            <div class="books-card">


                <!-- CARD HEADER -->

                <div class="books-card-header">


                    <div>

                        <h2 class="section-title">
                            Daftar Buku
                        </h2>

                        <p>
                            {{ $books->count() }}
                            buku terdaftar
                        </p>

                    </div>


                    <!-- SEARCH -->

                    <div class="book-search">

                        <span class="search-icon">
                            ⌕
                        </span>

                        <input
                            type="text"
                            id="book-search"
                            placeholder="Cari buku..."
                            autocomplete="off">

                    </div>


                </div>


                @if ($books->count() > 0)


                    <!-- =================================================
                         TABLE
                    ================================================== -->

                    <div class="table-wrapper">

                        <table class="books-table"
                               id="books-table">


                            <thead>

                                <tr>

                                    <th>
                                        ID
                                    </th>

                                    <th>
                                        Buku
                                    </th>

                                    <th>
                                        Asal
                                    </th>

                                    <th>
                                        Tahun
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Lokasi
                                    </th>

                                    <th class="action-column">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>


                                @foreach ($books as $book)

                                    <tr>


                                        <!-- ID -->

                                        <td class="book-id">

                                            #{{ $book->book_id }}

                                        </td>


                                        <!-- TITLE -->

                                        <td>

                                            <div class="book-title">

                                                {{ $book->title }}

                                            </div>

                                        </td>


                                        <!-- ORIGIN -->

                                        <td class="muted-cell">

                                            {{ $book->origin ?? '-' }}

                                        </td>


                                        <!-- YEAR -->

                                        <td class="muted-cell">

                                            {{ $book->publication_year ?? '-' }}

                                        </td>


                                        <!-- STATUS -->

                                        <td>

                                            @if ($book->status === 'public')

                                                <span class="status-badge status-public">
                                                    Public
                                                </span>

                                            @elseif ($book->status === 'archive')

                                                <span class="status-badge status-archive">
                                                    Arsip
                                                </span>

                                            @else

                                                <span class="status-badge">
                                                    {{ $book->status }}
                                                </span>

                                            @endif

                                        </td>


                                        <!-- LOCATION -->

                                        <td class="muted-cell">

                                            {{ $book->location->location_name ?? '-' }}

                                        </td>


                                        <!-- ACTION -->

                                        <td>

                                            <div class="book-actions">


                                                <a href="{{ route(
                                                    'books.show',
                                                    $book->book_id
                                                ) }}"
                                                class="action-detail">

                                                    Detail

                                                </a>


                                                <a href="{{ route(
                                                    'books.edit',
                                                    $book->book_id
                                                ) }}"
                                                class="action-edit">

                                                    Edit

                                                </a>


                                                <form
                                                    action="{{ route(
                                                        'books.destroy',
                                                        $book->book_id
                                                    ) }}"
                                                    method="POST"
                                                    onsubmit="return confirm(
                                                        'Apakah Anda yakin ingin menghapus buku ini?'
                                                    )">

                                                    @csrf

                                                    @method('DELETE')


                                                    <button
                                                        type="submit"
                                                        class="action-delete">

                                                        Hapus

                                                    </button>

                                                </form>


                                            </div>

                                        </td>


                                    </tr>

                                @endforeach


                            </tbody>


                        </table>

                    </div>


                @else


                    <!-- EMPTY STATE -->

                    <div class="empty-books">

                        <div class="empty-icon">
                            ▣
                        </div>

                        <h3>
                            Belum ada data buku
                        </h3>

                        <p>
                            Belum terdapat buku yang
                            terdaftar di perpustakaan.
                        </p>


                        <a href="{{ route('books.create') }}"
                           class="add-book-button">

                            + Tambah Buku

                        </a>

                    </div>


                @endif


            </div>


        </section>


    </main>


</div>


{{-- =========================================================
     SEARCH SCRIPT (tidak diubah)
========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const search =
            document.getElementById('book-search');

        const table =
            document.getElementById('books-table');

        if (!search || !table) {
            return;
        }


        const rows =
            table.querySelectorAll('tbody tr');


        search.addEventListener(
            'input',
            function () {

                const keyword =
                    this.value.toLowerCase().trim();


                rows.forEach(function (row) {

                    const text =
                        row.textContent.toLowerCase();


                    row.style.display =
                        text.includes(keyword)
                            ? ''
                            : 'none';

                });

            }
        );

    }
);

</script>


</body>

</html>