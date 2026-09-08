@php
    $tableRows = $books->map(function ($book) {
        $copies = $book->copies ?? collect();

        $borrowed = $copies->where('status', 'Dipinjam')->count();
        $lost = $copies->where('status', 'Hilang')->count();
        $damaged = $copies->where('status', 'Rusak')->count();

        $status = match (true) {
            $lost > 0 => 'Hilang',
            $damaged > 0 => 'Rusak',
            $borrowed > 0 => 'Dipinjam',
            default => 'Tersedia',
        };

        return [
            'id' => $book->book_id,
            'bookId' => $book->book_identifier ?? '-',
            'loanStatus' => $status,
            'tagNo' => $book->tag_no ?? '-',
            'bookNo' => $book->book_code ?? '-',
            'catNo' => $book->cat_no ?? '-',
            'equipment' => $book->equipment?->equipment_name ?? '-',
            'description' => $book->description ?? '-',
            'title' => $book->title ?? '-',
            'location' => $book->location?->location_name ?? $book->rack ?? '-',
            'remark' => $book->remark ?? '-',
            'author' => $book->authors->pluck('author_name')->join(', ') ?: '-',
            'publisher' => $book->publisher ?? '-',
            'qty' => $copies->count(),
        ];
    })->values()->all();
@endphp

<div
    class="relative isolate w-full min-w-0 max-w-full"
    x-data="booksTable(@js($tableRows))"
    :class="resizingColumn ? 'select-none' : ''"
