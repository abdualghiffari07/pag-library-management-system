<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Penulis - PAG Library</title>

    @vite([
        'resources/css/dashboard-admin.css',
        'resources/css/authors-create.css'
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
                        Tambah Penulis
                    </h1>

                    <div class="header-line"></div>
                </div>

            </div>


            {{-- HEADER RIGHT --}}
            <div class="header-right">

                <div class="admin-profile">

                    <div class="admin-avatar">
                        A
                    </div>

                    <div class="admin-info">

                        <strong>
                            Administrator
                        </strong>

                        <span>
                            Administrator
                        </span>

                    </div>

                </div>

            </div>

        </header>


        {{-- =================================================
             CONTENT
        ================================================== --}}
        <section class="content authors-create-page">


            {{-- =================================================
                 PAGE INTRO
            ================================================== --}}
            <div class="authors-create-intro">

                <div>

                    <span class="create-eyebrow">
                        KOLEKSI PERPUSTAKAAN
                    </span>

                    <h2>
                        Tambah Penulis
                    </h2>

                    <p>
                        Tambahkan informasi penulis baru ke dalam
                        koleksi perpustakaan PAG.
                    </p>

                </div>

            </div>


            {{-- =================================================
                 VALIDATION ERROR
            ================================================== --}}
            @if ($errors->any())

                <div class="create-alert error">

                    <div class="alert-icon">
                        !
                    </div>

                    <div>

                        <strong>
                            Data belum dapat disimpan
                        </strong>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 FORM CARD
            ================================================== --}}
            <div class="authors-form-card">

                <div class="form-card-header">

                    <div class="form-header-icon">
                        ✒
                    </div>

                    <div>

                        <h3>
                            Informasi Penulis
                        </h3>

                        <p>
                            Isi informasi penulis dengan lengkap.
                        </p>

                    </div>

                </div>


                <form action="{{ route('authors.store') }}"
                      method="POST"
                      class="authors-form">

                    @csrf


                    {{-- =================================================
                         NAMA PENULIS
                    ================================================== --}}
                    <div class="form-group">

                        <label for="author_name">
                            Nama Penulis
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="author_name"
                            name="author_name"
                            value="{{ old('author_name') }}"
                            placeholder="Contoh: Robert C. Martin"
                            maxlength="255"
                            required
                            autofocus
                            class="@error('author_name') input-error @enderror"
                        >

                        @error('author_name')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- =================================================
                         NAMA PENA
                    ================================================== --}}
                    <div class="form-group">

                        <label for="pseudonym">
                            Nama Pena
                            <span class="optional">
                                Opsional
                            </span>
                        </label>

                        <input
                            type="text"
                            id="pseudonym"
                            name="pseudonym"
                            value="{{ old('pseudonym') }}"
                            placeholder="Contoh: Mark Twain"
                            maxlength="255"
                            class="@error('pseudonym') input-error @enderror"
                        >

                        <span class="field-help">
                            Nama samaran yang digunakan penulis jika ada.
                        </span>

                        @error('pseudonym')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- =================================================
                         ROW: TANGGAL LAHIR + KEWARGANEGARAAN
                    ================================================== --}}
                    <div class="form-grid">


                        {{-- TANGGAL LAHIR --}}
                        <div class="form-group">

                            <label for="birth_date">
                                Tanggal Lahir
                                <span class="optional">
                                    Opsional
                                </span>
                            </label>

                            <input
                                type="date"
                                id="birth_date"
                                name="birth_date"
                                value="{{ old('birth_date') }}"
                                class="@error('birth_date') input-error @enderror"
                            >

                            @error('birth_date')
                                <span class="field-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        {{-- KEWARGANEGARAAN --}}
                        <div class="form-group">

                            <label for="nationality">
                                Kewarganegaraan
                                <span class="optional">
                                    Opsional
                                </span>
                            </label>

                            <select
                                id="nationality"
                                name="nationality"
                                class="@error('nationality') input-error @enderror"
                            >

                                <option value="">
                                    Pilih kewarganegaraan
                                </option>

                                <option value="Indonesia"
                                    {{ old('nationality') == 'Indonesia' ? 'selected' : '' }}>
                                    Indonesia
                                </option>

                                <option value="Malaysia"
                                    {{ old('nationality') == 'Malaysia' ? 'selected' : '' }}>
                                    Malaysia
                                </option>

                                <option value="Singapura"
                                    {{ old('nationality') == 'Singapura' ? 'selected' : '' }}>
                                    Singapura
                                </option>

                                <option value="Amerika Serikat"
                                    {{ old('nationality') == 'Amerika Serikat' ? 'selected' : '' }}>
                                    Amerika Serikat
                                </option>

                                <option value="Inggris"
                                    {{ old('nationality') == 'Inggris' ? 'selected' : '' }}>
                                    Inggris
                                </option>

                                <option value="Jepang"
                                    {{ old('nationality') == 'Jepang' ? 'selected' : '' }}>
                                    Jepang
                                </option>

                                <option value="Korea Selatan"
                                    {{ old('nationality') == 'Korea Selatan' ? 'selected' : '' }}>
                                    Korea Selatan
                                </option>

                                <option value="Tiongkok"
                                    {{ old('nationality') == 'Tiongkok' ? 'selected' : '' }}>
                                    Tiongkok
                                </option>

                                <option value="India"
                                    {{ old('nationality') == 'India' ? 'selected' : '' }}>
                                    India
                                </option>

                                <option value="Lainnya"
                                    {{ old('nationality') == 'Lainnya' ? 'selected' : '' }}>
                                    Lainnya
                                </option>

                            </select>

                            @error('nationality')
                                <span class="field-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- =================================================
                         BIOGRAFI
                    ================================================== --}}
                    <div class="form-group">

                        <label for="biography">
                            Biografi
                            <span class="optional">
                                Opsional
                            </span>
                        </label>

                        <textarea
                            id="biography"
                            name="biography"
                            rows="6"
                            maxlength="5000"
                            placeholder="Tuliskan informasi singkat mengenai penulis..."
                            class="@error('biography') input-error @enderror"
                        >{{ old('biography') }}</textarea>

                        <div class="textarea-footer">

                            <span class="field-help">
                                Maksimal 5000 karakter.
                            </span>

                            <span id="biography-counter">
                                0 / 5000
                            </span>

                        </div>

                        @error('biography')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- =================================================
                         WEBSITE
                    ================================================== --}}
                    <div class="form-group">

                        <label for="website">
                            Website
                            <span class="optional">
                                Opsional
                            </span>
                        </label>

                        <div class="website-input">

                            <span class="website-prefix">
                                🔗
                            </span>

                            <input
                                type="url"
                                id="website"
                                name="website"
                                value="{{ old('website') }}"
                                placeholder="https://example.com"
                                maxlength="255"
                                class="@error('website') input-error @enderror"
                            >

                        </div>

                        <span class="field-help">
                            Masukkan alamat website atau situs resmi penulis.
                        </span>

                        @error('website')
                            <span class="field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- =================================================
                         FORM ACTIONS
                    ================================================== --}}
                    <div class="form-actions">

                        <a href="{{ route('authors.index') }}"
                           class="btn-cancel">

                            Batal

                        </a>


                        <button type="submit"
                                class="btn-submit">

                            <span>
                                ✓
                            </span>

                            Simpan Penulis

                        </button>

                    </div>

                </form>

            </div>


            {{-- =================================================
                 FOOTNOTE
            ================================================== --}}
            <div class="create-footnote">

                <span class="footnote-icon">
                    ✓
                </span>

                <span>
                    Data penulis akan tersimpan ke dalam database
                    perpustakaan setelah formulir berhasil dikirim.
                </span>

            </div>


        </section>

    </main>

</div>


{{-- =====================================================
     SIMPLE SIDEBAR SCRIPT
====================================================== --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const sidebarToggle = document.getElementById('sidebar-toggle');
    const overlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle) {

        sidebarToggle.addEventListener('click', function () {

            document.body.classList.toggle('sidebar-open');

        });

    }

    if (overlay) {

        overlay.addEventListener('click', function () {

            document.body.classList.remove('sidebar-open');

        });

    }


    /* ==========================================
       BIOGRAPHY CHARACTER COUNTER
    ========================================== */

    const biography = document.getElementById('biography');
    const counter = document.getElementById('biography-counter');

    if (biography && counter) {

        function updateCounter() {

            counter.textContent =
                biography.value.length + ' / 5000';

        }

        biography.addEventListener('input', updateCounter);

        updateCounter();

    }

});

</script>

</body>

</html>