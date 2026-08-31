<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Daftar Pengunjung - PAG Library</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/landing.js'
    ])
</head>

<body
    class="min-h-screen overflow-x-hidden bg-[#F5F7FA] text-slate-800"
    x-data="{
        activeForm: @js(
            session(
                'active_form',
                $errors->register->any()
                    ? 'register'
                    : 'checkin'
            )
        ),

        registerCategory: @js(
            old('visitor_category', '')
        ),

        checkinCategory: @js(
            old('checkin_category', '')
        ),

        getLabel(category) {
            const labels = {
                pekerja: 'No. Pekerja',
                mahasiswa: 'NIM / NPM',
                tamu: 'No. Identitas / No. HP',
                lainnya: 'No. Identitas'
            };

            return labels[category]
                || 'Nomor Identitas';
        },

        getPlaceholder(category) {
            const placeholders = {
                pekerja: 'Contoh: 007',
                mahasiswa: 'Masukkan NIM / NPM',
                tamu: 'Masukkan No. Identitas atau No. HP',
                lainnya: 'Masukkan nomor identitas'
            };

            return placeholders[category]
                || 'Masukkan nomor identitas';
        }
    }"
>

    {{-- Header --}}
    <header
        class="fixed left-0 top-0 z-50 w-full border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur-md"
    >

        <div
            class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:h-20 sm:px-6 lg:px-8"
        >

            <a
                href="{{ route('landing') }}"
                class="flex min-w-0 items-center gap-2 sm:gap-3"
            >

                <div
                    class="flex h-11 w-12 shrink-0 items-center justify-center overflow-hidden rounded-lg sm:h-16 sm:w-16"
                >

                    <img
                        src="{{ asset('images/landing/logo-pertamina.png') }}"
                        alt="Logo PAG"
                        class="h-full w-full object-contain p-1"
                    >

                </div>

                <div class="min-w-0">

                    <h1
                        class="truncate text-sm font-bold leading-none text-[#102A43] sm:text-lg"
                    >
                        Perta Arun Gas Library
                    </h1>

                    <p
                        class="mt-1 hidden text-xs text-slate-500 sm:block"
                    >
                        Library Management System
                    </p>

                </div>

            </a>

            <a
                href="{{ route('landing') }}"
                class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#005DAA] hover:text-[#005DAA]"
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

                Kembali

            </a>

        </div>

    </header>

    {{-- Main --}}
    <main
        class="relative flex min-h-screen items-center justify-center overflow-hidden px-5 pb-12 pt-28 sm:px-6 sm:pt-32 lg:px-8"
    >

        {{-- Background --}}
        <div
            class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-[#005DAA]/20 blur-3xl"
        ></div>

        <div
            class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-[#E31E24]/20 blur-3xl"
        ></div>

        <div
            class="absolute right-1/4 top-1/3 h-48 w-48 rounded-full bg-[#00A651]/10 blur-3xl"
        ></div>

        <div class="relative z-10 w-full max-w-2xl">

            {{-- Title --}}
            <div class="mb-8 text-center">

                <div
                    class="mb-5 flex items-center justify-center gap-2"
                >
                    <span
                        class="h-1 w-10 rounded-full bg-[#E31E24]"
                    ></span>

                    <span
                        class="h-1 w-10 rounded-full bg-[#005DAA]"
                    ></span>

                    <span
                        class="h-1 w-10 rounded-full bg-[#00A651]"
                    ></span>
                </div>

                <p
                    class="text-xs font-semibold uppercase tracking-[0.2em] text-[#005DAA] sm:text-sm"
                >
                    Daftar Pengunjung
                </p>

                <h2
                    class="mt-3 text-3xl font-bold leading-tight text-[#102A43] sm:text-4xl"
                >
                    Selamat Datang di

                    <span class="text-[#E31E24]">
                        PAG Library
                    </span>
                </h2>

                <p
                    class="mx-auto mt-4 max-w-xl text-sm leading-6 text-slate-600 sm:text-base"
                >
                    Pengunjung baru harus mendaftar terlebih dahulu.
                    Jika sudah terdaftar, gunakan menu Masuk Pengunjung.
                </p>

            </div>

            {{-- Card --}}
            <div
                class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xl"
            >

                {{-- Tabs --}}
                <div
                    class="grid grid-cols-2 gap-2 border-b border-slate-200 bg-slate-50 p-2"
                >

                    <button
                        type="button"
                        @click="activeForm = 'checkin'"
                        :class="
                            activeForm === 'checkin'
                                ? 'bg-[#005DAA] text-white shadow'
                                : 'bg-transparent text-slate-600 hover:bg-white'
                        "
                        class="rounded-xl px-4 py-3 text-sm font-semibold transition"
                    >
                        Masuk Pengunjung
                    </button>

                    <button
                        type="button"
                        @click="activeForm = 'register'"
                        :class="
                            activeForm === 'register'
                                ? 'bg-[#005DAA] text-white shadow'
                                : 'bg-transparent text-slate-600 hover:bg-white'
                        "
                        class="rounded-xl px-4 py-3 text-sm font-semibold transition"
                    >
                        Pendaftaran Baru
                    </button>

                </div>

                <div class="p-6 sm:p-8 lg:p-10">

                    {{-- Success Register --}}
                    @if (session('register_success'))

                        <div
                            class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
                        >
                            {{ session('register_success') }}
                        </div>

                    @endif

                    {{-- Success Check In --}}
                    @if (session('checkin_success'))

                        <div
                            class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
                        >
                            {{ session('checkin_success') }}
                        </div>

                    @endif


                    {{-- Masuk Pengunjung --}}
                    <div
                        x-show="activeForm === 'checkin'"
                        x-cloak
                    >

                        <div class="mb-6">

                            <h3
                                class="text-xl font-bold text-[#102A43]"
                            >
                                Masuk Pengunjung
                            </h3>

                            <p
                                class="mt-2 text-sm leading-6 text-slate-500"
                            >
                                Masukkan nomor dan nama lengkap yang
                                sama dengan data ketika pertama kali
                                melakukan pendaftaran.
                            </p>

                        </div>

                        @if ($errors->checkin->any())

                            <div
                                class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600"
                            >

                                @foreach ($errors->checkin->all() as $error)
                                    <p>
                                        {{ $error }}
                                    </p>
                                @endforeach

                            </div>

                        @endif

                        <form
                            action="{{ route('visitors.checkin') }}"
                            method="POST"
                            class="space-y-6"
                        >

                            @csrf

                            {{-- Kategori --}}
                            <div>

                                <label
                                    for="checkin_category"
                                    class="mb-2 block text-sm font-semibold text-[#102A43]"
                                >
                                    Kategori Pengunjung
                                    <span class="text-[#E31E24]">*</span>
                                </label>

                                <select
                                    id="checkin_category"
                                    name="checkin_category"
                                    x-model="checkinCategory"
                                    required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition focus:border-[#005DAA] focus:ring-4 focus:ring-[#005DAA]/10"
                                >

                                    <option value="">
                                        Pilih kategori pengunjung
                                    </option>

                                    <option value="pekerja">
                                        Pekerja
                                    </option>

                                    <option value="mahasiswa">
                                        Mahasiswa
                                    </option>

                                    <option value="tamu">
                                        Tamu
                                    </option>

                                    <option value="lainnya">
                                        Lainnya
                                    </option>

                                </select>

                            </div>

                            {{-- Nomor --}}
                            <div>

                                <label
                                    for="checkin_number"
                                    class="mb-2 block text-sm font-semibold text-[#102A43]"
                                >
                                    <span
                                        x-text="getLabel(checkinCategory)"
                                    ></span>

                                    <span class="text-[#E31E24]">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="checkin_number"
                                    name="checkin_number"
                                    value="{{ old('checkin_number') }}"
                                    required
                                    maxlength="100"
                                    autocomplete="off"
                                    :placeholder="getPlaceholder(checkinCategory)"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#005DAA] focus:ring-4 focus:ring-[#005DAA]/10"
                                >

                            </div>

                            {{-- Nama --}}
                            <div>

                                <label
                                    for="checkin_name"
                                    class="mb-2 block text-sm font-semibold text-[#102A43]"
                                >
                                    Nama Lengkap
                                    <span class="text-[#E31E24]">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="checkin_name"
                                    name="checkin_name"
                                    value="{{ old('checkin_name') }}"
                                    required
                                    maxlength="255"
                                    autocomplete="name"
                                    placeholder="Masukkan nama lengkap"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#005DAA] focus:ring-4 focus:ring-[#005DAA]/10"
                                >

                                <p
                                    class="mt-2 text-xs text-slate-500"
                                >
                                    Nama harus sama lengkap dengan
                                    nama yang telah didaftarkan.
                                </p>

                            </div>

                            <button
                                type="submit"
                                class="flex w-full items-center justify-center rounded-xl bg-[#E31E24] px-5 py-3.5 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#c91820] hover:shadow-lg"
                            >
                                Masuk Pengunjung
                            </button>

                        </form>

                    </div>


                    {{-- Pendaftaran Baru --}}
                    <div
                        x-show="activeForm === 'register'"
                        x-cloak
                    >

                        <div class="mb-6">

                            <h3
                                class="text-xl font-bold text-[#102A43]"
                            >
                                Pendaftaran Pengunjung Baru
                            </h3>

                            <p
                                class="mt-2 text-sm leading-6 text-slate-500"
                            >
                                Daftarkan data Anda satu kali.
                                Nomor identitas tidak dapat digunakan
                                oleh pengunjung lain.
                            </p>

                        </div>

                        @if ($errors->register->any())

                            <div
                                class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600"
                            >

                                @foreach ($errors->register->all() as $error)
                                    <p>
                                        {{ $error }}
                                    </p>
                                @endforeach

                            </div>

                        @endif

                        <form
                            action="{{ route('visitors.store') }}"
                            method="POST"
                            class="space-y-6"
                        >

                            @csrf

                            {{-- Kategori --}}
                            <div>

                                <label
                                    for="visitor_category"
                                    class="mb-2 block text-sm font-semibold text-[#102A43]"
                                >
                                    Kategori Pengunjung
                                    <span class="text-[#E31E24]">*</span>
                                </label>

                                <select
                                    id="visitor_category"
                                    name="visitor_category"
                                    x-model="registerCategory"
                                    required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition focus:border-[#005DAA] focus:ring-4 focus:ring-[#005DAA]/10"
                                >

                                    <option value="">
                                        Pilih kategori pengunjung
                                    </option>

                                    <option value="pekerja">
                                        Pekerja
                                    </option>

                                    <option value="mahasiswa">
                                        Mahasiswa
                                    </option>

                                    <option value="tamu">
                                        Tamu
                                    </option>

                                    <option value="lainnya">
                                        Lainnya
                                    </option>

                                </select>

                            </div>

                            {{-- Nama --}}
                            <div>

                                <label
                                    for="visitor_name"
                                    class="mb-2 block text-sm font-semibold text-[#102A43]"
                                >
                                    Nama Lengkap
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
                                    placeholder="Masukkan nama lengkap sesuai identitas"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#005DAA] focus:ring-4 focus:ring-[#005DAA]/10"
                                >

                            </div>

                            {{-- Nomor --}}
                            <div>

                                <label
                                    for="employee_number"
                                    class="mb-2 block text-sm font-semibold text-[#102A43]"
                                >
                                    <span
                                        x-text="getLabel(registerCategory)"
                                    ></span>

                                    <span class="text-[#E31E24]">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="employee_number"
                                    name="employee_number"
                                    value="{{ old('employee_number') }}"
                                    required
                                    maxlength="100"
                                    autocomplete="off"
                                    :placeholder="getPlaceholder(registerCategory)"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-[#005DAA] focus:ring-4 focus:ring-[#005DAA]/10"
                                >

                                <p
                                    class="mt-2 text-xs leading-5 text-slate-500"
                                >
                                    Nomor hanya boleh didaftarkan satu
                                    kali dan tidak boleh sama dengan
                                    pengunjung lainnya.
                                </p>

                            </div>

                            {{-- Info --}}
                            <div
                                class="rounded-xl border border-[#005DAA]/20 bg-[#005DAA]/5 px-4 py-3"
                            >

                                <p
                                    class="text-xs leading-5 text-slate-600"
                                >
                                    Pastikan nama Anda ditulis lengkap.
                                    Untuk kunjungan berikutnya, nama
                                    dan nomor harus sesuai dengan data
                                    pendaftaran ini.
                                </p>

                            </div>

                            <button
                                type="submit"
                                class="flex w-full items-center justify-center rounded-xl bg-[#E31E24] px-5 py-3.5 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#c91820] hover:shadow-lg"
                            >
                                Daftar Pengunjung
                            </button>

                        </form>

                    </div>

                </div>

            </div>

            <p
                class="mt-6 text-center text-xs leading-5 text-slate-500"
            >
                Data pengunjung digunakan untuk pencatatan aktivitas
                kunjungan dan peminjaman buku di PAG Library.
            </p>

        </div>

    </main>

    {{-- Footer --}}
    <footer
        class="bg-[#102A43] px-5 py-6 text-white sm:px-6 sm:py-8 lg:px-8"
    >

        <div
            class="mx-auto flex max-w-7xl flex-col items-center gap-2 text-center sm:flex-row sm:justify-between sm:text-left"
        >

            <div>

                <h3 class="font-bold">
                    PAG Library
                </h3>

                <p
                    class="mt-1 text-xs text-white/60 sm:text-sm"
                >
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