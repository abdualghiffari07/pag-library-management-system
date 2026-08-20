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
            'bookNo' => $book->book_code ?? '-',
            'catNo' => $book->cat_no ?? '-',
            'location' => $book->location->location_name ?? '-',
            'title' => $book->title ?? '-',
            'author' => $book->authors->pluck('author_name')->join(', ') ?: '-',
            'publisher' => $book->publisher ?? '-',
            'qty' => $copies->count(),
            'loanStatus' => $status,
            'loanDate' => '-',
        ];
    })->values()->all();
@endphp

<div
    x-data="{
        tableRowData: @js($tableRows),
        selectedRows: [],
        selectAll: false,
        itemsPerPage: 20,
        currentPage: 1,

        get totalPages() {
            return Math.max(
                1,
                Math.ceil(this.tableRowData.length / this.itemsPerPage)
            );
        },

        get paginatedRows() {
            const start = (this.currentPage - 1) * this.itemsPerPage;

            return this.tableRowData.slice(
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
                this.selectedRows = [];
                this.selectAll = false;
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.selectedRows = [];
                this.selectAll = false;
            }
        },

        goToPage(page) {
            if (
                typeof page === 'number' &&
                page >= 1 &&
                page <= this.totalPages
            ) {
                this.currentPage = page;
                this.selectedRows = [];
                this.selectAll = false;
            }
        },

        handleSelectAll() {
            this.selectAll = !this.selectAll;

            this.selectedRows = this.selectAll
                ? this.paginatedRows.map(row => row.id)
                : [];
        },

        handleRowSelect(id) {
            if (this.selectedRows.includes(id)) {
                this.selectedRows = this.selectedRows.filter(
                    rowId => rowId !== id
                );
            } else {
                this.selectedRows.push(id);
            }

            this.selectAll =
                this.paginatedRows.length > 0 &&
                this.paginatedRows.every(
                    row => this.selectedRows.includes(row.id)
                );
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
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white pt-3 dark:border-white/[0.05] dark:bg-white/[0.03]">

        <div class="mb-3 flex flex-col gap-3 px-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Data Buku
                </h3>

                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                    Daftar koleksi buku perpustakaan
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
                >
                    <svg
                        class="h-4 w-4 fill-none stroke-current"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M2.29004 5.90393H17.7067"
                            stroke-width="1.5"
                            stroke-linecap="round"
                        />
                        <path
                            d="M17.7075 14.0961H2.29085"
                            stroke-width="1.5"
                            stroke-linecap="round"
                        />
                        <path
                            d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z"
                            stroke-width="1.5"
                        />
                        <path
                            d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z"
                            stroke-width="1.5"
                        />
                    </svg>

                    Filter
                </button>

                <button
                    type="button"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
                >
                    See all
                </button>

                <a
                    href="{{ route('books.create') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-2 text-xs font-medium text-white shadow-theme-xs transition hover:bg-brand-600"
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

        <div class="max-w-full overflow-x-auto">
            <table class="w-full min-w-[1200px] table-fixed">
                <thead class="border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                    <tr>
                        <th class="w-[105px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <div
                                    @click="handleSelectAll()"
                                    class="flex h-4 w-4 cursor-pointer items-center justify-center rounded border"
                                    :class="selectAll
                                        ? 'border-blue-500 bg-blue-500'
                                        : 'border-gray-300 bg-white dark:border-gray-700 dark:bg-transparent'"
                                >
                                    <svg
                                        :class="selectAll ? 'block' : 'hidden'"
                                        width="11"
                                        height="11"
                                        viewBox="0 0 14 14"
                                        fill="none"
                                    >
                                        <path
                                            d="M11.6668 3.5L5.25016 9.91667L2.3335 7"
                                            stroke="white"
                                            stroke-width="1.94437"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </div>

                                <span>BOOK NO.</span>
                            </div>
                        </th>

                        <th class="w-[95px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            CAT. NO.
                        </th>

                        <th class="w-[115px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            LOCATION
                        </th>

                        <th class="w-[190px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            TITLE
                        </th>

                        <th class="w-[150px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            AUTHOR
                        </th>

                        <th class="w-[130px] px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
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

                <tbody>
                    <template
                        x-for="row in paginatedRows"
                        :key="row.id"
                    >
                        <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50 dark:border-white/[0.05] dark:hover:bg-white/[0.02]">

                            <td class="px-3 py-3">
                                <div class="flex items-center gap-2">
                                    <div
                                        @click="handleRowSelect(row.id)"
                                        class="flex h-4 w-4 shrink-0 cursor-pointer items-center justify-center rounded border"
                                        :class="selectedRows.includes(row.id)
                                            ? 'border-blue-500 bg-blue-500'
                                            : 'border-gray-300 bg-white dark:border-gray-700 dark:bg-transparent'"
                                    >
                                        <svg
                                            :class="selectedRows.includes(row.id) ? 'block' : 'hidden'"
                                            width="11"
                                            height="11"
                                            viewBox="0 0 14 14"
                                            fill="none"
                                        >
                                            <path
                                                d="M11.6668 3.5L5.25016 9.91667L2.3335 7"
                                                stroke="white"
                                                stroke-width="1.94437"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </div>

                                    <span
                                        class="truncate text-xs font-semibold text-gray-700 dark:text-gray-300"
                                        x-text="row.bookNo"
                                    ></span>
                                </div>
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
                                    x-text="row.location"
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
                                <div class="flex items-center gap-2">

                                    <button
                                        type="button"
                                        class="text-gray-500 transition-colors hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-400"
                                        title="Lihat detail"
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
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                    </button>

                                    <button
                                        type="button"
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

                                </div>
                            </td>
                        </tr>
                    </template>

                    <template x-if="tableRowData.length === 0">
                        <tr>
                            <td
                                colspan="10"
                                class="px-3 py-10 text-center text-sm text-gray-500 dark:text-gray-400"
                            >
                                Belum ada data buku.
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 px-4 py-4 dark:border-white/[0.05]">
            <div class="flex items-center justify-between">

                <button
                    @click="prevPage"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5"
                >
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z"
                            fill="currentColor"
                        />
                    </svg>

                    <span class="hidden sm:inline">Previous</span>
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
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5"
                >
                    <span class="hidden sm:inline">Next</span>

                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8233 11.4336 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99812 17.4175 9.9986 17.4175 9.9986Z"
                            fill="currentColor"
                        />
                    </svg>
                </button>

            </div>
        </div>
    </div>
</div>