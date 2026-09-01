'use strict';

const registerBooksTable = () => {
    if (!window.Alpine) {
        return;
    }

    window.Alpine.data('booksTable', rows => ({
        tableRowData: rows,
        search: '',
        itemsPerPage: 20,
        currentPage: 1,
        selectionMode: false,
        selectedIds: [],

        columnWidths: {
            select: 45,
            no: 55,
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
            const saved = localStorage.getItem('pagBooksTableWidths');

            if (!saved) {
                return;
            }

            try {
                this.columnWidths = {
                    ...this.columnWidths,
                    ...JSON.parse(saved)
                };
            } catch (error) {
                console.error('Gagal membaca ukuran kolom:', error);
            }
        },

        get tableWidth() {
            return Object.entries(this.columnWidths).reduce(
                (total, [key, width]) => {
                    if (key === 'select' && !this.selectionMode) {
                        return total;
                    }

                    return total + Number(width);
                },
                0
            );
        },

        get filteredRows() {
            const keyword = this.search.trim().toLowerCase();

            if (!keyword) {
                return this.tableRowData;
            }

            return this.tableRowData.filter(row => [
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
            ].some(value =>
                String(value ?? '')
                    .toLowerCase()
                    .includes(keyword)
            ));
        },

        get totalPages() {
            return Math.max(
                1,
                Math.ceil(this.filteredRows.length / this.itemsPerPage)
            );
        },

        get paginatedRows() {
            const start = (this.currentPage - 1) * this.itemsPerPage;

            return this.filteredRows.slice(
                start,
                start + this.itemsPerPage
            );
        },

        get displayedPages() {
            const pages = [];

            for (let page = 1; page <= this.totalPages; page++) {
                if (
                    page === 1 ||
                    page === this.totalPages ||
                    (
                        page >= this.currentPage - 1 &&
                        page <= this.currentPage + 1
                    )
                ) {
                    pages.push(page);
                } else if (pages[pages.length - 1] !== '...') {
                    pages.push('...');
                }
            }

            return pages;
        },

        get currentPageIds() {
            return this.paginatedRows.map(row => Number(row.id));
        },

        get isCurrentPageSelected() {
            return (
                this.currentPageIds.length > 0 &&
                this.currentPageIds.every(
                    id => this.selectedIds.includes(id)
                )
            );
        },

        get hasCurrentPageSelection() {
            return this.currentPageIds.some(
                id => this.selectedIds.includes(id)
            );
        },

        enterSelectionMode() {
            this.selectionMode = true;
        },

        exitSelectionMode() {
            this.selectionMode = false;
            this.selectedIds = [];
        },

        toggleRow(id, checked) {
            id = Number(id);

            if (checked) {
                if (!this.selectedIds.includes(id)) {
                    this.selectedIds.push(id);
                }

                return;
            }

            this.selectedIds = this.selectedIds.filter(
                selectedId => selectedId !== id
            );
        },

        toggleCurrentPage(checked) {
            if (checked) {
                this.currentPageIds.forEach(id => {
                    if (!this.selectedIds.includes(id)) {
                        this.selectedIds.push(id);
                    }
                });

                return;
            }

            this.selectedIds = this.selectedIds.filter(
                id => !this.currentPageIds.includes(id)
            );
        },

        deleteSelected() {
            if (this.selectedIds.length === 0) {
                return;
            }

            const confirmed = confirm(
                `Apakah Anda yakin ingin menghapus ${this.selectedIds.length} buku yang dipilih?`
            );

            if (confirmed) {
                this.$refs.bulkDeleteForm.submit();
            }
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

        startResize(event, column) {
            event.preventDefault();

            this.resizingColumn = column;
            this.resizeStartX = event.clientX;
            this.resizeStartWidth = this.columnWidths[column];

            const minimumWidths = {
                no: 45,
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

            const handleMove = moveEvent => {
                if (!this.resizingColumn) {
                    return;
                }

                const difference = moveEvent.clientX - this.resizeStartX;
                const minimum = minimumWidths[this.resizingColumn] ?? 60;

                this.columnWidths[this.resizingColumn] = Math.max(
                    minimum,
                    this.resizeStartWidth + difference
                );
            };

            const handleUp = () => {
                localStorage.setItem(
                    'pagBooksTableWidths',
                    JSON.stringify(this.columnWidths)
                );

                this.resizingColumn = null;

                document.removeEventListener('mousemove', handleMove);
                document.removeEventListener('mouseup', handleUp);
            };

            document.addEventListener('mousemove', handleMove);
            document.addEventListener('mouseup', handleUp);
        },

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

            return classes[status] || '';
        }
    }));
};

document.addEventListener(
    'alpine:init',
    registerBooksTable,
    { once: true }
);