@extends('layouts.app')

@section('content')
@php
    $search = $search ?? request('search', '');
    $sort = $sort ?? request('sort', 'latest');
@endphp

<style>
    [x-cloak] { display: none !important; }
    .visitor-dialog {
        width: min(960px, calc(100% - 24px));
        max-height: calc(100dvh - 32px);
        margin: auto;
        padding: 0;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: white;
        color: #1f2937;
        overflow: auto;
    }
    .visitor-dialog::backdrop { background: rgba(15, 23, 42, .72); }
    .visitor-photo-dialog {
        width: min(900px, calc(100% - 24px));
        max-height: calc(100dvh - 32px);
        margin: auto;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: white;
        color: #1f2937;
    }
    .visitor-photo-dialog::backdrop { background: rgba(15, 23, 42, .88); }
    .visitor-photo-dialog img { max-height: calc(100dvh - 120px); width: 100%; object-fit: contain; }
    .dark .visitor-dialog, .dark .visitor-photo-dialog {
        background: #1d2939; color: #f9fafb; border-color: #344054;
    }
</style>

<div class="mx-auto max-w-7xl px-4 py-6" x-data="visitorDirectory()">
    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Daftar Pengunjung</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data pengunjung yang telah terdaftar di PAG Library.</p>
        </div>

        {{-- Pencarian dan filter --}}
        <form action="{{ route('visitors') }}" method="GET" class="flex w-full flex-col gap-2 sm:flex-row sm:flex-wrap lg:w-auto lg:justify-end">
            <div class="relative min-w-0 flex-1 sm:min-w-[220px]">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                </svg>
                <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, nomor, kategori..." aria-label="Cari pengunjung" class="h-11 w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <select name="sort" aria-label="Urutkan pengunjung" onchange="this.form.requestSubmit()" class="h-11 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 outline-none focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                <option value="latest" @selected($sort === 'latest')>Terbaru</option>
                <option value="oldest" @selected($sort === 'oldest')>Terlama</option>
                <option value="all" @selected($sort === 'all')>Semua</option>
            </select>
            <button type="submit" class="h-11 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white transition hover:bg-brand-600">Cari</button>
            @if ($search !== '' || $sort !== 'latest')
                <a href="{{ route('visitors') }}" class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-300 px-4 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">Reset</a>
            @endif
        </form>
    </div>

    {{-- Notifikasi --}}
    @foreach (['success' => 'green', 'error' => 'red'] as $type => $color)
        @if (session($type))
            <div class="mb-5 rounded-lg border px-4 py-3 text-sm {{ $type === 'success' ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-400' : 'border-red-200 bg-red-50 text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-400' }}" role="alert">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-200 px-5 py-4 dark:border-gray-800">
            <div>
                <h2 class="text-sm font-semibold text-gray-800 dark:text-white/90">Data Pengunjung</h2>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $visitors->total() }} pengunjung ditemukan.</p>
            </div>
            <span class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                {{ match ($sort) { 'oldest' => 'Pendaftaran terlama', 'all' => 'Semua · A–Z', default => 'Pendaftaran terbaru' } }}
            </span>
        </div>

        {{-- Tabel desktop --}}
        <div class="hidden overflow-x-auto lg:block">
            <table class="w-full min-w-[1050px]">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-white/[0.02]">
                    <tr>
                        @foreach (['No', 'Foto Profil', 'Nama Pengunjung', 'Kategori', 'Nomor Identitas', 'Waktu Pendaftaran', 'Kunjungan', 'Aksi'] as $heading)
                            <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 {{ $heading === 'Aksi' ? 'text-center' : '' }}">{{ $heading }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($visitors as $visitor)
                        @php
                            $category = strtolower(trim($visitor->visitor_category ?? 'lainnya'));
                            [$categoryLabel, $categoryClass] = match ($category) {
                                'pekerja' => ['Pekerja', 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400'],
                                'mahasiswa' => ['Mahasiswa', 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400'],
                                'tamu' => ['Tamu', 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400'],
                                default => ['Lainnya', 'bg-gray-100 text-gray-600 ring-gray-500/20 dark:bg-gray-800 dark:text-gray-300'],
                            };
                            $identityLabel = match ($category) {
                                'pekerja' => 'No. Pekerja', 'mahasiswa' => 'NIM / NPM', 'tamu' => 'No KTP', default => 'No. Identitas',
                            };
                            $number = (string) ($visitor->employee_number ?? '');
                            $displayNumber = $category === 'tamu' && mb_strlen($number) > 4
                                ? str_repeat('•', mb_strlen($number) - 4) . mb_substr($number, -4)
                                : ($number !== '' ? $number : '-');
                            $initial = mb_strtoupper(mb_substr(trim($visitor->visitor_name), 0, 1));
                            $canDelete = (int) $visitor->checkins_count === 0 && (int) $visitor->loans_count === 0;
                        @endphp
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $visitors->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3">
                                <button type="button" @click="openDetails(@js(route('visitors.details', $visitor->visitor_id)))" class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-full border border-gray-200 bg-gray-100 text-sm font-semibold text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" title="Lihat detail {{ $visitor->visitor_name }}">
                                    @if ($visitor->profile_photo)
                                        <img src="{{ route('visitors.profile-photo', $visitor->visitor_id) }}" alt="Foto profil {{ $visitor->visitor_name }}" loading="lazy" class="h-full w-full object-cover" x-on:error="$el.hidden = true; $el.nextElementSibling.hidden = false">
                                        <span hidden>{{ $initial }}</span>
                                    @else
                                        {{ $initial }}
                                    @endif
                                </button>
                            </td>
                            <td class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-white/90">{{ $visitor->visitor_name }}</td>
                            <td class="px-4 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $categoryClass }}">{{ $categoryLabel }}</span></td>
                            <td class="px-4 py-4">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $displayNumber }}</span>
                                <p class="mt-1 text-xs text-gray-400">{{ $identityLabel }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $visitor->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $visitor->checkins_count }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" @click="openDetails(@js(route('visitors.details', $visitor->visitor_id)))" class="rounded-lg border border-brand-500/20 bg-brand-500/10 px-3 py-2 text-xs font-medium text-brand-600 transition hover:bg-brand-500/20 dark:text-brand-400">Detail</button>
                                    @if ($canDelete)
                                        <form action="{{ route('visitors.destroy', $visitor->visitor_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengunjung ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-600 transition hover:bg-red-100 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-400">Hapus</button>
                                        </form>
                                    @else
                                        <button type="button" disabled title="Memiliki riwayat kunjungan atau peminjaman" class="rounded-lg border border-gray-200 px-3 py-2 text-xs text-gray-400 dark:border-gray-800">Hapus</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-6 py-14 text-center text-sm text-gray-500 dark:text-gray-400">{{ $search !== '' ? 'Pengunjung tidak ditemukan.' : 'Belum ada pengunjung yang terdaftar.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Kartu mobile --}}
        <div class="divide-y divide-gray-100 dark:divide-gray-800 lg:hidden">
            @forelse ($visitors as $visitor)
                @php
                    $category = strtolower(trim($visitor->visitor_category ?? 'lainnya'));
                    $categoryLabel = match ($category) { 'pekerja' => 'Pekerja', 'mahasiswa' => 'Mahasiswa', 'tamu' => 'Tamu', default => 'Lainnya' };
                    $identityLabel = match ($category) { 'pekerja' => 'No. Pekerja', 'mahasiswa' => 'NIM / NPM', 'tamu' => 'No KTP', default => 'No. Identitas' };
                    $number = (string) ($visitor->employee_number ?? '');
                    $displayNumber = $category === 'tamu' && mb_strlen($number) > 4 ? str_repeat('•', mb_strlen($number) - 4) . mb_substr($number, -4) : ($number !== '' ? $number : '-');
                    $initial = mb_strtoupper(mb_substr(trim($visitor->visitor_name), 0, 1));
                    $canDelete = (int) $visitor->checkins_count === 0 && (int) $visitor->loans_count === 0;
                @endphp
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        <button type="button" @click="openDetails(@js(route('visitors.details', $visitor->visitor_id)))" class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full border border-gray-200 bg-gray-100 font-semibold text-gray-500 dark:border-gray-700 dark:bg-gray-800">
                            @if ($visitor->profile_photo)
                                <img src="{{ route('visitors.profile-photo', $visitor->visitor_id) }}" alt="Foto profil {{ $visitor->visitor_name }}" loading="lazy" class="h-full w-full object-cover" x-on:error="$el.hidden = true; $el.nextElementSibling.hidden = false">
                                <span hidden>{{ $initial }}</span>
                            @else
                                {{ $initial }}
                            @endif
                        </button>
                        <div class="min-w-0 flex-1">
                            <p class="break-words text-sm font-semibold text-gray-800 dark:text-white/90">{{ $visitor->visitor_name }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $categoryLabel }} · {{ $identityLabel }}</p>
                        </div>
                        <span class="text-xs text-gray-400">#{{ $visitors->firstItem() + $loop->index }}</span>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div class="col-span-2"><p class="text-xs text-gray-400">Nomor Identitas</p><p class="mt-1 break-all font-medium text-gray-700 dark:text-gray-300">{{ $displayNumber }}</p></div>
                        <div><p class="text-xs text-gray-400">Terdaftar</p><p class="mt-1 text-xs text-gray-700 dark:text-gray-300">{{ $visitor->created_at?->format('d M Y, H:i') ?? '-' }}</p></div>
                        <div><p class="text-xs text-gray-400">Total Kunjungan</p><p class="mt-1 font-medium text-gray-700 dark:text-gray-300">{{ $visitor->checkins_count }}</p></div>
                    </div>
                    <div class="mt-4 flex justify-end gap-2 border-t border-gray-100 pt-3 dark:border-gray-800">
                        <button type="button" @click="openDetails(@js(route('visitors.details', $visitor->visitor_id)))" class="rounded-lg border border-brand-500/20 bg-brand-500/10 px-3 py-2 text-xs font-medium text-brand-600 dark:text-brand-400">Detail</button>
                        @if ($canDelete)
                            <form action="{{ route('visitors.destroy', $visitor->visitor_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengunjung ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-xs font-medium text-red-600 dark:border-red-900/50">Hapus</button>
                            </form>
                        @else
                            <button type="button" disabled title="Memiliki riwayat" class="rounded-lg border border-gray-200 px-3 py-2 text-xs text-gray-400 dark:border-gray-800">Hapus</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-5 py-12 text-center text-sm text-gray-500">{{ $search !== '' ? 'Pengunjung tidak ditemukan.' : 'Belum ada pengunjung yang terdaftar.' }}</div>
            @endforelse
        </div>

        @if ($visitors->hasPages())
            <div class="border-t border-gray-200 px-4 py-4 dark:border-gray-800">{{ $visitors->links() }}</div>
        @endif
    </div>

    {{-- Detail pengunjung --}}
    <dialog x-ref="detailsDialog" class="visitor-dialog dark:bg-gray-900 dark:text-white" @cancel.prevent="closeDetails()" @close="onDetailsClosed()" aria-labelledby="visitor-detail-title">
        <div class="sticky top-0 z-10 flex items-center justify-between gap-4 border-b border-gray-200 bg-white px-5 py-4 dark:border-gray-800 dark:bg-gray-900">
            <div>
                <h2 id="visitor-detail-title" class="text-lg font-semibold">Detail Pengunjung</h2>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Profil dan riwayat kunjungan.</p>
            </div>
            <button type="button" @click="closeDetails()" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800" aria-label="Tutup detail">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-5 sm:p-6">
            <div x-show="loading" class="py-16 text-center text-sm text-gray-500">Memuat detail pengunjung...</div>
            <div x-show="error && !detail" x-cloak class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-600" x-text="error"></div>
            <template x-if="detail">
                <div>
                    <div class="grid gap-6 sm:grid-cols-[170px_minmax(0,1fr)]">
                        <div>
                            <button type="button" @click="openPhoto(detail.profile_url, 'Foto profil ' + detail.name)" :disabled="!detail.profile_url || profileFailed" class="flex aspect-square w-full items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-100 text-4xl font-semibold text-gray-400 dark:border-gray-700 dark:bg-gray-800" title="Perbesar foto profil">
                                <template x-if="detail.profile_url && !profileFailed">
                                    <img :src="detail.profile_url" :alt="'Foto profil ' + detail.name" class="h-full w-full object-cover" x-on:error="profileFailed = true">
                                </template>
                                <template x-if="!detail.profile_url || profileFailed"><span x-text="detail.name.charAt(0).toUpperCase()"></span></template>
                            </button>
                            <p class="mt-2 text-center text-xs text-gray-400">Foto Profil</p>
                        </div>
                        <div class="min-w-0">
                            <h3 class="break-words text-xl font-semibold text-gray-800 dark:text-white/90" x-text="detail.name"></h3>
                            <p class="mt-1 text-sm capitalize text-gray-500 dark:text-gray-400" x-text="detail.category"></p>
                            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                <div><p class="text-xs text-gray-400" x-text="identityLabel(detail.category)"></p><p class="mt-1 break-all text-sm font-medium" x-text="detail.identity || '-'"></p></div>
                                <div><p class="text-xs text-gray-400">No HP</p><p class="mt-1 break-all text-sm font-medium" x-text="detail.phone || '-'"></p></div>
                                <div><p class="text-xs text-gray-400">Waktu Pendaftaran</p><p class="mt-1 text-sm font-medium" x-text="detail.registered_at"></p></div>
                                <div><p class="text-xs text-gray-400">Total Kunjungan</p><p class="mt-1 text-sm font-medium" x-text="detail.total_visits"></p></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-7 border-t border-gray-200 pt-5 dark:border-gray-800">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                            <div><h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Riwayat Selfie</h3><p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Foto yang diambil pada setiap kunjungan.</p></div>
                            <span class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs text-gray-500 dark:bg-gray-800" x-text="detail.total_visits + ' kunjungan'"></span>
                        </div>
                        <div x-show="history.length === 0 && !loading" class="rounded-lg border border-dashed border-gray-200 p-8 text-center text-sm text-gray-500 dark:border-gray-700">Belum ada riwayat selfie.</div>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <template x-for="entry in history" :key="entry.id">
                                <button type="button" @click="openPhoto(entry.photo_url, 'Selfie · ' + entry.date)" :disabled="failedPhotos[entry.id]" class="min-w-0 overflow-hidden rounded-xl border border-gray-200 text-left transition hover:border-brand-500 dark:border-gray-700">
                                    <div class="flex aspect-[4/3] items-center justify-center bg-gray-100 dark:bg-gray-800">
                                        <img x-show="!failedPhotos[entry.id]" :src="entry.photo_url" :alt="'Selfie ' + entry.date" loading="lazy" class="h-full w-full object-cover" x-on:error="failedPhotos[entry.id] = true">
                                        <span x-show="failedPhotos[entry.id]" x-cloak class="px-2 text-center text-xs text-gray-400">Foto tidak tersedia</span>
                                    </div>
                                    <div class="p-3"><p class="text-xs font-medium text-gray-700 dark:text-gray-300" x-text="entry.date"></p><p class="mt-1 text-[11px] text-gray-400">Lihat foto</p></div>
                                </button>
                            </template>
                        </div>
                        <div x-show="historyLoading" x-cloak class="py-3 text-center text-xs text-gray-400">Memuat riwayat...</div>
                        <div x-show="error && detail" x-cloak class="mt-3 rounded-lg bg-red-50 p-3 text-xs text-red-600" x-text="error"></div>
                        <div x-show="page < lastPage" x-cloak class="mt-5 text-center"><button type="button" @click="loadMore()" :disabled="historyLoading" class="rounded-lg border border-gray-300 px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">Tampilkan Riwayat Lainnya</button></div>
                    </div>
                </div>
            </template>
        </div>
    </dialog>

    {{-- Perbesar foto --}}
    <dialog x-ref="photoDialog" class="visitor-photo-dialog dark:bg-gray-900 dark:text-white" @cancel.prevent="closePhoto()" @close="photoUrl = null" aria-label="Pratinjau foto">
        <div class="mb-3 flex items-center justify-between gap-3">
            <p class="min-w-0 truncate text-sm font-medium" x-text="photoTitle"></p>
            <button type="button" @click="closePhoto()" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800" aria-label="Tutup foto">✕</button>
        </div>
        <img :src="photoUrl || ''" :alt="photoTitle" class="rounded-lg bg-gray-100 dark:bg-gray-800">
    </dialog>
