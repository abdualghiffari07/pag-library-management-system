<div
    x-data="{ openPassword: {{ $errors->passwordUpdate->any() ? 'true' : 'false' }} }"
    class="rounded-2xl border border-gray-200 p-5 dark:border-gray-800 lg:p-6"
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
                        Keamanan Akun
                    </h4>

                    <p
                        class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                    >
                        Kelola password untuk menjaga keamanan akun Administrator.
                    </p>

                </div>

                <button
                    x-show="!openPassword"
                    @click="openPassword = true"
                    type="button"
                    class="edit-button"
                >
                    Ubah Password
                </button>

            </div>

            <div
                x-show="!openPassword"
                x-cloak
                class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-white/[0.02]"
            >

                <div
                    class="flex items-center gap-3"
                >

                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-50 text-brand-500 dark:bg-brand-500/10"
                    >
                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <rect
                                x="5"
                                y="11"
                                width="14"
                                height="9"
                                rx="2"
                            />

                            <path
                                d="M8 11V8a4 4 0 0 1 8 0v3"
                            />
                        </svg>
                    </div>

                    <div>
                        <p
                            class="text-sm font-medium text-gray-800 dark:text-white/90"
                        >
                            Password akun
                        </p>

                        <p
                            class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                        >
                            Gunakan minimal 8 karakter dan jangan bagikan password kepada orang lain.
                        </p>
                    </div>

                </div>

            </div>

            <form
                x-show="openPassword"
                x-cloak
                action="{{ route('profile.password.update') }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <div class="space-y-5">

                    <div>
                        <label
                            for="current_password"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                        >
                            Password Saat Ini
                        </label>

                        <input
                            id="current_password"
                            name="current_password"
                            type="password"
                            autocomplete="current-password"
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                        >

                        @error('current_password', 'passwordUpdate')
                            <p
                                class="mt-1.5 text-xs text-red-500"
                            >
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div
                        class="grid grid-cols-1 gap-5 md:grid-cols-2"
                    >

                        <div>
                            <label
                                for="password"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                            >
                                Password Baru
                            </label>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                            >

                            @error('password', 'passwordUpdate')
                                <p
                                    class="mt-1.5 text-xs text-red-500"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="password_confirmation"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                            >
                                Konfirmasi Password
                            </label>

                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                            >
                        </div>

                    </div>

                </div>

                <div
                    class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end"
                >

                    <button
                        @click="openPassword = false"
                        type="button"
                        class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="flex justify-center rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600"
                    >
                        Simpan Password
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>