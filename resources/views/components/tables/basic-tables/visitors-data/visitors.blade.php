@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-7xl px-4 py-6">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>

            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                Daftar Pengunjung
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Daftar pengunjung yang melakukan kunjungan ke PAG Library.
            </p>

        </div>

    </div>


    {{-- =========================================================
         TABLE CARD
    ========================================================== --}}
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    >

        <div class="overflow-x-auto">

            <table class="w-full min-w-[700px]">

                {{-- =================================================
                     TABLE HEADER
                ================================================== --}}
                <thead
                    class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-white/[0.02]"
                >

                    <tr>

                        {{-- No --}}
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            No
                        </th>

                        {{-- Nama Pengunjung --}}
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            Nama Pengunjung
                        </th>

                        {{-- No. Pekerja --}}
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            No. Pekerja
                        </th>

                        {{-- Waktu Kunjungan --}}
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            Waktu Kunjungan
                        </th>

                        {{-- Aksi --}}
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- =================================================
                     TABLE BODY
                ================================================== --}}
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                    @forelse ($visitors as $visitor)

                        <tr
                            class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]"
                        >

                            {{-- =================================================
                                 NO
                            ================================================== --}}
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">

                                {{ $loop->iteration }}

                            </td>


                            {{-- =================================================
                                 NAMA PENGUNJUNG
                            ================================================== --}}
                            <td class="px-6 py-4">

                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">

                                    {{ $visitor->visitor_name }}

                                </p>

                            </td>


                            {{-- =================================================
                                 NO. PEKERJA
                            ================================================== --}}
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">

                                @if ($visitor->employee_number)

                                    {{ $visitor->employee_number }}

                                @else

                                    <span class="text-gray-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 WAKTU KUNJUNGAN
                            ================================================== --}}
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">

                                {{ \Carbon\Carbon::parse($visitor->created_at)->format('d M Y, H:i') }}

                            </td>


                            {{-- =================================================
                                 AKSI
                            ================================================== --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center justify-center">

                                    <form
                                        action="{{ route('visitors.destroy', $visitor->visitor_id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengunjung ini?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-600 transition hover:bg-red-100 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        {{-- =================================================
                             DATA KOSONG
                        ================================================== --}}
                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center"
                            >

                                <div class="flex flex-col items-center justify-center">

                                    {{-- Icon --}}
                                    <div
                                        class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800"
                                    >
                                        <span class="text-xl">
                                            👤
                                        </span>
                                    </div>

                                    {{-- Title --}}
                                    <p
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >
                                        Belum ada pengunjung
                                    </p>

                                    {{-- Description --}}
                                    <p class="mt-1 text-sm text-gray-400">
                                        Data pengunjung akan muncul di sini.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        @if (method_exists($visitors, 'links'))

            <div
                class="border-t border-gray-200 px-6 py-4 dark:border-gray-800"
            >

                {{ $visitors->links() }}

            </div>

        @endif

    </div>

</div>

@endsection