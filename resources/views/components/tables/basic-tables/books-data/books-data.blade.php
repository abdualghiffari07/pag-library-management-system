@php
    $tableRows = $books->map(function ($book) {
        $copies = $book->copies ?? collect();

        $borrowed = $copies->where('status', 'Dipinjam')->count();
        $lost = $copies->where('status', 'Hilang')->count();
        $damaged = $copies->where('status', 'Rusak')->count();

        if ($lost > 0) {
            $status = 'Hilang';
        } elseif ($damaged > 0) {
            $status = 'Rusak';
        } elseif ($borrowed > 0) {
            $status = 'Dipinjam';
        } else {
            $status = 'Tersedia';
        }

        return [
            'id' => $book->book_id,
            'tagNo' => $book->tag_no ?? '-',
            'bookNo' => $book->book_code ?? '-',
            'catNo' => $book->cat_no ?? '-',
            'equipment' => $book->equipment ?? '-',
            'description' => $book->description ?? '-',
            'title' => $book->title ?? '-',
            'location' => $book->location->location_name ?? $book->rack ?? '-',
            'remark' => $book->remark ?? '-',
            'author' => $book->authors->pluck('author_name')->join(', ') ?: '-',
            'publisher' => $book->publisher ?? '-',
            'qty' => $copies->count(),
            'loanStatus' => $status,
            'loanDate' => '-',
        ];
    })->values()->all();
@endphp

<div
    class="relative isolate w-full min-w-0 max-w-full"
    x-data="{
        tableRowData: @js($tableRows),
        search: '',
        itemsPerPage: 20,
        currentPage: 1,

        get filteredRows() {
            const keyword = this.search.trim().toLowerCase();

            if (!keyword) {
                return this.tableRowData;
            }

            return this.tableRowData.filter(row => {
                return [
                    row.tagNo,
                    row.bookNo,
                    row.catNo,
                    row.equipment,
                    row.description,
                    row.title,
                    row.location,
                    row.remark,
                    row.author,
                    row.publisher,
                    row.loanStatus,
                    row.loanDate,
                ].some(value =>
                    String(value ?? '')
                        .toLowerCase()
                        .includes(keyword)
                );
            });
        },

        get totalPages() {
            return Math.max(
                1,
                Math.ceil(this.filteredRows.length / this.itemsPerPage)
            );
        },

        get paginatedRows() {
            const start = (this.currentPage - 1) * this.itemsPerPage;

            return this.filteredRows.slice(
                start,
                start + this.itemsPerPage
            );
        },

        get displayedPages() {
            const pages = [];

            for (let i = 1; i <= this.totalPages; i++) {
                if (
                    i === 1 ||
                    i === this.totalPages ||
                    (i >= this.currentPage - 1 &&
                        i <= this.currentPage + 1)
                ) {
                    pages.push(i);
                } else if (pages[pages.length - 1] !== '...') {
                    pages.push('...');
                }
            }

            return pages;
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },

        goToPage(page) {
            if (
                typeof page === 'number' &&
                page >= 1 &&
                page <= this.totalPages
            ) {
                this.currentPage = page;
            }
        },

        getStatusClass(status) {
            const classes = {
                'Tersedia':
                    'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',

                'Dipinjam':
                    'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400',

                'Hilang':
                    'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',

                'Rusak':
                    'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',
            };

            return classes[status] || '';
        }
    }"
