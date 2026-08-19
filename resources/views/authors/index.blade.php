<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Penulis - PAG Library</title>

    @vite([
        'resources/css/dashboard-admin.css',
        'resources/js/dashboard-admin.js',
        'resources/css/header.css',
        'resources/css/authors.css',
        'resources/js/authors.js'
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

    <div
        id="sidebar-overlay"
        class="sidebar-overlay"
    ></div>


    {{-- =====================================================
         MAIN
    ====================================================== --}}

<main class="main dashboard-page">

    @include('layouts.header', ['pageTitle' => 'Penulis'])



        {{-- =================================================
             CONTENT
        ================================================== --}}

        <section class="content authors-page">


            {{-- =================================================
                 HERO
                 SAMA DENGAN DATA BUKU
            ================================================== --}}

            <section class="welcome-card">


                <div class="welcome-content">

                    <span class="welcome-small">
                        KOLEKSI PERPUSTAKAAN
                    </span>


                    <h1>
                        Penulis
                    </h1>


                    <p>
                        Kelola seluruh data penulis yang terkait
                        dengan koleksi buku perpustakaan PAG.
                    </p>

                </div>


                <div class="welcome-decoration">

                    <a
                        href="{{ route('authors.create') }}"
                        class="view-all"
                    >

                        <span>
                            +
                        </span>

                        Tambah Penulis

                    </a>

                </div>


            </section>

            
            {{-- =================================================
                 DATA PENULIS
                 SAMA DENGAN PANEL DATA BUKU
            ================================================== --}}

            <section class="authors-table-panel">


                {{-- =================================================
                     PANEL HEADER
                ================================================== --}}

                <div class="panel-header">


                    <div>

                        <h2>
                            Data Penulis
                        </h2>


                        <span>
                            Daftar penulis yang terdaftar
                            dalam perpustakaan.
                        </span>

                    </div>


                    {{-- =================================================
                         SEARCH
                    ================================================== --}}

                    <form
                        action="{{ route('authors.index') }}"
                        method="GET"
                        class="author-search"
                    >

                        <span class="search-icon">
                            ⌕
                        </span>


                        <input
                            type="search"
                            name="search"
                            id="author-search"
                            placeholder="Cari penulis..."
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >


                        @if(request('search'))

                            <a
                                href="{{ route('authors.index') }}"
                                class="search-clear"
                                title="Hapus pencarian"
                            >
                                ×
                            </a>

                        @endif

                    </form>


                </div>


                {{-- =================================================
                     TABLE WRAPPER
                ================================================== --}}

                <div class="table-wrapper">


                    <table class="authors-table">


                        {{-- =================================================
                             TABLE HEADER
                        ================================================== --}}

                        <thead>

                            <tr>

                                <th>
                                    NO
                                </th>

                                <th>
                                    ID
                                </th>

                                <th>
                                    PENULIS
                                </th>

                                <th>
                                    BUKU
                                </th>

                                <th>
                                    DIBUAT
                                </th>

                                <th class="text-right">
                                    AKSI
                                </th>

                            </tr>

                        </thead>


                        {{-- =================================================
                             TABLE BODY
                        ================================================== --}}

                        <tbody id="authors-table-body">


                            @forelse ($authors as $author)


                                <tr class="author-row">


                                    {{-- =====================================
                                         NO
                                    ====================================== --}}

                                    <td>

                                        <span class="author-number">

                                            {{ $authors->firstItem() + $loop->index }}

                                        </span>

                                    </td>


                                    {{-- =====================================
                                         ID
                                    ====================================== --}}

                                    <td>

                                        <span class="author-id">

                                            #{{ $author->author_id }}

                                        </span>

                                    </td>


                                    {{-- =====================================
                                         AUTHOR
                                    ====================================== --}}

                                    <td>

                                        <div class="author-name-wrapper">


                                            <div class="author-avatar">

                                                {{ strtoupper(
                                                    substr($author->author_name, 0, 1)
                                                ) }}

                                            </div>


                                            <div class="author-name-content">

                                                <strong class="author-name">

                                                    {{ $author->author_name }}

                                                </strong>

                                            </div>


                                        </div>

                                    </td>


                                    {{-- =====================================
                                         BOOK COUNT
                                    ====================================== --}}

                                    <td>

                                        <span class="book-count">

                                            {{ $author->books_count ?? $author->books->count() ?? 0 }}

                                            buku

                                        </span>

                                    </td>


                                    {{-- =====================================
                                         CREATED
                                    ====================================== --}}

                                    <td>

                                        <span class="author-date">

                                            {{ $author->created_at
                                                ? \Carbon\Carbon::parse($author->created_at)->format('d M Y')
                                                : '-' }}

                                        </span>

                                    </td>


                                    {{-- =====================================
                                         ACTION
                                    ====================================== --}}

                                    <td>

                                        <div class="author-actions">


                                            <a
                                                href="{{ route('authors.show', $author->author_id) }}"
                                                class="author-action manage"
                                            >
                                                Kelola
                                            </a>


                                            <form
                                                action="{{ route('authors.destroy', $author->author_id) }}"
                                                method="POST"
                                                class="delete-author-form"
                                            >

                                                @csrf

                                                @method('DELETE')


                                                <button
                                                    type="submit"
                                                    class="author-action delete"
                                                >
                                                    Hapus
                                                </button>

                                            </form>


                                        </div>

                                    </td>


                                </tr>


                            @empty


                                {{-- =====================================
                                     EMPTY STATE
                                ====================================== --}}

                                <tr>

                                    <td colspan="6">

                                        <div class="authors-empty">


                                            <div class="empty-icon">
                                                ✒
                                            </div>


                                            <h3>
                                                Belum ada penulis
                                            </h3>


                                            <p>
                                                Belum terdapat data penulis
                                                di perpustakaan.
                                            </p>


                                            <a
                                                href="{{ route('authors.create') }}"
                                                class="authors-empty-button"
                                            >
                                                Tambah Penulis
                                            </a>


                                        </div>

                                    </td>

                                </tr>


                            @endforelse


                        </tbody>


                    </table>


                </div>


                {{-- =================================================
                     PAGINATION
                ================================================== --}}

                @if ($authors instanceof \Illuminate\Pagination\LengthAwarePaginator)


                    <div class="authors-pagination">


                        {{-- =============================================
                             PAGINATION INFO
                        ============================================== --}}

                        <div class="pagination-info">

                            Menampilkan

                            <strong>
                                {{ $authors->firstItem() ?? 0 }}
                            </strong>

                            sampai

                            <strong>
                                {{ $authors->lastItem() ?? 0 }}
                            </strong>

                            dari

                            <strong>
                                {{ $authors->total() }}
                            </strong>

                            penulis

                        </div>


                        {{-- =============================================
                             PAGINATION LINKS
                        ============================================== --}}

                        <div class="pagination-links">


                            {{-- PREVIOUS --}}

                            @if ($authors->onFirstPage())

                                <span class="pagination-button disabled">
                                    « Previous
                                </span>

                            @else

                                <a
                                    href="{{ $authors->previousPageUrl() }}"
                                    class="pagination-button"
                                >
                                    « Previous
                                </a>

                            @endif


                            {{-- PAGE NUMBERS --}}

                            <div class="pagination-numbers">


                                @for (
                                    $page = 1;
                                    $page <= $authors->lastPage();
                                    $page++
                                )


                                    @if ($page == $authors->currentPage())

                                        <span class="pagination-number active">
                                            {{ $page }}
                                        </span>

                                    @else

                                        <a
                                            href="{{ $authors->url($page) }}"
                                            class="pagination-number"
                                        >
                                            {{ $page }}
                                        </a>

                                    @endif


                                @endfor


                            </div>


                            {{-- NEXT --}}

                            @if ($authors->hasMorePages())

                                <a
                                    href="{{ $authors->nextPageUrl() }}"
                                    class="pagination-button"
                                >
                                    Next »
                                </a>

                            @else

                                <span class="pagination-button disabled">
                                    Next »
                                </span>

                            @endif


                        </div>


                    </div>


                @endif


            </section>


        </section>


    </main>


</div>


</body>

</html>