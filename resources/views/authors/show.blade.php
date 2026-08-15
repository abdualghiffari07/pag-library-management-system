```blade
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $book->title }} - PAG Library</title>

    @vite([
        'resources/css/dashboard-admin.css',
        'resources/css/book-details.css',
        'resources/js/dashboard-admin.js'
    ])
</head>

<body>

<div class="app">

@include('layouts.sidebar')


    <div id="sidebar-overlay" class="sidebar-overlay"></div>


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
                        Detail Buku
                    </div>

                    <div class="header-line"></div>

                </div>

            </div>


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


        {{-- =====================================================
             CONTENT
        ====================================================== --}}

        <section class="content book-detail-content">

            {{-- BACK --}}

            <div class="detail-back">

                <a href="{{ route('books.index') }}">

                    <span class="back-arrow">
                        ←
                    </span>

                    <span>
                        Kembali ke Data Buku
                    </span>

                </a>

            </div>


            {{-- =================================================
                 BOOK HERO
            ================================================== --}}

            <div class="book-hero">

                <div class="book-hero-info">

                    <div class="detail-label">
                        DETAIL KOLEKSI
                    </div>


                    <h1>
                        {{ $book->title }}
                    </h1>


                    <p class="book-description">
                        {{ $book->description ?? 'Tidak ada deskripsi untuk buku ini.' }}
                    </p>


                    <div class="book-meta">

                        <span class="book-status
                            {{ $book->status === 'public' ? 'status-public' : 'status-archive' }}">

                            <span class="status-dot"></span>

                            {{ ucfirst($book->status ?? '-') }}

                        </span>


                        <span class="meta-item">
                            {{ $book->publication_year ?? '-' }}
                        </span>


                        <span class="meta-separator">
                            /
                        </span>


                        <span class="meta-item">
                            {{ $book->origin ?? '-' }}
                        </span>

                    </div>

                </div>


                <div class="book-hero-icon">
                    📖
                </div>

            </div>


            {{-- ALERT --}}

            @if (session('success'))

                <div class="detail-alert success">

                    <span class="alert-icon">
                        ✓
                    </span>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            @endif


            @if (session('error'))

                <div class="detail-alert error">

                    <span class="alert-icon">
                        !
                    </span>

                    <span>
                        {{ session('error') }}
                    </span>

                </div>

            @endif


            {{-- =================================================
                 INFORMATION + SUMMARY
            ================================================== --}}

            <div class="detail-grid">


                {{-- INFORMASI BUKU --}}

                <div class="panel detail-information-panel">

                    <div class="panel-header">

                        <div>

                            <h2>
                                Informasi Buku
                            </h2>

                            <span>
                                Informasi utama koleksi buku
                            </span>

                        </div>

                    </div>


                    <div class="book-information">

                        <div class="information-item">

                            <span class="information-label">
                                ID Buku
                            </span>

                            <strong class="book-id">
                                #{{ $book->book_id }}
                            </strong>

                        </div>


                        <div class="information-item">

                            <span class="information-label">
                                Tahun Terbit
                            </span>

                            <strong>
                                {{ $book->publication_year ?? '-' }}
                            </strong>

                        </div>


                        <div class="information-item">

                            <span class="information-label">
                                Asal
                            </span>

                            <strong>
                                {{ $book->origin ?? '-' }}
                            </strong>

                        </div>


                        <div class="information-item">

                            <span class="information-label">
                                Status Buku
                            </span>

                            <strong class="information-status">
                                {{ ucfirst($book->status ?? '-') }}
                            </strong>

                        </div>


                        <div class="information-item information-description">

                            <span class="information-label">
                                Deskripsi
                            </span>

                            <p>
                                {{ $book->description ?? '-' }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- RINGKASAN --}}

                <div class="panel summary-panel">

                    <div class="panel-header">

                        <div>

                            <h2>
                                Ringkasan
                            </h2>

                            <span>
                                Statistik eksemplar buku
                            </span>

                        </div>

                    </div>


                    <div class="summary-list">

                        {{-- TOTAL --}}

                        <div class="summary-item">

                            <div class="summary-icon total-icon">
                                ▣
                            </div>

                            <div class="summary-info">

                                <span>
                                    Total Eksemplar
                                </span>

                                <strong>
                                    {{ $book->copies->count() }}
                                </strong>

                            </div>

                        </div>


                        {{-- TERSEDIA --}}

                        <div class="summary-item">

                            <div class="summary-icon available-icon">
                                ✓
                            </div>

                            <div class="summary-info">

                                <span>
                                    Tersedia
                                </span>

                                <strong>
                                    {{ $book->copies->where('status', 'tersedia')->count() }}
                                </strong>

                            </div>

                        </div>


                        {{-- DIPINJAM --}}

                        <div class="summary-item">

                            <div class="summary-icon borrowed-icon">
                                ↗
                            </div>

                            <div class="summary-info">

                                <span>
                                    Dipinjam
                                </span>

                                <strong>
                                    {{ $book->copies->where('status', 'dipinjam')->count() }}
                                </strong>

                            </div>

                        </div>


                        {{-- RUSAK --}}

                        <div class="summary-item">

                            <div class="summary-icon damaged-icon">
                                !
                            </div>

                            <div class="summary-info">

                                <span>
                                    Kondisi Rusak
                                </span>

                                <strong>
                                    {{ $book->copies->where('status', 'rusak')->count() }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 EKSEMPLAR
            ================================================== --}}

            <div class="panel copies-panel">

                <div class="panel-header copies-header">

                    <div>

                        <h2>
                            Daftar Eksemplar
                        </h2>

                        <span>
                            {{ $book->copies->count() }}
                            eksemplar terdaftar untuk buku ini
                        </span>

                    </div>


                    <a href="{{ route('book-copies.create', ['book_id' => $book->book_id]) }}"
                       class="add-copy-button">

                        <span>
                            +
                        </span>

                        Tambah Eksemplar

                    </a>

                </div>


                @if ($book->copies->count() > 0)

                    <div class="copies-table-wrapper">

                        <table class="copies-table">

                            <thead>

                                <tr>

                                    <th class="col-number">
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

                                    <th class="col-action">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($book->copies as $index => $copy)

                                    <tr>

                                        <td class="number-cell">
                                            {{ $index + 1 }}
                                        </td>


                                        <td>

                                            <span class="copy-code">
                                                {{ $copy->copy_code }}
                                            </span>

                                        </td>


                                        <td>

                                            <span class="condition">
                                                {{ ucfirst($copy->condition) }}
                                            </span>

                                        </td>


                                        <td>

                                            @if ($copy->status === 'tersedia')

                                                <span class="copy-status available">
                                                    <span class="status-dot"></span>
                                                    Tersedia
                                                </span>

                                            @elseif ($copy->status === 'dipinjam')

                                                <span class="copy-status borrowed">
                                                    <span class="status-dot"></span>
                                                    Dipinjam
                                                </span>

                                            @elseif ($copy->status === 'rusak')

                                                <span class="copy-status damaged">
                                                    <span class="status-dot"></span>
                                                    Rusak
                                                </span>

                                            @elseif ($copy->status === 'hilang')

                                                <span class="copy-status lost">
                                                    <span class="status-dot"></span>
                                                    Hilang
                                                </span>

                                            @else

                                                <span class="copy-status">
                                                    {{ ucfirst($copy->status) }}
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            <span class="copy-notes">
                                                {{ $copy->notes ?? '-' }}
                                            </span>

                                        </td>


                                        <td>

                                            <div class="copy-actions">

                                                <a href="{{ route('book-copies.edit', $copy->copy_id) }}"
                                                   class="action-edit">
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
                                                        class="action-delete"
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

                    <div class="empty-copies">

                        <div class="empty-icon">
                            📚
                        </div>

                        <h3>
                            Belum Ada Eksemplar
                        </h3>

                        <p>
                            Belum ada eksemplar yang terdaftar
                            untuk buku ini.
                        </p>

                        <a href="{{ route('book-copies.create', ['book_id' => $book->book_id]) }}"
                           class="add-copy-button">

                            <span>+</span>

                            Tambah Eksemplar

                        </a>

                    </div>

                @endif

            </div>

        </section>

    </main>

</div>

</body>
</html>
```
