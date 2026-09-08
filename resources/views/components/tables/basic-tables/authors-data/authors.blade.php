@props([
    'authors',
    'search' => '',
])

@php
    $authorData = $authors->getCollection()->map(function ($author) {
        return [
            'id' => (string) $author->author_id,
            'name' => $author->author_name,
            'editUrl' => url('/authors/' . $author->author_id . '/edit'),
            'deleteUrl' => url('/authors/' . $author->author_id),
        ];
    })->values();
@endphp

<div
    x-data="{
        items: @js($authorData),
        search: @js($search),
        selectionMode: false,
        selected: [],

        get pageIds() {
            return this.items.map(author => String(author.id));
        },

        get allVisibleSelected() {
            return this.pageIds.length > 0 &&
                this.pageIds.every(id => this.selected.includes(id));
        },

        get someVisibleSelected() {
            return this.pageIds.some(id => this.selected.includes(id)) &&
                !this.allVisibleSelected;
        },

        isSelected(id) {
            return this.selected.includes(String(id));
        },

        toggleSelect(id) {
            id = String(id);

            if (this.isSelected(id)) {
                this.selected = this.selected.filter(value => value !== id);
            } else {
                this.selected.push(id);
            }
        },

        toggleSelectAll() {
            if (this.allVisibleSelected) {
                this.selected = this.selected.filter(
                    id => !this.pageIds.includes(id)
                );
            } else {
                this.selected = [
                    ...new Set([...this.selected, ...this.pageIds])
                ];
            }
        },

        toggleSelectionMode() {
            this.selectionMode = !this.selectionMode;

            if (!this.selectionMode) {
                this.selected = [];
            }
        },

        clearSelection() {
            this.selected = [];
        },

        clearSearch() {
            this.search = '';
            this.clearSelection();
            this.$refs.searchForm.requestSubmit();
        }
    }"
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pb-0 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6"
>

    {{-- Pesan --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-600 dark:border-success-800 dark:bg-success-500/10 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-600 dark:border-error-800 dark:bg-error-500/10 dark:text-error-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-5">

        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Data Penulis
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Daftar penulis buku yang terdaftar dalam sistem.
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            {{-- Search --}}
            <form
                x-ref="searchForm"
                action="{{ route('authors') }}"
                method="GET"
                class="relative w-full sm:w-[320px]"
            >
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
                    name="search"
                    x-model="search"
                    @input.debounce.450ms="
                        clearSelection();
                        $refs.searchForm.requestSubmit();
                    "
                    autocomplete="off"
                    placeholder="Cari penulis..."
                    class="h-10 w-full rounded-lg border border-gray-300 bg-white pl-9 pr-9 text-xs text-gray-800 shadow-theme-xs outline-none transition placeholder:text-gray-400 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                >

                <button
                    type="button"
                    x-show="search.length > 0"
                    x-cloak
                    @click="clearSearch()"
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
            </form>

            {{-- Actions --}}
            <div class="flex flex-wrap items-center justify-end gap-2">

                {{-- Hapus Terpilih --}}
                <form
                    x-show="selectionMode && selected.length > 0"
                    x-cloak
                    action="{{ route('authors.bulk-destroy') }}"
                    method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus semua penulis yang dipilih? Relasi penulis dengan buku akan dilepas, tetapi data buku tetap tersimpan.')"
                >
                    @csrf
                    @method('DELETE')

                    <input
                        type="hidden"
                        name="search"
                        value="{{ $search }}"
                    >

                    <template x-for="id in selected" :key="id">
                        <input
                            type="hidden"
                            name="ids[]"
                            :value="id"
                        >
                    </template>

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-error-500 px-3 text-xs font-medium text-white shadow-theme-xs transition hover:bg-error-600"
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
                                stroke-width="1.8"
                                d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6m5 4v6m4-6v6"
                            />
                        </svg>

                        Hapus Terpilih

                        <span
                            class="rounded bg-white/20 px-1.5 py-0.5 text-[10px]"
                            x-text="selected.length"
                        ></span>
                    </button>
                </form>

                {{-- Pilih --}}
                <button
                    type="button"
                    @click="toggleSelectionMode()"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-transparent px-3 text-xs font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                    :class="selectionMode
                        ? 'border-brand-500 text-brand-500 dark:border-brand-500 dark:text-brand-400'
                        : ''"
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
                            stroke-width="1.8"
                            d="M9 11l2 2 4-4M5 4h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"
                        />
                    </svg>

                    <span x-text="selectionMode ? 'Selesai' : 'Pilih'"></span>
                </button>

                {{-- Tambah --}}
                <a
                    href="{{ route('authors.create') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 text-xs font-medium text-white shadow-theme-xs transition hover:bg-brand-600"
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

                    Tambah Penulis
                </a>

            </div>

        </div>

        {{-- Jumlah Dipilih --}}
        <div
            x-show="selectionMode && selected.length > 0"
            x-cloak
            class="mt-3 flex items-center gap-3"
        >
            <span class="text-[11px] text-gray-500 dark:text-gray-400">
                <span
                    class="font-semibold text-gray-700 dark:text-gray-300"
                    x-text="selected.length"
                ></span>
                data dipilih
            </span>

            <button
                type="button"
                @click="clearSelection()"
                class="text-[11px] font-medium text-brand-500 transition hover:text-brand-600"
            >
                Batalkan pilihan
            </button>
        </div>

    </div>

    {{-- Table --}}
    <div class="max-w-full overflow-x-auto custom-scrollbar">

        <table class="min-w-full">

            <thead>
                <tr class="border-y border-gray-100 dark:border-gray-800">

                    {{-- Pilih Semua --}}
                    <th
                        x-show="selectionMode"
                        x-cloak
                        class="w-12 px-3 py-3 text-center"
                    >
                        <label class="relative inline-flex h-4 w-4 cursor-pointer items-center justify-center">

                            <input
                                type="checkbox"
                                :checked="allVisibleSelected"
                                @change="toggleSelectAll()"
                                x-effect="$el.indeterminate = someVisibleSelected"
                                class="peer absolute inset-0 h-4 w-4 cursor-pointer appearance-none rounded-[4px] border border-gray-300 bg-white transition checked:border-brand-500 checked:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-900 dark:checked:border-brand-500 dark:checked:bg-brand-500"
                                title="Pilih semua penulis pada halaman ini"
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
                    </th>

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

                @forelse ($authors as $index => $author)

                    @php
                        $authorId = (string) $author->author_id;
                    @endphp

                    <tr
                        class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]"
                        :class="selectionMode && isSelected(@js($authorId))
                            ? 'bg-brand-50/40 dark:bg-brand-500/[0.04]'
                            : ''"
                    >

                        {{-- Pilih --}}
                        <td
                            x-show="selectionMode"
                            x-cloak
                            class="w-12 px-3 py-3 text-center"
                        >
                            <label class="relative inline-flex h-4 w-4 cursor-pointer items-center justify-center">

                                <input
                                    type="checkbox"
                                    value="{{ $authorId }}"
                                    :checked="isSelected(@js($authorId))"
                                    @change="toggleSelect(@js($authorId))"
                                    class="author-select peer absolute inset-0 h-4 w-4 cursor-pointer appearance-none rounded-[4px] border border-gray-300 bg-white transition checked:border-brand-500 checked:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-900 dark:checked:border-brand-500 dark:checked:bg-brand-500"
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
                        </td>

                        {{-- No --}}
                        <td class="px-3 py-3">
                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ $authors->firstItem() + $index }}
                            </p>
                        </td>

                        {{-- ID Penulis --}}
                        <td class="px-3 py-3">
                            <span class="font-medium text-gray-700 text-theme-sm dark:text-gray-300">
                                #{{ $author->author_id }}
                            </span>
                        </td>

                        {{-- Nama Penulis --}}
                        <td class="px-3 py-3">
                            <p class="font-medium text-gray-700 text-theme-sm dark:text-gray-300">
                                {{ $author->author_name }}
                            </p>
                        </td>

                        {{-- Jumlah Buku --}}
                        <td class="px-3 py-3">
                            <span class="text-gray-700 text-theme-sm dark:text-gray-400">
                                {{ $author->books_count ?? 0 }} buku
                            </span>
                        </td>

                        {{-- Dibuat --}}
                        <td class="px-3 py-3">
                            <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                {{ $author->created_at
                                    ? \Carbon\Carbon::parse($author->created_at)->format('d M Y')
                                    : '-' }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-3 py-3">

                            <div class="flex items-center gap-2">

                                {{-- Edit --}}
                                <a
                                    href="{{ url('/authors/' . $author->author_id . '/edit') }}"
                                    title="Edit Penulis"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 transition hover:bg-gray-50 hover:text-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-brand-400"
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

                                {{-- Hapus --}}
                                <form
                                    method="POST"
                                    action="{{ url('/authors/' . $author->author_id) }}"
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
                                                d="M4 7H20M10 11V17M14 11V17M6 7L7 19C7.08333 20 7.91667 20.5 9 20.5H15C16.0833 20.5 16.9167 20 17 19L18 7M9 7V4.5C9 4.22386 9.22386 4 9.5 4H14.5C14.7761 4 15 4.22386 15 4.5V7"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            :colspan="selectionMode ? 7 : 6"
                            class="px-3 py-10 text-center"
                        >
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if ($search !== '')
                                    Penulis dengan pencarian "{{ $search }}" tidak ditemukan.
                                @else
                                    Belum ada data penulis.
                                @endif
                            </p>

                            @if ($search !== '')
                                <a
                                    href="{{ route('authors') }}"
                                    class="mt-3 inline-flex rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                                >
                                    Tampilkan Semua
                                </a>
                            @endif
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    @if ($authors->total() > 0)
        <div class="border-t border-gray-100 px-1 py-4 dark:border-gray-800">

            <div class="grid grid-cols-3 items-center gap-2">

                {{-- Previous --}}
                <div class="flex justify-start">

                    @if ($authors->onFirstPage())
                        <button
                            type="button"
                            disabled
                            class="inline-flex h-10 cursor-not-allowed items-center gap-2 rounded-lg border border-gray-200 px-3 text-sm font-medium text-gray-400 opacity-50 dark:border-gray-800 dark:text-gray-600"
                        >
                            <span>←</span>
                            <span class="hidden sm:inline">Previous</span>
                        </button>
                    @else
                        <a
                            href="{{ $authors->previousPageUrl() }}"
                            class="inline-flex h-10 items-center gap-2 rounded-lg border border-gray-300 px-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                        >
                            <span>←</span>
                            <span class="hidden sm:inline">Previous</span>
                        </a>
                    @endif

                </div>

                {{-- Page Numbers --}}
                <div class="flex items-center justify-center gap-1">

                    @php
                        $currentPage = $authors->currentPage();
                        $lastPage = $authors->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp

                    <span class="whitespace-nowrap text-xs text-gray-600 dark:text-gray-400 sm:hidden">
                        {{ $currentPage }} / {{ $lastPage }}
                    </span>

                    <div class="hidden items-center gap-1 sm:flex">

                        @if ($startPage > 1)
                            <a
                                href="{{ $authors->url(1) }}"
                                class="inline-flex h-10 min-w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 transition hover:bg-brand-500/[0.08] dark:text-gray-400"
                            >
                                1
                            </a>

                            @if ($startPage > 2)
                                <span class="px-1 text-gray-400">...</span>
                            @endif
                        @endif

                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page === $currentPage)
                                <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-lg bg-brand-500 text-sm font-medium text-white">
                                    {{ $page }}
                                </span>
                            @else
                                <a
                                    href="{{ $authors->url($page) }}"
                                    class="inline-flex h-10 min-w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 transition hover:bg-brand-500/[0.08] dark:text-gray-400"
                                >
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <span class="px-1 text-gray-400">...</span>
                            @endif

                            <a
                                href="{{ $authors->url($lastPage) }}"
                                class="inline-flex h-10 min-w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 transition hover:bg-brand-500/[0.08] dark:text-gray-400"
                            >
                                {{ $lastPage }}
                            </a>
                        @endif

                    </div>

                </div>

                {{-- Next --}}
                <div class="flex justify-end">

                    @if ($authors->hasMorePages())
                        <a
                            href="{{ $authors->nextPageUrl() }}"
                            class="inline-flex h-10 items-center gap-2 rounded-lg border border-gray-300 px-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                        >
                            <span class="hidden sm:inline">Next</span>
                            <span>→</span>
                        </a>
                    @else
                        <button
                            type="button"
                            disabled
                            class="inline-flex h-10 cursor-not-allowed items-center gap-2 rounded-lg border border-gray-200 px-3 text-sm font-medium text-gray-400 opacity-50 dark:border-gray-800 dark:text-gray-600"
                        >
                            <span class="hidden sm:inline">Next</span>
                            <span>→</span>
                        </button>
                    @endif

                </div>

            </div>

        </div>
    @endif

</div>