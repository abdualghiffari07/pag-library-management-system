<div
    x-data="{
        photoOpen: {{ $errors->has('profile_photo') ? 'true' : 'false' }},
        previewUrl: null,

        previewPhoto(event) {
            const file = event.target.files[0];

            if (!file) {
                this.previewUrl = null;
                return;
            }

            if (this.previewUrl) {
                URL.revokeObjectURL(this.previewUrl);
            }

            this.previewUrl = URL.createObjectURL(file);
        },

        closePhotoModal() {
            this.photoOpen = false;

            if (this.previewUrl) {
                URL.revokeObjectURL(this.previewUrl);
                this.previewUrl = null;
            }

            const input = this.$refs.photoInput;

            if (input) {
                input.value = '';
            }
        }
    }"
>

    {{-- Profile Card --}}
    <div
        class="mb-6 rounded-2xl border border-gray-200 p-5 dark:border-gray-800 lg:p-6"
    >

        <div
            class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between"
        >

            {{-- Profile --}}
            <div
                class="flex w-full flex-col items-center gap-5 sm:flex-row"
            >

                {{-- Avatar --}}
                <div class="relative shrink-0">

                    <div
                        class="h-20 w-20 overflow-hidden rounded-full border border-gray-200 bg-brand-500 shadow-theme-xs dark:border-gray-800"
                    >

                        @if ($user->profile_photo)

                            <img
                                src="{{ route('profile.photo') }}?v={{ $user->updated_at?->timestamp }}"
                                alt="Foto {{ $user->name }}"
                                class="h-full w-full object-cover"
                            >

                        @else

                            <div
                                class="flex h-full w-full items-center justify-center text-2xl font-bold uppercase text-white"
                            >
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                        @endif

                    </div>

                    {{-- Edit Photo Button --}}
                    <button
                        type="button"
                        @click="photoOpen = true"
                        class="absolute bottom-0 right-0 flex h-7 w-7 items-center justify-center rounded-full border-2 border-white bg-brand-500 text-white shadow-theme-xs transition hover:bg-brand-600 dark:border-gray-900"
                        title="Ubah foto profile"
                    >
                        <svg
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M14.5 4.5 19.5 9.5"
                            />

                            <path
                                d="M3 21l3.5-.7L19.4 7.4a2.1 2.1 0 0 0-3-3L3.5 17.3 3 21Z"
                            />
                        </svg>
                    </button>

                </div>

                {{-- User Information --}}
                <div
                    class="min-w-0 text-center sm:text-left"
                >

                    <h4
                        class="text-lg font-semibold text-gray-800 dark:text-white/90"
                    >
                        {{ $user->name }}
                    </h4>

                    <div
                        class="mt-2 flex flex-col items-center gap-1 sm:flex-row sm:gap-3"
                    >

                        <p
                            class="text-sm text-gray-500 dark:text-gray-400"
                        >
                            {{ $user->function_name ?: 'Administrator PAG Library' }}
                        </p>

                        <div
                            class="hidden h-3.5 w-px bg-gray-300 sm:block dark:bg-gray-700"
                        ></div>

                        <p
                            class="max-w-full truncate text-sm text-gray-500 dark:text-gray-400"
                        >
                            {{ $user->email }}
                        </p>

                    </div>

                    <button
                        type="button"
                        @click="photoOpen = true"
                        class="mt-3 inline-flex items-center gap-1.5 text-xs font-medium text-brand-500 transition hover:text-brand-600"
                    >
                        <svg
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                d="M14.5 4.5 19.5 9.5"
                            />

                            <path
                                d="M3 21l3.5-.7L19.4 7.4a2.1 2.1 0 0 0-3-3L3.5 17.3 3 21Z"
                            />
                        </svg>

                        {{ $user->profile_photo ? 'Ganti Foto' : 'Tambah Foto' }}
                    </button>

                </div>

            </div>

            {{-- Account Information --}}
            <div
                class="flex flex-wrap items-center justify-center gap-2 xl:justify-end"
            >

                {{-- Role --}}
                <span
                    class="rounded-full bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-600 dark:bg-brand-500/10 dark:text-brand-400"
                >
                    {{ $user->role?->role_name ?? 'Administrator' }}
                </span>

                {{-- Status --}}
                @if ($user->is_active)

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-600 dark:bg-green-500/10 dark:text-green-400"
                    >
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-green-500"
                        ></span>

                        Aktif
                    </span>

                @else

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 dark:bg-red-500/10 dark:text-red-400"
                    >
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-red-500"
                        ></span>

                        Tidak Aktif
                    </span>

                @endif

            </div>

        </div>

    </div>

    {{-- Photo Modal --}}
    <div
        x-show="photoOpen"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        @keydown.escape.window="closePhotoModal()"
    >

        <div
            x-show="photoOpen"
            x-transition
            @click.outside="closePhotoModal()"
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-900"
        >

            {{-- Modal Header --}}
            <div
                class="flex items-start justify-between gap-4"
            >

                <div>

                    <h3
                        class="text-lg font-semibold text-gray-800 dark:text-white/90"
                    >
                        Foto Profile
                    </h3>

                    <p
                        class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                    >
                        Gunakan foto Administrator yang jelas dan profesional.
                    </p>

                </div>

                <button
                    type="button"
                    @click="closePhotoModal()"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/[0.05]"
                >
                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            d="M18 6 6 18M6 6l12 12"
                        />
                    </svg>
                </button>

            </div>

            {{-- Preview --}}
            <div
                class="mt-6 flex justify-center"
            >

                <div
                    class="h-28 w-28 overflow-hidden rounded-full border-4 border-gray-100 bg-brand-500 shadow-theme-xs dark:border-gray-800"
                >

                    {{-- Preview foto baru --}}
                    <template x-if="previewUrl">
                        <img
                            :src="previewUrl"
                            alt="Preview foto profile"
                            class="h-full w-full object-cover"
                        >
                    </template>

                    {{-- Foto saat ini --}}
                    <template x-if="!previewUrl">
                        <div class="h-full w-full">

                            @if ($user->profile_photo)

                                <img
                                    src="{{ route('profile.photo') }}?v={{ $user->updated_at?->timestamp }}"
                                    alt="Foto {{ $user->name }}"
                                    class="h-full w-full object-cover"
                                >

                            @else

                                <div
                                    class="flex h-full w-full items-center justify-center text-3xl font-bold uppercase text-white"
                                >
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>

                            @endif

                        </div>
                    </template>

                </div>

            </div>

            {{-- Upload Form --}}
            <form
                action="{{ route('profile.photo.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-6"
            >
                @csrf

                <div>

                    <label
                        for="profile_photo"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Pilih Foto
                    </label>

                    <input
                        x-ref="photoInput"
                        id="profile_photo"
                        name="profile_photo"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        required
                        @change="previewPhoto($event)"
                        class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-3 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:file:bg-gray-800 dark:file:text-gray-300"
                    >

                    <p
                        class="mt-2 text-xs leading-5 text-gray-500 dark:text-gray-400"
                    >
                        Format JPG, JPEG, PNG atau WEBP.
                        Ukuran maksimal 5 MB.
                    </p>

                    @error('profile_photo')

                        <p
                            class="mt-2 text-xs font-medium text-red-500"
                        >
                            {{ $message }}
                        </p>

                    @enderror

                </div>

                {{-- Buttons --}}
                <div
                    class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"
                >

                    <button
                        type="button"
                        @click="closePhotoModal()"
                        class="flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.05]"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="flex justify-center rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600"
                    >
                        Simpan Foto
                    </button>

                </div>

            </form>

            {{-- Delete Photo --}}
            @if ($user->profile_photo)

                <div
                    class="mt-5 border-t border-gray-100 pt-5 dark:border-gray-800"
                >

                    <form
                        action="{{ route('profile.photo.delete') }}"
                        method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profile?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-100 dark:border-red-900/50 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20"
                        >
                            <svg
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    d="M3 6h18"
                                />

                                <path
                                    d="M8 6V4h8v2"
                                />

                                <path
                                    d="M19 6l-1 14H6L5 6"
                                />

                                <path
                                    d="M10 11v5M14 11v5"
                                />
                            </svg>

                            Hapus Foto Profile
                        </button>

                    </form>

                </div>

            @endif

        </div>

    </div>

</div>