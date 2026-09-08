@props([
    'borrowers',
    'search' => '',
])

<div
    class="space-y-6"
    x-data="borrowersTable()"
>
    <x-common.component-card title="Daftar Peminjam">

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

        <div class="mb-5">

            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Data Peminjam
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Data peminjaman buku PAG Library.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                {{-- Search --}}
                <form
                    action="{{ route('borrowers.index') }}"
                    method="GET"
                    class="relative w-full sm:w-[320px]"
                >
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                        <svg
                            width="18"
                            height="18"
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
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        autocomplete="off"
                        placeholder="Cari peminjam..."
                        class="h-10 w-full rounded-lg border border-gray-300 bg-transparent py-2 pl-10 pr-4 text-xs text-gray-800 shadow-theme-xs outline-none placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-400"
                    >
                </form>

                {{-- Action --}}
                <div class="flex flex-wrap items-center justify-end gap-2">

                    {{-- Kembalikan Terpilih --}}
                    <form
                        x-show="selectionMode && selected.length > 0"
                        x-cloak
                        action="{{ route('borrowers.bulk-return') }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin mengembalikan semua buku yang dipilih?')"
                    >
                        @csrf

                        <template
                            x-for="id in selected"
                            :key="id"
                        >
                            <input
                                type="hidden"
                                name="ids[]"
                                :value="id"
                            >
                        </template>

                        <button
                            type="submit"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-success-500 px-3 text-xs font-medium text-white shadow-theme-xs transition hover:bg-success-600"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M5 12L10 17L19 8"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>

                            <span>
                                Kembalikan Terpilih
                            </span>

                            <span
                                class="rounded bg-white/20 px-1.5 py-0.5 text-[10px]"
                                x-text="selected.length"
                            ></span>
                        </button>
                    </form>

                    {{-- Hapus Terpilih --}}
                    <form
                        x-show="selectionMode && selected.length > 0"
                        x-cloak
                        action="{{ route('borrowers.bulk-destroy') }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus semua data peminjaman yang dipilih?')"
                    >
                        @csrf
                        @method('DELETE')

                        <template
                            x-for="id in selected"
                            :key="id"
                        >
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
                                width="15"
                                height="15"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M3 6H5H21"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />

                                <path
                                    d="M8 6V4H16V6"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                                <path
                                    d="M19 6L18 20H6L5 6"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>

                            <span>
                                Hapus Terpilih
                            </span>

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
                        :class="{
                            'border-brand-500 text-brand-500 dark:border-brand-500 dark:text-brand-400':
                                selectionMode
                        }"
                    >
                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M9 11L11 13L15 9"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M5 4H19C19.5523 4 20 4.44772 20 5V19C20 19.5523 19.5523 20 19 20H5C4.44772 20 4 19.5523 4 19V5C4 4.44772 4.44772 4 5 4Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />
                        </svg>

                        <span
                            x-text="selectionMode ? 'Selesai' : 'Pilih'"
                        ></span>
                    </button>

                </div>

            </div>

            {{-- Info Pilihan --}}
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

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="max-w-full overflow-x-auto">

                <table
                    class="table-fixed border-collapse"
                    :style="`width: ${getTableWidth()}px`"
                >

                    <colgroup>

                        <col
                            :style="`
                                width: ${
                                    selectionMode
                                        ? columnWidths.select
                                        : 0
                                }px
                            `"
                        >

                        <col :style="`width: ${columnWidths.no}px`">
                        <col :style="`width: ${columnWidths.borrower}px`">
                        <col :style="`width: ${columnWidths.employeeNumber}px`">
                        <col :style="`width: ${columnWidths.status}px`">
                        <col :style="`width: ${columnWidths.category}px`">
                        <col :style="`width: ${columnWidths.bookId}px`">
                        <col :style="`width: ${columnWidths.title}px`">
                        <col :style="`width: ${columnWidths.copyId}px`">
                        <col :style="`width: ${columnWidths.loanDate}px`">
                        <col :style="`width: ${columnWidths.returnedDate}px`">
                        <col :style="`width: ${columnWidths.action}px`">

                    </colgroup>

                    <thead class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-white/[0.02]">

                        <tr>

                            {{-- Pilih Semua --}}
                            <th
                                class="overflow-hidden py-2.5 text-center"
                                :class="selectionMode ? 'px-3' : 'px-0'"
                            >
                                <div
                                    x-show="selectionMode"
                                    x-cloak
                                    class="flex items-center justify-center"
                                >
                                    <label class="relative inline-flex h-4 w-4 cursor-pointer items-center justify-center">

                                        <input
                                            type="checkbox"
                                            :checked="allVisibleSelected()"
                                            @change="toggleSelectAll()"
                                            x-effect="
                                                $el.indeterminate =
                                                    someVisibleSelected()
                                            "
                                            class="peer absolute inset-0 h-4 w-4 cursor-pointer appearance-none rounded-[4px] border border-gray-300 bg-white transition checked:border-brand-500 checked:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-900 dark:checked:border-brand-500 dark:checked:bg-brand-500"
                                            title="Pilih semua data pada halaman ini"
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
                                </div>
                            </th>

                            {{-- No --}}
                            <th class="relative px-3 py-2.5 text-left text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    No
                                </span>

                                <span
                                    @mousedown="startResize($event, 'no')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                            {{-- Nama Peminjam --}}
                            <th class="relative px-3 py-2.5 text-left text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    Nama Peminjam
                                </span>

                                <span
                                    @mousedown="startResize($event, 'borrower')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                            {{-- No Identitas --}}
                            <th class="relative px-3 py-2.5 text-left text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    No Identitas
                                </span>

                                <span
                                    @mousedown="startResize($event, 'employeeNumber')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                            {{-- Status --}}
                            <th class="relative px-2 py-2.5 text-center text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    Status
                                </span>

                                <span
                                    @mousedown="startResize($event, 'status')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                            {{-- Kategori --}}
                            <th class="relative px-3 py-2.5 text-left text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    Kategori
                                </span>

                                <span
                                    @mousedown="startResize($event, 'category')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                            {{-- ID Buku --}}
                            <th class="relative px-3 py-2.5 text-left text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    ID Buku
                                </span>

                                <span
                                    @mousedown="startResize($event, 'bookId')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                            {{-- Judul Buku --}}
                            <th class="relative px-3 py-2.5 text-left text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    Judul Buku
                                </span>

                                <span
                                    @mousedown="startResize($event, 'title')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                            {{-- ID Eksemplar --}}
                            <th class="relative px-3 py-2.5 text-left text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    ID Eksemplar
                                </span>

                                <span
                                    @mousedown="startResize($event, 'copyId')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                            {{-- Tanggal Pinjam --}}
                            <th class="relative px-3 py-2.5 text-left text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    Tanggal Pinjam
                                </span>

                                <span
                                    @mousedown="startResize($event, 'loanDate')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                            {{-- Tanggal Kembali --}}
                            <th class="relative px-3 py-2.5 text-left text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    Tanggal Kembali
                                </span>

                                <span
                                    @mousedown="startResize($event, 'returnedDate')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                            {{-- Action --}}
                            <th class="relative px-2 py-2.5 text-center text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400">

                                <span class="block truncate">
                                    Action
                                </span>

                                <span
                                    @mousedown="startResize($event, 'action')"
                                    class="absolute right-0 top-0 h-full w-1 cursor-col-resize select-none hover:bg-brand-500"
                                ></span>

                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                        @forelse ($borrowers as $index => $borrower)

                            @php
                                $isReturned = !empty(
                                    $borrower->returned_date
                                );

                                $borrowerName =
                                    $borrower->visitor_name
                                    ?: $borrower->borrower_name
                                    ?: '-';

                                $identityNumber =
                                    $borrower->employee_number
                                    ?: $borrower->nopek
                                    ?: '-';

                                $loanDate =
                                    $borrower->loan_date
                                        ? \Illuminate\Support\Carbon::parse(
                                            $borrower->loan_date
                                        )->format('d/m/Y')
                                        : '-';

                                $returnedDate =
                                    $borrower->returned_date
                                        ? \Illuminate\Support\Carbon::parse(
                                            $borrower->returned_date
                                        )->format('d/m/Y')
                                        : '-';
                            @endphp

                            <tr
                                class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]"
                                :class="
                                    selectionMode &&
                                    isSelected('{{ $borrower->loan_detail_id }}')
                                        ? 'bg-brand-50/40 dark:bg-brand-500/[0.04]'
                                        : ''
                                "
                            >

                                {{-- Pilih --}}
                                <td
                                    class="overflow-hidden py-3 text-center"
                                    :class="selectionMode ? 'px-3' : 'px-0'"
                                >
                                    <div
                                        x-show="selectionMode"
                                        x-cloak
                                        class="flex items-center justify-center"
                                    >
                                        <label class="relative inline-flex h-4 w-4 cursor-pointer items-center justify-center">

                                            <input
                                                type="checkbox"
                                                value="{{ $borrower->loan_detail_id }}"
                                                :checked="isSelected('{{ $borrower->loan_detail_id }}')"
                                                @change="toggleSelect('{{ $borrower->loan_detail_id }}')"
                                                class="borrower-select peer absolute inset-0 h-4 w-4 cursor-pointer appearance-none rounded-[4px] border border-gray-300 bg-white transition checked:border-brand-500 checked:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-900 dark:checked:border-brand-500 dark:checked:bg-brand-500"
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
                                    </div>
                                </td>

                                {{-- No --}}
                                <td class="overflow-hidden px-3 py-3 text-[11px] text-gray-700 dark:text-gray-300">
                                    {{ $borrowers->firstItem() + $index }}
                                </td>

                                {{-- Nama Peminjam --}}
                                <td class="overflow-hidden px-3 py-3">

                                    <span
                                        class="block truncate text-[11px] font-medium text-gray-800 dark:text-white/90"
                                        title="{{ $borrowerName }}"
                                    >
                                        {{ $borrowerName }}
                                    </span>

                                </td>

                                {{-- No Identitas --}}
                                <td class="overflow-hidden px-3 py-3">

                                    <span
                                        class="block truncate text-[11px] text-gray-600 dark:text-gray-400"
                                        title="{{ $identityNumber }}"
                                    >
                                        {{ $identityNumber }}
                                    </span>

                                </td>

                                {{-- Status --}}
                                <td class="overflow-hidden px-2 py-3 text-center">

                                    @if ($isReturned)

                                        <span
                                            class="inline-flex max-w-full items-center justify-center truncate whitespace-nowrap rounded-full bg-success-50 px-2.5 py-1 text-[10px] font-medium text-success-600 dark:bg-success-500/10 dark:text-success-400"
                                            title="Dikembalikan"
                                        >
                                            Dikembalikan
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex max-w-full items-center justify-center truncate whitespace-nowrap rounded-full bg-warning-50 px-2.5 py-1 text-[10px] font-medium text-warning-600 dark:bg-warning-500/10 dark:text-warning-400"
                                            title="Dipinjam"
                                        >
                                            Dipinjam
                                        </span>

                                    @endif

                                </td>

                                {{-- Kategori --}}
                                <td class="overflow-hidden px-3 py-3">

                                    <span
                                        class="block truncate text-[11px] text-gray-600 dark:text-gray-400"
                                        title="{{ $borrower->visitor_category ?? '-' }}"
                                    >
                                        {{ $borrower->visitor_category
                                            ? ucfirst($borrower->visitor_category)
                                            : '-' }}
                                    </span>

                                </td>

                                {{-- ID Buku --}}
                                <td class="overflow-hidden px-3 py-3">

                                    <span
                                        class="block truncate text-[11px] font-semibold text-gray-700 dark:text-gray-300"
                                        title="{{ $borrower->book_identifier ?? '-' }}"
                                    >
                                        {{ $borrower->book_identifier ?: '-' }}
                                    </span>

                                </td>

                                {{-- Judul Buku --}}
                                <td class="overflow-hidden px-3 py-3">

                                    <span
                                        class="block truncate text-[11px] font-medium text-gray-700 dark:text-gray-300"
                                        title="{{ $borrower->title ?? '-' }}"
                                    >
                                        {{ $borrower->title ?: '-' }}
                                    </span>

                                </td>

                                {{-- ID Eksemplar --}}
                                <td class="overflow-hidden px-3 py-3">

                                    <span
                                        class="block truncate text-[11px] text-gray-600 dark:text-gray-400"
                                        title="{{ $borrower->copy_code ?? '-' }}"
                                    >
                                        {{ $borrower->copy_code ?: '-' }}
                                    </span>

                                </td>

                                {{-- Tanggal Pinjam --}}
                                <td class="overflow-hidden px-3 py-3">

                                    <span
                                        class="block truncate whitespace-nowrap text-[11px] text-gray-600 dark:text-gray-400"
                                        title="{{ $loanDate }}"
                                    >
                                        {{ $loanDate }}
                                    </span>

                                </td>

                                {{-- Tanggal Kembali --}}
                                <td class="overflow-hidden px-3 py-3">

                                    <span
                                        class="block truncate whitespace-nowrap text-[11px] text-gray-600 dark:text-gray-400"
                                        title="{{ $returnedDate }}"
                                    >
                                        {{ $returnedDate }}
                                    </span>

                                </td>

                                {{-- Action --}}
                                <td class="overflow-hidden px-2 py-3">

                                    <div
                                        class="flex w-full items-center justify-center gap-1.5"
                                        :class="getActionLayout()"
                                    >

                                        @if (!$isReturned)

                                            <form
                                                action="{{ route(
                                                    'borrowers.return',
                                                    $borrower->loan_detail_id
                                                ) }}"
                                                method="POST"
                                                class="min-w-0 flex-1"
                                                onsubmit="return confirm('Apakah buku ini sudah dikembalikan?')"
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="flex w-full min-w-0 items-center justify-center overflow-hidden rounded-md bg-success-500 text-center font-medium leading-tight text-white shadow-theme-xs transition hover:bg-success-600"
                                                    :class="[
                                                        getActionTextSize(),
                                                        getActionPadding()
                                                    ]"
                                                    :title="getActionLabel()"
                                                >
                                                    <span
                                                        class="block w-full truncate"
                                                        x-text="getActionLabel()"
                                                    ></span>
                                                </button>

                                            </form>

                                        @else

                                            <div class="min-w-0 flex-1 text-center">

                                                <span
                                                    class="block truncate font-medium text-gray-400"
                                                    :class="getActionTextSize()"
                                                    x-text="getFinishedLabel()"
                                                    title="Selesai"
                                                ></span>

                                            </div>

                                        @endif

                            <form
                                action="{{ route(
                                    'borrowers.destroy',
                                    $borrower->loan_detail_id
                                ) }}"
                                method="POST"
                                class="shrink-0"
                                onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex h-8 w-8 items-center justify-center text-gray-500 transition-colors hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500"
                                    title="Hapus"
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

                        @empty

                            <tr>

                                <td
                                    colspan="12"
                                    class="px-4 py-10 text-center"
                                >
                                    <div class="text-xs text-gray-500 dark:text-gray-400">

                                        @if ($search !== '')

                                            Data peminjam dengan pencarian

                                            <span class="font-semibold text-gray-700 dark:text-gray-300">
                                                "{{ $search }}"
                                            </span>

                                            tidak ditemukan.

                                        @else

                                            Belum ada data peminjaman.

                                        @endif

                                    </div>

                                    @if ($search !== '')

                                        <a
                                            href="{{ route('borrowers.index') }}"
                                            class="mt-3 inline-flex rounded-md border border-gray-300 px-3 py-1.5 text-[10px] font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
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
            @if ($borrowers->total() > 0)

                <div class="border-t border-gray-100 px-4 py-4 dark:border-gray-800">

                    <div class="grid grid-cols-3 items-center gap-3">

                        {{-- Previous --}}
                        <div class="flex justify-start">

                            @if ($borrowers->onFirstPage())

                                <button
                                    type="button"
                                    disabled
                                    class="inline-flex h-11 cursor-not-allowed items-center gap-2 rounded-lg border border-gray-200 px-4 text-sm font-medium text-gray-400 opacity-50 dark:border-gray-800 dark:text-gray-600"
                                >
                                    <svg
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M15 18L9 12L15 6"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>

                                    Previous
                                </button>

                            @else

                                <a
                                    href="{{ $borrowers->previousPageUrl() }}"
                                    class="inline-flex h-11 items-center gap-2 rounded-lg border border-gray-300 px-4 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                                >
                                    <svg
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M15 18L9 12L15 6"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>

                                    Previous
                                </a>

                            @endif

                        </div>

                        {{-- Nomor Halaman --}}
                        <div class="flex items-center justify-center gap-2">

                            @php
                                $currentPage = $borrowers->currentPage();
                                $lastPage = $borrowers->lastPage();

                                $startPage = max(
                                    1,
                                    $currentPage - 2
                                );

                                $endPage = min(
                                    $lastPage,
                                    $currentPage + 2
                                );
                            @endphp

                            @if ($startPage > 1)

                                <a
                                    href="{{ $borrowers->url(1) }}"
                                    class="inline-flex h-10 min-w-10 items-center justify-center rounded-lg border border-gray-300 px-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                                >
                                    1
                                </a>

                                @if ($startPage > 2)

                                    <span class="px-1 text-sm text-gray-400">
                                        ...
                                    </span>

                                @endif

                            @endif

                            @for (
                                $page = $startPage;
                                $page <= $endPage;
                                $page++
                            )

                                @if ($page === $currentPage)

                                    <span
                                        class="inline-flex h-10 min-w-10 items-center justify-center rounded-lg bg-brand-500 px-3 text-sm font-medium text-white shadow-theme-xs"
                                    >
                                        {{ $page }}
                                    </span>

                                @else

                                    <a
                                        href="{{ $borrowers->url($page) }}"
                                        class="inline-flex h-10 min-w-10 items-center justify-center rounded-lg border border-gray-300 px-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                                    >
                                        {{ $page }}
                                    </a>

                                @endif

                            @endfor

                            @if ($endPage < $lastPage)

                                @if ($endPage < $lastPage - 1)

                                    <span class="px-1 text-sm text-gray-400">
                                        ...
                                    </span>

                                @endif

                                <a
                                    href="{{ $borrowers->url($lastPage) }}"
                                    class="inline-flex h-10 min-w-10 items-center justify-center rounded-lg border border-gray-300 px-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                                >
                                    {{ $lastPage }}
                                </a>

                            @endif

                        </div>

                        {{-- Next --}}
                        <div class="flex justify-end">

                            @if ($borrowers->hasMorePages())

                                <a
                                    href="{{ $borrowers->nextPageUrl() }}"
                                    class="inline-flex h-11 items-center gap-2 rounded-lg border border-gray-300 px-4 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]"
                                >
                                    Next

                                    <svg
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M9 18L15 12L9 6"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </a>

                            @else

                                <button
                                    type="button"
                                    disabled
                                    class="inline-flex h-11 cursor-not-allowed items-center gap-2 rounded-lg border border-gray-200 px-4 text-sm font-medium text-gray-400 opacity-50 dark:border-gray-800 dark:text-gray-600"
                                >
                                    Next

                                    <svg
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M9 18L15 12L9 6"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </button>

                            @endif

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </x-common.component-card>
</div>