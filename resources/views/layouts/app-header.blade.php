<header
    class="sticky top-0 z-99999 flex w-full min-w-0 overflow-visible border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 xl:border-b"
    x-data="{
        isApplicationMenuOpen: false,
        search: '',
        results: [],
        isSearching: false,
        showResults: false,
        searchTimer: null,

        toggleApplicationMenu() {
            this.isApplicationMenuOpen = !this.isApplicationMenuOpen;
        },

        globalSearch() {
            clearTimeout(this.searchTimer);

            const keyword = this.search.trim();

            if (keyword.length === 0) {
                this.results = [];
                this.showResults = false;
                this.isSearching = false;
                return;
            }

            this.showResults = true;
            this.isSearching = true;

            this.searchTimer = setTimeout(() => {
                fetch('{{ route('books.search') }}?q=' + encodeURIComponent(keyword), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data pencarian.');
                    }

                    return response.json();
                })
                .then(data => {
                    this.results = Array.isArray(data) ? data : [];
                    this.isSearching = false;
                })
                .catch(error => {
                    console.error('Search error:', error);
                    this.results = [];
                    this.isSearching = false;
                });
            }, 300);
        },

        clearSearch() {
            clearTimeout(this.searchTimer);

            this.search = '';
            this.results = [];
            this.isSearching = false;
            this.showResults = false;
        }
    }"
    @click.outside="showResults = false"
