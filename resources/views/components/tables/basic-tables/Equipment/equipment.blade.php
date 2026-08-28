@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <x-common.component-card title="Data Equipment">
            <div class="space-y-5">

                {{-- Header --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    {{-- Search --}}
                    <div class="w-full sm:max-w-sm">
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path
                                        d="M17.5 17.5L13.875 13.875M15.8333 9.16667C15.8333 12.8486 12.8486 15.8333 9.16667 15.8333C5.48477 15.8333 2.5 12.8486 2.5 9.16667C2.5 5.48477 5.48477 2.5 9.16667 2.5C12.8486 2.5 15.8333 5.48477 15.8333 9.16667Z"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </span>

                            <input
                                id="equipment-search"
                                type="text"
                                name="search"
                                value="{{ $search ?? '' }}"
                                placeholder="Cari equipment..."
                                autocomplete="off"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-11 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                            >

                            <span
                                id="equipment-search-loading"
                                class="pointer-events-none absolute right-4 top-1/2 hidden -translate-y-1/2"
                            >
                                <svg
                                    class="h-5 w-5 animate-spin text-brand-500"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-dasharray="40 20"
                                    />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Tambah --}}
                    <a
                        href="{{ route('equipment.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600"
                    >
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path
                                d="M10 4.16667V15.8333M4.16667 10H15.8333"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                            />
                        </svg>
                        Tambah Equipment
                    </a>
                </div>

                {{-- Search Info --}}
                <div id="equipment-search-info">
                    @if (!empty($search))
                        <div class="flex flex-col gap-2 rounded-lg border border-brand-200 bg-brand-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between dark:border-brand-500/20 dark:bg-brand-500/10">
                            <p class="text-sm text-brand-700 dark:text-brand-400">
                                Menampilkan hasil pencarian untuk:
                                <span class="font-semibold">"{{ $search }}"</span>
                            </p>

                            <button
                                type="button"
                                id="reset-equipment-search"
                                class="text-left text-sm font-medium text-brand-600 transition hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300"
                            >
                                Reset pencarian
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Alert Success --}}
                @if (session('success'))
                    <div class="rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/10 dark:text-success-400">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Alert Error --}}
                @if (session('error'))
                    <div class="rounded-lg border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-700 dark:border-error-500/30 dark:bg-error-500/10 dark:text-error-400">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Table --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800">
                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full min-w-[700px]">

                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-800">
                                    <th class="px-5 py-3.5 text-left">
                                        <p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                            NO.
                                        </p>
                                    </th>

                                    <th class="px-5 py-3.5 text-left">
                                        <p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                            EQUIPMENT
                                        </p>
                                    </th>

                                    <th class="px-5 py-3.5 text-center">
                                        <p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                            JUMLAH BUKU
                                        </p>
                                    </th>

                                    <th class="px-5 py-3.5 text-right">
                                        <p class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                                            AKSI
                                        </p>
                                    </th>
                                </tr>
                            </thead>

                            <tbody
                                id="equipment-table-body"
                                class="divide-y divide-gray-200 dark:divide-gray-800"
                            >
                                @forelse ($equipments as $equipment)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                        {{-- No --}}
                                        <td class="px-5 py-4">
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ $equipments->firstItem() + $loop->index }}
                                            </p>
                                        </td>

                                        {{-- Equipment --}}
                                        <td class="px-5 py-4">
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ $equipment->equipment_name }}
                                            </p>
                                        </td>

                                        {{-- Jumlah --}}
                                        <td class="px-5 py-4 text-center">
                                            @if ($equipment->books_count > 0)
                                                <span class="inline-flex rounded-full bg-brand-50 px-2.5 py-1 text-xs font-medium text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                                                    {{ $equipment->books_count }}
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-white/[0.05] dark:text-gray-400">
                                                    0
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-5 py-4">
                                            <div class="flex justify-end gap-2">

                                                {{-- Edit --}}
                                                <a
                                                    href="{{ route('equipment.edit', $equipment->equipment_id) }}"
                                                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                                                >
                                                    Edit
                                                </a>

                                                {{-- Hapus --}}
                                                @if ($equipment->books_count == 0)
                                                    <form
                                                        action="{{ route('equipment.destroy', $equipment->equipment_id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus equipment ini?')"
                                                    >
                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center justify-center rounded-lg bg-error-500 px-3 py-2 text-sm font-medium text-white transition hover:bg-error-600"
                                                        >
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <button
                                                        type="button"
                                                        disabled
                                                        title="Equipment masih digunakan oleh buku"
                                                        class="inline-flex cursor-not-allowed items-center justify-center rounded-lg bg-gray-200 px-3 py-2 text-sm font-medium text-gray-400 dark:bg-gray-800 dark:text-gray-600"
                                                    >
                                                        Hapus
                                                    </button>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">

                                                <svg
                                                    class="mb-3 h-10 w-10 text-gray-300 dark:text-gray-600"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"
                                                    />
                                                </svg>

                                                @if (!empty($search))
                                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Equipment
                                                        <span class="font-semibold">"{{ $search }}"</span>
                                                        tidak ditemukan.
                                                    </p>

                                                    <button
                                                        type="button"
                                                        id="reset-equipment-search-empty"
                                                        class="mt-3 text-sm font-medium text-brand-500 hover:text-brand-600"
                                                    >
                                                        Reset pencarian
                                                    </button>
                                                @else
                                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Belum ada data equipment.
                                                    </p>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div id="equipment-pagination">
                    @if ($equipments->hasPages())
                        <div class="pt-2">
                            {{ $equipments->appends(['search' => $search])->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </x-common.component-card>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('equipment-search');
    const tableBody = document.getElementById('equipment-table-body');
    const searchInfo = document.getElementById('equipment-search-info');
    const pagination = document.getElementById('equipment-pagination');
    const loading = document.getElementById('equipment-search-loading');

    if (!searchInput || !tableBody) {
        return;
    }

    let searchTimer;

    function searchEquipment() {
        const search = searchInput.value.trim();

        clearTimeout(searchTimer);

        searchTimer = setTimeout(function () {
            loading.classList.remove('hidden');

            const url = new URL(
                '{{ route('equipment.index') }}',
                window.location.origin
            );

            if (search !== '') {
                url.searchParams.set('search', search);
            }

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengambil data equipment.');
                }

                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const documentHTML = parser.parseFromString(html, 'text/html');

                const newTableBody = documentHTML.querySelector(
                    '#equipment-table-body'
                );

                const newSearchInfo = documentHTML.querySelector(
                    '#equipment-search-info'
                );

                const newPagination = documentHTML.querySelector(
                    '#equipment-pagination'
                );

                if (newTableBody) {
                    tableBody.innerHTML = newTableBody.innerHTML;
                }

                if (newSearchInfo) {
                    searchInfo.innerHTML = newSearchInfo.innerHTML;
                }

                if (newPagination) {
                    pagination.innerHTML = newPagination.innerHTML;
                }

                window.history.replaceState(
                    {},
                    '',
                    search !== ''
                        ? `${url.pathname}?search=${encodeURIComponent(search)}`
                        : url.pathname
                );
            })
            .catch(error => {
                console.error(error);
            })
            .finally(() => {
                loading.classList.add('hidden');
            });
        }, 250);
    }

    searchInput.addEventListener('input', searchEquipment);

    document.addEventListener('click', function (event) {
        if (
            event.target.id === 'reset-equipment-search' ||
            event.target.id === 'reset-equipment-search-empty'
        ) {
            searchInput.value = '';
            searchEquipment();
            searchInput.focus();
        }
    });
});
</script>
@endpush