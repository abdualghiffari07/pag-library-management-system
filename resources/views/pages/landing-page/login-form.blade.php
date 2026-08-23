{{-- Login Modal --}}
<div id="login-modal" class="hidden">

    <div id="login-panel">

        <div class="mb-6 text-center">

            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center overflow-hidden rounded-xl bg-[#005DAA]">

                <img
                    src="{{ asset('images/landing/logo-pertamina.png') }}"
                    alt="PAG Library"
                    class="h-full w-full object-contain p-2"
                >

            </div>

            <h2 class="text-2xl font-bold text-[#102A43]">
                Login
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Masuk ke PAG Library
            </p>

        </div>

        <form
            method="POST"
            action="{{ route('login.process') }}"
            class="space-y-5"
        >

            @csrf

            {{-- Email --}}
            <div>

                <label
                    for="email"
                    class="mb-2 block text-sm font-semibold text-[#102A43]"
                >
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="Masukkan email"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-[#005DAA] focus:ring-2 focus:ring-[#005DAA]/20"
                >

            </div>

            {{-- Password --}}
            <div>

                <label
                    for="password"
                    class="mb-2 block text-sm font-semibold text-[#102A43]"
                >
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-[#005DAA] focus:ring-2 focus:ring-[#005DAA]/20"
                >

            </div>

            {{-- Login Error --}}
            @if ($errors->login->any())
                <div class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-600">
                    {{ $errors->login->first() }}
                </div>
            @endif

            {{-- Submit --}}
            <button
                type="submit"
                class="w-full rounded-xl bg-[#E31E24] px-5 py-3.5 text-sm font-semibold text-white shadow-md transition hover:bg-[#c91820] hover:shadow-lg"
            >
                Login / Masuk
            </button>

        </form>

        {{-- Close --}}
        <button
            id="close-login"
            type="button"
            class="mt-5 w-full text-center text-sm font-medium text-slate-500 transition hover:text-[#E31E24]"
        >
            Kembali ke Home
        </button>

    </div>

</div>