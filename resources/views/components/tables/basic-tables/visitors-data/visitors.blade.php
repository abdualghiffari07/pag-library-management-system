@props([
    'visitors',
    'search' => '',
    'sort' => 'latest',
])

@php
    $getCategoryMeta = function ($category) {
        $category = strtolower(trim($category ?? 'lainnya'));

        return match ($category) {
            'pekerja' => [
                'key' => 'pekerja',
                'label' => 'Pekerja',
                'class' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400',
                'identity' => 'No. Pekerja',
            ],
            'mahasiswa' => [
                'key' => 'mahasiswa',
                'label' => 'Mahasiswa',
                'class' => 'bg-success-50 text-success-700 ring-success-600/20 dark:bg-success-500/10 dark:text-success-400',
                'identity' => 'NIM / NPM',
            ],
            'tamu' => [
                'key' => 'tamu',
                'label' => 'Tamu',
                'class' => 'bg-warning-50 text-warning-700 ring-warning-600/20 dark:bg-warning-500/10 dark:text-warning-400',
                'identity' => 'No KTP',
            ],
            default => [
                'key' => 'lainnya',
                'label' => 'Lainnya',
                'class' => 'bg-gray-100 text-gray-600 ring-gray-500/20 dark:bg-gray-800 dark:text-gray-300',
                'identity' => 'No. Identitas',
            ],
        };
    };

    $getDisplayNumber = function ($visitor, $category) {
        $number = (string) ($visitor->employee_number ?? '');

        if ($number === '') {
            return '-';
        }

        if ($category === 'tamu' && mb_strlen($number) > 4) {
            return str_repeat('•', mb_strlen($number) - 4)
                . mb_substr($number, -4);
        }

        return $number;
    };

    $getInitial = function ($name) {
        return mb_strtoupper(
            mb_substr(trim((string) $name), 0, 1)
        );
    };
@endphp

<style>
    [x-cloak] {
        display: none !important;
    }

    .visitor-dialog {
        width: min(1080px, calc(100% - 24px));
        max-height: calc(100dvh - 32px);
        margin: auto;
        padding: 0;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        background: white;
        color: #1f2937;
        overflow: hidden;
    }

    .visitor-dialog::backdrop {
        background: rgba(15, 23, 42, 0.72);
        backdrop-filter: blur(2px);
    }

    .visitor-photo-dialog {
        width: min(900px, calc(100% - 24px));
        max-height: calc(100dvh - 32px);
        margin: auto;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: white;
        color: #1f2937;
    }

    .visitor-photo-dialog::backdrop {
        background: rgba(15, 23, 42, 0.88);
    }

    .visitor-photo-dialog img {
        width: 100%;
        max-height: calc(100dvh - 120px);
        object-fit: contain;
    }

    .dark .visitor-dialog,
    .dark .visitor-photo-dialog {
        border-color: #344054;
        background: #1d2939;
        color: #f9fafb;
    }
</style>

