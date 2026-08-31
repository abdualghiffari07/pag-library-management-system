{{-- Summary --}}
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">

    {{-- Total Buku --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg
                class="fill-gray-800 dark:fill-white/90"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
            >
                <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M5.25 3.75C4.007 3.75 3 4.757 3 6v12c0 1.243 1.007 2.25 2.25 2.25h13.5C19.993 20.25 21 19.243 21 18V6c0-1.243-1.007-2.25-2.25-2.25H5.25Zm0 1.5h13.5c.414 0 .75.336.75.75v12a.75.75 0 0 1-.75.75H5.25A.75.75 0 0 1 4.5 18V6c0-.414.336-.75.75-.75ZM7.5 7.5a.75.75 0 0 0 0 1.5h9a.75.75 0 0 0 0-1.5h-9Zm0 3.75a.75.75 0 0 0 0 1.5h9a.75.75 0 0 0 0-1.5h-9ZM6.75 15.75A.75.75 0 0 1 7.5 15h5.25a.75.75 0 0 1 0 1.5H7.5a.75.75 0 0 1-.75-.75Z"
                />
            </svg>
        </div>

        <div class="mt-5 flex items-end justify-between gap-3">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Total Buku
                </span>

                <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($totalBooks) }}
                </h4>
            </div>

            <span class="text-right text-sm font-medium text-gray-500 dark:text-gray-400">
                Koleksi aktif
            </span>
        </div>
    </div>

    {{-- Sedang Dipinjam --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg
                class="fill-gray-800 dark:fill-white/90"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
            >
                <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M6 3a1.5 1.5 0 0 0-1.5 1.5v12A1.5 1.5 0 0 0 6 18h1.5v1.5A1.5 1.5 0 0 0 9 21h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 18 6h-1.5V4.5A1.5 1.5 0 0 0 15 3H6Zm0 1.5h9v12H6v-12ZM9 18h6a1.5 1.5 0 0 0 1.5-1.5v-9H18v12H9V18Z"
                />
            </svg>
        </div>

        <div class="mt-5 flex items-end justify-between gap-3">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Sedang Dipinjam
                </span>

                <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($borrowedBooks) }}
                </h4>
            </div>

            <span class="text-sm font-medium text-orange-600 dark:text-orange-400">
                Saat ini
            </span>
        </div>
    </div>

    {{-- Buku Tersedia --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg
                class="fill-gray-800 dark:fill-white/90"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
            >
                <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm0 1.5a7.5 7.5 0 1 1 0 15 7.5 7.5 0 0 1 0-15Zm4.28 4.22a.75.75 0 0 1 0 1.06l-5.5 5.5a.75.75 0 0 1-1.06 0l-2-2a.75.75 0 0 1 1.06-1.06l1.47 1.47 4.97-4.97a.75.75 0 0 1 1.06 0Z"
                />
            </svg>
        </div>

        <div class="mt-5 flex items-end justify-between gap-3">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Buku Tersedia
                </span>

                <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($availableBooks) }}
                </h4>
            </div>

            <span class="text-sm font-medium text-green-600 dark:text-green-400">
                Siap dipinjam
            </span>
        </div>
    </div>

    {{-- Total Pengunjung --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg
                class="fill-gray-800 dark:fill-white/90"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
            >
                <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M8.8 4.1a3.697 3.697 0 1 0 0 7.394 3.697 3.697 0 0 0 0-7.394Zm-2.197 3.697a2.197 2.197 0 1 1 4.394 0 2.197 2.197 0 0 1-4.394 0ZM3.81 14.79c1.02-1.01 2.56-1.59 4.94-1.59 2.38 0 3.92.58 4.94 1.59 1.01 1 1.46 2.25 1.67 3.16.13.58-.02 1.13-.4 1.54-.36.4-.91.66-1.54.66H4.08c-.64 0-1.18-.26-1.55-.66-.38-.41-.53-.96-.39-1.54.21-.91.66-2.16 1.67-3.16Z"
                />
            </svg>
        </div>

        <div class="mt-5 flex items-end justify-between gap-3">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Total Pengunjung
                </span>

                <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($visitors) }}
                </h4>
            </div>

            <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                Terdaftar
            </span>
        </div>
    </div>

</div>

{{-- Kategori Pengunjung --}}
@php
    $categoryTotal =
        $workerVisitors +
        $studentVisitors +
        $guestVisitors +
        $otherVisitors;

    $workerPercent = $categoryTotal > 0
        ? round(($workerVisitors / $categoryTotal) * 100)
        : 0;

    $studentPercent = $categoryTotal > 0
        ? round(($studentVisitors / $categoryTotal) * 100)
        : 0;

    $guestPercent = $categoryTotal > 0
        ? round(($guestVisitors / $categoryTotal) * 100)
        : 0;

    $otherPercent = $categoryTotal > 0
        ? max(0, 100 - $workerPercent - $studentPercent - $guestPercent)
        : 0;

    $workerEnd = $workerPercent;
    $studentEnd = $workerEnd + $studentPercent;
    $guestEnd = $studentEnd + $guestPercent;
