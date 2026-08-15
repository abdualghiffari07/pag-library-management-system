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

    <title>Edit Buku - PAG Library</title>

    @vite([
        'resources/css/dashboard-admin.css',
        'resources/css/book-edit.css',
        'resources/js/dashboard-admin.js',
        'resources/js/book-edit.js'
    ])

</head>


<body>

<div class="app">


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

                    <div class="header-title">
                        Edit Buku
                    </div>

                    <div class="header-line"></div>

                </div>

            </div>


            {{-- HEADER RIGHT --}}

            <div class="header-right">

                <button class="header-button"
                        type="button"
                        aria-label="Pencarian">

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

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

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

        <section class="content book-edit-page">


            {{-- =================================================
                 BACK
            ================================================== --}}

            <a href="{{ route('books.index') }}"
               class="back-button">

                <span class="back-icon">
                    ←
                </span>

                Kembali ke Data Buku

            </a>


{{-- =================================================
     DETAIL KOLEKSI
================================================== --}}

<div class="collection-summary">

    <div class="collection-summary-content">

        <div class="collection-eyebrow">
            DETAIL KOLEKSI
        </div>

        <h1>
            {{ $book->title }}
        </h1>

        <p>
            {{ $book->description ?: 'Belum ada deskripsi untuk buku ini.' }}
        </p>

    </div>

    <div class="collection-icon">
        📖
    </div>

