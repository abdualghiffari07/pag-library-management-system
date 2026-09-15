{{-- Detail Peminjam --}}

<div
    x-show="detailOpen"
    x-cloak
    x-transition.opacity
    @click.self="closeDetail()"
    class="fixed inset-0 z-[99999] flex items-center justify-center bg-gray-900/50 p-4 backdrop-blur-[2px]"
>
    <div
        x-show="detailOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="w-full max-w-2xl overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-900"
    >

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">

            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Detail Peminjam
                </h3>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Informasi peminjam dan transaksi buku.
                </p>
            </div>

            <button
                type="button"
                @click="closeDetail()"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                aria-label="Tutup"
            >
                <svg
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                >
                    <path
                        d="M6 6L18 18M18 6L6 18"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />
                </svg>
            </button>

        </div>

        {{-- Body --}}
        <div class="max-h-[75vh] overflow-y-auto px-5 py-5 sm:px-6">

            {{-- Peminjam --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-white/[0.02]">

                <div class="mb-4 flex items-center justify-between gap-4">

                    <div class="min-w-0">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-gray-400">
                            Nama Peminjam
                        </span>

                        <h4
                            class="mt-1 break-words text-base font-semibold text-gray-800 dark:text-white/90"
                            x-text="detail.name"
                        ></h4>
                    </div>

                    <span
                        class="inline-flex shrink-0 rounded-full px-2.5 py-1 text-[10px] font-medium"
                        :class="
                            detail.status === 'Dikembalikan'
                                ? 'bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400'
                                : 'bg-warning-50 text-warning-600 dark:bg-warning-500/10 dark:text-warning-400'
                        "
                        x-text="detail.status"
                    ></span>

                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <span class="text-[10px] uppercase tracking-wide text-gray-400">
                            No Identitas
                        </span>

                        <p
                            class="mt-1 break-words text-sm font-medium text-gray-700 dark:text-gray-300"
                            x-text="detail.identity"
                        ></p>
                    </div>

                    <div>
                        <span class="text-[10px] uppercase tracking-wide text-gray-400">
                            Kategori
                        </span>

                        <p
                            class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300"
                            x-text="detail.category"
                        ></p>
                    </div>

                </div>

            </div>

            {{-- Buku --}}
            <div class="mt-5">

                <h4 class="mb-3 text-sm font-semibold text-gray-800 dark:text-white/90">
                    Informasi Buku
                </h4>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                    <div class="rounded-lg border border-gray-100 p-3 dark:border-gray-800">
                        <span class="text-[10px] uppercase tracking-wide text-gray-400">
                            ID Buku
                        </span>

                        <p
                            class="mt-1 break-words text-sm font-medium text-gray-700 dark:text-gray-300"
                            x-text="detail.bookId"
                        ></p>
                    </div>

                    <div class="rounded-lg border border-gray-100 p-3 dark:border-gray-800">
                        <span class="text-[10px] uppercase tracking-wide text-gray-400">
                            ID Eksemplar
                        </span>

                        <p
                            class="mt-1 break-words text-sm font-medium text-gray-700 dark:text-gray-300"
                            x-text="detail.copyId"
                        ></p>
                    </div>

                    <div class="rounded-lg border border-gray-100 p-3 dark:border-gray-800 sm:col-span-2">
                        <span class="text-[10px] uppercase tracking-wide text-gray-400">
                            Judul Buku
                        </span>

                        <p
                            class="mt-1 break-words text-sm font-medium text-gray-700 dark:text-gray-300"
                            x-text="detail.title"
                        ></p>
                    </div>

                </div>

            </div>

            {{-- Peminjaman --}}
            <div class="mt-5">

                <h4 class="mb-3 text-sm font-semibold text-gray-800 dark:text-white/90">
                    Informasi Peminjaman
                </h4>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                    <div class="rounded-lg border border-gray-100 p-3 dark:border-gray-800">
                        <span class="text-[10px] uppercase tracking-wide text-gray-400">
                            Tanggal Pinjam
                        </span>

                        <p
                            class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300"
                            x-text="detail.loanDate"
                        ></p>
                    </div>

                    <div class="rounded-lg border border-gray-100 p-3 dark:border-gray-800">
                        <span class="text-[10px] uppercase tracking-wide text-gray-400">
                            Tanggal Kembali
                        </span>

                        <p
                            class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300"
                            x-text="detail.returnedDate"
                        ></p>
                    </div>

                </div>

            </div>

        </div>

        {{-- Footer --}}
        <div class="flex justify-end border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">

            <button
                type="button"
                @click="closeDetail()"
                class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-xs font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Tutup
            </button>

        </div>

    </div>
</div>