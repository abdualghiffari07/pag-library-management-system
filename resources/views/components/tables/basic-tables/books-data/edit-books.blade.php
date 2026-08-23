@extends('layouts.app')

@section('content')

    <div
        class="space-y-6"
        x-data="{
            bookNo: @js(old('book_no', $book->book_code)),
            bookNoExists: false,
            checkingBookNo: false,

            async checkBookNo() {
                const value = this.bookNo.trim();

                this.bookNoExists = false;

                if (!value || value === @js($book->book_code)) {
                    return;
                }

                this.checkingBookNo = true;

                try {
                    const response = await fetch(
                        '{{ route('books.check-book-no') }}?book_no=' +
                        encodeURIComponent(value)
                    );

                    const data = await response.json();

                    this.bookNoExists = data.exists;
                } catch (error) {
                    console.error(error);
                } finally {
                    this.checkingBookNo = false;
                }
            }
        }"
    >

        {{-- =========================================================
             EDIT BUKU
        ========================================================== --}}

        <x-common.component-card title="Edit Buku">

            <form
                action="{{ route('books.update', $book->book_id) }}"
                method="POST"
                class="space-y-6"
                @submit="if (bookNoExists || checkingBookNo) $event.preventDefault()"
            >
                @csrf
                @method('PUT')

                {{-- Form Buku --}}

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Book No. --}}

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            BOOK NO.
                        </label>

                        <input
                            type="text"
                            name="book_no"
                            x-model="bookNo"
                            @input.debounce.400ms="checkBookNo()"
                            required
                            class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90"
                            :class="bookNoExists
                                ? 'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700'
                                : 'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800'"
                        >

                        <p
                            x-show="bookNoExists"
                            x-cloak
                            class="mt-1.5 text-xs text-error-500"
                        >
                            Book No. sudah digunakan. Silakan gunakan Book No. lain.
                        </p>

                        @error('book_no')
                            <p class="mt-1.5 text-xs text-error-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Cat No. --}}

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            CAT. NO.
                        </label>

                        <input
                            type="text"
                            name="cat_no"
                            value="{{ old('cat_no', $book->cat_no) }}"
                            required
                            placeholder="Masukkan nomor katalog"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                        >

                        @error('cat_no')
                            <p class="mt-1.5 text-xs text-error-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
{{-- lOCATION --}}

<div>
    <label
        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
    >
        LOCATION
    </label>

    <input
        type="text"
        name="rack"
        value="{{ old('rack', $book->rack) }}"
        required
        placeholder="Masukkan lokasi buku"
        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
    >

    @error('rack')
        <p class="mt-1.5 text-xs text-error-500">
            {{ $message }}
        </p>
    @enderror
</div>

                    {{-- Title --}}

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            TITLE
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $book->title) }}"
                            required
                            placeholder="Masukkan judul buku"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                        >

                        @error('title')
                            <p class="mt-1.5 text-xs text-error-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Author --}}

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            AUTHOR
                        </label>

                        <input
                            type="text"
                            name="author"
                            value="{{ old(
                                'author',
                                $book->authors->pluck('author_name')->join(', ')
                            ) }}"
                            required
                            placeholder="Masukkan nama penulis"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                        >

                        @error('author')
                            <p class="mt-1.5 text-xs text-error-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Publisher --}}

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            PUBLISHER
                        </label>

                        <input
                            type="text"
                            name="publisher"
                            value="{{ old('publisher', $book->publisher) }}"
                            required
                            placeholder="Masukkan nama penerbit"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                        >

                        @error('publisher')
                            <p class="mt-1.5 text-xs text-error-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Quantity --}}

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            QTY
                        </label>

                        <input
                            type="number"
                            name="qty"
                            value="{{ old('qty', $book->copies->count()) }}"
                            min="1"
                            required
                            placeholder="Masukkan jumlah buku"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                        >

                        @error('qty')
                            <p class="mt-1.5 text-xs text-error-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Description --}}

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        DESCRIPTION
                    </label>

                    <textarea
                        name="description"
                        rows="6"
                        placeholder="Masukkan deskripsi buku..."
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >{{ old('description', $book->description) }}</textarea>

                    @error('description')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Action --}}

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-800">
                    <a
                        href="{{ route('data-buku') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        :disabled="bookNoExists || checkingBookNo"
                        :class="bookNoExists || checkingBookNo
                            ? 'cursor-not-allowed opacity-50'
                            : 'hover:bg-brand-600'"
                        class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs transition"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </x-common.component-card>

        {{-- =========================================================
             PEMINJAMAN
        ========================================================== --}}

        @php
            $totalQty = $book->copies->count();

            $borrowedCopies = $book->copies
                ->filter(fn ($copy) => strtolower(trim($copy->status)) === 'dipinjam')
                ->count();

            $availableCopies = $book->copies
                ->filter(fn ($copy) => strtolower(trim($copy->status)) === 'tersedia')
                ->count();
        @endphp

        <x-common.component-card title="Peminjaman">

            <div class="space-y-6">

                {{-- Ringkasan --}}

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                    {{-- Total QTY --}}

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            TOTAL QTY
                        </p>

                        <p class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white">
                            {{ $totalQty }}
                        </p>
                    </div>

                    {{-- Sedang Dipinjam --}}

                    <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-5 dark:border-yellow-500/20 dark:bg-yellow-500/10">
                        <p class="text-xs font-medium text-yellow-700 dark:text-yellow-400">
                            SEDANG DIPINJAM
                        </p>

                        <p class="mt-2 text-3xl font-semibold text-yellow-800 dark:text-yellow-400">
                            {{ $borrowedCopies }}
                        </p>
                    </div>

                    {{-- Tersedia --}}

                    <div class="rounded-lg border border-green-200 bg-green-50 p-5 dark:border-green-500/20 dark:bg-green-500/10">
                        <p class="text-xs font-medium text-green-700 dark:text-green-400">
                            TERSEDIA
                        </p>

                        <p class="mt-2 text-3xl font-semibold text-green-800 dark:text-green-400">
                            {{ $availableCopies }}
                        </p>
                    </div>

                </div>

                {{-- Daftar Peminjam --}}

                @if($activeLoans->count() > 0)

                    <div class="space-y-4">

                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-white">
                                Daftar Peminjam
                            </p>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Daftar buku yang sedang dipinjam.
                            </p>
                        </div>

                        @foreach($activeLoans as $loanDetail)

                            <div class="rounded-lg border border-gray-200 p-5 dark:border-gray-800">

                                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                                    {{-- Nama Peminjam --}}

                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            NAMA PEMINJAM
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">
                                            {{ $loanDetail->loan->borrower_name }}
                                        </p>
                                    </div>

                                    {{-- No. Pekerja --}}

                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            NO. PEKERJA
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">
                                            {{ $loanDetail->loan->nopek ?? '-' }}
                                        </p>
                                    </div>

                                    {{-- Tanggal Pinjam --}}

                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            TANGGAL PINJAM
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">
                                            {{ \Carbon\Carbon::parse($loanDetail->loan->loan_date)->format('d-m-Y') }}
                                        </p>
                                    </div>

                                </div>

                                {{-- Eksemplar --}}

                                @if($loanDetail->bookCopy)
                                    <div class="mt-4">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            EKSEMPLAR
                                        </p>

                                        <p class="mt-1 text-sm text-gray-800 dark:text-white">
                                            {{ $loanDetail->bookCopy->copy_code }}
                                        </p>
                                    </div>
                                @endif

                                {{-- Action --}}

                                <div class="mt-4 flex justify-end border-t border-gray-100 pt-4 dark:border-gray-800">

                                <form
                                    action="{{ route('books.return', [
                                        'book_id' => $book->book_id,
                                        'loan_detail_id' => $loanDetail->loan_detail_id,
                                    ]) }}"
                                    method="POST"
                                >
                                        @csrf

                                        <button
                                            type="submit"
                                            onclick="return confirm('Apakah buku milik {{ $loanDetail->loan->borrower_name }} sudah dikembalikan?')"
                                            class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700"
                                        >
                                            Buku Sudah Dikembalikan
                                        </button>
                                    </form>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Belum ada buku yang sedang dipinjam.
                        </p>
                    </div>

                @endif

                {{-- Form Peminjaman --}}

                @if($availableCopies > 0)

                    <div class="border-t border-gray-200 pt-5 dark:border-gray-800">

                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">
                                Pinjam Buku
                            </p>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Masih tersedia
                                <span class="font-semibold">
                                    {{ $availableCopies }}
                                </span>
                                eksemplar untuk dipinjam.
                            </p>
                        </div>

                        <form
                            action="{{ route('books.borrow', $book->book_id) }}"
                            method="POST"
                            class="space-y-5"
                        >
                            @csrf

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                                {{-- Nama Peminjam --}}

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        NAMA PEMINJAM
                                    </label>

                                    <input
                                        type="text"
                                        name="borrower_name"
                                        value="{{ old('borrower_name') }}"
                                        required
                                        placeholder="Masukkan nama peminjam"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    >

                                    @error('borrower_name')
                                        <p class="mt-1.5 text-xs text-error-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- No. Pekerja --}}

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        NO. PEKERJA
                                        <span class="font-normal text-gray-400">
                                            (Opsional)
                                        </span>
                                    </label>

                                    <input
                                        type="text"
                                        name="nopek"
                                        value="{{ old('nopek') }}"
                                        placeholder="Masukkan No. Pekerja"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    >

                                    @error('nopek')
                                        <p class="mt-1.5 text-xs text-error-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Tanggal Pinjaman --}}

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        TANGGAL PINJAMAN
                                    </label>

                                    <input
                                        type="date"
                                        name="loan_date"
                                        value="{{ old('loan_date', now()->format('Y-m-d')) }}"
                                        required
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    >

                                    @error('loan_date')
                                        <p class="mt-1.5 text-xs text-error-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                            </div>

                            {{-- Tombol Peminjaman --}}

                            <div class="border-t border-gray-200 pt-5 dark:border-gray-800">

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600"
                                >
                                    Simpan Peminjaman
                                </button>

                            </div>

                        </form>

                    </div>

                @else

                    {{-- Semua Eksemplar Dipinjam --}}

                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-500/20 dark:bg-red-500/10">

                        <p class="text-sm font-medium text-red-700 dark:text-red-400">
                            Semua eksemplar buku sedang dipinjam.
                        </p>

                    </div>

                @endif

            </div>

        </x-common.component-card>

    </div>

@endsection