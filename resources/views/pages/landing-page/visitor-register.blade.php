<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Pengunjung - PAG Library</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/landing.js'
    ])
</head>

<body class="min-h-screen overflow-x-hidden bg-[#F5F7FA] text-slate-800">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <header class="fixed left-0 top-0 z-50 w-full border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur-md">

        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:h-20 sm:px-6 lg:px-8">

            {{-- LOGO --}}
            <a
                href="{{ route('landing') }}"
                class="flex min-w-0 items-center gap-2 sm:gap-3"
            >

                <div class="flex h-11 w-12 shrink-0 items-center justify-center overflow-hidden rounded-lg sm:h-16 sm:w-16 sm:rounded-xl">

                    <img
                        src="{{ asset('images/landing/logo-pertamina.png') }}"
                        alt="Logo PAG"
                        class="h-full w-full object-contain p-1"
                    >

                </div>

                <div class="min-w-0">

                    <h1 class="truncate text-sm font-bold leading-none text-[#102A43] sm:text-lg">
                        Perta Arun Gas Library
                    </h1>

                    <p class="mt-1 hidden text-xs text-slate-500 sm:block">
                        Library Management System
                    </p>

                </div>

            </a>

            {{-- KEMBALI --}}
            <a
                href="{{ route('landing') }}"
                class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#005DAA] hover:text-[#005DAA] sm:px-5"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                <span>
                    Kembali
                </span>

            </a>

        </div>

    </header>


    {{-- =========================================================
         MAIN
    ========================================================== --}}
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-5 pb-12 pt-28 sm:px-6 sm:pt-32 lg:px-8">

        {{-- Background Decoration --}}
        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-[#005DAA]/20 blur-3xl"></div>

        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-[#E31E24]/20 blur-3xl"></div>

        <div class="absolute right-1/4 top-1/3 h-48 w-48 rounded-full bg-[#00A651]/10 blur-3xl"></div>


        {{-- CONTENT --}}
        <div class="relative z-10 w-full max-w-2xl">

            {{-- TITLE --}}
            <div class="mb-8 text-center">

                <div class="mb-5 flex items-center justify-center gap-2">

                    <span class="h-1 w-10 rounded-full bg-[#E31E24]"></span>

                    <span class="h-1 w-10 rounded-full bg-[#005DAA]"></span>

                    <span class="h-1 w-10 rounded-full bg-[#00A651]"></span>

                </div>

                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#005DAA] sm:text-sm sm:tracking-[0.25em]">
                    Daftar Pengunjung
                </p>

                <h2 class="mt-3 text-3xl font-bold leading-tight text-[#102A43] sm:text-4xl">
                    Selamat Datang di
                    <span class="text-[#E31E24]">
                        PAG Library
                    </span>
                </h2>

                <p class="mx-auto mt-4 max-w-xl text-sm leading-6 text-slate-600 sm:text-base sm:leading-7">
                    Silakan masukkan data diri Anda sebelum
                    menggunakan fasilitas perpustakaan.
                </p>

            </div>


            {{-- FORM CARD --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-xl sm:p-8 lg:p-10">

                {{-- SUCCESS --}}
                @if (session('success'))

                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">

                        <div class="flex items-start gap-3">

                            <svg
                                class="mt-0.5 h-5 w-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                            <span>
                                {{ session('success') }}
                            </span>

                        </div>

                    </div>

                @endif


                {{-- ERROR --}}
                @if ($errors->visitor->any())

                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                        <div class="flex items-start gap-3">

                            <svg
                                class="mt-0.5 h-5 w-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v3m0 4h.01M10.29 3.86l-8.18 14a2 2 0 001.71 3h16.36a2 2 0 001.71-3l-8.18-14a2 2 0 00-3.42 0z"
                                />
                            </svg>

                            <span>
                                {{ $errors->visitor->first() }}
                            </span>

                        </div>

                    </div>

                @endif


                {{-- FORM --}}
                <form
                    action="{{ route('visitors.store') }}"
                    method="POST"
                    class="space-y-6"
                >

                    @csrf


                    {{-- NAMA --}}
                    <div>

                        <label
                            for="visitor_name"
                            class="mb-2 block text-sm font-semibold text-[#102A43]"
                        >
                            Nama Pengunjung
                            <span class="text-[#E31E24]">*</span>
                        </label>

                        <input
                            type="text"
                            id="visitor_name"
                            name="visitor_name"
                            value="{{ old('visitor_name') }}"
                            required
                            maxlength="255"
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#005DAA] focus:ring-4 focus:ring-[#005DAA]/10"
                        >

                        @error('visitor_name', 'visitor')
                            <p class="mt-2 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- NO PEKERJA --}}
                    <div>

                        <label
                            for="employee_number"
                            class="mb-2 block text-sm font-semibold text-[#102A43]"
                        >
                            No. Pekerja

                            <span class="font-normal text-slate-400">
                                (Opsional)
                            </span>
                        </label>

                        <input
                            type="text"
                            id="employee_number"
                            name="employee_number"
                            value="{{ old('employee_number') }}"
                            maxlength="100"
                            placeholder="Masukkan nomor pekerja jika ada"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#005DAA] focus:ring-4 focus:ring-[#005DAA]/10"
                        >

                        @error('employee_number', 'visitor')
                            <p class="mt-2 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- SUBMIT --}}
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#E31E24] px-5 py-3.5 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#c91820] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-[#E31E24]/20"
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 12h14M12 5l7 7-7 7"
                            />
                        </svg>

                        Masukkan

                    </button>

                </form>

            </div>


            {{-- INFO --}}
            <p class="mt-6 text-center text-xs leading-5 text-slate-500">
                Data pengunjung digunakan untuk pencatatan aktivitas
                kunjungan perpustakaan.
            </p>

        </div>

    </main>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}
    <footer class="bg-[#102A43] px-5 py-6 text-white sm:px-6 sm:py-8 lg:px-8">

        <div class="mx-auto flex max-w-7xl flex-col items-center gap-2 text-center sm:flex-row sm:justify-between sm:text-left">

            <div>

                <h3 class="font-bold">
                    PAG Library
                </h3>

                <p class="mt-1 text-xs text-white/60 sm:text-sm">
                    Library Management System
                </p>

            </div>

            <p class="text-xs text-white/60 sm:text-sm">
                © {{ date('Y') }} PAG Library
            </p>

        </div>

    </footer>

</body>

</html>