</div>

<script>
function visitorDirectory() {
    return {
        loading: false,
        historyLoading: false,
        error: '',
        detail: null,
        detailUrl: '',
        history: [],
        page: 1,
        lastPage: 1,
        requestId: 0,
        profileFailed: false,
        failedPhotos: {},
        photoUrl: null,
        photoTitle: '',

        identityLabel(category) {
            return {
                pekerja: 'No. Pekerja',
                mahasiswa: 'NIM / NPM',
                tamu: 'No KTP',
                lainnya: 'No. Identitas'
            }[category] || 'No. Identitas';
        },

        openDetails(url) {
            this.requestId++;
            this.detailUrl = url;
            this.detail = null;
            this.history = [];
            this.error = '';
            this.page = 1;
            this.lastPage = 1;
            this.profileFailed = false;
            this.failedPhotos = {};
            if (!this.$refs.detailsDialog.open) this.$refs.detailsDialog.showModal();
            this.loadDetails(1);
        },

        async loadDetails(page = 1, append = false) {
            const token = ++this.requestId;
            this.error = '';
            this.loading = !append;
            this.historyLoading = append;

            try {
                const response = await fetch(this.detailUrl + '?page=' + page, {
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                });
                if (!response.ok) throw new Error('Detail pengunjung tidak dapat dimuat. Silakan coba kembali.');
                const data = await response.json();
                if (token !== this.requestId) return;

                this.detail = data.visitor;
                this.history = append ? this.history.concat(data.history) : data.history;
                this.page = data.page;
                this.lastPage = data.last_page;
            } catch (error) {
                if (token === this.requestId) this.error = error.message || 'Terjadi kesalahan saat memuat data.';
            } finally {
                if (token === this.requestId) {
                    this.loading = false;
                    this.historyLoading = false;
                }
            }
        },

        loadMore() {
            if (!this.historyLoading && this.page < this.lastPage) {
                this.loadDetails(this.page + 1, true);
            }
        },

        closeDetails() {
            this.closePhoto();
            if (this.$refs.detailsDialog.open) this.$refs.detailsDialog.close();
        },

        onDetailsClosed() {
            this.requestId++;
            this.detail = null;
            this.history = [];
            this.loading = false;
            this.historyLoading = false;
            this.error = '';
        },

        openPhoto(url, title) {
            if (!url) return;
            this.photoUrl = url;
            this.photoTitle = title;
            this.$nextTick(() => {
                if (!this.$refs.photoDialog.open) this.$refs.photoDialog.showModal();
            });
        },

        closePhoto() {
            if (this.$refs.photoDialog.open) this.$refs.photoDialog.close();
        }
    };
}
</script>
@endsection