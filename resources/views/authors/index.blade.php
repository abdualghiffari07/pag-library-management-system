<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Penulis - PAG Library</title>

    @vite([
        'resources/css/dashboard-admin.css',
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
    <div id="sidebar-overlay"
         class="sidebar-overlay">
    </div>


    {{-- =====================================================
         MAIN
    ====================================================== --}}
    <main class="main">

        {{-- =================================================
             HEADER
        ================================================== --}}
        <header class="header">

            <div class="header-left">

                <button type="button"
                        id="sidebar-toggle"
                        class="sidebar-toggle"
                        aria-label="Buka menu">
                    ☰
                </button>

                <div>
                    <h1 class="header-title">
                        Penulis
                    </h1>

                    <div class="header-line"></div>
                </div>

            </div>


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

        </header>


        {{-- =================================================
             CONTENT
        ================================================== --}}
        <section class="content authors-page">


            {{-- =================================================
                 HERO
            ================================================== --}}
            <section class="authors-hero">

                <div class="authors-hero-content">

                    <span class="authors-eyebrow">
                        KOLEKSI PERPUSTAKAAN
                    </span>

                    <h2>
                        Penulis
                    </h2>

                    <p>
                        Kelola seluruh data penulis yang terkait
                        dengan koleksi buku perpustakaan PAG.
                    </p>

                </div>


                <div class="authors-hero-decoration">

                    <span class="decoration-letter">
                        ✒
                    </span>

                </div>


                <a href="{{ route('authors.create') }}"
                   class="authors-add-button">

                    <span>+</span>

                    Tambah Penulis

                </a>

            </section>





                {{-- =================================================
                     TABLE
                ================================================== --}}
                <div class="authors-table-wrapper">

                {{-- SEARCH --}}
<form
    action="{{ route('authors.index') }}"
    method="GET"
    class="authors-search"
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
        <a href="{{ route('authors.index') }}"
           class="search-clear"
           title="Hapus pencarian">
            ×
        </a>
    @endif

</form>

                    <table class="authors-table">

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


                        <tbody id="authors-table-body">

                            @forelse ($authors as $author)

                        <tr class="author-row">

                            {{-- NO --}}
                            <td>
                                <span class="author-number">
                                    {{ $authors->firstItem() + $loop->index }}
                                </span>
                            </td>

                            {{-- ID --}}
                            <td>
                                <span class="author-id">
                                    #{{ $author->author_id }}
                                </span>
                            </td>


                                    {{-- AUTHOR --}}
                                    <td>

                                        <div class="author-name-wrapper">

                                            <div class="author-avatar">
                                                {{ strtoupper(substr($author->author_name, 0, 1)) }}
                                            </div>

                                            <div class="author-name-content">

                                            <strong class="author-name">
                                                {{ $author->author_name }}
                                            </strong>


                                            </div>

                                        </div>

                                    </td>


                                    {{-- BOOK COUNT --}}
                                    <td>

                                        <span class="book-count">

                                            {{ $author->books_count ?? $author->books->count() ?? 0 }}

                                            buku

                                        </span>

                                    </td>


                                    {{-- CREATED --}}
                                    <td>

                                        <span class="author-date">

                                            {{ $author->created_at
                                                ? \Carbon\Carbon::parse($author->created_at)->format('d M Y')
                                                : '-' }}

                                        </span>

                                    </td>


                                {{-- ACTION --}}
                                <td>

                                    <div class="author-actions">

                                        <a href="{{ route('authors.show', $author->author_id) }}"
                                        class="author-action manage">

                                            Kelola

                                        </a>


                                        <form
                                            action="{{ route('authors.destroy', $author->author_id) }}"
                                            method="POST"
                                            class="delete-author-form">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="author-action delete">

                                                Hapus

                                            </button>

                                        </form>

                                    </div>

                                </td>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5">

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

                                            <a href="{{ route('authors.create') }}"
                                               class="authors-empty-button">

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


        <div class="pagination-links">

            {{-- PREVIOUS --}}
            @if ($authors->onFirstPage())
                <span class="pagination-button disabled">
                    « Previous
                </span>
            @else
                <a href="{{ $authors->previousPageUrl() }}"
                class="pagination-button">
                    « Previous
                </a>
            @endif


            {{-- PAGE NUMBERS --}}
            <div class="pagination-numbers">

                @for ($page = 1; $page <= $authors->lastPage(); $page++)

                    @if ($page == $authors->currentPage())

                        <span class="pagination-number active">
                            {{ $page }}
                        </span>

                    @else

                        <a href="{{ $authors->url($page) }}"
                        class="pagination-number">
                            {{ $page }}
                        </a>

                    @endif

                @endfor

            </div>


            {{-- NEXT --}}
            @if ($authors->hasMorePages())

                <a href="{{ $authors->nextPageUrl() }}"
                class="pagination-button">
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