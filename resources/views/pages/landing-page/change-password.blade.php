<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#102A43">

    <title>Ubah Password - PAG Library</title>

    @vite([
        'resources/css/app.css',
        'resources/css/background-slider.css',
        'resources/js/app.js',
        'resources/js/background-slider.js'
    ])
</head>

<body class="min-h-screen bg-gray-950">

    {{-- Background --}}
    <x-background-slider />

    <div class="relative z-10 flex min-h-screen flex-col">

        {{-- Header --}}
        <header class="border-b border-white/10 bg-black/20 backdrop-blur-md">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-4 px-5 py-4 sm:px-6 lg:px-8">

                {{-- Brand --}}
                <a
                    href="{{ route('worker.dashboard') }}"
                    class="flex items-center gap-3"
                >
                    <img
                        src="{{ asset('images/landing/logo-pertamina.png') }}"
                        alt="Logo Pertamina"
                        class="h-10 w-auto"
                    >

                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold tracking-wide text-white">
                            PERTA ARUN GAS
                        </p>

                        <p class="text-[11px] tracking-wider text-white/60">
                            LIBRARY MANAGEMENT SYSTEM
                        </p>
                    </div>
                </a>

                {{-- Navigation --}}
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('worker.dashboard') }}"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/10 px-4 text-sm font-medium text-white transition hover:bg-white/20"
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
                                d="M15 18l-6-6 6-6"
                            />
                        </svg>

                        Kembali
                    </a>

                    <form
                        action="{{ route('logout') }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex h-10 items-center justify-center rounded-lg border border-white/20 bg-white/10 px-4 text-sm font-medium text-white transition hover:bg-white/20"
                        >
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Main --}}
        <main class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6">
            <div class="w-full max-w-md">

                {{-- Heading --}}
                <div class="mb-6 text-center">
                    <span class="inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold tracking-wide text-white/80 backdrop-blur-md">
                        PAG LIBRARY
                    </span>

                    <h1 class="mt-4 text-2xl font-bold text-white sm:text-3xl">
                        Ubah Password
                    </h1>

                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-white/65">
                        Perbarui password akun Anda jika diperlukan untuk menjaga keamanan akun.
                    </p>
                </div>

                {{-- Card --}}
                <div class="overflow-hidden rounded-2xl border border-white/15 bg-white/95 shadow-2xl backdrop-blur-xl">

                    {{-- Account --}}
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-5">
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400">
                            Akun Pekerja
                        </p>

                        <div class="mt-3 flex items-center gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-50 text-sm font-bold text-brand-600">
                                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-800">
                                    {{ $user->name }}
                                </p>

                                <p class="mt-0.5 truncate text-xs text-gray-500">
                                    {{ $user->email }}
                                </p>

                                <p class="mt-0.5 text-xs text-gray-400">
                                    No. Pekerja: {{ $user->nopek }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">

                        {{-- Success --}}
                        @if (session('success'))
                            <div class="mb-5 rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-600">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Error --}}
                        @if ($errors->any())
                            <div class="mb-5 rounded-xl border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-600">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        {{-- Info --}}
                        <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50 p-4">
                            <div class="flex gap-3">
                                <div class="mt-0.5 shrink-0 text-blue-600">
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
                                            d="M13 16h-1v-4h-1m1-4h.01M12 3a9 9 0 110 18 9 9 0 010-18z"
                                        />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-blue-700">
                                        Informasi Password
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-blue-600">
                                        Penggantian password tidak wajib. Anda dapat tetap menggunakan password saat ini atau menggantinya kapan saja melalui halaman ini.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Form --}}
                        <form
                            action="{{ route('worker.password.update') }}"
                            method="POST"
                            class="space-y-5"
                        >
                            @csrf

                            {{-- Current Password --}}
                            <div>
                                <label
                                    for="current_password"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Password Saat Ini
                                    <span class="text-error-500">*</span>
                                </label>

                                <div class="relative">
                                    <input
                                        type="password"
                                        id="current_password"
                                        name="current_password"
                                        autocomplete="current-password"
                                        required
                                        autofocus
                                        placeholder="Masukkan password saat ini"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 pr-11 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10"
                                    >

                                    <button
                                        type="button"
                                        data-password-toggle="current_password"
                                        class="absolute right-0 top-0 flex h-11 w-11 items-center justify-center text-gray-400 transition hover:text-gray-600"
                                        aria-label="Tampilkan password"
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
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.269 2.943 9.542 7-1.273 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                @error('current_password')
                                    <p class="mt-1.5 text-xs text-error-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div>
                                <label
                                    for="password"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Password Baru
                                    <span class="text-error-500">*</span>
                                </label>

                                <div class="relative">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        autocomplete="new-password"
                                        minlength="8"
                                        required
                                        placeholder="Minimal 8 karakter"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 pr-11 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10"
                                    >

                                    <button
                                        type="button"
                                        data-password-toggle="password"
                                        class="absolute right-0 top-0 flex h-11 w-11 items-center justify-center text-gray-400 transition hover:text-gray-600"
                                        aria-label="Tampilkan password"
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
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.269 2.943 9.542 7-1.273 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <p class="mt-1.5 text-xs leading-5 text-gray-400">
                                    Gunakan minimal 8 karakter.
                                </p>

                                @error('password')
                                    <p class="mt-1.5 text-xs text-error-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label
                                    for="password_confirmation"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Konfirmasi Password Baru
                                    <span class="text-error-500">*</span>
                                </label>

                                <div class="relative">
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        autocomplete="new-password"
                                        minlength="8"
                                        required
                                        placeholder="Ulangi password baru"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 pr-11 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10"
                                    >

                                    <button
                                        type="button"
                                        data-password-toggle="password_confirmation"
                                        class="absolute right-0 top-0 flex h-11 w-11 items-center justify-center text-gray-400 transition hover:text-gray-600"
                                        aria-label="Tampilkan password"
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
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.269 2.943 9.542 7-1.273 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col gap-3 pt-1 sm:flex-row">
                                <a
                                    href="{{ route('worker.dashboard') }}"
                                    class="inline-flex h-11 flex-1 items-center justify-center rounded-lg border border-gray-300 bg-white px-5 text-sm font-medium text-gray-600 transition hover:bg-gray-50"
                                >
                                    Batal
                                </a>

                                <button
                                    type="submit"
                                    class="inline-flex h-11 flex-1 items-center justify-center rounded-lg bg-brand-500 px-5 text-sm font-semibold text-white transition hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500/30"
                                >
                                    Simpan Password
                                </button>
                            </div>
                        </form>

                        {{-- Security --}}
                        <div class="mt-5 border-t border-gray-100 pt-5">
                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs font-semibold text-gray-600">
                                    Keamanan akun
                                </p>

                                <p class="mt-1 text-xs leading-5 text-gray-500">
                                    Jangan membagikan password kepada orang lain. Gunakan password yang hanya Anda ketahui.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <p class="mt-6 text-center text-xs text-white/50">
                    © {{ date('Y') }} PAG Library · Perta Arun Gas
                </p>
            </div>
        </main>
    </div>

    <script>
        document.querySelectorAll('[data-password-toggle]')
            .forEach((button) => {
                button.addEventListener('click', () => {
                    const targetId =
                        button.getAttribute('data-password-toggle');

                    const input =
                        document.getElementById(targetId);

                    if (!input) {
                        return;
                    }

                    const isPassword =
                        input.type === 'password';

                    input.type =
                        isPassword ? 'text' : 'password';

                    button.setAttribute(
                        'aria-label',
                        isPassword
                            ? 'Sembunyikan password'
                            : 'Tampilkan password'
                    );
                });
            });
    </script>

</body>

</html>