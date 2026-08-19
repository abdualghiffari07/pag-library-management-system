<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        {{ $book->title }} - PAG Library
    </title>

    @vite([
        'resources/css/dashboard-admin.css',
        'resources/css/book-details.css',
        'resources/js/dashboard-admin.js'
    ])

</head>


<body>

<div class="app">


@include('layouts.sidebar')


    {{-- MOBILE OVERLAY --}}

    <div id="sidebar-overlay"
         class="sidebar-overlay">
    </div>


    {{-- =====================================================
         MAIN
    ====================================================== --}}

    <main class="main dashboard-page book-detail-page">


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
                        Detail Buku
                    </div>

                    <div class="header-line"></div>

                </div>

            </div>


            {{-- HEADER RIGHT --}}

            <div class="header-right">

                <button class="header-button"
                        type="button">

                    ⌕

                </button>


                <button type="button"
                        id="sidebar-toggle"
                        class="sidebar-toggle"
                        aria-label="Buka menu">
                    ☰
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


        {{-- =====================================================
             CONTENT
        ====================================================== --}}

        <section class="content book-detail-content">


            {{-- BACK --}}

            <a href="{{ route('books.index') }}"
               class="back-button">

                <span class="back-icon">
                    ←
                </span>

                Kembali ke Data Buku

            </a>


            {{-- =================================================
                 BOOK HERO
            ================================================== --}}

            <div class="book-hero">

                <div class="book-hero-content">

                    <div class="book-label">
                        Detail Koleksi
                    </div>

                    <h1 class="book-title">
                        {{ $book->title }}
                    </h1>

                    @if ($book->description)

                        <p class="book-description">
                            {{ $book->description }}
                        </p>

                    @else

                        <p class="book-description">
                            Informasi detail koleksi buku
                            PAG Library.
                        </p>

                    @endif

                </div>


                <div class="book-hero-icon">
                    📖
                </div>

            </div>


            {{-- =================================================
                 ALERT
            ================================================== --}}

            @if (session('success'))

                <div class="detail-alert success">

                    {{ session('success') }}

                </div>

            @endif


            @if (session('error'))

                <div class="detail-alert error">

                    {{ session('error') }}

                </div>

            @endif


            {{-- =================================================
                 BOOK INFORMATION
            ================================================== --}}

            <div class="book-info-grid">


                {{-- INFORMASI BUKU --}}

                <div class="detail-panel">

                    <div class="detail-panel-header">

                        <h2>
                            Informasi Buku
                        </h2>

                        <p>
                            Informasi utama koleksi buku
                        </p>

                    </div>


                    <div class="book-info">


                        <div class="info-item">

                            <div class="info-label">
                                ID Buku
                            </div>

                            <div class="info-value">
                                #{{ $book->book_id }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Tahun Terbit
                            </div>

                            <div class="info-value">
                                {{ $book->publication_year ?? '-' }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Asal
                            </div>

                            <div class="info-value">
                                {{ $book->origin ?? '-' }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Status Buku
                            </div>

                            <div class="info-value">

                                {{ $book->status ?? '-' }}

                            </div>

                        </div>


                    </div>


                    <div class="description-panel">

                        <div class="info-label">
                            Deskripsi
                        </div>

                        <div class="description-text">

                            {{ $book->description ?? 'Tidak ada deskripsi.' }}

                        </div>

                    </div>

                </div>


                {{-- RINGKASAN --}}

                <div class="detail-panel">

                    <div class="detail-panel-header">

                        <h2>
                            Ringkasan
                        </h2>

                        <p>
                            Statistik eksemplar buku
                        </p>

                    </div>


                    <div class="book-info">

                        <div class="info-item">

                            <div class="info-label">
                                Total Eksemplar
                            </div>

                            <div class="info-value">
                                {{ $book->copies->count() }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Tersedia
                            </div>

                            <div class="info-value">

                                {{ $book->copies->where('status', 'tersedia')->count() }}

                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Dipinjam
                            </div>

                            <div class="info-value">

                                {{ $book->copies->where('status', 'dipinjam')->count() }}

                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Kondisi Rusak
                            </div>

                            <div class="info-value">

                                {{ $book->copies->where('status', 'rusak')->count() }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 EKSEMPLAR
            ================================================== --}}

            <div class="detail-panel copy-section">

                <div class="detail-panel-header">

                    <h2>
                        Daftar Eksemplar
                    </h2>

                    <p>
                        {{ $book->copies->count() }}
                        eksemplar terdaftar untuk buku ini
                    </p>

                </div>


                @if ($book->copies->count() > 0)

                    <div class="copy-table-wrapper">

                        <table class="copy-table">

                            <thead>

                                <tr>

                                    <th>
                                        No
                                    </th>

                                    <th>
                                        Kode Eksemplar
                                    </th>

                                    <th>
                                        Kondisi
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Catatan
                                    </th>

                                    <th>
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($book->copies as $index => $copy)

                                    <tr>

                                        <td>
                                            {{ $index + 1 }}
                                        </td>


                                        <td class="copy-code">

                                            {{ $copy->copy_code }}

                                        </td>


                                        <td class="condition">

                                            {{ $copy->condition }}

                                        </td>


                                        <td>

                                            @if ($copy->status === 'tersedia')

                                                <span class="copy-status available">
                                                    Tersedia
                                                </span>

                                            @elseif ($copy->status === 'dipinjam')

                                                <span class="copy-status borrowed">
                                                    Dipinjam
                                                </span>

                                            @elseif ($copy->status === 'rusak')

                                                <span class="copy-status damaged">
                                                    Rusak
                                                </span>

                                            @elseif ($copy->status === 'hilang')

                                                <span class="copy-status lost">
                                                    Hilang
                                                </span>

                                            @else

                                                <span class="copy-status">
                                                    {{ $copy->status }}
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            {{ $copy->notes ?? '-' }}

                                        </td>


                                        <td>

                                            <div class="copy-actions">


                                                <a href="{{ route('book-copies.edit', $copy->copy_id) }}"
                                                   class="action-button action-edit">

                                                    Edit

                                                </a>


                                                <form
                                                    action="{{ route('book-copies.destroy', $copy->copy_id) }}"
                                                    method="POST"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="action-button action-delete"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus eksemplar ini?')"
                                                    >

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

                    <div class="empty-copy">

                        Belum ada eksemplar untuk buku ini.

                    </div>

                @endif

            </div>


        </section>

    </main>

</div>

</body>

</html>