>
    <div class="flex grow flex-col items-center justify-between xl:flex-row xl:px-6">

        {{-- Header kiri --}}
        <div class="flex w-full min-w-0 items-center justify-between gap-2 border-b border-gray-200 px-3 py-3 dark:border-gray-800 sm:gap-4 lg:py-4 xl:flex-1 xl:justify-normal xl:border-b-0 xl:px-0">

            {{-- Sidebar --}}
            <button
                type="button"
                class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-500 dark:border-gray-800 dark:text-gray-400 lg:h-11 lg:w-11 xl:flex"
                :class="{ 'bg-gray-100 dark:bg-white/[0.03]': !$store.sidebar.isExpanded }"
                @click="$store.sidebar.toggleExpanded()"
                aria-label="Toggle Sidebar"
            >
                <svg
                    x-show="!$store.sidebar.isMobileOpen"
                    width="16"
                    height="12"
                    viewBox="0 0 16 12"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75H1.33325C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25H14.6666C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75H1.33325C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75H7.99992C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25H1.33325Z"
                        fill="currentColor"
                    />
                </svg>

                <svg
                    x-show="$store.sidebar.isMobileOpen"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M6 6L18 18M6 18L18 6"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                    />
                </svg>
            </button>

            {{-- Sidebar mobile --}}
            <button
                type="button"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-gray-500 dark:text-gray-400 lg:h-11 lg:w-11 xl:hidden"
                :class="{ 'bg-gray-100 dark:bg-white/[0.03]': $store.sidebar.isMobileOpen }"
                @click="$store.sidebar.toggleMobileOpen()"
                aria-label="Toggle Mobile Sidebar"
            >
                <svg
                    x-show="!$store.sidebar.isMobileOpen"
                    width="16"
                    height="12"
                    viewBox="0 0 16 12"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75H1.33325C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25H14.6666C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75H1.33325C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75H7.99992C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25H1.33325Z"
                        fill="currentColor"
                    />
                </svg>

                <svg
                    x-show="$store.sidebar.isMobileOpen"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M6 6L18 18M6 18L18 6"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                    />
                </svg>
            </button>

            {{-- Logo mobile --}}
            <a href="{{ route('dashboard') }}" class="shrink-0 xl:hidden">
                <img
                    class="dark:hidden"
                    src="{{ asset('images/logo/logo-pertamina.svg') }}"
                    alt="PAG Library"
                >
                <img
                    class="hidden dark:block"
                    src="{{ asset('images/logo/logo-pertamina-white.svg') }}"
                    alt="PAG Library"
                >
            </a>

            {{-- Menu aplikasi mobile --}}
            <button
                type="button"
                @click="toggleApplicationMenu()"
                class="z-99999 flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-gray-700 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 xl:hidden"
                aria-label="Application Menu"
            >
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <circle cx="6" cy="12" r="1.5" fill="currentColor" />
                    <circle cx="12" cy="12" r="1.5" fill="currentColor" />
                    <circle cx="18" cy="12" r="1.5" fill="currentColor" />
                </svg>
            </button>

            {{-- Global Search --}}
            <div class="relative hidden xl:block">
                <form @submit.prevent>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2">
                            <svg
                                class="fill-gray-500 dark:fill-gray-400"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z"
                                    fill="currentColor"
                                />
                            </svg>
                        </span>

                        <input
                            type="text"
                            x-model="search"
                            @input="globalSearch()"
                            @focus="if (search.trim().length > 0) showResults = true"
                            @keydown.escape="showResults = false"
                            placeholder="Cari data..."
                            autocomplete="off"
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-12 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 xl:w-[430px]"
                        >

                        <button
                            type="button"
                            x-show="search.length > 0"
                            x-cloak
                            @click="clearSearch()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-gray-700 dark:hover:text-white"
                            title="Hapus pencarian"
                        >
                            <svg
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M6 6L18 18M6 18L18 6"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Hasil pencarian --}}
                <div
                    x-show="showResults"
                    x-cloak
                    class="absolute left-0 top-[52px] z-[100000] w-[430px] overflow-hidden rounded-lg border border-gray-200 bg-white shadow-xl dark:border-gray-800 dark:bg-gray-900"
                >
                    <div
                        x-show="isSearching"
                        class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400"
                    >
                        Mencari data...
                    </div>

                    <template x-if="!isSearching && results.length > 0">
                        <div class="max-h-[420px] overflow-y-auto py-2">
                            <template
                                x-for="(result, index) in results"
                                :key="`${result.type}-${result.title}-${index}`"
                            >
                                <a
                                    :href="result.url"
                                    @click="showResults = false"
                                    class="flex items-start gap-3 px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-white/[0.03]"
                                >
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-white/[0.05] dark:text-gray-300">

                                        {{-- Buku --}}
                                        <template x-if="result.type === 'Buku'">
                                            <svg
                                                width="20"
                                                height="20"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <path
                                                    d="M5 4.5C5 3.67157 5.67157 3 6.5 3H18.5C19.3284 3 20 3.67157 20 4.5V20.5C20 21.3284 19.3284 22 18.5 22H6.5C5.67157 22 5 21.3284 5 20.5V4.5Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                />
                                                <path
                                                    d="M8 7H17M8 11H17M8 15H14"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                />
                                            </svg>
                                        </template>

                                        {{-- Penulis --}}
                                        <template x-if="result.type === 'Penulis'">
                                            <svg
                                                width="20"
                                                height="20"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <path
                                                    d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                />
                                                <circle
                                                    cx="12"
                                                    cy="7"
                                                    r="4"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                />
                                            </svg>
                                        </template>

                                        {{-- Lokasi --}}
                                        <template x-if="result.type === 'Lokasi'">
                                            <svg
                                                width="20"
                                                height="20"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <path
                                                    d="M20 10C20 15 12 21 12 21C12 21 4 15 4 10C4 5.58172 7.58172 2 12 2C16.4183 2 20 5.58172 20 10Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                />
                                                <circle
                                                    cx="12"
                                                    cy="10"
                                                    r="2.5"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                />
                                            </svg>
                                        </template>

                                        {{-- Equipment --}}
                                        <template x-if="result.type === 'Equipment'">
                                            <svg
                                                width="20"
                                                height="20"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <path
                                                    d="M4 7H20V20H4V7Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                />
                                                <path
                                                    d="M8 7V4H16V7M9 12H15"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                />
                                            </svg>
                                        </template>

                                        {{-- Pengunjung --}}
                                        <template x-if="result.type === 'Pengunjung'">
                                            <svg
                                                width="20"
                                                height="20"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <circle
                                                    cx="12"
                                                    cy="8"
                                                    r="4"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                />
                                                <path
                                                    d="M4 21C4 17.6863 7.58172 15 12 15C16.4183 15 20 17.6863 20 21"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                />
                                            </svg>
                                        </template>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <span
                                            class="text-[10px] font-medium uppercase tracking-wide text-brand-500"
                                            x-text="result.type"
                                        ></span>

                                        <p
                                            class="truncate text-sm font-medium text-gray-800 dark:text-white/90"
                                            x-text="result.title"
                                        ></p>

                                        <p
                                            class="mt-1 truncate text-xs text-gray-500 dark:text-gray-400"
                                            x-text="result.description"
                                        ></p>

                                        <p
                                            x-show="result.meta"
                                            class="mt-1 truncate text-[11px] text-gray-400 dark:text-gray-500"
                                            x-text="result.meta"
                                        ></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>

                    <div
                        x-show="!isSearching && search.trim().length > 0 && results.length === 0"
                        class="px-4 py-5 text-center"
                    >
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Data tidak ditemukan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Header kanan --}}
        <div
            class="w-full shrink-0 items-center justify-between gap-4 px-5 py-4 shadow-theme-md xl:w-auto xl:justify-end xl:px-0 xl:py-0 xl:shadow-none"
            :class="isApplicationMenuOpen ? 'flex' : 'hidden xl:flex'"
        >
            <div class="flex items-center gap-2 2xsm:gap-3">

                {{-- Light / Dark Mode --}}
                <button
                    type="button"
                    class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-gray-100 hover:text-dark-900 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                    @click="$store.theme.toggle()"
                    aria-label="Toggle Theme"
                    title="Ubah tema"
                >
                    {{-- Light --}}
                    <svg
                        class="hidden dark:block"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <path
                            d="M10 1.5V4M10 16V18.5M18.5 10H16M4 10H1.5M16 4L14.5 5.5M5.5 14.5L4 16M16 16L14.5 14.5M5.5 5.5L4 4"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                        />
                        <circle
                            cx="10"
                            cy="10"
                            r="3.5"
                            stroke="currentColor"
                            stroke-width="1.5"
                        />
                    </svg>

                    {{-- Dark --}}
                    <svg
                        class="dark:hidden"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <path
                            d="M17.45 11.97C16.7 12.3 15.87 12.5 15 12.5C11.69 12.5 9 9.81 9 6.5C9 5.63 9.2 4.8 9.53 4.05C5.76 4.29 2.75 7.43 2.75 11.25C2.75 15.23 5.97 18.25 9.75 18.25C13.57 18.25 16.71 15.24 17.45 11.97Z"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </button>

                {{-- Administrator --}}
                <div class="shrink-0">
                    <x-header.user-dropdown />
                </div>
            </div>
        </div>
    </div>
</header>