@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Tambah Buku" />

    <div class="space-y-6">
        <x-common.component-card title="Tambah Buku">
            <form
                action="{{ route('books.store') }}"
                method="POST"
                class="space-y-6"
                x-data="{
                    bookNo: '{{ old('book_no') }}',
                    bookNoExists: false,
                    checkingBookNo: false,

                    async checkBookNo() {
                        const value = this.bookNo.trim();

                        this.bookNoExists = false;

                        if (!value) {
                            return;
                        }

                        this.checkingBookNo = true;

                        try {
                            const response = await fetch(
                                '{{ route('books.check-book-no') }}?book_no=' + encodeURIComponent(value)
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
                @submit="if (bookNoExists || checkingBookNo) $event.preventDefault()"
            >
                @csrf

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Book No. --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            BOOK NO.
                        </label>

                        <div class="relative">
                            <input
                                type="text"
                                name="book_no"
                                x-model="bookNo"
                                @input.debounce.400ms="checkBookNo()"
                                value="{{ old('book_no') }}"
                                required
                                placeholder="Masukkan nomor buku"
                                class="h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 dark:text-white/90 shadow-theme-xs placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:placeholder:text-gray-400"
                                :class="bookNoExists
                                    ? 'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700'
                                    : 'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800'"
                            >

                            <span
                                x-show="bookNoExists"
                                x-cloak
                                class="absolute top-1/2 right-3.5 -translate-y-1/2"
                            >
                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 16 16"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                                        fill="#F04438"
                                    />
                                </svg>
                            </span>
                        </div>

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

                    {{-- Cat. No. --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            CAT. NO.
                        </label>

                        <input
                            type="text"
                            name="cat_no"
                            value="{{ old('cat_no') }}"
                            required
                            placeholder="Masukkan nomor katalog"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:placeholder:text-gray-400"
                        >

                        @error('cat_no')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Location --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            LOCATION
                        </label>

                        <select
                            name="location_id"
                            required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 shadow-theme-xs focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900"
                        >
                            <option value="">Pilih lokasi</option>

                            @foreach(\App\Models\Location::all() as $location)
                                <option
                                    value="{{ $location->location_id }}"
                                    {{ old('location_id') == $location->location_id ? 'selected' : '' }}
                                >
                                    {{ $location->location_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('location_id')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
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
                            required
                            placeholder="Masukkan judul buku"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:placeholder:text-gray-400"
                        >

                        @error('title')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
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
                            value="{{ old('author') }}"
                            required
                            placeholder="Masukkan nama penulis"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:placeholder:text-gray-400"
                        >

                        @error('author')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
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
                            required
                            placeholder="Masukkan nama penerbit"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:placeholder:text-gray-400"
                        >

                        @error('publisher')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
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
                            value="{{ old('qty', 1) }}"
                            min="1"
                            required
                            placeholder="Masukkan jumlah buku"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:placeholder:text-gray-400"
                        >

                        @error('qty')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
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
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:placeholder:text-gray-400"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
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
                        :disabled="bookNoExists || checkingBookNo"
                        :class="bookNoExists || checkingBookNo
                            ? 'cursor-not-allowed opacity-50'
                            : 'hover:bg-brand-600'"
                        class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs transition"
                    >
                        Simpan Buku
                    </button>
                </div>
            </form>
        </x-common.component-card>
    </div>
@endsection