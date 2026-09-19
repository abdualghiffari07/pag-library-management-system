{{-- Detail Pengunjung --}}
<dialog
    x-ref="detailsDialog"
    class="visitor-dialog dark:bg-gray-900 dark:text-white"
    x-on:cancel.prevent="closeDetails()"
    x-on:close="onDetailsClosed()"
    aria-labelledby="visitor-detail-title"
>
    {{-- Header --}}
    <div class="sticky top-0 z-20 flex items-center justify-between gap-4 border-b border-gray-200 bg-white px-5 py-4 dark:border-gray-800 dark:bg-gray-900 sm:px-6">
        <div class="min-w-0">
            <h2
                id="visitor-detail-title"
                class="text-lg font-semibold text-gray-800 dark:text-white/90"
            >
                Detail Pengunjung
            </h2>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Profil, kunjungan, dan riwayat peminjaman buku.
            </p>
        </div>

        <button
            type="button"
            x-on:click="closeDetails()"
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-gray-200"
            aria-label="Tutup detail"
        >
            <svg
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-width="2"
                    stroke-linecap="round"
                    d="M18 6L6 18M6 6l12 12"
                />
            </svg>
        </button>
    </div>

    <div class="max-h-[calc(100dvh-100px)] overflow-y-auto">
        {{-- Loading --}}
        <div
            x-show="loading"
            class="flex min-h-[420px] items-center justify-center"
        >
            <div class="text-center">
                <svg
                    class="mx-auto h-7 w-7 animate-spin text-brand-500"
                    viewBox="0 0 24 24"
                    fill="none"
                >
                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                        stroke="currentColor"
                        stroke-width="2"
                        opacity=".2"
                    />

                    <path
                        d="M21 12a9 9 0 00-9-9"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                    />
                </svg>

                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                    Memuat detail pengunjung...
                </p>
            </div>
        </div>

        {{-- Error --}}
        <div
            x-show="error && !detail && !loading"
            x-cloak
            class="m-6 rounded-xl border border-error-200 bg-error-50 p-4 text-sm text-error-600 dark:border-error-500/20 dark:bg-error-500/10 dark:text-error-400"
            x-text="error"
        ></div>

        <template x-if="detail">
            <div class="p-5 sm:p-6">

                {{-- Profil --}}
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.02]">
                    <div class="grid gap-5 p-5 sm:p-6 md:grid-cols-[160px_minmax(0,1fr)]">

                        {{-- Foto --}}
                        <div>
                            <button
                                type="button"
                                x-on:click="
                                    openPhoto(
                                        detail.profile_url,
                                        'Foto profil ' + detail.name
                                    )
                                "
                                :disabled="!detail.profile_url || profileFailed"
                                class="mx-auto flex aspect-square w-full max-w-[160px] items-center justify-center overflow-hidden rounded-2xl border border-gray-200 bg-gray-100 text-4xl font-bold text-gray-400 transition hover:border-brand-400 disabled:cursor-default dark:border-gray-700 dark:bg-gray-800"
                                title="Lihat foto profil"
                            >
                                <template x-if="detail.profile_url && !profileFailed">
                                    <img
                                        :src="detail.profile_url"
                                        :alt="'Foto profil ' + detail.name"
                                        class="h-full w-full object-cover"
                                        x-on:error="profileFailed = true"
                                    >
                                </template>

                                <template x-if="!detail.profile_url || profileFailed">
                                    <span
                                        x-text="
                                            detail.name
                                                ? detail.name.charAt(0).toUpperCase()
                                                : '?'
                                        "
                                    ></span>
                                </template>
                            </button>

                            <p class="mt-2 text-center text-xs text-gray-400">
                                Foto Profil
                            </p>
                        </div>

                        {{-- Informasi --}}
                        <div class="min-w-0">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <h3
                                        class="break-words text-xl font-semibold text-gray-800 dark:text-white/90"
                                        x-text="detail.name"
                                    ></h3>

                                    <p class="mt-1 text-xs text-gray-400">
                                        ID Pengunjung

                                        <span
                                            class="font-medium text-gray-600 dark:text-gray-300"
                                            x-text="'#' + detail.id"
                                        ></span>
                                    </p>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">

                                    {{-- Kategori --}}
                                    <span
                                        class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-medium"
                                        :class="categoryClass(detail.category)"
                                        x-text="categoryLabel(detail.category)"
                                    ></span>

                                    {{-- Status --}}
                                    <span
                                        class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-medium"
                                        :class="
                                            detail.is_active
                                                ? 'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400'
                                                : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400'
                                        "
                                        x-text="
                                            detail.is_active
                                                ? 'Aktif'
                                                : 'Nonaktif'
                                        "
                                    ></span>
                                </div>
                            </div>

                            {{-- Biodata --}}
                            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                {{-- Identitas --}}
                                <div class="rounded-xl bg-gray-50 p-3 dark:bg-white/[0.03]">
                                    <p
                                        class="text-[11px] text-gray-400"
                                        x-text="identityLabel(detail.category)"
                                    ></p>

                                    <p
                                        class="mt-1 break-all text-sm font-medium text-gray-700 dark:text-gray-300"
                                        x-text="detail.identity || '-'"
                                    ></p>
                                </div>

                                {{-- No HP --}}
                                <div class="rounded-xl bg-gray-50 p-3 dark:bg-white/[0.03]">
                                    <p class="text-[11px] text-gray-400">
                                        No HP
                                    </p>

                                    <p
                                        class="mt-1 break-all text-sm font-medium text-gray-700 dark:text-gray-300"
                                        x-text="detail.phone || '-'"
                                    ></p>
                                </div>

                                {{-- Email --}}
                                <div class="rounded-xl bg-gray-50 p-3 dark:bg-white/[0.03]">
                                    <p class="text-[11px] text-gray-400">
                                        Email
                                    </p>

                                    <p
                                        class="mt-1 break-all text-sm font-medium text-gray-700 dark:text-gray-300"
                                        x-text="detail.email || '-'"
                                    ></p>
                                </div>

                                {{-- Waktu Pendaftaran --}}
                                <div class="rounded-xl bg-gray-50 p-3 dark:bg-white/[0.03]">
                                    <p class="text-[11px] text-gray-400">
                                        Waktu Pendaftaran
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300"
                                        x-text="detail.registered_at || '-'"
                                    ></p>
                                </div>

                                {{-- Kunjungan Terakhir --}}
                                <div class="rounded-xl bg-gray-50 p-3 dark:bg-white/[0.03] sm:col-span-2">
                                    <p class="text-[11px] text-gray-400">
                                        Kunjungan Terakhir
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300"
                                        x-text="detail.last_visit || '-'"
                                    ></p>
                                </div>
                            </div>

                            {{-- Status Pengunjung --}}
                            <div class="mt-5 border-t border-gray-100 pt-5 dark:border-gray-800">
                                <div class="flex flex-col gap-4 rounded-xl bg-gray-50 p-4 dark:bg-white/[0.03] sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="h-2.5 w-2.5 rounded-full"
                                                :class="
                                                    detail.is_active
                                                        ? 'bg-success-500'
                                                        : 'bg-gray-400'
                                                "
                                            ></span>

                                            <p class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                                Status Pengunjung
                                            </p>
                                        </div>

                                        <p
                                            class="mt-1.5 text-xs leading-5 text-gray-500 dark:text-gray-400"
                                            x-text="
                                                detail.is_active
                                                    ? 'Pengunjung aktif dan dapat melakukan check-in ke PAG Library.'
                                                    : 'Pengunjung dinonaktifkan dan tidak dapat melakukan check-in.'
                                            "
                                        ></p>
                                    </div>

                                    <div class="shrink-0">
                                        <button
                                            type="button"
                                            x-on:click="toggleVisitorStatus()"
                                            :disabled="statusLoading"
                                            class="inline-flex min-w-[170px] items-center justify-center rounded-lg px-4 py-2.5 text-xs font-medium transition disabled:cursor-not-allowed disabled:opacity-50"
                                            :class="
                                                detail.is_active
                                                    ? 'border border-warning-200 bg-warning-50 text-warning-600 hover:bg-warning-100 dark:border-warning-900/50 dark:bg-warning-900/20 dark:text-warning-400'
                                                    : 'border border-success-200 bg-success-50 text-success-600 hover:bg-success-100 dark:border-success-900/50 dark:bg-success-900/20 dark:text-success-400'
                                            "
                                        >
                                            <span
                                                x-show="!statusLoading"
                                                x-text="
                                                    detail.is_active
                                                        ? 'Nonaktifkan Pengunjung'
                                                        : 'Aktifkan Pengunjung'
                                                "
                                            ></span>

                                            <span
                                                x-show="statusLoading"
                                                x-cloak
                                            >
                                                Memproses...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Statistik --}}
                <div class="mt-5 grid grid-cols-2 gap-3 lg:grid-cols-4">

                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Total Kunjungan
                        </p>

                        <p
                            class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90"
                            x-text="detail.total_visits ?? 0"
                        ></p>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Total Peminjaman
                        </p>

                        <p
                            class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90"
                            x-text="detail.total_loans ?? 0"
                        ></p>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Sedang Dipinjam
                        </p>

                        <p
                            class="mt-3 text-2xl font-semibold"
                            :class="
                                Number(detail.active_loans) > 0
                                    ? 'text-warning-600 dark:text-warning-400'
                                    : 'text-success-600 dark:text-success-400'
                            "
                            x-text="detail.active_loans ?? 0"
                        ></p>
                    </div>

                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Peminjaman Terakhir
                        </p>

                        <p
                            class="mt-3 text-xs font-medium leading-5 text-gray-700 dark:text-gray-300"
                            x-text="detail.last_loan || '-'"
                        ></p>
                    </div>
                </div>

                {{-- Riwayat --}}
                <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800">

                    {{-- Tabs --}}
                    <div class="flex overflow-x-auto border-b border-gray-200 bg-gray-50 px-4 pt-2 dark:border-gray-800 dark:bg-white/[0.02]">

                        <button
                            type="button"
                            x-on:click="activeHistoryTab = 'visits'"
                            class="relative shrink-0 px-4 py-3 text-sm font-medium transition"
                            :class="
                                activeHistoryTab === 'visits'
                                    ? 'text-brand-500'
                                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'
                            "
                        >
                            Riwayat Kunjungan

                            <span
                                x-show="activeHistoryTab === 'visits'"
                                class="absolute inset-x-0 bottom-0 h-0.5 bg-brand-500"
                            ></span>
                        </button>

                        <button
                            type="button"
                            x-on:click="activeHistoryTab = 'loans'"
                            class="relative shrink-0 px-4 py-3 text-sm font-medium transition"
                            :class="
                                activeHistoryTab === 'loans'
                                    ? 'text-brand-500'
                                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'
                            "
                        >
                            Riwayat Peminjaman

                            <span
                                x-show="activeHistoryTab === 'loans'"
                                class="absolute inset-x-0 bottom-0 h-0.5 bg-brand-500"
                            ></span>
                        </button>
                    </div>

                    {{-- Riwayat Kunjungan --}}
                    <div
                        x-show="activeHistoryTab === 'visits'"
                        class="p-4 sm:p-5"
                    >
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                    Aktivitas Kunjungan
                                </h3>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Riwayat check-in dan selfie pengunjung.
                                </p>
                            </div>

                            <span
                                class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-300"
                                x-text="visitTotal + ' kunjungan'"
                            ></span>
                        </div>

                        {{-- Empty --}}
                        <div
                            x-show="visits.length === 0 && !visitLoading"
                            x-cloak
                            class="rounded-xl border border-dashed border-gray-200 px-4 py-10 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400"
                        >
                            Belum ada riwayat kunjungan.
                        </div>

                        {{-- List --}}
                        <div class="space-y-3">
                            <template
                                x-for="entry in visits"
                                :key="entry.id"
                            >
                                <div class="flex gap-4 rounded-xl border border-gray-100 p-3 transition hover:border-gray-200 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-white/[0.02]">

                                    {{-- Selfie --}}
                                    <button
                                        type="button"
                                        x-on:click="
                                            openPhoto(
                                                entry.photo_url,
                                                'Selfie · ' + entry.datetime
                                            )
                                        "
                                        class="h-20 w-24 shrink-0 overflow-hidden rounded-xl border border-gray-200 bg-gray-100 transition hover:border-brand-400 dark:border-gray-700 dark:bg-gray-800"
                                        title="Lihat selfie"
                                    >
                                        <img
                                            :src="entry.photo_url"
                                            :alt="'Selfie ' + entry.datetime"
                                            loading="lazy"
                                            class="h-full w-full object-cover"
                                        >
                                    </button>

                                    {{-- Informasi --}}
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                    Check-in Perpustakaan
                                                </p>

                                                <p
                                                    class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                                    x-text="entry.date"
                                                ></p>
                                            </div>

                                            <span
                                                class="shrink-0 rounded-full bg-success-50 px-2.5 py-1 text-[10px] font-medium text-success-600 dark:bg-success-500/10 dark:text-success-400"
                                                x-text="entry.time"
                                            ></span>
                                        </div>

                                        <p
                                            class="mt-2 text-[11px] text-gray-400"
                                            x-text="'Check-in #' + entry.id"
                                        ></p>

                                        <button
                                            type="button"
                                            x-on:click="
                                                openPhoto(
                                                    entry.photo_url,
                                                    'Selfie · ' + entry.datetime
                                                )
                                            "
                                            class="mt-2 text-xs font-medium text-brand-500 transition hover:text-brand-600"
                                        >
                                            Lihat selfie
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Loading --}}
                        <div
                            x-show="visitLoading"
                            x-cloak
                            class="py-4 text-center text-xs text-gray-400"
                        >
                            Memuat riwayat kunjungan...
                        </div>

                        {{-- More --}}
                        <div
                            x-show="visitPage < visitLastPage"
                            x-cloak
                            class="mt-4 text-center"
                        >
                            <button
                                type="button"
                                x-on:click="loadMoreVisits()"
                                :disabled="visitLoading"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-xs font-medium text-gray-600 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                            >
                                <span x-show="!visitLoading">
                                    Tampilkan Kunjungan Lainnya
                                </span>

                                <span
                                    x-show="visitLoading"
                                    x-cloak
                                >
                                    Memuat...
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- Riwayat Peminjaman --}}
                    <div
                        x-show="activeHistoryTab === 'loans'"
                        x-cloak
                        class="p-4 sm:p-5"
                    >
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                    Riwayat Peminjaman Buku
                                </h3>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Buku yang pernah atau sedang dipinjam.
                                </p>
                            </div>

                            <span
                                class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-300"
                                x-text="loanTotal + ' peminjaman'"
                            ></span>
                        </div>

                        {{-- Empty --}}
                        <div
                            x-show="loans.length === 0 && !loanLoading"
                            x-cloak
                            class="rounded-xl border border-dashed border-gray-200 px-4 py-10 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400"
                        >
                            Belum ada riwayat peminjaman.
                        </div>

                        {{-- List --}}
                        <div class="space-y-3">
                            <template
                                x-for="loan in loans"
                                :key="loan.id"
                            >
                                <div class="rounded-xl border border-gray-100 p-4 transition hover:border-gray-200 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-white/[0.02]">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="min-w-0">
                                            <p
                                                class="break-words text-sm font-semibold text-gray-800 dark:text-white/90"
                                                x-text="loan.title || '-'"
                                            ></p>

                                            <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-gray-500 dark:text-gray-400">
                                                <span>
                                                    ID Buku:

                                                    <strong
                                                        class="font-medium text-gray-700 dark:text-gray-300"
                                                        x-text="loan.book_id || '-'"
                                                    ></strong>
                                                </span>

                                                <span>
                                                    Eksemplar:

                                                    <strong
                                                        class="font-medium text-gray-700 dark:text-gray-300"
                                                        x-text="loan.copy_id || '-'"
                                                    ></strong>
                                                </span>
                                            </div>
                                        </div>

                                        <span
                                            class="inline-flex w-fit shrink-0 rounded-full px-2.5 py-1 text-[10px] font-medium"
                                            :class="loanStatusClass(loan.status_key)"
                                            x-text="loan.status || '-'"
                                        ></span>
                                    </div>

                                    <div class="mt-4 grid grid-cols-1 gap-3 border-t border-gray-100 pt-3 sm:grid-cols-3 dark:border-gray-800">
                                        <div>
                                            <p class="text-[10px] uppercase tracking-wide text-gray-400">
                                                Tanggal Pinjam
                                            </p>

                                            <p
                                                class="mt-1 text-xs font-medium text-gray-700 dark:text-gray-300"
                                                x-text="loan.loan_date || '-'"
                                            ></p>
                                        </div>

                                        <div>
                                            <p class="text-[10px] uppercase tracking-wide text-gray-400">
                                                Batas Kembali
                                            </p>

                                            <p
                                                class="mt-1 text-xs font-medium text-gray-700 dark:text-gray-300"
                                                x-text="loan.due_date || '-'"
                                            ></p>
                                        </div>

                                        <div>
                                            <p class="text-[10px] uppercase tracking-wide text-gray-400">
                                                Dikembalikan
                                            </p>

                                            <p
                                                class="mt-1 text-xs font-medium text-gray-700 dark:text-gray-300"
                                                x-text="loan.returned_date || '-'"
                                            ></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Loading --}}
                        <div
                            x-show="loanLoading"
                            x-cloak
                            class="py-4 text-center text-xs text-gray-400"
                        >
                            Memuat riwayat peminjaman...
                        </div>

                        {{-- More --}}
                        <div
                            x-show="loanPage < loanLastPage"
                            x-cloak
                            class="mt-4 text-center"
                        >
                            <button
                                type="button"
                                x-on:click="loadMoreLoans()"
                                :disabled="loanLoading"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-xs font-medium text-gray-600 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                            >
                                <span x-show="!loanLoading">
                                    Tampilkan Peminjaman Lainnya
                                </span>

                                <span
                                    x-show="loanLoading"
                                    x-cloak
                                >
                                    Memuat...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Error --}}
                <div
                    x-show="error && detail"
                    x-cloak
                    class="mt-4 rounded-xl border border-error-200 bg-error-50 p-3 text-xs text-error-600 dark:border-error-500/20 dark:bg-error-500/10 dark:text-error-400"
                    x-text="error"
                ></div>
            </div>
        </template>
    </div>
</dialog>

{{-- Preview Foto --}}
<dialog
    x-ref="photoDialog"
    class="visitor-photo-dialog dark:bg-gray-900 dark:text-white"
    x-on:cancel.prevent="closePhoto()"
    x-on:close="photoUrl = null"
    aria-label="Pratinjau foto"
>
    <div class="mb-3 flex items-center justify-between gap-3">
        <p
            class="min-w-0 truncate text-sm font-medium text-gray-800 dark:text-white/90"
            x-text="photoTitle"
        ></p>

        <button
            type="button"
            x-on:click="closePhoto()"
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 dark:hover:bg-gray-800"
            aria-label="Tutup foto"
        >
            <svg
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-width="2"
                    stroke-linecap="round"
                    d="M18 6L6 18M6 6l12 12"
                />
            </svg>
        </button>
    </div>

    <div class="overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800">
        <img
            :src="photoUrl || ''"
            :alt="photoTitle || 'Pratinjau foto'"
            class="w-full object-contain"
        >
    </div>
</dialog>