@php
    $user = auth()->user();

    $user?->loadMissing('role');

    $roleName = $user?->role?->role_name
        ? ucfirst($user->role->role_name)
        : 'Administrator';

    $initial = strtoupper(
        substr($user?->name ?? 'A', 0, 1)
    );
@endphp

<div
    class="relative"
    x-data="{
        dropdownOpen: false,

        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
        },

        closeDropdown() {
            this.dropdownOpen = false;
        }
    }"
    @click.away="closeDropdown()"
>

    {{-- User Button --}}
    <button
        class="flex items-center text-gray-700 dark:text-gray-400"
        @click.prevent="toggleDropdown()"
        type="button"
    >

        {{-- Avatar --}}
        <span
            class="mr-3 h-11 w-11 shrink-0 overflow-hidden rounded-full border border-gray-200 bg-brand-500 dark:border-gray-700"
        >

            @if ($user?->profile_photo)

                <img
                    src="{{ route('profile.photo') }}?v={{ $user->updated_at?->timestamp }}"
                    alt="Foto {{ $user->name }}"
                    class="h-full w-full object-cover"
                >

            @else

                <span
                    class="flex h-full w-full items-center justify-center text-sm font-semibold uppercase text-white"
                >
                    {{ $initial }}
                </span>

            @endif

        </span>

        {{-- Name --}}
        <span
            class="mr-1 hidden max-w-[150px] truncate font-medium text-theme-sm sm:block"
        >
            {{ $user?->name ?? 'Administrator' }}
        </span>

        {{-- Dropdown Arrow --}}
        <svg
            class="h-5 w-5 transition-transform duration-200"
            :class="{ 'rotate-180': dropdownOpen }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
            />
        </svg>

    </button>

    {{-- Dropdown --}}
    <div
        x-show="dropdownOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-50 mt-[17px] flex w-[280px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark"
    >

        {{-- User Info --}}
        <div
            class="flex items-center gap-3 border-b border-gray-100 px-2 pb-3 dark:border-gray-800"
        >

            {{-- Dropdown Avatar --}}
            <div
                class="h-11 w-11 shrink-0 overflow-hidden rounded-full border border-gray-200 bg-brand-500 dark:border-gray-700"
            >

                @if ($user?->profile_photo)

                    <img
                        src="{{ route('profile.photo') }}?v={{ $user->updated_at?->timestamp }}"
                        alt="Foto {{ $user->name }}"
                        class="h-full w-full object-cover"
                    >

                @else

                    <div
                        class="flex h-full w-full items-center justify-center text-sm font-semibold uppercase text-white"
                    >
                        {{ $initial }}
                    </div>

                @endif

            </div>

            <div class="min-w-0">

                <span
                    class="block truncate font-medium text-gray-700 text-theme-sm dark:text-gray-300"
                >
                    {{ $user?->name ?? 'Administrator' }}
                </span>

                <span
                    class="mt-0.5 block text-theme-xs text-gray-500 dark:text-gray-400"
                >
                    {{ $roleName }}
                </span>

                @if ($user?->email)

                    <span
                        class="mt-0.5 block max-w-[185px] truncate text-theme-xs text-gray-400 dark:text-gray-500"
                    >
                        {{ $user->email }}
                    </span>

                @endif

            </div>

        </div>

        {{-- Menu --}}
        <ul
            class="flex flex-col gap-1 border-b border-gray-200 py-3 dark:border-gray-800"
        >

            {{-- Profile --}}
            <li>

                <a
                    href="{{ route('profile') }}"
                    class="group flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 text-theme-sm transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                    @click="closeDropdown()"
                >

                    <span
                        class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300"
                    >

                        <svg
                            width="22"
                            height="22"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                                fill="currentColor"
                            />
                        </svg>

                    </span>

                    Profil Administrator
                </a>

            </li>

        </ul>

        {{-- Sign Out --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <button
                type="submit"
                class="group mt-3 flex w-full items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 text-theme-sm transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                @click="closeDropdown()"
            >

                <span
                    class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        />
                    </svg>

                </span>

                Keluar
            </button>

        </form>

    </div>

</div>