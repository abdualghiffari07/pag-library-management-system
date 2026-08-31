@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-7xl px-4 py-6">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                Daftar Pengunjung
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Data pengunjung yang telah terdaftar di PAG Library.
            </p>
        </div>

        {{-- Search --}}
        <form action="{{ route('visitors') }}" method="GET" class="flex w-full gap-2 sm:w-auto">
            <div class="relative w-full sm:w-72">
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
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama, nomor, kategori..."
                    class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                >
            </div>

            <button
                type="submit"
                class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
            >
                Cari
            </button>

            @if (!empty($search))
                <a
                    href="{{ route('visitors') }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                >
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Notification --}}
    @if (session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">

                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-white/[0.02]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            No
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Nama Pengunjung
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Kategori
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Nomor Identitas
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Waktu Pendaftaran
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                    @forelse ($visitors as $visitor)

                        @php
                            $category = strtolower(trim($visitor->visitor_category ?? 'lainnya'));

                            [$categoryLabel, $categoryClass] = match ($category) {
                                'pekerja' => [
                                    'Pekerja',
                                    'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400'
                                ],
                                'mahasiswa' => [
                                    'Mahasiswa',
                                    'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400'
                                ],
                                'tamu' => [
                                    'Tamu',
                                    'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400'
                                ],
                                default => [
                                    'Lainnya',
                                    'bg-gray-100 text-gray-600 ring-gray-500/20 dark:bg-gray-800 dark:text-gray-300'
                                ],
                            };
                        @endphp

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                            {{-- No --}}
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Nama --}}
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $visitor->visitor_name }}
                                </p>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $categoryClass }}">
                                    {{ $categoryLabel }}
                                </span>
                            </td>

                            {{-- Nomor Identitas --}}
                            <td class="px-6 py-4">
                                @if ($visitor->employee_number)
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $visitor->employee_number }}
                                    </span>

                                    <p class="mt-1 text-xs text-gray-400">
                                        @switch($category)
                                            @case('pekerja')
                                                No. Pekerja
                                                @break

                                            @case('mahasiswa')
                                                NIM / NPM
                                                @break

                                            @case('tamu')
                                                No. Identitas / No. HP
                                                @break

                                            @default
                                                No. Identitas
                                        @endswitch
                                    </p>
                                @else
                                    <span class="text-sm text-gray-400">
                                        -
                                    </span>
                                @endif
                            </td>

                            {{-- Waktu Pendaftaran --}}
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                @if ($visitor->created_at)
                                    {{ \Carbon\Carbon::parse($visitor->created_at)->format('d M Y, H:i') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
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

                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                        <svg
                                            class="h-6 w-6 text-gray-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87m-1-7.13a4 4 0 010 7.75"
                                            />
                                        </svg>
                                    </div>

                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        @if (!empty($search))
                                            Pengunjung tidak ditemukan
                                        @else
                                            Belum ada pengunjung
                                        @endif
                                    </p>

                                    <p class="mt-1 text-sm text-gray-400">
                                        @if (!empty($search))
                                            Tidak ada data yang sesuai dengan pencarian.
                                        @else
                                            Data pengunjung akan muncul di sini setelah melakukan pendaftaran.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection