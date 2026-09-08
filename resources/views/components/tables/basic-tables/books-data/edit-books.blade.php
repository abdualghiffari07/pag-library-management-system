@props([
    'book',
    'totalQty' => 0,
    'borrowedCopies' => 0,
    'availableCopies' => 0,
    'activeLoans' => null,
    'equipments' => null,
    'authors' => null,
    'visitors' => null,
])

@php
    $activeLoans = $activeLoans ?? collect();
    $equipments = $equipments ?? collect();
    $authors = $authors ?? collect();
    $visitors = $visitors ?? collect();
@endphp

<div class="space-y-6">

    {{-- Edit Buku --}}
    <x-common.component-card title="Edit Buku">
        <form
            action="{{ route('books.update', $book->book_id) }}"
            method="POST"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Book No --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        BOOK NO.
                    </label>

                    <input
                        type="text"
                        name="book_no"
                        value="{{ old('book_no', $book->book_code) }}"
                        required
                        autocomplete="off"
                        placeholder="Masukkan nomor buku"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >

                    @error('book_no')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tag No --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        TAG NO.
                    </label>

                    <input
                        type="text"
                        name="tag_no"
                        value="{{ old('tag_no', $book->tag_no) }}"
                        placeholder="Masukkan Tag No."
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >

                    @error('tag_no')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Cat No --}}
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
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >

                    @error('cat_no')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Equipment --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        EQUIPMENT
                    </label>

                    <select
                        name="equipment_id"
                        required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >
                        <option value="">
                            Pilih Equipment
                        </option>

                        @foreach ($equipments as $equipment)
                            <option
                                value="{{ $equipment->equipment_id }}"
                                @selected(
                                    old(
                                        'equipment_id',
                                        $book->equipment_id
                                    ) == $equipment->equipment_id
                                )
                            >
                                {{ $equipment->equipment_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('equipment_id')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Location --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        LOCATION
                    </label>

                    <input
                        type="text"
                        name="location"
                        value="{{ old('location', $book->rack) }}"
                        required
                        placeholder="Masukkan lokasi buku"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >

                    @error('location')
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
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
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
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
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
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
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
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >

                    @error('qty')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remark --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        REMARK
                    </label>

                    <input
                        type="text"
                        name="remark"
                        value="{{ old('remark', $book->remark) }}"
                        placeholder="Masukkan catatan buku"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >

                    @error('remark')
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
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
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
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>
    </x-common.component-card>

    {{-- Peminjaman --}}
    <x-common.component-card title="Peminjaman">
        <div class="space-y-6">

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        TOTAL QTY
                    </p>

                    <p class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white">
                        {{ $totalQty }}
                    </p>
                </div>

                <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-5 dark:border-yellow-500/20 dark:bg-yellow-500/10">
                    <p class="text-xs font-medium text-yellow-700 dark:text-yellow-400">
                        SEDANG DIPINJAM
                    </p>

                    <p class="mt-2 text-3xl font-semibold text-yellow-800 dark:text-yellow-400">
                        {{ $borrowedCopies }}
                    </p>
                </div>

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
            @if ($activeLoans->isNotEmpty())

                <div class="space-y-4">

                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">
                            Daftar Peminjam
                        </p>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Daftar eksemplar buku yang sedang dipinjam.
                        </p>
                    </div>

                    @foreach ($activeLoans as $loanDetail)

                        <div class="rounded-lg border border-gray-200 p-5 dark:border-gray-800">

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        NAMA PEMINJAM
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ $loanDetail->loan->borrower_name }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        NOMOR IDENTITAS
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ $loanDetail->loan->nopek ?? '-' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        TANGGAL PINJAM
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ \Carbon\Carbon::parse(
                                            $loanDetail->loan->loan_date
                                        )->format('d-m-Y') }}
                                    </p>
                                </div>

                            </div>

                            @if ($loanDetail->bookCopy)
                                <div class="mt-4">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        EKSEMPLAR
                                    </p>

                                    <p class="mt-1 text-sm text-gray-800 dark:text-white">
                                        {{ $loanDetail->bookCopy->copy_code }}
                                    </p>
                                </div>
                            @endif

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
            @if ($availableCopies > 0)

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
                        x-data="{
                            openVisitor: false,

                            visitorId: @js((string) old('visitor_id', '')),

                            borrowerName: @js(
                                old('borrower_name', '')
                            ),

                            identityNumber: @js(
                                old('nopek', '')
                            ),

                            visitors: @js(
                                $visitors->map(function ($visitor) {
                                    return [
                                        'id' => (string) $visitor->visitor_id,
                                        'name' => $visitor->visitor_name,
                                        'identity' => $visitor->employee_number ?? '',
                                        'category' => $visitor->visitor_category ?? '',
                                    ];
                                })->values()
                            ),

                            initVisitor() {
                                if (!this.visitorId) {
                                    return;
                                }

                                const visitor = this.visitors.find(
                                    item =>
                                        String(item.id) ===
                                        String(this.visitorId)
                                );

                                if (!visitor) {
                                    this.visitorId = '';
                                    this.borrowerName = '';
                                    this.identityNumber = '';
                                    return;
                                }

                                this.borrowerName = visitor.name ?? '';
                                this.identityNumber = visitor.identity ?? '';
                            },

                            get filteredVisitors() {
                                const keyword = this.borrowerName
                                    .toLowerCase()
                                    .trim();

                                if (!keyword) {
                                    return this.visitors.slice(0, 8);
                                }

                                return this.visitors
                                    .filter(visitor => {
                                        const name = String(
                                            visitor.name ?? ''
                                        ).toLowerCase();

                                        const identity = String(
                                            visitor.identity ?? ''
                                        ).toLowerCase();

                                        return (
                                            name.includes(keyword) ||
                                            identity.includes(keyword)
                                        );
                                    })
                                    .slice(0, 8);
                            },

                            handleVisitorInput() {
                                this.openVisitor = true;

                                if (!this.visitorId) {
                                    this.identityNumber = '';
                                    return;
                                }

                                const selected = this.visitors.find(
                                    visitor =>
                                        String(visitor.id) ===
                                        String(this.visitorId)
                                );

                                if (
                                    !selected ||
                                    selected.name !== this.borrowerName
                                ) {
                                    this.visitorId = '';
                                    this.identityNumber = '';
                                }
                            },

                            selectVisitor(visitor) {
                                this.visitorId = String(visitor.id);
                                this.borrowerName = visitor.name ?? '';
                                this.identityNumber = visitor.identity ?? '';
                                this.openVisitor = false;
                            },

                            clearVisitor() {
                                this.visitorId = '';
                                this.borrowerName = '';
                                this.identityNumber = '';
                                this.openVisitor = false;
                            }
                        }"
                        x-init="initVisitor()"
                        @submit="
                            if (!visitorId) {
                                $event.preventDefault();
                                openVisitor = true;
                            }
                        "
                    >
                        @csrf

                        <input
                            type="hidden"
                            name="visitor_id"
                            :value="visitorId"
                        >

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                            {{-- Nama Peminjam --}}
                            <div
                                class="relative"
                                @click.outside="openVisitor = false"
                            >
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    NAMA PEMINJAM
                                </label>

                                <div class="relative">

                                    <input
                                        type="text"
                                        name="borrower_name"
                                        x-model="borrowerName"
                                        @input="handleVisitorInput()"
                                        @focus="openVisitor = true"
                                        autocomplete="off"
                                        required
                                        placeholder="Cari nama atau nomor identitas"
                                        class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 outline-none focus:ring-3 dark:bg-gray-900 dark:text-white/90"
                                        :class="
                                            borrowerName && !visitorId
                                                ? 'border-yellow-400 focus:border-yellow-400 focus:ring-yellow-500/10'
                                                : 'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700'
                                        "
                                    >

                                    <button
                                        x-show="borrowerName.length > 0"
                                        x-cloak
                                        type="button"
                                        @click="clearVisitor()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-gray-600 dark:hover:text-gray-200"
                                        title="Hapus pilihan"
                                    >
                                        <svg
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M18 6L6 18M6 6L18 18"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                            />
                                        </svg>
                                    </button>

                                </div>

                                {{-- Dropdown --}}
                                <div
                                    x-show="openVisitor"
                                    x-cloak
                                    class="absolute left-0 right-0 z-50 mt-2 max-h-72 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-gray-900"
                                >

                                    <template x-if="filteredVisitors.length > 0">
                                        <div class="py-1">

                                            <template
                                                x-for="visitor in filteredVisitors"
                                                :key="visitor.id"
                                            >
                                                <button
                                                    type="button"
                                                    @click="selectVisitor(visitor)"
                                                    class="flex w-full items-center justify-between gap-4 border-b border-gray-100 px-4 py-3 text-left transition last:border-b-0 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-white/[0.05]"
                                                >
                                                    <div class="min-w-0">

                                                        <p
                                                            class="truncate text-sm font-medium text-gray-800 dark:text-white/90"
                                                            x-text="visitor.name"
                                                        ></p>

                                                        <div class="mt-1 flex min-w-0 items-center gap-2 text-[11px] text-gray-500 dark:text-gray-400">

                                                            <span
                                                                class="truncate"
                                                                x-text="
                                                                    visitor.identity
                                                                        ? 'No Identitas: ' + visitor.identity
                                                                        : 'No Identitas: -'
                                                                "
                                                            ></span>

                                                            <span
                                                                x-show="visitor.category"
                                                                class="shrink-0 text-gray-300 dark:text-gray-700"
                                                            >
                                                                •
                                                            </span>

                                                            <span
                                                                x-show="visitor.category"
                                                                class="truncate capitalize"
                                                                x-text="visitor.category"
                                                            ></span>

                                                        </div>

                                                    </div>

                                                    <svg
                                                        width="16"
                                                        height="16"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        class="shrink-0 text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M9 18L15 12L9 6"
                                                            stroke="currentColor"
                                                            stroke-width="1.8"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                    </svg>

                                                </button>
                                            </template>

                                        </div>
                                    </template>

                                    {{-- Tidak ditemukan --}}
                                    <template
                                        x-if="
                                            borrowerName.length > 0 &&
                                            filteredVisitors.length === 0
                                        "
                                    >
                                        <div class="px-4 py-4">

                                            <p class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                Pengunjung tidak ditemukan.
                                            </p>

                                            <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">
                                                Peminjam harus terdaftar terlebih dahulu di Daftar Pengunjung.
                                            </p>

                                        </div>
                                    </template>

                                    {{-- Tidak ada pengunjung --}}
                                    <template
                                        x-if="
                                            borrowerName.length === 0 &&
                                            visitors.length === 0
                                        "
                                    >
                                        <div class="px-4 py-4">

                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Belum ada data pada Daftar Pengunjung.
                                            </p>

                                        </div>
                                    </template>

                                </div>

                                @error('visitor_id')
                                    <p class="mt-1.5 text-xs text-error-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                                @error('borrower_name')
                                    <p class="mt-1.5 text-xs text-error-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <p
                                    x-show="
                                        borrowerName.length > 0 &&
                                        !visitorId
                                    "
                                    x-cloak
                                    class="mt-1.5 text-[11px] text-yellow-500"
                                >
                                    Pilih peminjam dari dropdown.
                                </p>

                            </div>

                            {{-- Nomor Identitas --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    NOMOR IDENTITAS
                                </label>

                                <input
                                    type="text"
                                    name="nopek"
                                    x-model="identityNumber"
                                    readonly
                                    placeholder="Otomatis dari Daftar Pengunjung"
                                    class="h-11 w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                                >

                                @error('nopek')
                                    <p class="mt-1.5 text-xs text-error-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Tanggal Peminjaman --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    TANGGAL PINJAMAN
                                </label>

                                <input
                                    type="date"
                                    name="loan_date"
                                    value="{{ old(
                                        'loan_date',
                                        now()->format('Y-m-d')
                                    ) }}"
                                    required
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 outline-none focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                >

                                @error('loan_date')
                                    <p class="mt-1.5 text-xs text-error-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>

                        {{-- Pengunjung Terpilih --}}
                        <div
                            x-show="visitorId"
                            x-cloak
                            class="flex items-center gap-2 rounded-lg border border-green-500/20 bg-green-500/10 px-4 py-3"
                        >
                            <svg
                                width="17"
                                height="17"
                                viewBox="0 0 24 24"
                                fill="none"
                                class="shrink-0 text-green-500"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M5 12L10 17L19 8"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>

                            <div class="min-w-0">

                                <p class="text-xs font-medium text-green-600 dark:text-green-400">
                                    Peminjam terdaftar di Daftar Pengunjung
                                </p>

                                <p class="mt-0.5 truncate text-[11px] text-gray-500 dark:text-gray-400">
                                    <span x-text="borrowerName"></span>

                                    <template x-if="identityNumber">
                                        <span>
                                            —
                                            <span x-text="identityNumber"></span>
                                        </span>
                                    </template>
                                </p>

                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-5 dark:border-gray-800">

                            <button
                                type="submit"
                                :disabled="!visitorId"
                                :class="
                                    visitorId
                                        ? 'bg-brand-500 hover:bg-brand-600'
                                        : 'cursor-not-allowed bg-gray-400 opacity-60'
                                "
                                class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs transition"
                            >
                                Simpan Peminjaman
                            </button>

                        </div>

                    </form>

                </div>

            @else

                <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-500/20 dark:bg-red-500/10">
                    <p class="text-sm font-medium text-red-700 dark:text-red-400">
                        Semua eksemplar buku sedang dipinjam.
                    </p>
                </div>

            @endif

        </div>
    </x-common.component-card>

</div>