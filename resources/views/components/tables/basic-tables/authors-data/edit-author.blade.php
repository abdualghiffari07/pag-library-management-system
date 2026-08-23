@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-4xl px-4 py-6">

    {{-- Header --}}
    <div class="mb-6">

        <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">

            <a
                href="{{ route('authors') }}"
                class="transition hover:text-blue-600"
            >
                Data Penulis
            </a>

            <span>/</span>

            <span class="text-gray-700 dark:text-gray-300">
                Edit Penulis
            </span>

        </div>

        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
            Edit Penulis
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Perbarui informasi penulis buku.
        </p>

    </div>


    {{-- Form Card --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

        <form
            action="{{ route('authors.update', $author->author_id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            {{-- Error --}}
            @if ($errors->any())

                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

                    <p class="mb-1 font-medium">
                        Terdapat kesalahan:
                    </p>

                    <ul class="list-inside list-disc">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =====================================================
                 INFORMASI DASAR
            ====================================================== --}}

            <div class="mb-6">

                <h2 class="mb-1 text-base font-semibold text-gray-800 dark:text-white/90">
                    Informasi Dasar
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi identitas utama penulis.
                </p>

            </div>


            {{-- Nama Lengkap --}}
            <div class="mb-5">

                <label
                    for="author_name"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Nama Lengkap
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="author_name"
                    name="author_name"
                    value="{{ old('author_name', $author->author_name) }}"
                    required
                    maxlength="255"
                    placeholder="Contoh: Dr. Budi Santoso, S.Pd."
                    class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >

                @error('author_name')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Nama Pena --}}
            <div class="mb-5">

                <label
                    for="pseudonym"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Nama Pena
                </label>

                <input
                    type="text"
                    id="pseudonym"
                    name="pseudonym"
                    value="{{ old('pseudonym', $author->pseudonym) }}"
                    maxlength="255"
                    placeholder="Masukkan nama pena jika ada"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >

                @error('pseudonym')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- =====================================================
                 PENDIDIKAN
            ====================================================== --}}

            <div class="mb-6 mt-8 border-t border-gray-100 pt-6 dark:border-gray-800">

                <h2 class="mb-1 text-base font-semibold text-gray-800 dark:text-white/90">
                    Latar Belakang Pendidikan
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi pendidikan dan pelatihan yang relevan dengan keahlian penulis.
                </p>

            </div>


            {{-- Pendidikan --}}
            <div class="mb-5">

                <label
                    for="education"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Latar Belakang Pendidikan
                </label>

                <textarea
                    id="education"
                    name="education"
                    rows="4"
                    placeholder="Contoh:
S1 Pendidikan Matematika - Universitas Indonesia, 2015
S2 Pendidikan Matematika - Universitas Gadjah Mada, 2018"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >{{ old('education', $author->education) }}</textarea>

                @error('education')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Pendidikan Lain --}}
            <div class="mb-5">

                <label
                    for="additional_education"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Pendidikan Lain
                </label>

                <textarea
                    id="additional_education"
                    name="additional_education"
                    rows="4"
                    placeholder="Masukkan sertifikasi, pelatihan, riset, workshop, atau pendidikan lain yang relevan..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >{{ old('additional_education', $author->additional_education) }}</textarea>

                @error('additional_education')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- =====================================================
                 PROFIL PROFESIONAL
            ====================================================== --}}

            <div class="mb-6 mt-8 border-t border-gray-100 pt-6 dark:border-gray-800">

                <h2 class="mb-1 text-base font-semibold text-gray-800 dark:text-white/90">
                    Profil Profesional
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi perjalanan karir, publikasi, dan keahlian penulis.
                </p>

            </div>


            {{-- Perjalanan Karir --}}
            <div class="mb-5">

                <label
                    for="career"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Perjalanan Karir
                </label>

                <textarea
                    id="career"
                    name="career"
                    rows="5"
                    placeholder="Jelaskan pekerjaan atau profesi penulis yang relevan dengan bidang buku..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >{{ old('career', $author->career) }}</textarea>

                @error('career')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Publikasi --}}
            <div class="mb-5">

                <label
                    for="publications"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Publikasi
                </label>

                <textarea
                    id="publications"
                    name="publications"
                    rows="5"
                    placeholder="Masukkan daftar buku, artikel, jurnal, atau publikasi ilmiah yang pernah ditulis..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >{{ old('publications', $author->publications) }}</textarea>

                @error('publications')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Prestasi --}}
            <div class="mb-5">

                <label
                    for="achievements"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Prestasi atau Penghargaan
                </label>

                <textarea
                    id="achievements"
                    name="achievements"
                    rows="4"
                    placeholder="Masukkan prestasi atau penghargaan profesional dan akademik..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >{{ old('achievements', $author->achievements) }}</textarea>

                @error('achievements')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Bidang Keahlian --}}
            <div class="mb-5">

                <label
                    for="expertise"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Bidang Keahlian
                </label>

                <input
                    type="text"
                    id="expertise"
                    name="expertise"
                    value="{{ old('expertise', $author->expertise) }}"
                    maxlength="255"
                    placeholder="Contoh: Teknologi Informasi, Pendidikan, Matematika"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >

                @error('expertise')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Minat Penelitian --}}
            <div class="mb-5">

                <label
                    for="research_interest"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Minat Penelitian
                </label>

                <textarea
                    id="research_interest"
                    name="research_interest"
                    rows="4"
                    placeholder="Masukkan bidang atau topik penelitian yang diminati..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >{{ old('research_interest', $author->research_interest) }}</textarea>

                @error('research_interest')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- =====================================================
                 INFORMASI KONTAK
            ====================================================== --}}

            <div class="mb-6 mt-8 border-t border-gray-100 pt-6 dark:border-gray-800">

                <h2 class="mb-1 text-base font-semibold text-gray-800 dark:text-white/90">
                    Informasi Kontak
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi kontak profesional penulis.
                </p>

            </div>


            {{-- Email --}}
            <div class="mb-5">

                <label
                    for="email"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $author->email) }}"
                    maxlength="255"
                    placeholder="nama@email.com"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >

                @error('email')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Media Sosial --}}
            <div class="mb-6">

                <label
                    for="social_media"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Media Sosial
                </label>

                <input
                    type="text"
                    id="social_media"
                    name="social_media"
                    value="{{ old('social_media', $author->social_media) }}"
                    maxlength="255"
                    placeholder="Contoh: LinkedIn, Instagram, X, atau akun profesional lainnya"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >

                @error('social_media')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- =====================================================
                 BUKU
            ====================================================== --}}

            <div class="mb-6 mt-8 border-t border-gray-100 pt-6 dark:border-gray-800">

                <h2 class="mb-1 text-base font-semibold text-gray-800 dark:text-white/90">
                    Buku yang Ditulis
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Masukkan judul buku yang pernah atau sedang ditulis oleh penulis.
                </p>

            </div>


            {{-- Buku --}}
            <div class="mb-6">

                <label
                    for="books"
                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Judul Buku
                </label>

                <textarea
                    id="books"
                    name="books"
                    rows="4"
                    placeholder="Contoh:
Dasar-Dasar Pemrograman
Pengantar Teknologi Informasi
Sistem Informasi Perpustakaan"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-500"
                >{{ old('books', $author->books) }}</textarea>

                <p class="mt-1 text-xs text-gray-400">
                    Masukkan satu atau beberapa judul buku. Setiap judul dapat ditulis pada baris baru.
                </p>

                @error('books')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Buttons --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">

                <a
                    href="{{ route('authors') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                >
                    Perbarui Penulis
                </button>

            </div>

        </form>

    </div>

</div>

@endsection