<div class="space-y-6" x-data="visitorDirectory()">
    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Data Pengunjung
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Data pengunjung yang telah terdaftar di PAG Library.
            </p>
        </div>

        {{-- Search --}}
        <form
            action="{{ route('visitors') }}"
            method="GET"
            class="flex w-full flex-col gap-2 sm:flex-row sm:flex-wrap lg:w-auto lg:justify-end"
        >
            <div class="relative min-w-0 flex-1 sm:min-w-[260px]">
                <svg
                    class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                    />
                </svg>

                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    autocomplete="off"
                    placeholder="Cari nama, nomor, kategori..."
                    class="h-11 w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                >
            </div>

            <select
                name="sort"
                onchange="this.form.requestSubmit()"
                class="h-11 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            >
                <option value="latest" @selected($sort === 'latest')>
                    Terbaru
                </option>
                <option value="oldest" @selected($sort === 'oldest')>
                    Terlama
                </option>
                <option value="all" @selected($sort === 'all')>
                    Semua
                </option>
            </select>

            <button
                type="submit"
                class="h-11 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white transition hover:bg-brand-600"
            >
                Cari
            </button>

            @if ($search !== '' || $sort !== 'latest')
                <a
                    href="{{ route('visitors') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-300 px-4 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                >
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Notification --}}
    @if (session('success'))
        <div class="rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-600 dark:border-success-800 dark:bg-success-500/10 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-600 dark:border-error-800 dark:bg-error-500/10 dark:text-error-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Card --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Card Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-5 py-4 dark:border-gray-800">
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                    Daftar Pengunjung
                </h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ $visitors->total() }} pengunjung ditemukan.
                </p>
            </div>

            <span class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                @switch($sort)
                    @case('oldest')
                        Pendaftaran terlama
                        @break
                    @case('all')
                        Semua · A–Z
                        @break
                    @default
                        Pendaftaran terbaru
                @endswitch
            </span>
        </div>

        {{-- Desktop --}}
        <div class="hidden overflow-x-auto lg:block">
            <table class="w-full min-w-[1150px]">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-white/[0.02]">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            No
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Foto
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Nama Pengunjung
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Kategori
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Nomor Identitas
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Waktu Pendaftaran
                        </th>
                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Kunjungan
                        </th>
                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Status
                        </th>
                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($visitors as $visitor)
                        @php
                            $meta = $getCategoryMeta($visitor->visitor_category);
                            $displayNumber = $getDisplayNumber($visitor, $meta['key']);
                            $initial = $getInitial($visitor->visitor_name);
                            $isActive = (bool) ($visitor->is_active ?? true);

                            $canDelete =
                                (int) $visitor->checkins_count === 0
                                && (int) $visitor->loans_count === 0;
                        @endphp

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            {{-- No --}}
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $visitors->firstItem() + $loop->index }}
                            </td>

                            {{-- Foto --}}
                            <td class="px-4 py-3">
                                <button
                                    type="button"
                                    x-on:click="openDetails(
                                        @js(route('visitors.details', $visitor->visitor_id))
                                    )"
                                    class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-full border border-gray-200 bg-gray-100 text-sm font-semibold text-gray-500 transition hover:border-brand-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                                    title="Lihat detail {{ $visitor->visitor_name }}"
                                >
                                    @if ($visitor->profile_photo)
                                        <img
                                            src="{{ route('visitors.profile-photo', $visitor->visitor_id) }}"
                                            alt="Foto profil {{ $visitor->visitor_name }}"
                                            loading="lazy"
                                            class="h-full w-full object-cover"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >
                                        <span
                                            style="display: none;"
                                            class="h-full w-full items-center justify-center"
                                        >
                                            {{ $initial }}
                                        </span>
                                    @else
                                        {{ $initial }}
                                    @endif
                                </button>
                            </td>

                            {{-- Nama --}}
                            <td class="px-4 py-4">
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $visitor->visitor_name }}
                                </p>

                                @unless ($isActive)
                                    <p class="mt-1 text-xs text-gray-400">
                                        Pengunjung nonaktif
                                    </p>
                                @endunless
                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $meta['class'] }}">
                                    {{ $meta['label'] }}
                                </span>
                            </td>

                            {{-- Identitas --}}
                            <td class="px-4 py-4">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $displayNumber }}
                                </span>
                                <p class="mt-1 text-xs text-gray-400">
                                    {{ $meta['identity'] }}
                                </p>
                            </td>

                            {{-- Terdaftar --}}
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $visitor->created_at
                                    ? $visitor->created_at->format('d M Y, H:i')
                                    : '-' }}
                            </td>

                            {{-- Kunjungan --}}
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex min-w-8 items-center justify-center rounded-lg bg-gray-100 px-2.5 py-1.5 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    {{ $visitor->checkins_count }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-4 text-center">
                                @if ($isActive)
                                    <span class="inline-flex rounded-full bg-success-50 px-2.5 py-1 text-xs font-semibold text-success-600 ring-1 ring-inset ring-success-600/20 dark:bg-success-500/10 dark:text-success-400">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-500 ring-1 ring-inset ring-gray-500/20 dark:bg-gray-800 dark:text-gray-400">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        type="button"
                                        x-on:click="openDetails(
                                            @js(route('visitors.details', $visitor->visitor_id))
                                        )"
                                        class="rounded-lg border border-brand-500/20 bg-brand-500/10 px-3 py-2 text-xs font-medium text-brand-600 transition hover:bg-brand-500 hover:text-white dark:text-brand-400"
                                    >
                                        Detail
                                    </button>

                                    @if ($canDelete)
                                        <form
                                            action="{{ route('visitors.destroy', $visitor->visitor_id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengunjung ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg border border-error-200 bg-error-50 px-3 py-2 text-xs font-medium text-error-600 transition hover:bg-error-100 dark:border-error-900/50 dark:bg-error-900/20 dark:text-error-400"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <button
                                            type="button"
                                            disabled
                                            title="Pengunjung memiliki riwayat kunjungan atau peminjaman"
                                            class="cursor-not-allowed rounded-lg border border-gray-200 px-3 py-2 text-xs text-gray-400 dark:border-gray-800"
                                        >
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="9"
                                class="px-6 py-14 text-center text-sm text-gray-500 dark:text-gray-400"
                            >
                                {{ $search !== ''
                                    ? 'Pengunjung tidak ditemukan.'
                                    : 'Belum ada pengunjung yang terdaftar.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="divide-y divide-gray-100 dark:divide-gray-800 lg:hidden">
            @forelse ($visitors as $visitor)
                @php
                    $meta = $getCategoryMeta($visitor->visitor_category);
                    $displayNumber = $getDisplayNumber($visitor, $meta['key']);
                    $initial = $getInitial($visitor->visitor_name);
                    $isActive = (bool) ($visitor->is_active ?? true);

                    $canDelete =
                        (int) $visitor->checkins_count === 0
                        && (int) $visitor->loans_count === 0;
                @endphp

                <div class="p-4">
                    <div class="flex items-start gap-3">
                        {{-- Foto --}}
                        <button
                            type="button"
                            x-on:click="openDetails(
                                @js(route('visitors.details', $visitor->visitor_id))
                            )"
                            class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full border border-gray-200 bg-gray-100 font-semibold text-gray-500 dark:border-gray-700 dark:bg-gray-800"
                        >
                            @if ($visitor->profile_photo)
                                <img
                                    src="{{ route('visitors.profile-photo', $visitor->visitor_id) }}"
                                    alt="Foto profil {{ $visitor->visitor_name }}"
                                    loading="lazy"
                                    class="h-full w-full object-cover"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <span
                                    style="display: none;"
                                    class="h-full w-full items-center justify-center"
                                >
                                    {{ $initial }}
                                </span>
                            @else
                                {{ $initial }}
                            @endif
                        </button>

                        {{-- Nama --}}
                        <div class="min-w-0 flex-1">
                            <p class="break-words text-sm font-semibold text-gray-800 dark:text-white/90">
                                {{ $visitor->visitor_name }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $meta['label'] }} · {{ $meta['identity'] }}
                            </p>

                            <div class="mt-2">
                                @if ($isActive)
                                    <span class="inline-flex rounded-full bg-success-50 px-2.5 py-1 text-[10px] font-semibold text-success-600 dark:bg-success-500/10 dark:text-success-400">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-semibold text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <span class="text-xs text-gray-400">
                            #{{ $visitors->firstItem() + $loop->index }}
                        </span>
                    </div>

                    {{-- Informasi --}}
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="col-span-2 rounded-lg bg-gray-50 p-3 dark:bg-white/[0.02]">
                            <p class="text-xs text-gray-400">
                                Nomor Identitas
                            </p>
                            <p class="mt-1 break-all text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $displayNumber }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/[0.02]">
                            <p class="text-xs text-gray-400">
                                Terdaftar
                            </p>
                            <p class="mt-1 text-xs font-medium text-gray-700 dark:text-gray-300">
                                {{ $visitor->created_at
                                    ? $visitor->created_at->format('d M Y, H:i')
                                    : '-' }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/[0.02]">
                            <p class="text-xs text-gray-400">
                                Total Kunjungan
                            </p>
                            <p class="mt-1 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $visitor->checkins_count }}
                            </p>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="mt-4 flex justify-end gap-2 border-t border-gray-100 pt-3 dark:border-gray-800">
                        <button
                            type="button"
                            x-on:click="openDetails(
                                @js(route('visitors.details', $visitor->visitor_id))
                            )"
                            class="rounded-lg border border-brand-500/20 bg-brand-500/10 px-3 py-2 text-xs font-medium text-brand-600 transition hover:bg-brand-500 hover:text-white dark:text-brand-400"
                        >
                            Detail
                        </button>

                        @if ($canDelete)
                            <form
                                action="{{ route('visitors.destroy', $visitor->visitor_id) }}"
                                method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengunjung ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="rounded-lg border border-error-200 bg-error-50 px-3 py-2 text-xs font-medium text-error-600 transition hover:bg-error-100 dark:border-error-900/50 dark:bg-error-900/20 dark:text-error-400"
                                >
                                    Hapus
                                </button>
                            </form>
                        @else
                            <button
                                type="button"
                                disabled
                                title="Pengunjung memiliki riwayat kunjungan atau peminjaman"
                                class="cursor-not-allowed rounded-lg border border-gray-200 px-3 py-2 text-xs text-gray-400 dark:border-gray-800"
                            >
                                Hapus
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-5 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                    {{ $search !== ''
                        ? 'Pengunjung tidak ditemukan.'
                        : 'Belum ada pengunjung yang terdaftar.' }}
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($visitors->hasPages())
            <div class="border-t border-gray-200 px-4 py-4 dark:border-gray-800">
                {{ $visitors->links() }}
            </div>
        @endif
    </div>

    {{-- Detail --}}
    <x-tables.basic-tables.visitors-data.visitor-detail />
