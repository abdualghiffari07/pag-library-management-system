@props([
    'authors' => collect(),
])

@php
    $authorData = $authors->map(function ($author, $index) {
        return [
            'no' => $index + 1,
            'id' => $author->author_id,
            'name' => $author->author_name,
            'books' => $author->books_count ?? 0,
            'created_at' => $author->created_at
                ? \Carbon\Carbon::parse($author->created_at)->format('d M Y')
                : '-',
        ];
    })->values();
@endphp

<div
    x-data="{
        items: {{ $authorData->toJson() }},
        itemsPerPage: 15,
        currentPage: 1,

        get totalPages() {
            return Math.max(
                1,
                Math.ceil(this.items.length / this.itemsPerPage)
            );
        },

        get paginatedItems() {
            const start =
                (this.currentPage - 1) * this.itemsPerPage;

            return this.items.slice(
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
        }
    }"
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pb-0 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6"
>

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Data Penulis
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Daftar penulis buku yang terdaftar dalam sistem.
            </p>
        </div>

        <div>
            <a
                href="{{ route('authors.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
            >
                <svg
                    class="h-5 w-5"
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

                Tambah Penulis
            </a>
        </div>

    </div>


    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="max-w-full overflow-x-auto custom-scrollbar">

        <table class="min-w-full">

            <thead>
                <tr class="border-y border-gray-100 dark:border-gray-800">

                    <th class="px-3 py-3 text-left font-normal">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            No
                        </p>
                    </th>

                    <th class="px-3 py-3 text-left font-normal">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            ID Penulis
                        </p>
                    </th>

                    <th class="px-3 py-3 text-left font-normal">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            Penulis
                        </p>
                    </th>

                    <th class="px-3 py-3 text-left font-normal">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            Buku
                        </p>
                    </th>

                    <th class="px-3 py-3 text-left font-normal">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            Dibuat
                        </p>
                    </th>

                    <th class="px-3 py-3 text-left font-normal">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            Aksi
                        </p>
                    </th>

                </tr>
            </thead>


            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                <template
                    x-for="author in paginatedItems"
                    :key="author.id"
                >

                    <tr>

                        {{-- No --}}
                        <td class="px-3 py-3">
                            <p
                                class="text-gray-700 text-theme-sm dark:text-gray-400"
                                x-text="author.no"
                            ></p>
                        </td>


                        {{-- ID Penulis --}}
                        <td class="px-3 py-3">
                            <span class="font-medium text-gray-700 text-theme-sm dark:text-gray-300">
                                #<span x-text="author.id"></span>
                            </span>
                        </td>


                        {{-- Nama Penulis --}}
                        <td class="px-3 py-3">
                            <p
                                class="font-medium text-gray-700 text-theme-sm dark:text-gray-300"
                                x-text="author.name"
                            ></p>
                        </td>


                        {{-- Jumlah Buku --}}
                        <td class="px-3 py-3">
                            <span class="text-gray-700 text-theme-sm dark:text-gray-400">
                                <span x-text="author.books"></span> buku
                            </span>
                        </td>


                        {{-- Dibuat --}}
                        <td class="px-3 py-3">
                            <span
                                class="text-gray-500 text-theme-sm dark:text-gray-400"
                                x-text="author.created_at"
                            ></span>
                        </td>


                        {{-- Aksi --}}
                        <td class="px-3 py-3">

                            <div class="flex items-center gap-2">

                                {{-- EDIT --}}
                                <a
                                    :href="'/authors/' + author.id + '/edit'"
                                    title="Edit Penulis"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 transition hover:bg-gray-50 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                >

                                    <svg
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M13.5 6.5L17.5 10.5M4 20L7.5 19.5L19.5 7.5C20.3284 6.67157 20.3284 5.32843 19.5 4.5C18.6716 3.67157 17.3284 3.67157 16.5 4.5L4.5 16.5L4 20Z"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>

                                </a>


                                {{-- DELETE --}}
                                <form
                                    method="POST"
                                    :action="'/authors/' + author.id"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus penulis ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        title="Hapus Penulis"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 transition hover:bg-red-50 hover:text-red-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                    >

                                        <svg
                                            width="18"
                                            height="18"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M4 7H20"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                            />

                                            <path
                                                d="M10 11V17"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                            />

                                            <path
                                                d="M14 11V17"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                            />

                                            <path
                                                d="M6 7L7 19C7.08333 20 7.91667 20.5 9 20.5H15C16.0833 20.5 16.9167 20 17 19L18 7"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />

                                            <path
                                                d="M9 7V4.5C9 4.22386 9.22386 4 9.5 4H14.5C14.7761 4 15 4.22386 15 4.5V7"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                            />
                                        </svg>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                </template>


                {{-- Empty State --}}
                <template x-if="items.length === 0">

                    <tr>
                        <td
                            colspan="6"
                            class="px-3 py-10 text-center"
                        >
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Belum ada data penulis.
                            </p>
                        </td>
                    </tr>

                </template>

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         PAGINATION
    ====================================================== --}}

    <div class="px-1 py-4">

        <div class="flex items-center justify-between">

            {{-- Previous --}}
            <button
                @click="prevPage"
                :disabled="currentPage === 1"
                :class="currentPage === 1
                    ? 'opacity-50 cursor-not-allowed'
                    : ''"
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
                        d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z"
                        fill="currentColor"
                    />
                </svg>

                <span class="hidden sm:inline">
                    Previous
                </span>

            </button>


            {{-- Mobile --}}
            <span class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden">
                Page
                <span x-text="currentPage"></span>
                of
                <span x-text="totalPages"></span>
            </span>


            {{-- Desktop --}}
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


            {{-- Next --}}
            <button
                @click="nextPage"
                :disabled="currentPage === totalPages"
                :class="currentPage === totalPages
                    ? 'opacity-50 cursor-not-allowed'
                    : ''"
                class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5"
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
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4336 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.17685 11.9085 4.1767 12.2013 4.4695L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99812 17.4175 9.9986 17.4175 9.9986Z"
                        fill="currentColor"
                    />
                </svg>

            </button>

        </div>

    </div>

</div>