{{-- Form Daftar Pengunjung --}}
<section
    id="visitor"
    class="relative overflow-hidden bg-[#005DAA] px-5 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24"
>

    <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full bg-[#E31E24]/30 blur-3xl sm:h-72 sm:w-72"></div>

    <div class="absolute -bottom-20 -left-20 h-56 w-56 rounded-full bg-[#00A651]/30 blur-3xl sm:h-72 sm:w-72"></div>

    <div class="relative mx-auto max-w-2xl">

        <div class="mb-8 text-center text-white">

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/70 sm:text-sm">
                Daftar Pengunjung
            </p>

            <h2 class="mt-3 text-3xl font-bold sm:text-4xl">
                Selamat Datang di PAG Library
            </h2>

            <p class="mx-auto mt-4 max-w-xl text-sm leading-6 text-white/80 sm:text-base sm:leading-7">
                Silakan masukkan data diri Anda sebelum menggunakan
                fasilitas perpustakaan.
            </p>

        </div>

        <div class="rounded-2xl bg-white p-6 shadow-2xl sm:p-8">

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->visitor->any())
                <div class="mb-6 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-600">
                    {{ $errors->visitor->first() }}
                </div>
            @endif

            <form
                action="{{ route('visitors.store') }}"
                method="POST"
                class="space-y-5"
            >
                @csrf

                {{-- Nama Pengunjung --}}
                <div>

                    <label
                        for="visitor_name"
                        class="mb-2 block text-sm font-semibold text-[#102A43]"
                    >
                        Nama Pengunjung
                        <span class="text-red-500">*</span>
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
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-[#005DAA] focus:ring-2 focus:ring-[#005DAA]/20"
                    >

                    @error('visitor_name')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- No. Pekerja --}}
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
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-[#005DAA] focus:ring-2 focus:ring-[#005DAA]/20"
                    >

                    @error('employee_number')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full rounded-xl bg-[#E31E24] px-5 py-3.5 text-sm font-semibold text-white shadow-md transition hover:bg-[#c91820] hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#E31E24]/30"
                >
                    Masukkan
                </button>

            </form>

        </div>

    </div>

</section>