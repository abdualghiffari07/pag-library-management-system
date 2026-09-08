@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <x-common.component-card title="Tambah Buku">
        <form
            action="{{ route('books.store') }}"
            method="POST"
            class="space-y-6"
            x-data="{
                bookIdentifier: @js(old('book_identifier', '')),
                bookIdExists: false,
                bookIdChecked: false,
                checkingBookId: false,

                bookNo: @js(old('book_no', '')),

                qty: @js(old('qty')),
                copyIds: @js(old('copy_ids', [])),

                authorOpen: false,
                equipmentOpen: false,

                authorSearch: @js(old('author', '')),
                equipmentSearch: @js(old('equipment', '')),

                authors: @js(
                    $authors->map(function ($author) {
                        return [
                            'id' => $author->author_id,
                            'name' => $author->author_name,
                        ];
                    })->values()
                ),

                equipments: @js(
                    $equipments->map(function ($equipment) {
                        return [
                            'id' => $equipment->equipment_id,
                            'name' => $equipment->equipment_name,
                        ];
                    })->values()
                ),

                init() {
                    this.syncCopyIds();
                },

                get filteredAuthors() {
                    const search = this.authorSearch.trim().toLowerCase();

                    if (!search) {
                        return this.authors;
                    }

                    return this.authors.filter(author =>
                        author.name.toLowerCase().includes(search)
                    );
                },

                get filteredEquipments() {
                    const search = this.equipmentSearch.trim().toLowerCase();

                    if (!search) {
                        return this.equipments;
                    }

                    return this.equipments.filter(equipment =>
                        equipment.name.toLowerCase().includes(search)
                    );
                },

                get hasDuplicateCopyIds() {
                    const values = this.copyIds
                        .map(value => String(value ?? '').trim().toLowerCase())
                        .filter(value => value !== '');

                    return new Set(values).size !== values.length;
                },

                isDuplicateCopyId(index) {
                    const value = String(
                        this.copyIds[index] ?? ''
                    ).trim().toLowerCase();

                    if (!value) {
                        return false;
                    }

                    return this.copyIds.filter(copyId =>
                        String(copyId ?? '').trim().toLowerCase() === value
                    ).length > 1;
                },

                syncCopyIds() {
                    const total = parseInt(this.qty);

                    if (!total || total < 1) {
                        this.copyIds = [];
                        return;
                    }

                    while (this.copyIds.length < total) {
                        this.copyIds.push('');
                    }

                    if (this.copyIds.length > total) {
                        this.copyIds.splice(total);
                    }
                },

                async checkBookId() {
                    const value = this.bookIdentifier.trim();

                    this.bookIdExists = false;
                    this.bookIdChecked = false;

                    if (!value) {
                        this.checkingBookId = false;
                        return;
                    }

                    this.checkingBookId = true;

                    try {
                        const response = await fetch(
                            '{{ route('books.check-book-id') }}?book_id=' +
                            encodeURIComponent(value),
                            {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            }
                        );

                        const data = await response.json();

                        this.bookIdExists = data.exists;
                        this.bookIdChecked = true;
                    } catch (error) {
                        console.error('Gagal memeriksa ID Buku:', error);
                        this.bookIdChecked = false;
                    } finally {
                        this.checkingBookId = false;
                    }
                },

                selectAuthor(author) {
                    this.authorSearch = author.name;
                    this.authorOpen = false;
                },

                selectEquipment(equipment) {
                    this.equipmentSearch = equipment.name;
                    this.equipmentOpen = false;
                },

                closeDropdowns() {
                    this.authorOpen = false;
                    this.equipmentOpen = false;
                }
            }"
            @click.outside="closeDropdowns()"
            @submit="
                if (
                    bookIdExists ||
                    checkingBookId ||
                    hasDuplicateCopyIds
                ) {
                    $event.preventDefault();
                }
            "
        >
            @csrf

            {{-- Informasi utama --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- ID Buku --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        ID BUKU
                    </label>

                    <div class="relative">
                        <input
                            type="text"
                            name="book_identifier"
                            x-model="bookIdentifier"
                            @input="
                                bookIdChecked = false;
                                bookIdExists = false;
                            "
                            @input.debounce.400ms="checkBookId()"
                            required
                            autocomplete="off"
                            placeholder="Contoh: PAG-BK-001"
                            class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                            :class="bookIdExists
                                ? 'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700'
                                : 'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800'"
                        >

                        <span
                            x-show="checkingBookId"
                            x-cloak
                            class="absolute right-3.5 top-1/2 -translate-y-1/2"
                        >
                            <svg
                                class="h-4 w-4 animate-spin text-gray-400"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    class="opacity-25"
                                />
                                <path
                                    d="M21 12a9 9 0 00-9-9"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </span>

                        <span
                            x-show="bookIdExists && !checkingBookId"
                            x-cloak
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-error-500"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    stroke="currentColor"
                                    stroke-width="2"
                                />
                                <path
                                    d="M12 7V13M12 17H12.01"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </span>
                    </div>

                    <p
                        x-show="checkingBookId"
                        x-cloak
                        class="mt-1.5 text-xs text-gray-400"
                    >
                        Memeriksa ID Buku...
                    </p>

                    <p
                        x-show="bookIdChecked && !checkingBookId && !bookIdExists"
                        x-cloak
                        class="mt-1.5 text-xs text-success-500"
                    >
                        ID Buku tersedia.
                    </p>

                    <p
                        x-show="bookIdExists && !checkingBookId"
                        x-cloak
                        class="mt-1.5 text-xs text-error-500"
                    >
                        ID Buku sudah digunakan. Silakan gunakan ID Buku lain.
                    </p>

                    @error('book_identifier')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Book No --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        BOOK NO.
                    </label>

                    <input
                        type="text"
                        name="book_no"
                        x-model="bookNo"
                        autocomplete="off"
                        placeholder="Masukkan nomor buku"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
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
                        value="{{ old('tag_no') }}"
                        placeholder="Masukkan Tag No."
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
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
                        value="{{ old('cat_no') }}"
                        placeholder="Masukkan nomor katalog"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                    >

                    @error('cat_no')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Equipment --}}
                <div class="relative">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        EQUIPMENT
                    </label>

                    <div class="relative">
                        <input
                            type="text"
                            name="equipment"
                            x-model="equipmentSearch"
                            @focus="equipmentOpen = true; authorOpen = false"
                            @input="equipmentOpen = true"
                            autocomplete="off"
                            placeholder="Ketik atau pilih equipment"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                        >

                        <button
                            type="button"
                            @click.stop="
                                equipmentOpen = !equipmentOpen;
                                authorOpen = false;
                            "
                            class="absolute right-0 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-white"
                        >
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                class="transition-transform duration-200"
                                :class="equipmentOpen ? 'rotate-180' : ''"
                            >
                                <path
                                    d="M5 7.5L10 12.5L15 7.5"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                    </div>

                    <div
                        x-show="equipmentOpen"
                        x-cloak
                        x-transition
                        class="absolute z-50 mt-1 w-full overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                    >
                        <div class="max-h-[220px] overflow-y-auto py-1">
                            <template x-if="filteredEquipments.length === 0">
                                <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                    Equipment belum tersedia.
                                </div>
                            </template>

                            <template
                                x-for="equipment in filteredEquipments"
                                :key="equipment.id"
                            >
                                <button
                                    type="button"
                                    @click="selectEquipment(equipment)"
                                    class="flex w-full items-center px-4 py-2.5 text-left text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                    x-text="equipment.name"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <p class="mt-1.5 text-xs text-gray-400">
                        Ketik equipment baru atau pilih dari daftar.
                    </p>

                    @error('equipment')
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
                        value="{{ old('location') }}"
                        autocomplete="off"
                        placeholder="Masukkan lokasi buku"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
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
                        value="{{ old('title') }}"
                        placeholder="Masukkan judul buku"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                    >

                    @error('title')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Author --}}
                <div class="relative">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        AUTHOR
                    </label>

                    <div class="relative">
                        <input
                            type="text"
                            name="author"
                            x-model="authorSearch"
                            @focus="authorOpen = true; equipmentOpen = false"
                            @input="authorOpen = true"
                            autocomplete="off"
                            placeholder="Ketik atau pilih nama penulis"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                        >

                        <button
                            type="button"
                            @click.stop="
                                authorOpen = !authorOpen;
                                equipmentOpen = false;
                            "
                            class="absolute right-0 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-white"
                        >
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                class="transition-transform duration-200"
                                :class="authorOpen ? 'rotate-180' : ''"
                            >
                                <path
                                    d="M5 7.5L10 12.5L15 7.5"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                    </div>

                    <div
                        x-show="authorOpen"
                        x-cloak
                        x-transition
                        class="absolute z-50 mt-1 w-full overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                    >
                        <div class="max-h-[220px] overflow-y-auto py-1">
                            <template x-if="filteredAuthors.length === 0">
                                <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                    Author belum tersedia.
                                </div>
                            </template>

                            <template
                                x-for="author in filteredAuthors"
                                :key="author.id"
                            >
                                <button
                                    type="button"
                                    @click="selectAuthor(author)"
                                    class="flex w-full items-center px-4 py-2.5 text-left text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                    x-text="author.name"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <p class="mt-1.5 text-xs text-gray-400">
                        Ketik nama penulis baru atau pilih dari daftar.
                    </p>

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
                        value="{{ old('publisher') }}"
                        placeholder="Masukkan nama penerbit"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                    >

                    @error('publisher')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- QTY --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        QTY
                    </label>

                    <input
                        type="number"
                        name="qty"
                        x-model.number="qty"
                        @input="syncCopyIds()"
                        min="1"
                        placeholder="Masukkan jumlah buku"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                    >

                    <p class="mt-1.5 text-xs text-gray-400">
                        Kosongkan jika belum ingin menambahkan eksemplar.
                    </p>

                    @error('qty')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- ID Eksemplar --}}
            <div
                x-show="Number(qty) >= 1"
                x-cloak
                class="rounded-lg border border-gray-200 p-4 dark:border-gray-800"
            >
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                        ID Eksemplar
                    </h4>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Masukkan ID berbeda untuk setiap eksemplar sesuai jumlah QTY.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <template
                        x-for="(copyId, index) in copyIds"
                        :key="index"
                    >
                        <div>
                            <label
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                                x-text="'ID EKSEMPLAR ' + (index + 1)"
                            ></label>

                            <input
                                type="text"
                                name="copy_ids[]"
                                x-model="copyIds[index]"
                                required
                                autocomplete="off"
                                :placeholder="'Masukkan ID eksemplar ' + (index + 1)"
                                class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                                :class="isDuplicateCopyId(index)
                                    ? 'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700'
                                    : 'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800'"
                            >

                            <p
                                x-show="isDuplicateCopyId(index)"
                                x-cloak
                                class="mt-1.5 text-xs text-error-500"
                            >
                                ID eksemplar tidak boleh sama.
                            </p>
                        </div>
                    </template>
                </div>

                @error('copy_ids')
                    <p class="mt-3 text-xs text-error-500">
                        {{ $message }}
                    </p>
                @enderror

                @error('copy_ids.*')
                    <p class="mt-3 text-xs text-error-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    DESCRIPTION
                </label>

                <textarea
                    name="description"
                    rows="5"
                    placeholder="Masukkan deskripsi buku..."
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                >{{ old('description') }}</textarea>

                @error('description')
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

                <textarea
                    name="remark"
                    rows="4"
                    placeholder="Masukkan remark..."
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                >{{ old('remark') }}</textarea>

                @error('remark')
                    <p class="mt-1.5 text-xs text-error-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-800">
                <a
                    href="{{ route('data-buku') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    :disabled="
                        bookIdExists ||
                        checkingBookId ||
                        hasDuplicateCopyIds
                    "
                    :class="
                        bookIdExists ||
                        checkingBookId ||
                        hasDuplicateCopyIds
                            ? 'cursor-not-allowed opacity-50'
                            : 'hover:bg-brand-600'
                    "
                    class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs transition"
                >
                    Simpan Buku
                </button>
            </div>
        </form>
    </x-common.component-card>
</div>
@endsection