<header
    class="sticky top-0 flex w-full bg-white border-gray-200 z-99999 dark:border-gray-800 dark:bg-gray-900 xl:border-b"
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

    searchBooks() {
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
    @click.outside="showResults = false"
>
    <div class="flex flex-col items-center justify-between grow xl:flex-row xl:px-6">
        <div
            class="flex items-center justify-between w-full gap-2 px-3 py-3 border-b border-gray-200 dark:border-gray-800 sm:gap-4 xl:justify-normal xl:border-b-0 xl:px-0 lg:py-4"
        >

            {{-- Sidebar Toggle --}}
            <button
                class="hidden xl:flex items-center justify-center w-10 h-10 text-gray-500 border border-gray-200 rounded-lg dark:border-gray-800 dark:text-gray-400 lg:h-11 lg:w-11"
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
                        d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25H14.6666C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z"
                        fill="currentColor"
                    />
                </svg>

                <svg
                    x-show="$store.sidebar.isMobileOpen"
                    class="fill-current"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92788 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
                        fill="currentColor"
                    />
                </svg>
            </button>

            {{-- Mobile Menu Toggle --}}
            <button
                class="flex xl:hidden items-center justify-center w-10 h-10 text-gray-500 rounded-lg dark:text-gray-400 lg:h-11 lg:w-11"
                :class="{ 'bg-gray-100 dark:bg-white/[0.03]': $store.sidebar.isMobileOpen }"
                @click="$store.sidebar.toggleMobileOpen()"
                aria-label="Toggle Mobile Menu"
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
                        d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25H14.6666C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.41422 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z"
                        fill="currentColor"
                    />
                </svg>

                <svg
                    x-show="$store.sidebar.isMobileOpen"
                    class="fill-current"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92788 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C18.0711 17.4865 18.0711 17.0116 18.0711 17.4865C18.0711 17.0116 17.7794C6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
                        fill="currentColor"
                    />
                </svg>
            </button>

            {{-- Logo Mobile --}}
            <a href="/dashboard" class="xl:hidden">
                <img
                    class="dark:hidden"
                    src="/images/logo/logo-pertamina.svg"
                    alt="Logo"
                />
                <img
                    class="hidden dark:block"
                    src="/images/logo/logo-pertamina-white.svg"
                    alt="Logo"
                />
            </a>

            {{-- Application Menu --}}
            <button
                @click="toggleApplicationMenu()"
                class="flex items-center justify-center w-10 h-10 text-gray-700 rounded-lg z-99999 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 xl:hidden"
            >
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M5.99902 10.4951C6.82745 10.4951 7.49902 11.1667 7.49902 11.9951V12.0051C7.49902 12.8335 6.82745 13.5051 5.99902 13.5051C5.1706 13.5051 4.49902 12.8335 4.49902 12.0051V11.9951C4.49902 11.1667 5.1706 10.4951 5.99902 10.4951ZM17.999 10.4951C18.8275 10.4951 19.499 11.1667 19.499 11.9951V12.0051C19.499 12.8335 18.8275 13.5051 17.999 13.5051C17.1706 13.5051 16.499 12.8335 16.499 12.0051V11.9951C16.499 11.1667 17.1706 10.4951 17.999 10.4951ZM13.499 11.9951C13.499 10.4951 10.499 11.1667 10.499 11.9951V12.0051C10.499 12.8335 11.1706 13.5051 11.999 13.5051C12.8275 13.5051 13.499 12.8335 13.499 12.0051V11.9951Z"
                        fill="currentColor"
                    />
                </svg>
            </button>

            {{-- Search --}}
<div class="relative hidden xl:block">
    <form @submit.prevent>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
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
                        fill=""
                    />
                </svg>
            </span>

            <input
                type="text"
                x-model="search"
                @input="searchBooks()"
                @focus="search.length > 0 ? showResults = true : null"
                placeholder="Cari buku..."
                autocomplete="off"
                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-12 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 xl:w-[430px]"
            />

            {{-- Clear --}}
            <button
                type="button"
                x-show="search.length > 0"
                @click="clearSearch()"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 dark:hover:text-white"
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

    {{-- Search Results --}}
    <div
        x-show="showResults"
        x-cloak
        class="absolute left-0 top-14 z-50 w-[430px] overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-gray-900"
    >

        {{-- Loading --}}
        <div
            x-show="isSearching"
            class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400"
        >
            Mencari buku...
        </div>

        {{-- Results --}}
        <template x-if="!isSearching && results.length > 0">
            <div class="py-2">
                <template
                    x-for="book in results"
                    :key="book.book_id"
                >
                    <a
                        :href="book.url"
                        @click="showResults = false"
                        class="flex items-start gap-3 px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-white/[0.03]"
                    >

                        {{-- Book Icon --}}
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-white/[0.05] dark:text-gray-300">
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
                        </div>

                        {{-- Book Information --}}
                        <div class="min-w-0 flex-1">

                            {{-- Title --}}
                            <p
                                class="truncate text-sm font-medium text-gray-800 dark:text-white/90"
                                x-text="book.title"
                            ></p>

                            {{-- Book Code + Author --}}
                            <p
                                class="mt-1 truncate text-xs text-gray-500 dark:text-gray-400"
                                x-text="book.book_code + ' • ' + book.author"
                            ></p>

                            {{-- Location --}}
                            <p
                                class="mt-1 truncate text-xs text-gray-400 dark:text-gray-500"
                                x-text="book.location"
                            ></p>

                        </div>
                    </a>
                </template>
            </div>
        </template>

        {{-- No Result --}}
        <div
            x-show="!isSearching && search.trim().length > 0 && results.length === 0"
            class="px-4 py-5 text-center"
        >
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Buku tidak ditemukan.
            </p>
        </div>

    </div>
</div>

        {{-- Right Side --}}
        <div
            :class="isApplicationMenuOpen ? 'flex' : 'hidden'"
            class="items-center justify-between w-full gap-4 px-5 py-4 xl:flex shadow-theme-md xl:justify-end xl:px-0 xl:shadow-none"
        >
            <div class="flex items-center gap-2 2xsm:gap-3">

                {{-- Theme Toggle --}}
                <button
                    class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                    @click="$store.theme.toggle()"
                    aria-label="Toggle Theme"
                >
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

                {{-- User Dropdown --}}
                <x-header.user-dropdown />
            </div>
        </div>
    </div>
</header>