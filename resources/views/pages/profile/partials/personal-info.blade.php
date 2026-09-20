<div
    x-data="{ editing: {{ $errors->hasAny(['name', 'email', 'nopek', 'function_name']) ? 'true' : 'false' }} }"
    class="mb-6 rounded-2xl border border-gray-200 p-5 dark:border-gray-800 lg:p-6"
>

    <div
        class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between"
    >

        <div class="w-full">

            <div
                class="mb-6 flex items-center justify-between gap-4"
            >

                <div>
                    <h4
                        class="text-lg font-semibold text-gray-800 dark:text-white/90"
                    >
                        Informasi Administrator
                    </h4>

                    <p
                        class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                    >
                        Informasi akun Administrator yang digunakan pada PAG Library.
                    </p>
                </div>

                <button
                    x-show="!editing"
                    @click="editing = true"
                    type="button"
                    class="edit-button"
                >
                    <svg
                        class="fill-current"
                        width="18"
                        height="18"
                        viewBox="0 0 18 18"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206Z"
                        />
                    </svg>

                    Edit
                </button>

            </div>

            {{-- View --}}
            <div
                x-show="!editing"
                x-cloak
                class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4"
            >

                <div>
                    <p
                        class="mb-2 text-xs text-gray-500 dark:text-gray-400"
                    >
                        Nama Lengkap
                    </p>

                    <p
                        class="text-sm font-medium text-gray-800 dark:text-white/90"
                    >
                        {{ $user->name }}
                    </p>
                </div>

                <div>
                    <p
                        class="mb-2 text-xs text-gray-500 dark:text-gray-400"
                    >
                        Email
                    </p>

                    <p
                        class="break-all text-sm font-medium text-gray-800 dark:text-white/90"
                    >
                        {{ $user->email }}
                    </p>
                </div>

                <div>
                    <p
                        class="mb-2 text-xs text-gray-500 dark:text-gray-400"
                    >
                        No. Pekerja
                    </p>

                    <p
                        class="text-sm font-medium text-gray-800 dark:text-white/90"
                    >
                        {{ $user->nopek ?: '-' }}
                    </p>
                </div>

                <div>
                    <p
                        class="mb-2 text-xs text-gray-500 dark:text-gray-400"
                    >
                        Jabatan / Fungsi
                    </p>

                    <p
                        class="text-sm font-medium text-gray-800 dark:text-white/90"
                    >
                        {{ $user->function_name ?: '-' }}
                    </p>
                </div>

                <div>
                    <p
                        class="mb-2 text-xs text-gray-500 dark:text-gray-400"
                    >
                        Role
                    </p>

                    <p
                        class="text-sm font-medium text-gray-800 dark:text-white/90"
                    >
                        {{ $user->role?->role_name ?? 'Administrator' }}
                    </p>
                </div>

                <div>
                    <p
                        class="mb-2 text-xs text-gray-500 dark:text-gray-400"
                    >
                        Status
                    </p>

                    <p
                        class="text-sm font-medium text-gray-800 dark:text-white/90"
                    >
                        {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </p>
                </div>

                <div>
                    <p
                        class="mb-2 text-xs text-gray-500 dark:text-gray-400"
                    >
                        Dibuat
                    </p>

                    <p
                        class="text-sm font-medium text-gray-800 dark:text-white/90"
                    >
                        {{ $user->created_at?->format('d/m/Y') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p
                        class="mb-2 text-xs text-gray-500 dark:text-gray-400"
                    >
                        Terakhir Diperbarui
                    </p>

                    <p
                        class="text-sm font-medium text-gray-800 dark:text-white/90"
                    >
                        {{ $user->updated_at?->format('d/m/Y H:i') ?? '-' }}
                    </p>
                </div>

            </div>

            {{-- Edit --}}
            <form
                x-show="editing"
                x-cloak
                action="{{ route('profile.update') }}"
                method="POST"
            >
                @csrf
                @method('PATCH')

                <div
                    class="grid grid-cols-1 gap-5 md:grid-cols-2"
                >

                    <div>
                        <label
                            for="name"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                        >
                            Nama Lengkap
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                        >

                        @error('name')
                            <p
                                class="mt-1.5 text-xs text-red-500"
                            >
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="email"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                        >
                            Email
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                        >

                        @error('email')
                            <p
                                class="mt-1.5 text-xs text-red-500"
                            >
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="nopek"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                        >
                            No. Pekerja
                        </label>

                        <input
                            id="nopek"
                            name="nopek"
                            type="text"
                            value="{{ old('nopek', $user->nopek) }}"
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                        >

                        @error('nopek')
                            <p
                                class="mt-1.5 text-xs text-red-500"
                            >
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="function_name"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                        >
                            Jabatan / Fungsi
                        </label>

                        <input
                            id="function_name"
                            name="function_name"
                            type="text"
                            value="{{ old('function_name', $user->function_name) }}"
                            placeholder="Contoh: Administrator Library"
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                        >

                        @error('function_name')
                            <p
                                class="mt-1.5 text-xs text-red-500"
                            >
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                <div
                    class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end"
                >

                    <button
                        @click="editing = false"
                        type="button"
                        class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="flex justify-center rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>