>
    {{-- Bulk Delete --}}
    <form
        x-ref="bulkDeleteForm"
        action="{{ route('books.bulk-destroy') }}"
        method="POST"
        class="hidden"
    >
        @csrf
        @method('DELETE')

        <template x-for="id in selectedIds" :key="id">
            <input
                type="hidden"
                name="book_ids[]"
                :value="id"
            >
        </template>
    </form>

    <div class="relative w-full min-w-0 max-w-full rounded-xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">

        {{-- Header --}}
        <div class="relative z-10 border-b border-gray-100 bg-white px-4 py-4 dark:border-white/[0.05] dark:bg-transparent sm:px-5">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

                <div class="min-w-0">

                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                        Data Buku
                    </h3>

                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                        Daftar koleksi buku perpustakaan
                    </p>

                    {{-- Search --}}
                    <div class="relative mt-4 w-full sm:w-80">

                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m21 21-4.35-4.35m2.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                                />
                            </svg>
                        </span>

                        <input
                            type="text"
                            x-model="search"
                            @input="currentPage = 1"
                            placeholder="Cari buku..."
                            autocomplete="off"
                            class="h-10 w-full rounded-lg border border-gray-300 bg-white pl-9 pr-9 text-xs text-gray-800 shadow-theme-xs outline-none transition focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                        >

                        <button
                            type="button"
                            x-show="search.length > 0"
                            x-cloak
                            @click="search = ''; currentPage = 1"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-gray-600 dark:hover:text-gray-200"
                            title="Hapus pencarian"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 6l12 12M18 6 6 18"
                                />
                            </svg>
                        </button>

                    </div>

                </div>

                {{-- Header Actions --}}
                <div class="flex flex-wrap items-center justify-end gap-2">

                    <div
                        x-show="!selectionMode"
                        x-cloak
                        class="flex flex-wrap items-center justify-end gap-2"
                    >
                        {{-- Filter --}}
                        <button
                            type="button"
                            @click="filterOpen = !filterOpen"
                            class="relative inline-flex h-10 items-center justify-center gap-1.5 rounded-lg border px-3 text-xs font-medium shadow-theme-xs transition"
                            :class="
                                filterOpen || activeFilterCount > 0
                                    ? 'border-brand-300 bg-brand-50 text-brand-600 dark:border-brand-500/40 dark:bg-brand-500/10 dark:text-brand-400'
                                    : 'border-gray-300 bg-white text-gray-700 hover:border-brand-300 hover:bg-brand-50 hover:text-brand-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-brand-500/50 dark:hover:bg-brand-500/10 dark:hover:text-brand-400'
                            "
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.667V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.333L3.2 4.6A1 1 0 013 4Z"
                                />
                            </svg>

                            Filter

                            <span
                                x-show="activeFilterCount > 0"
                                x-cloak
                                class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-500 px-1.5 text-[10px] font-semibold text-white"
                                x-text="activeFilterCount"
                            ></span>
                        </button>

                        {{-- Pilih --}}
                        <button
                            type="button"
                            @click="enterSelectionMode()"
                            class="inline-flex h-10 items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 text-xs font-medium text-gray-700 shadow-theme-xs transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-brand-500/50 dark:hover:bg-brand-500/10 dark:hover:text-brand-400"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"
                                />
                            </svg>

                            Pilih
                        </button>

                        {{-- Tambah Buku --}}
                        <a
                            href="{{ route('books.create') }}"
                            class="inline-flex h-10 items-center justify-center gap-1.5 rounded-lg bg-brand-500 px-3 text-xs font-medium text-white shadow-theme-xs transition hover:bg-brand-600"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>

                            Tambah Buku
                        </a>

                    </div>

                    {{-- Selection Actions --}}
                    <div
                        x-show="selectionMode"
                        x-cloak
                        class="flex flex-wrap items-center justify-end gap-2"
                    >
                        <div
                            class="inline-flex h-10 items-center rounded-lg border px-3 text-xs font-medium"
                            :class="
                                selectedIds.length > 0
                                    ? 'border-brand-200 bg-brand-50 text-brand-600 dark:border-brand-500/20 dark:bg-brand-500/10 dark:text-brand-400'
                                    : 'border-gray-200 bg-gray-50 text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400'
                            "
                        >
                            <span x-text="selectedIds.length"></span>
                            <span class="ml-1">
                                buku dipilih
                            </span>
                        </div>

                        <button
                            type="button"
                            x-show="selectedIds.length > 0"
                            x-cloak
                            @click="deleteSelected()"
                            class="inline-flex h-10 items-center justify-center gap-1.5 rounded-lg bg-red-500 px-3 text-xs font-medium text-white shadow-theme-xs transition hover:bg-red-600"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>

                            Hapus Terpilih
                        </button>

                        <button
                            type="button"
                            @click="exitSelectionMode()"
                            class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-3 text-xs font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            Selesai
                        </button>

                    </div>

                </div>

            </div>

            {{-- Filter Panel --}}
            <div
                x-show="filterOpen && !selectionMode"
                x-cloak
                x-transition
                class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/70"
            >
                <div class="mb-4 flex items-center justify-between gap-3">

                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white/90">
                            Filter Data Buku
                        </p>

                        <p class="mt-0.5 text-[11px] text-gray-500 dark:text-gray-400">
                            Tampilkan buku berdasarkan data yang dipilih.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="filterOpen = false"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-200 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                        title="Tutup filter"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 6l12 12M18 6 6 18"
                            />
                        </svg>
                    </button>

                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">

                    {{-- Status --}}
                    <div>
                        <label class="mb-1.5 block text-[11px] font-medium uppercase text-gray-500 dark:text-gray-400">
                            Status Peminjaman
                        </label>

                        <select
                            x-model="filters.status"
                            @change="currentPage = 1"
                            class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-700 outline-none transition focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            <option value="">
                                Semua Status
                            </option>

                            <template
                                x-for="option in statusOptions"
                                :key="option"
                            >
                                <option
                                    :value="option"
                                    x-text="option"
                                ></option>
                            </template>
                        </select>
                    </div>

                    {{-- Location --}}
                    <div>
                        <label class="mb-1.5 block text-[11px] font-medium uppercase text-gray-500 dark:text-gray-400">
                            Location
                        </label>

                        <select
                            x-model="filters.location"
                            @change="currentPage = 1"
                            class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-700 outline-none transition focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            <option value="">
                                Semua Location
                            </option>

                            <template
                                x-for="option in locationOptions"
                                :key="option"
                            >
                                <option
                                    :value="option"
                                    x-text="option"
                                ></option>
                            </template>
                        </select>
                    </div>

                    {{-- Author --}}
                    <div>
                        <label class="mb-1.5 block text-[11px] font-medium uppercase text-gray-500 dark:text-gray-400">
                            Penulis
                        </label>

                        <select
                            x-model="filters.author"
                            @change="currentPage = 1"
                            class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-700 outline-none transition focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            <option value="">
                                Semua Penulis
                            </option>

                            <template
                                x-for="option in authorOptions"
                                :key="option"
                            >
                                <option
                                    :value="option"
                                    x-text="option"
                                ></option>
                            </template>
                        </select>
                    </div>

                    {{-- Equipment --}}
                    <div>
                        <label class="mb-1.5 block text-[11px] font-medium uppercase text-gray-500 dark:text-gray-400">
                            Equipment
                        </label>

                        <select
                            x-model="filters.equipment"
                            @change="currentPage = 1"
                            class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-700 outline-none transition focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            <option value="">
                                Semua Equipment
                            </option>

                            <template
                                x-for="option in equipmentOptions"
                                :key="option"
                            >
                                <option
                                    :value="option"
                                    x-text="option"
                                ></option>
                            </template>
                        </select>
                    </div>

                    {{-- Publisher --}}
                    <div>
                        <label class="mb-1.5 block text-[11px] font-medium uppercase text-gray-500 dark:text-gray-400">
                            Publisher
                        </label>

                        <select
                            x-model="filters.publisher"
                            @change="currentPage = 1"
                            class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-xs text-gray-700 outline-none transition focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            <option value="">
                                Semua Publisher
                            </option>

                            <template
                                x-for="option in publisherOptions"
                                :key="option"
                            >
                                <option
                                    :value="option"
                                    x-text="option"
                                ></option>
                            </template>
                        </select>
                    </div>

                </div>

                {{-- Filter Footer --}}
                <div class="mt-4 flex flex-col gap-3 border-t border-gray-200 pt-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">

                    <div class="text-[11px] text-gray-500 dark:text-gray-400">

                        Menampilkan

                        <span
                            class="font-semibold text-gray-700 dark:text-gray-300"
                            x-text="filteredRows.length"
                        ></span>

                        dari

                        <span
                            class="font-semibold text-gray-700 dark:text-gray-300"
                            x-text="tableRowData.length"
                        ></span>

                        buku

                    </div>

                    <div class="flex items-center gap-2">

                        <button
                            type="button"
                            @click="resetFilters()"
                            :disabled="!hasActiveFilters"
                            :class="
                                !hasActiveFilters
                                    ? 'cursor-not-allowed opacity-50'
                                    : 'hover:bg-gray-100 dark:hover:bg-gray-700'
                            "
                            class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 text-[11px] font-medium text-gray-700 transition dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            <svg
                                class="h-3.5 w-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4v6h6M20 20v-6h-6M5.5 18.5A8 8 0 0118 5.5M18.5 18.5A8 8 0 006 5.5"
                                />
                            </svg>

                            Reset Filter
                        </button>

                        <button
                            type="button"
                            @click="filterOpen = false"
                            class="inline-flex h-9 items-center justify-center rounded-lg bg-brand-500 px-3 text-[11px] font-medium text-white transition hover:bg-brand-600"
                        >
                            Selesai
                        </button>

                    </div>

                </div>

            </div>

            {{-- Active Filters --}}
            <div
                x-show="hasActiveFilters && !filterOpen && !selectionMode"
                x-cloak
                class="mt-3 flex flex-wrap items-center gap-2"
            >
                <span class="text-[11px] text-gray-500 dark:text-gray-400">
                    Filter aktif:
                </span>

                <template x-if="filters.status">
                    <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-1 text-[10px] font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                        Status:
                        <span
                            class="ml-1"
                            x-text="filters.status"
                        ></span>
                    </span>
                </template>

                <template x-if="filters.location">
                    <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-1 text-[10px] font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                        Location:
                        <span
                            class="ml-1"
                            x-text="filters.location"
                        ></span>
                    </span>
                </template>

                <template x-if="filters.author">
                    <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-1 text-[10px] font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                        Penulis:
                        <span
                            class="ml-1"
                            x-text="filters.author"
                        ></span>
                    </span>
                </template>

                <template x-if="filters.equipment">
                    <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-1 text-[10px] font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                        Equipment:
                        <span
                            class="ml-1"
                            x-text="filters.equipment"
                        ></span>
                    </span>
                </template>

                <template x-if="filters.publisher">
                    <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-1 text-[10px] font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                        Publisher:
                        <span
                            class="ml-1"
                            x-text="filters.publisher"
                        ></span>
                    </span>
                </template>

                <button
                    type="button"
                    @click="resetFilters()"
                    class="text-[10px] font-medium text-red-500 transition hover:text-red-600"
                >
                    Hapus semua filter
                </button>
            </div>

        </div>

        {{-- Table --}}
        <div class="relative z-0 block w-full max-w-full overflow-x-auto">

            <table
                class="table-fixed border-collapse"
                :style="`width: ${tableWidth}px`"
            >

                <colgroup>
                    <col :style="selectionMode ? `width: ${columnWidths.select}px` : 'width: 0px'">
                    <col :style="`width: ${columnWidths.no}px`">
                    <col :style="`width: ${columnWidths.status}px`">
                    <col :style="`width: ${columnWidths.bookId}px`">
                    <col :style="`width: ${columnWidths.tagNo}px`">
                    <col :style="`width: ${columnWidths.bookNo}px`">
                    <col :style="`width: ${columnWidths.catNo}px`">
                    <col :style="`width: ${columnWidths.equipment}px`">
                    <col :style="`width: ${columnWidths.description}px`">
                    <col :style="`width: ${columnWidths.title}px`">
                    <col :style="`width: ${columnWidths.location}px`">
                    <col :style="`width: ${columnWidths.remark}px`">
                    <col :style="`width: ${columnWidths.author}px`">
                    <col :style="`width: ${columnWidths.publisher}px`">
                    <col :style="`width: ${columnWidths.qty}px`">
                    <col :style="`width: ${columnWidths.action}px`">
                </colgroup>

                <thead class="border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">

                    <tr>

                        {{-- Pilih --}}
                        <th
                            class="overflow-hidden py-2.5 text-center"
                            :class="selectionMode ? 'px-3' : 'px-0'"
                        >
                            <div
                                x-show="selectionMode"
                                x-cloak
                                class="flex items-center justify-center"
                            >
                                <label class="relative inline-flex h-4 w-4 cursor-pointer items-center justify-center">

                                    <input
                                        type="checkbox"
                                        :checked="isCurrentPageSelected"
                                        @change="toggleCurrentPage($event.target.checked)"
                                        x-effect="$el.indeterminate = hasCurrentPageSelection && !isCurrentPageSelected"
                                        class="peer absolute inset-0 h-4 w-4 cursor-pointer appearance-none rounded-[4px] border border-gray-300 bg-white transition checked:border-brand-500 checked:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-900 dark:checked:border-brand-500 dark:checked:bg-brand-500"
                                    >

                                    <svg
                                        class="pointer-events-none absolute h-3 w-3 text-white opacity-0 transition peer-checked:opacity-100"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="3"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>

                                </label>
                            </div>
                        </th>

                        {{-- No --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            NO

                            <div
                                @mousedown.stop="startResize($event, 'no')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Status --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            STATUS PINJAMAN

                            <div
                                @mousedown.stop="startResize($event, 'status')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- ID Buku --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            ID BUKU

                            <div
                                @mousedown.stop="startResize($event, 'bookId')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Tag --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            TAG NO

                            <div
                                @mousedown.stop="startResize($event, 'tagNo')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Book No --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            BOOK NO.

                            <div
                                @mousedown.stop="startResize($event, 'bookNo')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Cat No --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            CAT. NO.

                            <div
                                @mousedown.stop="startResize($event, 'catNo')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Equipment --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            EQUIPMENT

                            <div
                                @mousedown.stop="startResize($event, 'equipment')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Description --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            DESCRIPTION

                            <div
                                @mousedown.stop="startResize($event, 'description')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Title --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            TITLE

                            <div
                                @mousedown.stop="startResize($event, 'title')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Location --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            LOCATION

                            <div
                                @mousedown.stop="startResize($event, 'location')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Remark --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            REMARK

                            <div
                                @mousedown.stop="startResize($event, 'remark')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Author --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            AUTHOR

                            <div
                                @mousedown.stop="startResize($event, 'author')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Publisher --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            PUBLISHER

                            <div
                                @mousedown.stop="startResize($event, 'publisher')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Qty --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            QTY

                            <div
                                @mousedown.stop="startResize($event, 'qty')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                        {{-- Action --}}
                        <th class="relative px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            ACTION

                            <div
                                @mousedown.stop="startResize($event, 'action')"
                                class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize transition hover:bg-brand-500"
                            ></div>
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <template
                        x-for="(row, index) in paginatedRows"
                        :key="row.id"
                    >
                        <tr
                            class="border-b border-gray-100 transition-colors hover:bg-gray-50 dark:border-white/[0.05] dark:hover:bg-white/[0.02]"
                            :class="
                                selectionMode && selectedIds.includes(Number(row.id))
                                    ? 'bg-brand-50/40 dark:bg-brand-500/[0.04]'
                                    : ''
                            "
                        >
                            {{-- Pilih --}}
                            <td
                                class="overflow-hidden py-3 text-center"
                                :class="selectionMode ? 'px-3' : 'px-0'"
                            >
                                <div
                                    x-show="selectionMode"
                                    x-cloak
                                    class="flex items-center justify-center"
                                >
                                    <label class="relative inline-flex h-4 w-4 cursor-pointer items-center justify-center">

                                        <input
                                            type="checkbox"
                                            :checked="selectedIds.includes(Number(row.id))"
                                            @change="toggleRow(Number(row.id), $event.target.checked)"
                                            class="peer absolute inset-0 h-4 w-4 cursor-pointer appearance-none rounded-[4px] border border-gray-300 bg-white transition checked:border-brand-500 checked:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-900 dark:checked:border-brand-500 dark:checked:bg-brand-500"
                                        >

                                        <svg
                                            class="pointer-events-none absolute h-3 w-3 text-white opacity-0 transition peer-checked:opacity-100"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="3"
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>

                                    </label>
                                </div>
                            </td>

                            {{-- No --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="((currentPage - 1) * itemsPerPage) + index + 1"
                                ></span>

                            </td>

                            {{-- Status --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="inline-block whitespace-nowrap rounded-full px-2 py-0.5 text-[10px] font-medium"
                                    :class="getStatusClass(row.loanStatus)"
                                    x-text="row.loanStatus"
                                ></span>

                            </td>

                            {{-- ID Buku --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs font-semibold text-gray-700 dark:text-gray-300"
                                    x-text="row.bookId"
                                    :title="row.bookId"
                                ></span>

                            </td>

                            {{-- Tag --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs font-medium text-gray-700 dark:text-gray-300"
                                    x-text="row.tagNo"
                                    :title="row.tagNo"
                                ></span>

                            </td>

                            {{-- Book No --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs font-semibold text-gray-700 dark:text-gray-300"
                                    x-text="row.bookNo"
                                    :title="row.bookNo"
                                ></span>

                            </td>

                            {{-- Cat No --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.catNo"
                                    :title="row.catNo"
                                ></span>

                            </td>

                            {{-- Equipment --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.equipment"
                                    :title="row.equipment"
                                ></span>

                            </td>

                            {{-- Description --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.description"
                                    :title="row.description"
                                ></span>

                            </td>

                            {{-- Title --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs font-medium text-gray-700 dark:text-gray-300"
                                    x-text="row.title"
                                    :title="row.title"
                                ></span>

                            </td>

                            {{-- Location --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.location"
                                    :title="row.location"
                                ></span>

                            </td>

                            {{-- Remark --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.remark"
                                    :title="row.remark"
                                ></span>

                            </td>

                            {{-- Author --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.author"
                                    :title="row.author"
                                ></span>

                            </td>

                            {{-- Publisher --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.publisher"
                                    :title="row.publisher"
                                ></span>

                            </td>

                            {{-- Qty --}}
                            <td class="overflow-hidden px-3 py-3">

                                <span
                                    class="block truncate text-xs font-medium text-gray-700 dark:text-gray-300"
                                    x-text="row.qty"
                                ></span>

                            </td>

                            {{-- Action --}}
                            <td class="overflow-hidden px-3 py-3">

                                <div class="flex items-center gap-3">

                                    <a
                                        :href="'{{ url('/books') }}/' + row.id + '/edit'"
                                        class="shrink-0 text-gray-500 transition-colors hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-400"
                                        title="Edit buku"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                                            />
                                        </svg>
                                    </a>

                                    <form
                                        method="POST"
                                        :action="'{{ url('/books') }}/' + row.id"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="shrink-0 text-gray-500 transition-colors hover:text-red-500 dark:text-gray-400 dark:hover:text-red-500"
                                            title="Hapus buku"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    </template>

                    {{-- Kosong --}}
                    <template x-if="filteredRows.length === 0">

                        <tr>

                            <td
                                colspan="16"
                                class="px-3 py-10 text-center text-sm text-gray-500 dark:text-gray-400"
                            >
                                <div class="space-y-2">

                                    <p x-show="search || hasActiveFilters">
                                        Tidak ada buku yang sesuai dengan pencarian atau filter.
                                    </p>

                                    <p x-show="!search && !hasActiveFilters">
                                        Belum ada data buku.
                                    </p>

                                    <button
                                        type="button"
                                        x-show="hasActiveFilters"
                                        x-cloak
                                        @click="resetFilters()"
                                        class="text-xs font-medium text-brand-500 hover:text-brand-600"
                                    >
                                        Reset filter
                                    </button>

                                </div>
                            </td>

                        </tr>

                    </template>

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="relative z-10 border-t border-gray-200 bg-white px-4 py-4 dark:border-white/[0.05] dark:bg-transparent">

            <div class="flex items-center justify-between">

                <button
                    type="button"
                    @click="prevPage()"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'cursor-not-allowed opacity-50' : ''"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:px-3.5"
                >
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <path
                            d="M2.583 9.999 8.86 4.47M2.583 9.999 8.86 15.53M2.583 9.999h14.835"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                    <span class="hidden sm:inline">
                        Previous
                    </span>
                </button>

                <span class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden">
                    Page
                    <span x-text="currentPage"></span>
                    of
                    <span x-text="totalPages"></span>
                </span>

                <ul class="hidden items-center gap-0.5 sm:flex">

                    <template
                        x-for="page in displayedPages"
                        :key="page"
                    >
                        <li>

                            <button
                                type="button"
                                x-show="page !== '...'"
                                @click="goToPage(page)"
                                :class="
                                    currentPage === page
                                        ? 'bg-brand-500 text-white'
                                        : 'text-gray-700 hover:bg-brand-500/[0.08] hover:text-brand-500 dark:text-gray-400'
                                "
                                class="flex h-10 w-10 items-center justify-center rounded-lg text-theme-sm font-medium"
                                x-text="page"
                            ></button>

                            <span
                                x-show="page === '...'"
                                class="flex h-10 w-10 items-center justify-center text-gray-500"
                            >
                                ...
                            </span>

                        </li>
                    </template>

                </ul>

                <button
                    type="button"
                    @click="nextPage()"
                    :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'cursor-not-allowed opacity-50' : ''"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:px-3.5"
                >
                    <span class="hidden sm:inline">
                        Next
                    </span>

                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <path
                            d="M17.418 9.999 11.14 4.47M17.418 9.999 11.14 15.53M17.418 9.999H2.583"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </button>

            </div>

        </div>

    </div>

</div>