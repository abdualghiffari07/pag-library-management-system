{{-- =========================================================
     SUMMARY CARDS
     PAG LIBRARY REPORT
========================================================= --}}

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">

    {{-- TOTAL BUKU --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M5.25 3.75C4.00736 3.75 3 4.75736 3 6V18C3 19.2426 4.00736 20.25 5.25 20.25H18.75C19.9926 20.25 21 19.2426 21 18V6C21 4.75736 19.9926 3.75 18.75 3.75H5.25ZM5.25 5.25H18.75C19.1642 5.25 19.5 5.58579 19.5 6V18C19.5 18.4142 19.1642 18.75 18.75 18.75H5.25C4.83579 18.75 4.5 18.4142 4.5 18V6C4.5 5.58579 4.83579 5.25 5.25 5.25ZM7.5 7.5C7.08579 7.5 6.75 7.83579 6.75 8.25C6.75 8.66421 7.08579 9 7.5 9H16.5C16.9142 9 17.25 8.66421 17.25 8.25C17.25 7.83579 16.9142 7.5 16.5 7.5H7.5ZM7.5 11.25C7.08579 11.25 6.75 11.5858 6.75 12C6.75 12.4142 7.08579 12.75 7.5 12.75H16.5C16.9142 12.75 17.25 12.4142 17.25 12.75 17.25 12.75 16.5 12.75H7.5ZM7.5 15C7.08579 15 6.75 15.3358 6.75 15.75C6.75 16.1642 7.08579 16.5 7.5 16.5H12.75C13.1642 16.5 13.5 16.1642 13.5 15.75C13.5 15.3358 13.1642 15 12.75 15H7.5Z" />
            </svg>
        </div>

        <div class="mt-5 flex items-end justify-between">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Total Buku
                </span>

                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                    {{ number_format($totalBooks) }}
                </h4>
            </div>

            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Koleksi aktif
            </span>
        </div>
    </div>

    {{-- SEDANG DIPINJAM --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M4.5 4.5C4.5 3.67157 5.17157 3 6 3H15C15.8284 3 16.5 3.67157 16.5 4.5V6H18C18.8284 6 19.5 6.67157 19.5 7.5V19.5C19.5 20.3284 18.8284 21 18 21H9C8.17157 21 7.5 20.3284 7.5 19.5V18H6C5.17157 18 4.5 17.3284 4.5 16.5V4.5ZM9 18V19.5H18V7.5H16.5V16.5C16.5 17.3284 15.8284 18 15 18H9ZM15 4.5H6V16.5H15V4.5ZM8.25 7.5C8.25 7.08579 8.58579 6.75 9 6.75H12C12.4142 6.75 12.75 7.08579 12.75 7.5C12.75 7.91421 12.4142 8.25 12 8.25H9C8.58579 8.25 8.25 7.91421 8.25 7.5ZM8.25 10.5C8.25 10.0858 8.58579 9.75 9 9.75H12C12.4142 9.75 12.75 10.0858 12.75 10.5C12.75 10.9142 12.4142 11.25 12 11.25H9C8.58579 11.25 8.25 10.5 8.25 10.5Z" />
            </svg>
        </div>

        <div class="mt-5 flex items-end justify-between">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Sedang Dipinjam
                </span>

                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                    {{ number_format($borrowedBooks) }}
                </h4>
            </div>

            <span class="text-sm font-medium text-warning-600 dark:text-orange-400">
                Saat ini
            </span>
        </div>
    </div>

    {{-- BUKU TERSEDIA --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3ZM12 4.5C16.1421 4.5 19.5 7.85786 19.5 12C19.5 16.1421 16.1421 19.5 12 19.5C7.85786 19.5 4.5 16.1421 4.5 12C4.5 7.85786 7.85786 4.5 12 4.5ZM16.2803 8.71967C16.5732 9.01256 16.5732 9.48744 16.2803 9.78033L10.7803 15.2803C10.4874 15.5732 10.0126 15.5732 9.71967 15.2803L7.71967 13.2803C7.42678 12.9874 7.42678 12.5126 7.71967 12.2197C8.01256 11.9268 8.48744 11.9268 8.78033 12.2197L10.25 13.6893L15.2197 8.71967C15.5126 8.42678 15.9874 8.42678 16.2803 8.71967Z" />
            </svg>
        </div>

        <div class="mt-5 flex items-end justify-between">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Buku Tersedia
                </span>

                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                    {{ number_format($availableBooks) }}
                </h4>
            </div>

            <span class="text-sm font-medium text-success-600 dark:text-success-500">
                Siap dipinjam
            </span>
        </div>
    </div>

    {{-- PENGUNJUNG --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.80443 5.60156C7.59109 5.60156 6.60749 6.58517 6.60749 7.79851C6.60749 9.01185 7.59109 9.99545 8.80443 9.99545C10.0178 9.99545 11.0014 9.01185 11.0014 7.79851C11.0014 6.58517 10.0178 5.60156 8.80443 5.60156ZM5.10749 7.79851C5.10749 5.75674 6.76267 4.10156 8.80443 4.10156C10.8462 4.10156 12.5014 5.75674 12.5014 7.79851C12.5014 9.84027 10.8462 11.4955 8.80443 11.4955C6.76267 11.4955 5.10749 9.84027 5.10749 7.79851ZM4.86252 15.3208C4.08769 16.0881 3.70377 17.0608 3.51705 17.8611C3.48384 18.0034 3.5211 18.1175 3.60712 18.2112C3.70161 18.3141 3.86659 18.3987 4.07591 18.3987H13.4249C13.6343 18.3987 13.7992 18.3141 13.9838 17.8611C13.7971 17.0608 13.4132 16.088 12.6383 15.3208C11.8821 14.572 10.6899 13.955 8.75042 13.955C6.81096 13.955 5.61877 14.572 4.86252 15.3208Z" />
            </svg>
        </div>

        <div class="mt-5 flex items-end justify-between">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Pengunjung
                </span>

                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                    {{ number_format($visitors) }}
                </h4>
            </div>

            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Pernah meminjam
            </span>
        </div>
    </div>

</div>


{{-- =========================================================
     STATISTIK PENGUNJUNG & PEMINJAMAN
========================================================= --}}

<div class="mt-6 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- HEADER --}}
    <div class="flex flex-col gap-5 border-b border-gray-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800 sm:px-6">

        <div>
            <h3 id="reportChartTitle" class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Statistik Pengunjung
            </h3>

            <p id="reportChartDescription" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Jumlah pengunjung berdasarkan periode
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

            {{-- TOGGLE --}}
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

            {{-- DATE PICKER --}}
            <div
                x-data="{
                    init() {
                        flatpickr(this.$refs.datepicker, {
                            mode: 'range',
                            static: true,
                            monthSelectorType: 'static',
                            dateFormat: 'Y-m-d',
                            defaultDate: [
                                '{{ $startDate->format('Y-m-d') }}',
                                '{{ $endDate->format('Y-m-d') }}'
                            ],
                            prevArrow: '<svg class=\'stroke-current\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M15.25 6L9 12.25L15.25 18.5\' stroke=\'\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>',
                            nextArrow: '<svg class=\'stroke-current\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M8.75 19L15 12.75L8.75 6.5\' stroke=\'\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>',

                            onReady: (selectedDates, dateStr, instance) => {
                                instance.element.value = dateStr.replace('to', '-');
                            },

                            onChange: (selectedDates, dateStr, instance) => {

                                if (selectedDates.length !== 2) {
                                    return;
                                }

                                const startDate = selectedDates[0]
                                    .toISOString()
                                    .split('T')[0];

                                const endDate = selectedDates[1]
                                    .toISOString()
                                    .split('T')[0];

                                instance.element.value = `${startDate} - ${endDate}`;

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
                    class="h-10 w-full min-w-[220px] rounded-lg border border-gray-200 bg-white py-2.5 pl-11 pr-4 text-sm font-medium text-gray-700 shadow-theme-xs focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                    placeholder="Pilih tanggal"
                    readonly
                />

                <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center">
                    <svg
                        class="fill-gray-700 dark:fill-gray-400"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M6.66683 1.54199C7.08104 1.54199 7.41683 1.87778 7.41683 2.29199V3.00033H12.5835V2.29199C12.5835 1.87778 12.9193 1.54199 13.3335 1.54199C13.7477 1.54199 14.0835 1.87778 14.0835 2.29199V3.00033L15.4168 3.00033C16.5214 3.00033 17.4168 3.89576 17.4168 5.00033V7.50033V15.8337C17.4168 16.9382 16.5214 17.8337 15.4168 17.8337H4.5835C3.47893 17.8337 2.5835 16.9382 2.5835 15.8337V7.50033V5.00033C2.5835 3.89576 3.47893 3.00033 4.5835 3.00033L5.91683 3.00033V2.29199C5.91683 1.87778 6.25262 1.54199 6.66683 1.54199ZM6.66683 4.50033H4.5835C4.30735 4.50033 4.0835 4.72418 4.0835 5.00033V6.75033H15.9168V5.00033C15.9168 4.72418 15.693 4.50033 15.4168 4.50033H13.3335H6.66683ZM15.9168 8.25033H4.0835V15.8337C4.0835 16.1098 4.30735 16.3337 4.5835 16.3337H15.4168C15.693 16.3337 15.9168 16.1098 15.9168 15.8337V8.25033Z"
                        />
                    </svg>
                </div>

            </div>

        </div>

    </div>


    {{-- CHART --}}
    <div class="px-5 pb-6 pt-5 sm:px-6">

        <div class="relative w-full">

            <div
                id="reportChart"
                class="h-[350px] w-full overflow-hidden"
            ></div>

        </div>


        {{-- LEGEND --}}
        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-800">

            <div class="flex items-center gap-2">

                <span
                    id="reportChartDot"
                    class="h-2.5 w-2.5 rounded-full bg-brand-500"
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


{{-- =========================================================
     DATA REPORT
========================================================= --}}

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