@endphp

<div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Kategori Pengunjung
        </h3>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Distribusi pengunjung berdasarkan kategori pendaftaran.
        </p>
    </div>

    {{-- Donut & Legend --}}
    <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-2">

        {{-- Donut --}}
        <div class="flex items-center justify-center">
            <div
                class="relative h-52 w-52 rounded-full"
                style="
                    background:
                        conic-gradient(
                            #16a34a 0% {{ $workerEnd }}%,
                            #0891b2 {{ $workerEnd }}% {{ $studentEnd }}%,
                            #6366f1 {{ $studentEnd }}% {{ $guestEnd }}%,
                            #f59e0b {{ $guestEnd }}% 100%
                        );
                "
            >
                <div class="absolute inset-8 flex flex-col items-center justify-center rounded-full bg-white shadow-inner dark:bg-gray-900">
                    <span class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ number_format($categoryTotal) }}
                    </span>

                    <span class="mt-1 text-xs font-medium text-gray-400">
                        Total
                    </span>
                </div>
            </div>
        </div>

        {{-- Legend --}}
        <div class="space-y-4">

            {{-- Pekerja --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="h-3 w-3 rounded-full bg-green-600"></span>

                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Pekerja
                        </p>

                        <p class="text-xs text-gray-400">
                            {{ number_format($workerVisitors) }} pengunjung
                        </p>
                    </div>
                </div>

                <span class="text-sm font-semibold text-gray-800 dark:text-white">
                    {{ $workerPercent }}%
                </span>
            </div>

            {{-- Mahasiswa --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="h-3 w-3 rounded-full bg-cyan-600"></span>

                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Mahasiswa
                        </p>

                        <p class="text-xs text-gray-400">
                            {{ number_format($studentVisitors) }} pengunjung
                        </p>
                    </div>
                </div>

                <span class="text-sm font-semibold text-gray-800 dark:text-white">
                    {{ $studentPercent }}%
                </span>
            </div>

            {{-- Tamu --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="h-3 w-3 rounded-full bg-indigo-500"></span>

                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tamu
                        </p>

                        <p class="text-xs text-gray-400">
                            {{ number_format($guestVisitors) }} pengunjung
                        </p>
                    </div>
                </div>

                <span class="text-sm font-semibold text-gray-800 dark:text-white">
                    {{ $guestPercent }}%
                </span>
            </div>

            {{-- Lainnya --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="h-3 w-3 rounded-full bg-amber-500"></span>

                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Lainnya
                        </p>

                        <p class="text-xs text-gray-400">
                            {{ number_format($otherVisitors) }} pengunjung
                        </p>
                    </div>
                </div>

                <span class="text-sm font-semibold text-gray-800 dark:text-white">
                    {{ $otherPercent }}%
                </span>
            </div>

        </div>

    </div>

    {{-- Detail --}}
    <div class="mt-8 border-t border-gray-100 pt-6 dark:border-gray-800">

        <div class="mb-5">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                Komposisi Pengunjung
            </h4>

            <p class="mt-1 text-xs text-gray-400">
                Persentase masing-masing kategori dari total pengunjung.
            </p>
        </div>

        <div class="space-y-5">

            {{-- Pekerja --}}
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        Pekerja
                    </span>

                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                        {{ number_format($workerVisitors) }}/{{ number_format($categoryTotal) }}
                    </span>
                </div>

                <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                    <div
                        class="h-full rounded-full bg-green-600 transition-all duration-500"
                        style="width: {{ $workerPercent }}%"
                    ></div>
                </div>
            </div>

            {{-- Mahasiswa --}}
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        Mahasiswa
                    </span>

                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                        {{ number_format($studentVisitors) }}/{{ number_format($categoryTotal) }}
                    </span>
                </div>

                <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                    <div
                        class="h-full rounded-full bg-cyan-600 transition-all duration-500"
                        style="width: {{ $studentPercent }}%"
                    ></div>
                </div>
            </div>

            {{-- Tamu --}}
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        Tamu
                    </span>

                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                        {{ number_format($guestVisitors) }}/{{ number_format($categoryTotal) }}
                    </span>
                </div>

                <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                    <div
                        class="h-full rounded-full bg-indigo-500 transition-all duration-500"
                        style="width: {{ $guestPercent }}%"
                    ></div>
                </div>
            </div>

            {{-- Lainnya --}}
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        Lainnya
                    </span>

                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                        {{ number_format($otherVisitors) }}/{{ number_format($categoryTotal) }}
                    </span>
                </div>

                <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                    <div
                        class="h-full rounded-full bg-amber-500 transition-all duration-500"
                        style="width: {{ $otherPercent }}%"
                    ></div>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- Statistik --}}
<div class="mt-6 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Header --}}
    <div class="flex flex-col gap-5 border-b border-gray-200 px-5 py-5 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between sm:px-6">

        <div>
            <h3
                id="reportChartTitle"
                class="text-lg font-semibold text-gray-800 dark:text-white/90"
            >
                Statistik Pengunjung
            </h3>

            <p
                id="reportChartDescription"
                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
            >
                Jumlah pengunjung yang terdaftar berdasarkan periode.
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

            {{-- Toggle --}}
            <div class="flex w-fit rounded-lg bg-gray-100 p-1 dark:bg-gray-800">
                <button
                    type="button"
                    data-report-chart="visitors"
                    class="report-chart-button rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-800 shadow-sm dark:bg-gray-700 dark:text-white"
                >
                    Pengunjung
                </button>

                <button
                    type="button"
                    data-report-chart="loans"
                    class="report-chart-button rounded-md px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                    Peminjaman
                </button>
            </div>

            {{-- Date Picker --}}
            <div
                x-data="{
                    init() {
                        const formatLocalDate = (date) => {
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const day = String(date.getDate()).padStart(2, '0');

                            return `${year}-${month}-${day}`;
                        };

                        flatpickr(this.$refs.datepicker, {
                            mode: 'range',
                            static: true,
                            monthSelectorType: 'static',
                            dateFormat: 'Y-m-d',
                            defaultDate: [
                                @js($startDate->format('Y-m-d')),
                                @js($endDate->format('Y-m-d'))
                            ],

                            onReady(selectedDates, dateStr, instance) {
                                if (selectedDates.length === 2) {
                                    instance.element.value =
                                        `${formatLocalDate(selectedDates[0])} - ${formatLocalDate(selectedDates[1])}`;
                                }
                            },

                            onChange(selectedDates, dateStr, instance) {
                                if (selectedDates.length !== 2) {
                                    return;
                                }

                                const startDate = formatLocalDate(selectedDates[0]);
                                const endDate = formatLocalDate(selectedDates[1]);

                                instance.element.value =
                                    `${startDate} - ${endDate}`;

                                const url = new URL(window.location.href);

                                url.searchParams.set('start_date', startDate);
                                url.searchParams.set('end_date', endDate);

                                window.location.href = url.toString();
                            }
                        });
                    }
                }"
                class="relative w-full sm:w-auto"
            >
                <input
                    x-ref="datepicker"
                    type="text"
                    class="h-10 w-full min-w-[220px] rounded-lg border border-gray-200 bg-white py-2.5 pl-11 pr-4 text-sm font-medium text-gray-700 shadow-sm outline-none focus:border-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                    placeholder="Pilih tanggal"
                    readonly
                >

                <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center">
                    <svg
                        class="fill-gray-500 dark:fill-gray-400"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M6.667 1.542a.75.75 0 0 1 .75.75V3h5.166v-.708a.75.75 0 0 1 1.5 0V3h1.334a2 2 0 0 1 2 2v10.834a2 2 0 0 1-2 2H4.583a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1.334v-.708a.75.75 0 0 1 .75-.75ZM4.083 6.75h11.834V5a.5.5 0 0 0-.5-.5H4.583a.5.5 0 0 0-.5.5v1.75Zm0 1.5v7.584a.5.5 0 0 0 .5.5h10.834a.5.5 0 0 0 .5-.5V8.25H4.083Z"
                        />
                    </svg>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart --}}
    <div class="px-5 pb-6 pt-5 sm:px-6">
        <div class="relative w-full">
            <div
                id="reportChart"
                class="h-[350px] w-full overflow-hidden"
            ></div>
        </div>

        {{-- Legend --}}
        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <span
                    id="reportChartDot"
                    class="h-2.5 w-2.5 rounded-full bg-blue-500"
                ></span>

                <span
                    id="reportChartLegend"
                    class="text-sm font-medium text-gray-600 dark:text-gray-300"
                >
                    Pengunjung
                </span>
            </div>

            <div class="text-right">
                <span class="text-xs text-gray-400">
                    Total
                </span>

                <p
                    id="reportChartTotal"
                    class="text-lg font-semibold text-gray-800 dark:text-white/90"
                >
                    0
                </p>
            </div>
        </div>
    </div>

</div>

{{-- Report Data --}}
<script>
    window.pagReportData = {
        months: @json($chartMonths ?? []),
        visitors: @json($visitorData ?? []),
        loans: @json($loanData ?? []),
        startDate: @json($startDate->format('Y-m-d')),
        endDate: @json($endDate->format('Y-m-d')),
        groupByDay: @json($groupByDay)
    };
</script>