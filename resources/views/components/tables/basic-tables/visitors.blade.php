<div x-data="{
    orders: [
        {
            id: 1,
            user: {
                image: './images/user/user-17.jpg',
                name: 'Lindsey Curtis',
                role: 'Web Designer',
            },
            projectName: 'Agency Website',
            team: {
                images: [
                    './images/user/user-22.jpg',
                    './images/user/user-23.jpg',
                    './images/user/user-24.jpg',
                ],
            },
            budget: '3.9K',
            status: 'Active',
        },
        {
            id: 2,
            user: {
                image: './images/user/user-18.jpg',
                name: 'Kaiya George',
                role: 'Project Manager',
            },
            projectName: 'Technology',
            team: {
                images: [
                    './images/user/user-25.jpg',
                    './images/user/user-26.jpg',
                ],
            },
            budget: '24.9K',
            status: 'Pending',
        },
        {
            id: 3,
            user: {
                image: './images/user/user-19.jpg',
                name: 'Zain Geidt',
                role: 'Content Writer',
            },
            projectName: 'Blog Writing',
            team: {
                images: [
                    './images/user/user-27.jpg',
                ],
            },
            budget: '12.7K',
            status: 'Active',
        },
        {
            id: 4,
            user: {
                image: './images/user/user-20.jpg',
                name: 'Abram Schleifer',
                role: 'Digital Marketer',
            },
            projectName: 'Social Media',
            team: {
                images: [
                    './images/user/user-28.jpg',
                    './images/user/user-29.jpg',
                    './images/user/user-30.jpg',
                ],
            },
            budget: '2.8K',
            status: 'Cancel',
        },
        {
            id: 5,
            user: {
                image: './images/user/user-21.jpg',
                name: 'Carla George',
                role: 'Front-end Developer',
            },
            projectName: 'Website',
            team: {
                images: [
                    './images/user/user-31.jpg',
                    './images/user/user-32.jpg',
                    './images/user/user-33.jpg',
                ],
            },
            budget: '4.5K',
            status: 'Active',
        },
    ],

    itemsPerPage: 5,
    currentPage: 1,

    get totalPages() {
        return Math.ceil(this.orders.length / this.itemsPerPage);
    },

    get paginatedOrders() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.orders.slice(start, end);
    },

    get displayedPages() {
        const range = [];

        for (let i = 1; i <= this.totalPages; i++) {
            if (
                i === 1 ||
                i === this.totalPages ||
                (i >= this.currentPage - 1 && i <= this.currentPage + 1)
            ) {
                range.push(i);
            } else if (range[range.length - 1] !== '...') {
                range.push('...');
            }
        }

        return range;
    },

    prevPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
        }
    },

    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
        }
    },

    goToPage(page) {
        if (
            typeof page === 'number' &&
            page >= 1 &&
            page <= this.totalPages
        ) {
            this.currentPage = page;
        }
    },

    getStatusClass(status) {
        const classes = {
            'Active': 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500',
            'Pending': 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400',
            'Cancel': 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500',
        };

        return classes[status] || '';
    }
}">

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- TABLE --}}
        <div class="w-full overflow-x-auto custom-scrollbar">

            <table class="w-full table-auto">

                {{-- TABLE HEADER --}}
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">

                        <th class="w-[25%] px-4 py-3 text-left sm:px-5">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                User
                            </p>
                        </th>

                        <th class="w-[23%] px-4 py-3 text-left sm:px-5">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                Project Name
                            </p>
                        </th>

                        <th class="w-[18%] px-4 py-3 text-left sm:px-5">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                Team
                            </p>
                        </th>

                        <th class="w-[17%] px-4 py-3 text-left sm:px-5">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                Status
                            </p>
                        </th>

                        <th class="w-[17%] px-4 py-3 text-left sm:px-5">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                Budget
                            </p>
                        </th>

                    </tr>
                </thead>

                {{-- TABLE BODY --}}
                <tbody>

                    <template x-for="order in paginatedOrders" :key="order.id">

                        <tr class="border-b border-gray-100 dark:border-gray-800">

                            {{-- USER --}}
                            <td class="px-4 py-3 sm:px-5">
                                <div class="flex min-w-0 items-center gap-2.5">

                                    <div class="h-8 w-8 shrink-0 overflow-hidden rounded-full sm:h-9 sm:w-9">
                                        <img
                                            :src="order.user.image"
                                            :alt="order.user.name"
                                            class="h-full w-full object-cover"
                                        >
                                    </div>

                                    <div class="min-w-0">
                                        <span
                                            class="block truncate font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                            x-text="order.user.name"
                                        ></span>

                                        <span
                                            class="block truncate text-gray-500 text-theme-xs dark:text-gray-400"
                                            x-text="order.user.role"
                                        ></span>
                                    </div>

                                </div>
                            </td>

                            {{-- PROJECT --}}
                            <td class="px-4 py-3 sm:px-5">
                                <p
                                    class="truncate text-gray-500 text-theme-sm dark:text-gray-400"
                                    x-text="order.projectName"
                                ></p>
                            </td>

                            {{-- TEAM --}}
                            <td class="px-4 py-3 sm:px-5">
                                <div class="flex -space-x-2">

                                    <template
                                        x-for="(teamImage, index) in order.team.images"
                                        :key="index"
                                    >
                                        <div class="h-6 w-6 shrink-0 overflow-hidden rounded-full border-2 border-white dark:border-gray-900">
                                            <img
                                                :src="teamImage"
                                                alt="team member"
                                                class="h-full w-full object-cover"
                                            >
                                        </div>
                                    </template>

                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3 sm:px-5">
                                <p
                                    class="inline-block whitespace-nowrap rounded-full px-2 py-0.5 text-[11px] font-medium"
                                    :class="getStatusClass(order.status)"
                                    x-text="order.status"
                                ></p>
                            </td>

                            {{-- BUDGET --}}
                            <td class="px-4 py-3 sm:px-5">
                                <p
                                    class="text-gray-500 text-theme-sm dark:text-gray-400"
                                    x-text="order.budget"
                                ></p>
                            </td>

                        </tr>

                    </template>

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-800 sm:px-5 sm:py-4">

            <div class="flex items-center justify-between">

                {{-- PREVIOUS --}}
                <button
                    @click="prevPage"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1
                        ? 'cursor-not-allowed opacity-50'
                        : ''"
                    class="flex items-center gap-1.5 rounded-lg border border-gray-300
                           bg-white px-2.5 py-2 text-xs font-medium
                           text-gray-700 shadow-theme-xs
                           hover:bg-gray-50
                           dark:border-gray-700 dark:bg-gray-800
                           dark:text-gray-400 dark:hover:bg-white/[0.03]
                           sm:px-3 sm:py-2.5"
                >

                    <svg
                        width="17"
                        height="17"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <path
                            d="M12.5 15L7.5 10L12.5 5M7.5 10H17"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                    <span class="hidden sm:inline">
                        Previous
                    </span>

                </button>

                {{-- MOBILE --}}
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400 sm:hidden">
                    <span x-text="currentPage"></span>
                    /
                    <span x-text="totalPages"></span>
                </span>

                {{-- PAGE NUMBERS --}}
                <ul class="hidden items-center gap-0.5 sm:flex">

                    <template
                        x-for="page in displayedPages"
                        :key="page"
                    >

                        <li>

                            <button
                                x-show="page !== '...'"
                                @click="goToPage(page)"
                                :class="currentPage === page
                                    ? 'bg-blue-500 text-white'
                                    : 'text-gray-700 hover:bg-blue-500/[0.08] hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-500'"
                                class="flex h-9 w-9 items-center justify-center rounded-lg text-xs font-medium"
                                x-text="page"
                            ></button>

                            <span
                                x-show="page === '...'"
                                class="flex h-9 w-9 items-center justify-center text-gray-500"
                            >
                                ...
                            </span>

                        </li>

                    </template>

                </ul>

                {{-- NEXT --}}
                <button
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages
                        ? 'cursor-not-allowed opacity-50'
                        : ''"
                    class="flex items-center gap-1.5 rounded-lg border border-gray-300
                           bg-white px-2.5 py-2 text-xs font-medium
                           text-gray-700 shadow-theme-xs
                           hover:bg-gray-50
                           dark:border-gray-700 dark:bg-gray-800
                           dark:text-gray-400 dark:hover:bg-white/[0.03]
                           sm:px-3 sm:py-2.5"
                >

                    <span class="hidden sm:inline">
                        Next
                    </span>

                    <svg
                        width="17"
                        height="17"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <path
                            d="M7.5 15L12.5 10L7.5 5M12.5 10H3"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                </button>

            </div>

        </div>

    </div>

</div>