</div>


            {{-- =================================================
                 VALIDATION ERROR
            ================================================== --}}

            @if ($errors->any())

                <div class="form-alert error">

                    <div class="form-alert-title">
                        Terjadi kesalahan
                    </div>

                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =================================================
                 FORM
            ================================================== --}}

            <form action="{{ route('books.update', $book->book_id) }}"
                  method="POST"
                  class="book-edit-form">

                @csrf

                @method('PUT')


                {{-- =================================================
                     BASIC INFORMATION
                ================================================== --}}

                <div class="edit-panel">

                    <div class="edit-panel-header">

                        <div>

                            <h2>
                                Informasi Buku
                            </h2>

                            <p>
                                Informasi dasar mengenai koleksi buku.
                            </p>

                        </div>

                    </div>


                    <div class="edit-panel-body">


                        {{-- JUDUL --}}

                        <div class="form-group full-width">

                            <label for="title">
                                Judul Buku
                                <span>*</span>
                            </label>

                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title', $book->title) }}"
                                placeholder="Masukkan judul buku"
                                required
                            >

                            @error('title')

                                <small class="field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- ASAL --}}

                        <div class="form-group">

                            <label for="origin">
                                Asal
                            </label>

                            <input
                                type="text"
                                id="origin"
                                name="origin"
                                value="{{ old('origin', $book->origin) }}"
                                placeholder="Contoh: Amerika Serikat"
                            >

                            @error('origin')

                                <small class="field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- TAHUN TERBIT --}}

                        <div class="form-group">

                            <label for="publication_year">
                                Tahun Terbit
                            </label>

                            <input
                                type="number"
                                id="publication_year"
                                name="publication_year"
                                value="{{ old('publication_year', $book->publication_year) }}"
                                placeholder="Contoh: 2022"
                                min="1000"
                                max="2100"
                            >

                            @error('publication_year')

                                <small class="field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- COVER --}}

                        <div class="form-group full-width">

                            <label for="cover">
                                Cover
                            </label>

                            <input
                                type="text"
                                id="cover"
                                name="cover"
                                value="{{ old('cover', $book->cover) }}"
                                placeholder="Contoh: books/laravel.jpg"
                            >

                            <small class="field-help">
                                Masukkan path atau lokasi file cover buku.
                            </small>

                            @error('cover')

                                <small class="field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- LOKASI --}}

                        <div class="form-group">

                            <label for="location_id">
                                Lokasi
                            </label>

                            <select name="location_id"
                                    id="location_id">

                                <option value="">
                                    -- Pilih Lokasi --
                                </option>

                                @foreach ($locations as $location)

                                    <option
                                        value="{{ $location->location_id }}"
                                        {{ old('location_id', $book->location_id) == $location->location_id ? 'selected' : '' }}
                                    >
                                        {{ $location->location_name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('location_id')

                                <small class="field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- STATUS --}}

                        <div class="form-group">

                            <label for="status">
                                Status
                                <span>*</span>
                            </label>

                            <select name="status"
                                    id="status"
                                    required>

                                <option
                                    value="public"
                                    {{ old('status', $book->status) == 'public' ? 'selected' : '' }}
                                >
                                    Public
                                </option>

                                <option
                                    value="arsip"
                                    {{ old('status', $book->status) == 'arsip' ? 'selected' : '' }}
                                >
                                    Arsip
                                </option>

                            </select>

                            @error('status')

                                <small class="field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- DESKRIPSI --}}

                        <div class="form-group full-width">

                            <label for="description">
                                Deskripsi
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="6"
                                placeholder="Masukkan deskripsi buku..."
                            >{{ old('description', $book->description) }}</textarea>

                            @error('description')

                                <small class="field-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>

                    </div>

                </div>


{{-- =========================================================
     KLASIFIKASI BUKU
========================================================= --}}

<div class="edit-card classification-card">

    <div class="classification-header">

        <div class="classification-icon">
            ◈
        </div>

        <div>
            <h2>Klasifikasi Buku</h2>

            <p>
                Tentukan penulis dan kategori yang terkait dengan buku.
            </p>
        </div>

    </div>


    <div class="classification-body">

        {{-- PENULIS --}}

        <div class="classification-field">

            <label for="author-search">
                Penulis
            </label>

            <div
                class="tag-input-wrapper"
                id="author-tag-wrapper"
            >

                <div
                    class="selected-tags"
                    id="selected-authors"
                >

                    @foreach ($book->authors as $author)

                        <div
                            class="selected-tag"
                            data-id="{{ $author->author_id }}"
                        >

                            <span>
                                {{ $author->author_name }}
                            </span>

                            <button
                                type="button"
                                class="remove-tag"
                                aria-label="Hapus penulis"
                            >
                                ×
                            </button>

                            <input
                                type="hidden"
                                name="authors[]"
                                value="{{ $author->author_id }}"
                            >

                        </div>

                    @endforeach

                </div>


                <input
                    type="text"
                    id="author-search"
                    class="tag-search-input"
                    placeholder="Ketik nama penulis..."
                    autocomplete="off"
                >


                <div
                    id="author-suggestions"
                    class="autocomplete-dropdown"
                ></div>

            </div>

            <div class="classification-help">
                Ketik nama penulis untuk mencari, lalu klik hasil yang muncul.
            </div>

        </div>


        {{-- KATEGORI --}}

        <div class="classification-field">

            <label for="category-search">
                Kategori
            </label>

            <div
                class="tag-input-wrapper"
                id="category-tag-wrapper"
            >

                <div
                    class="selected-tags"
                    id="selected-categories"
                >

                    @foreach ($book->categories as $category)

                        <div
                            class="selected-tag"
                            data-id="{{ $category->category_id }}"
                        >

                            <span>
                                {{ $category->category_name }}
                            </span>

                            <button
                                type="button"
                                class="remove-tag"
                                aria-label="Hapus kategori"
                            >
                                ×
                            </button>

                            <input
                                type="hidden"
                                name="categories[]"
                                value="{{ $category->category_id }}"
                            >

                        </div>

                    @endforeach

                </div>


                <input
                    type="text"
                    id="category-search"
                    class="tag-search-input"
                    placeholder="Ketik kategori..."
                    autocomplete="off"
                >


                <div
                    id="category-suggestions"
                    class="autocomplete-dropdown"
                ></div>

            </div>

            <div class="classification-help">
                Ketik kategori untuk mencari, lalu klik hasil yang muncul.
            </div>

        </div>

    </div>

</div>



            {{-- =================================================
                 KATEGORI
            ================================================== --}}

            <div class="form-group form-grid-full">

                <label for="category-search">
                    Kategori
                </label>

                @php
                    $selectedCategories = old(
                        'categories',
                        $book->categories
                            ->pluck('category_id')
                            ->toArray()
                    );
                @endphp


                {{-- TAG KATEGORI --}}

                <div
                    class="tag-input-wrapper"
                    id="category-tag-wrapper"
                >

                    <div
                        class="selected-tags"
                        id="selected-categories"
                    >

                        @foreach ($book->categories as $category)

                            @if (in_array($category->category_id, $selectedCategories))

                                <div
                                    class="selected-tag"
                                    data-id="{{ $category->category_id }}"
                                >

                                    <span>
                                        {{ $category->category_name }}
                                    </span>

                                    <button
                                        type="button"
                                        class="remove-tag"
                                        aria-label="Hapus kategori"
                                    >
                                        ×
                                    </button>

                                    <input
                                        type="hidden"
                                        name="categories[]"
                                        value="{{ $category->category_id }}"
                                    >

                                </div>

                            @endif

                        @endforeach

                    </div>


                    {{-- INPUT PENCARIAN --}}

                    <input
                        type="text"
                        id="category-search"
                        class="form-control tag-search-input"
                        placeholder="Ketik kategori..."
                        autocomplete="off"
                    >


                    {{-- DROPDOWN --}}

                    <div
                        id="category-suggestions"
                        class="autocomplete-dropdown"
                    ></div>

                </div>


                <small class="form-help">
                    Ketik kategori untuk mencari. Klik hasil pencarian
                    untuk memilih.
                </small>

            </div>

        </div>

    </div>

</div>


                {{-- =================================================
                     FORM ACTION
                ================================================== --}}

<div class="form-actions">

    <a
        href="{{ route('books.index') }}"
        class="btn btn-cancel"
    >
        <span class="btn-icon">←</span>
        <span>Batal</span>
    </a>


    <button
        type="submit"
        class="btn btn-primary"
        id="update-book-button"
    >

        <span class="btn-spinner"></span>

        <span class="btn-icon">✓</span>

        <span class="btn-text">
            Update Buku
        </span>

    </button>

</div>


            </form>

        </section>

    </main>

</div>

</body>
</html>