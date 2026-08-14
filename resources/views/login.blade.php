<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Login - PAG Library</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-black font-sans">

    <div class="relative flex min-h-screen items-center justify-center overflow-hidden">

        {{-- Background --}}
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('/images/library-pag.png');">
        </div>

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/65"></div>

        {{-- Red Overlay --}}
        <div
            class="absolute inset-0 bg-gradient-to-br from-red-950/70 via-transparent to-orange-950/40">
        </div>


        {{-- Login Card --}}
        <div
            class="relative z-10 w-full max-w-md px-6">

            <div
                class="rounded-3xl border border-white/20 bg-black/35 p-8 shadow-2xl backdrop-blur-xl sm:p-10">

                {{-- Logo / Brand --}}
                <div class="mb-8 text-center">

                    <div
                        class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/20 bg-white/10">

                        <span class="text-2xl font-bold text-white">
                            PAG
                        </span>

                    </div>

                    <h1
                        class="text-3xl font-semibold tracking-tight text-white">
                        Selamat Datang
                    </h1>

                    <p class="mt-2 text-sm text-white/60">
                        Masuk ke PAG Library Management System
                    </p>

                </div>


                {{-- Error --}}
                @if ($errors->any())

                    <div
                        class="mb-5 rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">

                        {{ $errors->first() }}

                    </div>

                @endif


                {{-- Login Form --}}
                <form
                    method="POST"
                    action="{{ route('login.process') }}"
                    class="space-y-5">

                    @csrf


                    {{-- Email --}}
                    <div>

                        <label
                            for="email"
                            class="mb-2 block text-sm font-medium text-white/80">

                            Email

                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="Masukkan email Anda"
                            class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3.5 text-sm text-white outline-none placeholder:text-white/35 transition focus:border-red-400/60 focus:bg-white/15 focus:ring-2 focus:ring-red-400/20">

                    </div>


                    {{-- Password --}}
                    <div>

                        <label
                            for="password"
                            class="mb-2 block text-sm font-medium text-white/80">

                            Password

                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password Anda"
                            class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3.5 text-sm text-white outline-none placeholder:text-white/35 transition focus:border-red-400/60 focus:bg-white/15 focus:ring-2 focus:ring-red-400/20">

                    </div>


                    {{-- Login Button --}}
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-red-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-red-900/30 transition hover:bg-red-500 hover:shadow-red-900/50 active:scale-[0.98]">

                        Masuk

                    </button>

                </form>


                {{-- Footer --}}
                <div class="mt-8 text-center">

                    <p class="text-xs text-white/40">
                        PAG Library Management System
                    </p>

                    <p class="mt-1 text-xs text-white/30">
                        Perta Arun Gas
                    </p>

                </div>

            </div>

        </div>

    </div>

</body>

</html>