<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $author->author_name }} - PAG Library</title>

    @vite([
        'resources/css/dashboard-admin.css',
        'resources/css/authors-details.css',
        'resources/js/dashboard-admin.js'
    ])
</head>

<body>

<div class="app">

    {{-- =================================================
         SIDEBAR
    ================================================== --}}
    @include('layouts.sidebar')


    {{-- =================================================
         MOBILE OVERLAY
    ================================================== --}}
    <div id="sidebar-overlay" class="sidebar-overlay"></div>


    {{-- =================================================
         MAIN
    ================================================== --}}
    <main class="main dashboard-page author-details-page-main">


@include('layouts.header', ['pageTitle' => 'Penulis'])


        {{-- =================================================
             CONTENT
        ================================================== --}}
        <section class="content author-details-page">


            {{-- =================================================
                 BACK
            ================================================== --}}
            <div class="author-back-wrapper">

                <a
                    href="{{ route('authors.index') }}"
                    class="author-back-button"
                >
                    ←
                    <span>Kembali ke Daftar Penulis</span>
                </a>

            </div>


            {{-- =================================================
                 AUTHOR PROFILE
            ================================================== --}}
            <section class="author-hero-card">

                <div class="author-hero-content">

                    <div class="author-avatar-large">
                        {{ strtoupper(substr($author->author_name, 0, 1)) }}
                    </div>

                    <div class="author-hero-text">

                        <span class="author-section-label">
                            PROFIL PENULIS
                        </span>

                        <h2>
                            {{ $author->author_name }}
                        </h2>

                        @if ($author->pseudonym)

                            <p class="author-pseudonym">
                                Nama pena:
                                <strong>
                                    {{ $author->pseudonym }}
                                </strong>
                            </p>

                        @else

                            <p class="author-pseudonym">
                                Tidak memiliki nama pena
                            </p>

                        @endif

                    </div>

                </div>


                <div class="author-hero-actions">

                    <button
                        type="button"
                        class="author-edit-button"
                        onclick="showAuthorEdit()"
                    >
                        ✎
                        <span>Edit Penulis</span>
                    </button>

                </div>

            </section>


            {{-- =================================================
                 STATISTICS
            ================================================== --}}
            <div class="author-stat-grid">


                {{-- TOTAL BOOK --}}
                <div class="author-stat-card">

                    <div class="author-stat-content">

                        <span>
                            Total Buku
                        </span>

                        <strong>
                            {{ $author->books->count() }}
                        </strong>

                    </div>

                </div>


                {{-- NATIONALITY --}}
                <div class="author-stat-card">


                    <div class="author-stat-content">

                        <span>
                            Kewarganegaraan
                        </span>

                        <strong>
                            {{ $author->nationality ?: '-' }}
                        </strong>

                    </div>

                </div>


                {{-- CREATED --}}
                <div class="author-stat-card">


                    <div class="author-stat-content">

                        <span>
                            Terdaftar Sejak
                        </span>

                        <strong>
                            {{ $author->created_at
                                ? \Carbon\Carbon::parse($author->created_at)->format('d M Y')
                                : '-' }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 AUTHOR INFORMATION
            ================================================== --}}
            <section class="author-information-card">

                <div class="author-section-header">

                    <div>

                        <span class="author-section-label">
                            INFORMASI PENULIS
                        </span>

                        <h2>
                            Data Penulis
                        </h2>

                    </div>

                </div>


                {{-- =================================================
                     VIEW MODE
                ================================================== --}}
                <div id="author-information-view">

                    <div class="author-information-grid">


                        {{-- FULL NAME --}}
                        <div class="author-information-item">

                            <span>
                                Nama Lengkap
                            </span>

                            <strong>
                                {{ $author->author_name }}
                            </strong>

                        </div>


                        {{-- PSEUDONYM --}}
                        <div class="author-information-item">

                            <span>
                                Nama Pena
                            </span>

                            <strong>
                                {{ $author->pseudonym ?: '-' }}
                            </strong>

                        </div>


                        {{-- BIRTH DATE --}}
                        <div class="author-information-item">

                            <span>
                                Tanggal Lahir
                            </span>

                            <strong>

                                @if ($author->birth_date)

                                    {{ \Carbon\Carbon::parse($author->birth_date)->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </strong>

                        </div>


                        {{-- NATIONALITY --}}
                        <div class="author-information-item">

                            <span>
                                Kewarganegaraan
                            </span>

                            <strong>
                                {{ $author->nationality ?: '-' }}
                            </strong>

                        </div>


                        {{-- WEBSITE --}}
                        <div class="author-information-item author-information-full">

                            <span>
                                Website
                            </span>

                            @if ($author->website)

                                <a
                                    href="{{ $author->website }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    {{ $author->website }}
                                </a>

                            @else

                                <strong>
                                    -
                                </strong>

                            @endif

                        </div>

                    </div>


                    {{-- BIOGRAPHY --}}
                    <div class="author-biography">

                        <span>
                            Biografi
                        </span>

                        @if ($author->biography)

                            <p>
                                {{ $author->biography }}
                            </p>

                        @else

                            <p class="author-no-biography">
                                Belum ada informasi biografi untuk penulis ini.
                            </p>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                     EDIT MODE
                ================================================== --}}
                <div
                    id="author-information-edit"
                    style="display: none;"
                >

                    <form
                        action="{{ route('authors.update', $author->author_id) }}"
                        method="POST"
                        class="author-information-edit-form"
                    >

                        @csrf
                        @method('PUT')


                        <div class="author-information-grid">


                            {{-- FULL NAME --}}
                            <div class="author-information-item">

                                <label for="edit_author_name">
                                    Nama Lengkap
                                </label>

                                <input
                                    type="text"
                                    id="edit_author_name"
                                    name="author_name"
                                    value="{{ old('author_name', $author->author_name) }}"
                                    required
                                >

                            </div>


                            {{-- PSEUDONYM --}}
                            <div class="author-information-item">

                                <label for="edit_pseudonym">
                                    Nama Pena
                                </label>

                                <input
                                    type="text"
                                    id="edit_pseudonym"
                                    name="pseudonym"
                                    value="{{ old('pseudonym', $author->pseudonym) }}"
                                >

                            </div>


                            {{-- BIRTH DATE --}}
                            <div class="author-information-item">

                                <label for="edit_birth_date">
                                    Tanggal Lahir
                                </label>

                                <input
                                    type="date"
                                    id="edit_birth_date"
                                    name="birth_date"
                                    value="{{ old('birth_date', $author->birth_date) }}"
                                >

                            </div>


                            {{-- NATIONALITY --}}
                            <div class="author-information-item">

                                <label for="edit_nationality">
                                    Kewarganegaraan
                                </label>

                                <input
                                    type="text"
                                    id="edit_nationality"
                                    name="nationality"
                                    value="{{ old('nationality', $author->nationality) }}"
                                >

                            </div>


                            {{-- WEBSITE --}}
                            <div class="author-information-item author-information-full">

                                <label for="edit_website">
                                    Website
                                </label>

                                <input
                                    type="url"
                                    id="edit_website"
                                    name="website"
                                    value="{{ old('website', $author->website) }}"
                                    placeholder="https://example.com"
                                >

                            </div>

                        </div>


                        {{-- BIOGRAPHY --}}
                        <div class="author-biography author-biography-edit">

                            <label for="edit_biography">
                                Biografi
                            </label>

                            <textarea
                                id="edit_biography"
                                name="biography"
                                rows="5"
                            >{{ old('biography', $author->biography) }}</textarea>

                        </div>


                        {{-- EDIT ACTIONS --}}
                        <div class="author-information-edit-actions">

                            <button
                                type="button"
                                class="author-edit-cancel"
                                onclick="cancelAuthorEdit()"
                            >
                                Batal
                            </button>

                            <button
                                type="submit"
                                class="author-edit-save"
                            >
                                ✓
                                <span>Simpan Perubahan</span>
                            </button>

                        </div>

                    </form>

                </div>

            </section>


            {{-- =================================================
                 BOOK COLLECTION
            ================================================== --}}
            <section class="author-books-card">

                <div class="author-section-header author-books-header">

                    <div>

                        <span class="author-section-label">
                            KOLEKSI BUKU
                        </span>

                        <h2>
                            Buku yang Ditulis
                        </h2>

                        <p>
                            {{ $author->books->count() }}
                            buku terkait dengan penulis ini.
                        </p>

                    </div>

                </div>


                @if ($author->books->count() > 0)

                    <div class="author-books-table-wrapper">

                        <table class="author-books-table">

                            <thead>

                                <tr>

                                    <th class="column-no">
                                        NO
                                    </th>

                                    <th>
                                        ID BUKU
                                    </th>

                                    <th>
                                        JUDUL BUKU
                                    </th>

                                    <th>
                                        TAHUN TERBIT
                                    </th>

                                    <th class="column-action">
                                        AKSI
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($author->books as $index => $book)

                                    <tr>

                                        <td class="column-no">
                                            {{ $index + 1 }}
                                        </td>

                                        <td>

                                            <span class="author-book-id">
                                                #{{ $book->book_id }}
                                            </span>

                                        </td>

                                        <td>

                                            <strong class="author-book-title">
                                                {{ $book->title }}
                                            </strong>

                                        </td>

                                        <td>
                                            {{ $book->publication_year ?? '-' }}
                                        </td>

                                        <td class="column-action">

                                            <a
                                                href="{{ route('books.show', $book->book_id) }}"
                                                class="author-book-detail-button"
                                            >
                                                Detail
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="author-no-books">
                        <h3>Belum Ada Buku</h3>

                        <p>
                            Penulis ini belum memiliki buku yang terdaftar
                            dalam koleksi perpustakaan.
                        </p>
                    </div>

                @endif

            </section>


            {{-- =================================================
                 BOTTOM ACTIONS
            ================================================== --}}
            <div class="author-bottom-actions">

                <a
                    href="{{ route('authors.index') }}"
                    class="author-bottom-back"
                >
                    ←
                    <span>Kembali</span>
                </a>

                <button
                    type="button"
                    class="author-bottom-confirm"
                    onclick="confirmAuthorBack()"
                >
                    ✓
                    <span>Konfirmasi</span>
                </button>

            </div>


        </section>

    </main>

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}
<script>

    function showAuthorEdit() {

        const viewMode =
            document.getElementById('author-information-view');

        const editMode =
            document.getElementById('author-information-edit');

        const nameInput =
            document.getElementById('edit_author_name');


        if (viewMode && editMode) {

            viewMode.style.display = 'none';

            editMode.style.display = 'block';

            if (nameInput) {
                nameInput.focus();
            }

        }

    }


    function cancelAuthorEdit() {

        const viewMode =
            document.getElementById('author-information-view');

        const editMode =
            document.getElementById('author-information-edit');


        if (viewMode && editMode) {

            editMode.style.display = 'none';

            viewMode.style.display = 'block';

        }

    }


        function confirmAuthorBack() {
                const confirmed = confirm(
                    'Apakah Anda yakin ingin Konfirmasi Perubahan?'
                );

                if (confirmed) {
                    window.location.href = "{{ route('authors.index') }}";
                }
            }

</script>


</body>

</html>