</div>

<script>
    window.visitorDirectory = function () {
        return {
            loading: false,
            visitLoading: false,
            loanLoading: false,
            statusLoading: false,
            statusChanged: false,

            error: '',
            detail: null,
            detailUrl: '',

            activeHistoryTab: 'visits',

            visits: [],
            loans: [],

            visitPage: 1,
            visitLastPage: 1,
            visitTotal: 0,

            loanPage: 1,
            loanLastPage: 1,
            loanTotal: 0,

            requestId: 0,
            profileFailed: false,

            photoUrl: null,
            photoTitle: '',

            identityLabel(category) {
                return {
                    pekerja: 'No. Pekerja',
                    mahasiswa: 'NIM / NPM',
                    tamu: 'No KTP',
                    lainnya: 'No. Identitas',
                }[category] || 'No. Identitas';
            },

            categoryLabel(category) {
                return {
                    pekerja: 'Pekerja',
                    mahasiswa: 'Mahasiswa',
                    tamu: 'Tamu',
                    lainnya: 'Lainnya',
                }[category] || 'Lainnya';
            },

            categoryClass(category) {
                return {
                    pekerja:
                        'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                    mahasiswa:
                        'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400',
                    tamu:
                        'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400',
                    lainnya:
                        'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300',
                }[category]
                    || 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300';
            },

            loanStatusClass(status) {
                return {
                    returned:
                        'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400',
                    borrowed:
                        'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400',
                    overdue:
                        'bg-error-50 text-error-600 dark:bg-error-500/10 dark:text-error-400',
                }[status]
                    || 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300';
            },

            buildDetailUrl(params = {}) {
                const url = new URL(
                    this.detailUrl,
                    window.location.origin
                );

                Object.entries(params).forEach(([key, value]) => {
                    url.searchParams.set(key, value);
                });

                return url.toString();
            },

            async fetchDetail(params = {}) {
                const response = await fetch(
                    this.buildDetailUrl(params),
                    {
                        method: 'GET',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                    }
                );

                if (!response.ok) {
                    throw new Error(
                        'Detail pengunjung tidak dapat dimuat.'
                    );
                }

                return await response.json();
            },

            openDetails(url) {
                this.requestId++;
                this.detailUrl = url;
                this.detail = null;
                this.error = '';
                this.statusChanged = false;

                this.activeHistoryTab = 'visits';

                this.visits = [];
                this.loans = [];

                this.visitPage = 1;
                this.visitLastPage = 1;
                this.visitTotal = 0;

                this.loanPage = 1;
                this.loanLastPage = 1;
                this.loanTotal = 0;

                this.loading = false;
                this.visitLoading = false;
                this.loanLoading = false;
                this.statusLoading = false;
                this.profileFailed = false;

                this.$nextTick(() => {
                    const dialog = this.$refs.detailsDialog;

                    if (!dialog) {
                        console.error(
                            'Dialog detail pengunjung tidak ditemukan.'
                        );
                        return;
                    }

                    if (!dialog.open) {
                        dialog.showModal();
                    }

                    this.loadInitialDetail();
                });
            },

            async loadInitialDetail() {
                const token = this.requestId;

                this.loading = true;
                this.error = '';

                try {
                    const data = await this.fetchDetail({
                        visit_page: 1,
                        loan_page: 1,
                    });

                    if (token !== this.requestId) {
                        return;
                    }

                    this.detail = data.visitor;

                    this.visits =
                        data.visits?.items ?? [];

                    this.visitPage =
                        data.visits?.page ?? 1;

                    this.visitLastPage =
                        data.visits?.last_page ?? 1;

                    this.visitTotal =
                        data.visits?.total ?? 0;

                    this.loans =
                        data.loans?.items ?? [];

                    this.loanPage =
                        data.loans?.page ?? 1;

                    this.loanLastPage =
                        data.loans?.last_page ?? 1;

                    this.loanTotal =
                        data.loans?.total ?? 0;
                } catch (error) {
                    if (token === this.requestId) {
                        this.error =
                            error.message
                            || 'Terjadi kesalahan saat memuat detail pengunjung.';
                    }
                } finally {
                    if (token === this.requestId) {
                        this.loading = false;
                    }
                }
            },

            async loadMoreVisits() {
                if (
                    this.visitLoading
                    || this.visitPage >= this.visitLastPage
                ) {
                    return;
                }

                const token = this.requestId;

                this.visitLoading = true;
                this.error = '';

                try {
                    const data = await this.fetchDetail({
                        visit_page: this.visitPage + 1,
                        loan_page: 1,
                    });

                    if (token !== this.requestId) {
                        return;
                    }

                    this.visits = [
                        ...this.visits,
                        ...(data.visits?.items ?? []),
                    ];

                    this.visitPage =
                        data.visits?.page ?? this.visitPage;

                    this.visitLastPage =
                        data.visits?.last_page ?? this.visitLastPage;

                    this.visitTotal =
                        data.visits?.total ?? this.visitTotal;
                } catch (error) {
                    if (token === this.requestId) {
                        this.error =
                            error.message
                            || 'Riwayat kunjungan gagal dimuat.';
                    }
                } finally {
                    if (token === this.requestId) {
                        this.visitLoading = false;
                    }
                }
            },

            async loadMoreLoans() {
                if (
                    this.loanLoading
                    || this.loanPage >= this.loanLastPage
                ) {
                    return;
                }

                const token = this.requestId;

                this.loanLoading = true;
                this.error = '';

                try {
                    const data = await this.fetchDetail({
                        visit_page: 1,
                        loan_page: this.loanPage + 1,
                    });

                    if (token !== this.requestId) {
                        return;
                    }

                    this.loans = [
                        ...this.loans,
                        ...(data.loans?.items ?? []),
                    ];

                    this.loanPage =
                        data.loans?.page ?? this.loanPage;

                    this.loanLastPage =
                        data.loans?.last_page ?? this.loanLastPage;

                    this.loanTotal =
                        data.loans?.total ?? this.loanTotal;
                } catch (error) {
                    if (token === this.requestId) {
                        this.error =
                            error.message
                            || 'Riwayat peminjaman gagal dimuat.';
                    }
                } finally {
                    if (token === this.requestId) {
                        this.loanLoading = false;
                    }
                }
            },

            async toggleVisitorStatus() {
                if (
                    !this.detail
                    || !this.detail.status_url
                    || this.statusLoading
                ) {
                    return;
                }

                const confirmation = this.detail.is_active
                    ? 'Apakah Anda yakin ingin menonaktifkan pengunjung ini?'
                    : 'Apakah Anda yakin ingin mengaktifkan kembali pengunjung ini?';

                if (!window.confirm(confirmation)) {
                    return;
                }

                this.statusLoading = true;
                this.error = '';

                try {
                    const response = await fetch(
                        this.detail.status_url,
                        {
                            method: 'PATCH',
                            headers: {
                                Accept: 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': @js(csrf_token()),
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({}),
                        }
                    );

                    let data = {};

                    try {
                        data = await response.json();
                    } catch (error) {
                        data = {};
                    }

                    if (!response.ok) {
                        throw new Error(
                            data.message
                            || 'Status pengunjung gagal diubah.'
                        );
                    }

                    this.detail.is_active = Boolean(
                        data.is_active
                    );

                    this.statusChanged = true;

                    window.alert(
                        data.message
                        || 'Status pengunjung berhasil diubah.'
                    );
                } catch (error) {
                    this.error =
                        error.message
                        || 'Status pengunjung gagal diubah.';
                } finally {
                    this.statusLoading = false;
                }
            },

            closeDetails() {
                this.closePhoto();

                const dialog = this.$refs.detailsDialog;

                if (dialog && dialog.open) {
                    dialog.close();
                }
            },

            onDetailsClosed() {
                const shouldReload = this.statusChanged;

                this.requestId++;
                this.detail = null;
                this.detailUrl = '';

                this.visits = [];
                this.loans = [];

                this.visitPage = 1;
                this.visitLastPage = 1;
                this.visitTotal = 0;

                this.loanPage = 1;
                this.loanLastPage = 1;
                this.loanTotal = 0;

                this.error = '';

                this.loading = false;
                this.visitLoading = false;
                this.loanLoading = false;
                this.statusLoading = false;

                this.profileFailed = false;
                this.statusChanged = false;

                if (shouldReload) {
                    window.location.reload();
                }
            },

            openPhoto(url, title) {
                if (!url) {
                    return;
                }

                this.photoUrl = url;
                this.photoTitle = title || 'Pratinjau foto';

                this.$nextTick(() => {
                    const dialog = this.$refs.photoDialog;

                    if (dialog && !dialog.open) {
                        dialog.showModal();
                    }
                });
            },

            closePhoto() {
                const dialog = this.$refs.photoDialog;

                if (dialog && dialog.open) {
                    dialog.close();
                }

                this.photoUrl = null;
                this.photoTitle = '';
            },
        };
    };
</script>