>
    <div
        class="relative w-full min-w-0 max-w-full rounded-xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]"
    >

        {{-- HEADER --}}
        <div
            class="relative z-10 mb-3 flex min-w-0 max-w-full flex-col gap-3 bg-white px-4 pt-3 dark:bg-transparent sm:flex-row sm:items-center sm:justify-between"
        >

            <div class="min-w-0">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Data Buku
                </h3>

                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                    Daftar koleksi buku perpustakaan
                </p>
            </div>

            <div class="flex w-full min-w-0 max-w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">

                {{-- SEARCH --}}
                <div class="relative w-full sm:w-64">

                    <span
                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
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
                                d="m21 21-4.35-4.35m2.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                            />
                        </svg>
                    </span>

                    <input
                        type="text"
                        x-model="search"
                        @input="currentPage = 1"
                        placeholder="Cari buku..."
                        class="h-10 w-full rounded-lg border border-gray-300 bg-transparent pl-9 pr-9 text-xs text-gray-800 shadow-theme-xs outline-none transition focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                    >

                    <button
                        type="button"
                        x-show="search.length > 0"
                        @click="search = ''; currentPage = 1"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
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
                                d="M6 6l12 12M18 6L6 18"
                            />
                        </svg>
                    </button>

                </div>

                {{-- TAMBAH BUKU --}}
                <a
                    href="{{ route('books.create') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-lg bg-brand-500 px-3 py-2 text-xs font-medium text-white shadow-theme-xs transition hover:bg-brand-600"
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

        </div>

        {{-- TABLE --}}
        <div
            class="relative z-0 block w-full max-w-full overflow-x-auto"
        >
            <table class="min-w-[2100px] table-fixed">

                {{-- TABLE HEADER --}}
                <thead
                    class="border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900"
                >
                    <tr>

                        <th class="w-[55px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            NO
                        </th>

                        <th class="w-[110px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            TAG NO
                        </th>

                        <th class="w-[110px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            BOOK NO.
                        </th>

                        <th class="w-[100px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            CAT. NO.
                        </th>

                        <th class="w-[120px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            EQUIPMENT
                        </th>

                        <th class="w-[200px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            DESCRIPTION
                        </th>

                        <th class="w-[190px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            TITLE
                        </th>

                        <th class="w-[120px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            LOCATION
                        </th>

                        <th class="w-[130px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            REMARK
                        </th>

                        <th class="w-[160px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            AUTHOR
                        </th>

                        <th class="w-[140px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            PUBLISHER
                        </th>

                        <th class="w-[55px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            QTY
                        </th>

                        <th class="w-[130px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            STATUS PINJAMAN
                        </th>

                        <th class="w-[130px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            TANGGAL PINJAMAN
                        </th>

                        <th class="w-[80px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            ACTION
                        </th>

                    </tr>
                </thead>

                {{-- TABLE BODY --}}
                <tbody>

                    <template
                        x-for="(row, index) in paginatedRows"
                        :key="row.id"
                    >
                        <tr
                            class="border-b border-gray-100 transition-colors hover:bg-gray-50 dark:border-white/[0.05] dark:hover:bg-white/[0.02]"
                        >

                            <td class="px-3 py-3">
                                <span
                                    class="text-xs text-gray-600 dark:text-gray-400"
                                    x-text="((currentPage - 1) * itemsPerPage) + index + 1"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="block truncate text-xs font-medium text-gray-700 dark:text-gray-300"
                                    x-text="row.tagNo"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="block truncate text-xs font-semibold text-gray-700 dark:text-gray-300"
                                    x-text="row.bookNo"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.catNo"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.equipment"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.description"
                                    :title="row.description"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="block truncate text-xs font-medium text-gray-700 dark:text-gray-300"
                                    x-text="row.title"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.location"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.remark"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.author"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="block truncate text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.publisher"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="text-xs font-medium text-gray-700 dark:text-gray-300"
                                    x-text="row.qty"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="inline-block whitespace-nowrap rounded-full px-2 py-0.5 text-[10px] font-medium"
                                    :class="getStatusClass(row.loanStatus)"
                                    x-text="row.loanStatus"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <span
                                    class="whitespace-nowrap text-xs text-gray-600 dark:text-gray-400"
                                    x-text="row.loanDate"
                                ></span>
                            </td>

                            <td class="px-3 py-3">
                                <div class="flex items-center gap-3">

                                    {{-- EDIT --}}
                                    <a
                                        :href="'{{ url('/books') }}/' + row.id + '/edit'"
                                        class="text-gray-500 transition-colors hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-400"
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

                                    {{-- HAPUS --}}
                                    <form
                                        method="POST"
                                        :action="'{{ url('/books') }}/' + encodeURIComponent(row.bookNo)"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-gray-500 transition-colors hover:text-red-500 dark:text-gray-400 dark:hover:text-red-500"
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

                    {{-- DATA KOSONG --}}
                    <template x-if="filteredRows.length === 0">
                        <tr>
                            <td
                                colspan="15"
                                class="px-3 py-10 text-center text-sm text-gray-500 dark:text-gray-400"
                            >
                                <span x-show="search">
                                    Tidak ada buku yang cocok dengan pencarian
                                    "<span x-text="search"></span>".
                                </span>

                                <span x-show="!search">
                                    Belum ada data buku.
                                </span>
                            </td>
                        </tr>
                    </template>

                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div
            class="relative z-10 border-t border-gray-200 bg-white px-4 py-4 dark:border-white/[0.05] dark:bg-transparent"
        >
            <div class="flex items-center justify-between">

                <button
                    type="button"
                    @click="prevPage"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'cursor-not-allowed opacity-50' : ''"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5"
                >
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path
                            d="M2.58301 9.99868L8.86018 4.46984M2.58301 9.99868L8.86018 15.5301M2.58301 9.99868H17.4175"
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
                                :class="currentPage === page
                                    ? 'bg-blue-500 text-white'
                                    : 'text-gray-700 hover:bg-blue-500/[0.08] hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-500'"
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
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'cursor-not-allowed opacity-50' : ''"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5"
                >
                    <span class="hidden sm:inline">
                        Next
                    </span>

                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path
                            d="M17.4175 9.9986L11.1403 4.46984M17.4175 9.9986L11.1403 15.5301M17.4175 9.9986H2.58301"
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