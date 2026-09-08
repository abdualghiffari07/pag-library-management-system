@props([
    'locations',
])

<div class="space-y-6">
    <x-common.component-card title="Location">

        @if (session('success'))
            <div class="mb-5 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-600 dark:border-success-800 dark:bg-success-500/10 dark:text-success-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-lg border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-600 dark:border-error-800 dark:bg-error-500/10 dark:text-error-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Data Location
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kelola daftar lokasi penyimpanan buku.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

                {{-- Search --}}
                <div class="relative w-full sm:w-[300px]">

                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 20 20"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M3.04199 8.33366C3.04199 5.41015 5.41182 3.04199 8.33366 3.04199C11.2555 3.04199 13.6253 5.41015 13.6253 8.33366C13.6253 11.2572 11.2555 13.6253 8.33366 13.6253C5.41182 13.6253 3.04199 11.2572 3.04199 8.33366ZM8.33366 1.54199C4.5837 1.54199 1.54199 4.58203 1.54199 8.33366C1.54199 12.0853 4.5837 15.1253 8.33366 15.1253C9.97544 15.1253 11.4814 14.5425 12.6564 13.5727L16.1368 17.0531C16.4297 17.346 16.9046 17.346 17.1975 17.0531C17.4904 16.7602 17.4904 16.2853 17.1975 15.9924L13.7171 12.512C14.687 11.337 15.1253 9.97544 15.1253 8.33366C15.1253 4.58203 12.0836 1.54199 8.33366 1.54199Z"
                                fill="currentColor"
                            />
                        </svg>
                    </span>

                    <input
                        id="location-search"
                        type="text"
                        autocomplete="off"
                        placeholder="Cari location..."
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-11 pr-4 text-sm text-gray-800 shadow-theme-xs outline-none placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                    >

                </div>

                {{-- Tambah --}}
                <a
                    href="{{ route('locations.create') }}"
                    class="inline-flex h-11 items-center justify-center whitespace-nowrap rounded-lg bg-brand-500 px-4 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600"
                >
                    Tambah Location
                </a>

            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="max-w-full overflow-x-auto">

                <table class="w-full min-w-[800px]">

                    <thead class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-white/[0.02]">

                        <tr>

                            <th class="w-[80px] px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                No
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Location
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Description
                            </th>

                            <th class="w-[150px] px-5 py-3 text-center text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Jumlah Buku
                            </th>

                            <th class="w-[180px] px-5 py-3 text-center text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody
                        id="location-table-body"
                        class="divide-y divide-gray-100 dark:divide-gray-800"
                    >

                        @forelse ($locations as $index => $location)

                            <tr
                                class="location-row transition hover:bg-gray-50 dark:hover:bg-white/[0.02]"
                                data-search="{{ strtolower(
                                    ($location->location_name ?? '') . ' ' .
                                    ($location->description ?? '') . ' ' .
                                    ($location->books_count ?? 0)
                                ) }}"
                            >

                                <td class="location-number px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                                        {{ $location->location_name }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $location->description ?: '-' }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex min-w-[44px] items-center justify-center rounded-lg bg-gray-100 px-3 py-1.5 text-sm font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                        {{ $location->books_count ?? 0 }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-center gap-2">

                                        <a
                                            href="{{ route('locations.edit', $location) }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('locations.destroy', $location) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus location ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-lg bg-error-500 px-3 py-2 text-xs font-medium text-white shadow-theme-xs transition hover:bg-error-600"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr id="location-empty-data">

                                <td
                                    colspan="5"
                                    class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Belum ada data Location.
                                </td>

                            </tr>

                        @endforelse

                        @if ($locations->isNotEmpty())

                            <tr
                                id="location-search-empty"
                                class="hidden"
                            >

                                <td
                                    colspan="5"
                                    class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Location yang dicari tidak ditemukan.
                                </td>

                            </tr>

                        @endif

                    </tbody>

                </table>

            </div>

        </div>

    </x-common.component-card>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput =
            document.getElementById('location-search');

        const rows =
            document.querySelectorAll('.location-row');

        const emptySearch =
            document.getElementById('location-search-empty');

        if (!searchInput) {
            return;
        }

        searchInput.addEventListener('input', function () {
            const keyword = this.value
                .toLowerCase()
                .trim();

            let visibleNumber = 1;
            let visibleCount = 0;

            rows.forEach(function (row) {
                const searchableText =
                    row.dataset.search?.toLowerCase() ?? '';

                const matched =
                    searchableText.includes(keyword);

                if (matched) {
                    row.classList.remove('hidden');

                    const numberCell =
                        row.querySelector('.location-number');

                    if (numberCell) {
                        numberCell.textContent =
                            visibleNumber;
                    }

                    visibleNumber++;
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            if (!emptySearch) {
                return;
            }

            if (visibleCount === 0) {
                emptySearch.classList.remove('hidden');
            } else {
                emptySearch.classList.add('hidden');
            }
        });
    });
</script>