'use strict';

const registerBooksTable = () => {
    if (!window.Alpine) {
        return;
    }

    window.Alpine.data('booksTable', rows => ({
        tableRowData: rows,

        search: '',

        filterOpen: false,

        filters: {
            status: '',
            location: '',
            author: '',
            equipment: '',
            publisher: ''
        },

        itemsPerPage: 20,
        currentPage: 1,

        selectionMode: false,
        selectedIds: [],

        columnWidths: {
            select: 45,
            no: 55,
            bookId: 80,
            status: 130,
            tagNo: 110,
            bookNo: 110,
            catNo: 100,
            equipment: 130,
            description: 220,
            title: 200,
            location: 130,
            remark: 140,
            author: 170,
            publisher: 150,
            qty: 70,
            action: 90
        },

        resizingColumn: null,
        resizeStartX: 0,
        resizeStartWidth: 0,

        init() {
            const saved = localStorage.getItem(
                'pagBooksTableWidths'
            );

            if (!saved) {
                return;
            }

            try {
                this.columnWidths = {
                    ...this.columnWidths,
                    ...JSON.parse(saved)
                };
            } catch (error) {
                console.error(
                    'Gagal membaca ukuran kolom:',
                    error
                );
            }
        },

        // Lebar tabel
        get tableWidth() {
            return Object.entries(
                this.columnWidths
            ).reduce(
                (total, [key, width]) => {
                    if (
                        key === 'select' &&
                        !this.selectionMode
                    ) {
                        return total;
                    }

                    return total + Number(width);
                },
                0
            );
        },

        // Pilihan filter
        getUniqueOptions(field) {
            return [
                ...new Set(
                    this.tableRowData
                        .map(row =>
                            String(
                                row[field] ?? ''
                            ).trim()
                        )
                        .filter(value =>
                            value !== '' &&
                            value !== '-'
                        )
                )
            ].sort((a, b) =>
                a.localeCompare(
                    b,
                    undefined,
                    {
                        sensitivity: 'base'
                    }
                )
            );
        },

        get statusOptions() {
            const preferredOrder = [
                'Tersedia',
                'Dipinjam',
                'Hilang',
                'Rusak'
            ];

            const available =
                this.getUniqueOptions(
                    'loanStatus'
                );

            return preferredOrder.filter(
                status =>
                    available.includes(status)
            );
        },

        get locationOptions() {
            return this.getUniqueOptions(
                'location'
            );
        },

        get equipmentOptions() {
            return this.getUniqueOptions(
                'equipment'
            );
        },

        get publisherOptions() {
            return this.getUniqueOptions(
                'publisher'
            );
        },

        get authorOptions() {
            const authors = [];

            this.tableRowData.forEach(row => {
                const value = String(
                    row.author ?? ''
                ).trim();

                if (
                    !value ||
                    value === '-'
                ) {
                    return;
                }

                value
                    .split(',')
                    .map(author =>
                        author.trim()
                    )
                    .filter(Boolean)
                    .forEach(author => {
                        authors.push(author);
                    });
            });

            return [
                ...new Set(authors)
            ].sort((a, b) =>
                a.localeCompare(
                    b,
                    undefined,
                    {
                        sensitivity: 'base'
                    }
                )
            );
        },

        // Jumlah filter
        get activeFilterCount() {
            return Object.values(
                this.filters
            ).filter(value =>
                String(value).trim() !== ''
            ).length;
        },

        get hasActiveFilters() {
            return this.activeFilterCount > 0;
        },

        resetFilters() {
            this.filters = {
                status: '',
                location: '',
                author: '',
                equipment: '',
                publisher: ''
            };

            this.currentPage = 1;
        },

        // Pencarian dan filter
        get filteredRows() {
            const keyword =
                this.search
                    .trim()
                    .toLowerCase();

            return this.tableRowData.filter(
                row => {
                    // Search
                    if (keyword) {
                        const searchableValues = [
                            row.id,
                            row.bookId,
                            row.loanStatus,
                            row.tagNo,
                            row.bookNo,
                            row.catNo,
                            row.equipment,
                            row.description,
                            row.title,
                            row.location,
                            row.remark,
                            row.author,
                            row.publisher,
                            row.qty
                        ];

                        const matchedSearch =
                            searchableValues.some(
                                value =>
                                    String(
                                        value ?? ''
                                    )
                                        .toLowerCase()
                                        .includes(
                                            keyword
                                        )
                            );

                        if (!matchedSearch) {
                            return false;
                        }
                    }

                    // Status
                    if (
                        this.filters.status &&
                        String(
                            row.loanStatus ?? ''
                        ) !==
                            this.filters.status
                    ) {
                        return false;
                    }

                    // Location
                    if (
                        this.filters.location &&
                        String(
                            row.location ?? ''
                        ) !==
                            this.filters.location
                    ) {
                        return false;
                    }

                    // Equipment
                    if (
                        this.filters.equipment &&
                        String(
                            row.equipment ?? ''
                        ) !==
                            this.filters.equipment
                    ) {
                        return false;
                    }

                    // Publisher
                    if (
                        this.filters.publisher &&
                        String(
                            row.publisher ?? ''
                        ) !==
                            this.filters.publisher
                    ) {
                        return false;
                    }

                    // Author
                    if (this.filters.author) {
                        const rowAuthors =
                            String(
                                row.author ?? ''
                            )
                                .split(',')
                                .map(author =>
                                    author.trim()
                                )
                                .filter(Boolean);

                        if (
                            !rowAuthors.includes(
                                this.filters.author
                            )
                        ) {
                            return false;
                        }
                    }

                    return true;
                }
            );
        },

        // Pagination
        get totalPages() {
            return Math.max(
                1,
                Math.ceil(
                    this.filteredRows.length /
                        this.itemsPerPage
                )
            );
        },

        get paginatedRows() {
            if (
                this.currentPage >
                this.totalPages
            ) {
                this.currentPage =
                    this.totalPages;
            }

            const start =
                (
                    this.currentPage - 1
                ) * this.itemsPerPage;

            return this.filteredRows.slice(
                start,
                start +
                    this.itemsPerPage
            );
        },

        get displayedPages() {
            const pages = [];

            for (
                let page = 1;
                page <= this.totalPages;
                page++
            ) {
                if (
                    page === 1 ||
                    page ===
                        this.totalPages ||
                    (
                        page >=
                            this.currentPage -
                                1 &&
                        page <=
                            this.currentPage +
                                1
                    )
                ) {
                    pages.push(page);
                } else if (
                    pages[
                        pages.length - 1
                    ] !== '...'
                ) {
                    pages.push('...');
                }
            }

            return pages;
        },

        prevPage() {
            if (
                this.currentPage > 1
            ) {
                this.currentPage--;
            }
        },

        nextPage() {
            if (
                this.currentPage <
                this.totalPages
            ) {
                this.currentPage++;
            }
        },

        goToPage(page) {
            if (
                typeof page ===
                    'number' &&
                page >= 1 &&
                page <=
                    this.totalPages
            ) {
                this.currentPage =
                    page;
            }
        },

        // Seleksi
        get currentPageIds() {
            return this.paginatedRows.map(
                row => Number(row.id)
            );
        },

        get isCurrentPageSelected() {
            return (
                this.currentPageIds.length >
                    0 &&
                this.currentPageIds.every(
                    id =>
                        this.selectedIds.includes(
                            id
                        )
                )
            );
        },

        get hasCurrentPageSelection() {
            return this.currentPageIds.some(
                id =>
                    this.selectedIds.includes(
                        id
                    )
            );
        },

        enterSelectionMode() {
            this.filterOpen = false;
            this.selectionMode = true;
        },

        exitSelectionMode() {
            this.selectionMode = false;
            this.selectedIds = [];
        },

        toggleRow(id, checked) {
            id = Number(id);

            if (checked) {
                if (
                    !this.selectedIds.includes(
                        id
                    )
                ) {
                    this.selectedIds.push(
                        id
                    );
                }

                return;
            }

            this.selectedIds =
                this.selectedIds.filter(
                    selectedId =>
                        selectedId !== id
                );
        },

        toggleCurrentPage(checked) {
            if (checked) {
                this.currentPageIds.forEach(
                    id => {
                        if (
                            !this.selectedIds.includes(
                                id
                            )
                        ) {
                            this.selectedIds.push(
                                id
                            );
                        }
                    }
                );

                return;
            }

            this.selectedIds =
                this.selectedIds.filter(
                    id =>
                        !this.currentPageIds.includes(
                            id
                        )
                );
        },

        // Hapus banyak
        deleteSelected() {
            if (
                this.selectedIds.length ===
                0
            ) {
                return;
            }

            const confirmed = confirm(
                `Apakah Anda yakin ingin menghapus ${this.selectedIds.length} buku yang dipilih?`
            );

            if (confirmed) {
                this.$refs
                    .bulkDeleteForm
                    .submit();
            }
        },

        // Resize kolom
        startResize(event, column) {
            event.preventDefault();

            this.resizingColumn =
                column;

            this.resizeStartX =
                event.clientX;

            this.resizeStartWidth =
                this.columnWidths[
                    column
                ];

            const minimumWidths = {
                no: 45,
                bookId: 60,
                status: 100,
                tagNo: 70,
                bookNo: 70,
                catNo: 70,
                equipment: 90,
                description: 100,
                title: 100,
                location: 80,
                remark: 80,
                author: 100,
                publisher: 100,
                qty: 50,
                action: 70
            };

            const handleMove =
                moveEvent => {
                    if (
                        !this.resizingColumn
                    ) {
                        return;
                    }

                    const difference =
                        moveEvent.clientX -
                        this.resizeStartX;

                    const minimum =
                        minimumWidths[
                            this
                                .resizingColumn
                        ] ?? 60;

                    this.columnWidths[
                        this.resizingColumn
                    ] = Math.max(
                        minimum,
                        this.resizeStartWidth +
                            difference
                    );
                };

            const handleUp = () => {
                localStorage.setItem(
                    'pagBooksTableWidths',
                    JSON.stringify(
                        this.columnWidths
                    )
                );

                this.resizingColumn =
                    null;

                document.removeEventListener(
                    'mousemove',
                    handleMove
                );

                document.removeEventListener(
                    'mouseup',
                    handleUp
                );
            };

            document.addEventListener(
                'mousemove',
                handleMove
            );

            document.addEventListener(
                'mouseup',
                handleUp
            );
        },

        // Status
        getStatusClass(status) {
            const classes = {
                Tersedia:
                    'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400',

                Dipinjam:
                    'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400',

                Hilang:
                    'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400',

                Rusak:
                    'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400'
            };

            return (
                classes[status] || ''
            );
        }
    }));
};

document.addEventListener(
    'alpine:init',
    registerBooksTable,
    {
        once: true
    }
);