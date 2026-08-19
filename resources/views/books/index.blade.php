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
    'resources/js/books.js',
    'resources/js/dashboard-admin.js'
])

</head>

<body>

<div class="app">

    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    @include('layouts.sidebar')


    {{-- =====================================================
         MOBILE OVERLAY
    ====================================================== --}}

    <div id="sidebar-overlay"
         class="sidebar-overlay">
    </div>


    {{-- =====================================================
         MAIN
    ====================================================== --}}

    <main class="main dashboard-page books-main">

        {{-- =================================================
             HEADER
        ================================================== --}}

        <header class="header books-header">

            <div class="header-left">

                {{-- MOBILE SIDEBAR BUTTON --}}

                <button type="button"
                        id="sidebar-toggle"
                        class="sidebar-toggle"
                        aria-label="Buka menu">

                    ☰

                </button>


                <div>

                    <h1 class="header-title">
                        Data Buku
                    </h1>

                    <div class="header-line"></div>

                </div>

            </div>


            {{-- =================================================
                 ADMIN PROFILE
            ================================================== --}}

            <div class="header-right">

                <div class="admin-profile">

                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>

                    <div class="admin-info">

                        <div class="admin-name">
                            {{ auth()->user()->name ?? 'Administrator' }}
                            <span class="admin-caret">⌄</span>
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

        <section class="content books-page">

            <div class="books-content">


                {{-- =================================================
                     HERO CARD
                ================================================== --}}

                <section class="welcome-card">

                    <div class="welcome-content">

                        <span class="welcome-small">
                            KOLEKSI PERPUSTAKAAN
                        </span>

                        <h1>
                            Data Buku
                        </h1>

                        <p>
                            Kelola seluruh data buku yang tersedia
                            di perpustakaan PAG.
                        </p>

                    </div>


                    {{-- =================================================
                         TAMBAH BUKU
                    ================================================== --}}

                    <div class="welcome-decoration">

                        <a href="{{ route('books.create') }}"
                           class="view-all">

                            <span>+</span>

                            Tambah Buku

                        </a>

                    </div>

                </section>


                {{-- =================================================
                     TABLE PANEL
                ================================================== --}}

                <section class="panel books-table-panel">


                    {{-- =================================================
                         PANEL HEADER
                    ================================================== --}}

                    <div class="panel-header">

                        <div class="panel-title">

                            <h2>
                                Daftar Buku
                            </h2>

                            <span>
                                {{ $books->count() }} buku terdaftar
                            </span>

                        </div>


                        {{-- =================================================
                             SEARCH
                        ================================================== --}}

                        <form method="GET"
                              action="{{ route('books.index') }}"
                              class="book-search">

                            <span class="search-icon">
                                ⌕
                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari buku..."
                                autocomplete="off"
                            >

                        </form>

                    </div>


                    {{-- =================================================
                         TABLE
                    ================================================== --}}

                    <div class="table-wrapper">

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        ID
                                    </th>

                                    <th>
                                        BUKU
                                    </th>

                                    <th>
                                        ASAL
                                    </th>

                                    <th>
                                        TAHUN
                                    </th>

                                    <th>
                                        STATUS
                                    </th>

                                    <th>
                                        LOKASI
                                    </th>

                                    <th>
                                        AKSI
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse ($books as $book)

                                    <tr>

                                        {{-- =================================================
                                             ID
                                        ================================================== --}}

                                        <td class="book-id">
                                            #{{ $book->book_id }}
                                        </td>


                                        {{-- =================================================
                                             TITLE
                                        ================================================== --}}

                                        <td class="book-title-cell">

                                            <strong>
                                                {{ $book->title }}
                                            </strong>

                                        </td>


                                        {{-- =================================================
                                             ORIGIN
                                        ================================================== --}}

                                        <td>
                                            {{ $book->origin ?? '-' }}
                                        </td>


                                        {{-- =================================================
                                             YEAR
                                        ================================================== --}}

                                        <td>
                                            {{ $book->publication_year ?? '-' }}
                                        </td>


                                        {{-- =================================================
                                             STATUS
                                        ================================================== --}}

                                        <td>

                                            <span class="badge">
                                                {{ $book->status ?? 'Public' }}
                                            </span>

                                        </td>


                                        {{-- =================================================
                                             LOCATION
                                        ================================================== --}}

                                        <td class="book-location">
                                            {{ $book->location->location_name ?? '-' }}
                                        </td>


                                        {{-- =================================================
                                             ACTION
                                        ================================================== --}}

                                        <td class="action-cell">

                                            <div class="book-actions">

                                                {{-- DETAIL --}}

<a href="{{ route('books.show', $book->book_id) }}"
   class="view-all">
    Detail
</a>

<a href="{{ route('books.edit', $book->book_id) }}"
   class="view-all">
    Edit
</a>

<form action="{{ route('books.destroy', $book->book_id) }}"
      method="POST">

    @csrf
    @method('DELETE')

    <button type="submit"
            class="view-all"
            onclick="return confirm('Yakin ingin menghapus buku ini?')">
        Hapus
    </button>

</form>

                                            </div>

                                        </td>

                                    </tr>


                                @empty

                                    <tr>

                                        <td colspan="7"
                                            class="empty-table">

                                            Belum ada data buku.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                         PAGINATION
                    ================================================== --}}

                    @if(method_exists($books, 'links'))

                        <div class="pagination-wrapper">

                            {{ $books->links() }}

                        </div>

                    @endif

                </section>

            </div>

        </section>

    </main>

</div>


{{-- =====================================================
     MOBILE SIDEBAR SCRIPT
====================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.querySelector('.sidebar');

    const toggle = document.getElementById('sidebar-toggle');

    const overlay = document.getElementById('sidebar-overlay');


    if (!sidebar || !toggle || !overlay) {
        return;
    }


    function openSidebar() {

        sidebar.classList.add('open');

        overlay.classList.add('active');

        document.body.classList.add('sidebar-open');

    }


    function closeSidebar() {

        sidebar.classList.remove('open');

        overlay.classList.remove('active');

        document.body.classList.remove('sidebar-open');

    }


    toggle.addEventListener('click', function () {

        if (sidebar.classList.contains('open')) {

            closeSidebar();

        } else {

            openSidebar();

        }

    });


    overlay.addEventListener('click', closeSidebar);


    window.addEventListener('resize', function () {

        if (window.innerWidth > 900) {

            closeSidebar();

        }

    });

});

</script>


</body>

</html>