document.addEventListener('alpine:init', () => {
    Alpine.data('borrowersTable', () => ({
        selectionMode: false,
        selected: [],

        columnWidths: {
            select: 45,
            no: 55,
            borrower: 160,
            employeeNumber: 120,
            status: 110,
            category: 100,
            bookId: 100,
            title: 190,
            copyId: 115,
            loanDate: 110,
            returnedDate: 120,
            action: 220,
        },

        resizeStartX: 0,
        resizeStartWidth: 0,
        resizingColumn: null,

        init() {
            this.restoreWidths();
        },

        minimumWidth(column) {
            const minimums = {
                select: 40,
                no: 45,
                borrower: 90,
                employeeNumber: 80,
                status: 80,
                category: 75,
                bookId: 75,
                title: 90,
                copyId: 85,
                loanDate: 85,
                returnedDate: 90,
                action: 95,
            };

            return minimums[column] ?? 60;
        },

        getTableWidth() {
            return Object.entries(this.columnWidths)
                .filter(([column]) => {
                    if (
                        column === 'select' &&
                        !this.selectionMode
                    ) {
                        return false;
                    }

                    return true;
                })
                .reduce(
                    (total, [, width]) =>
                        total + width,
                    0
                );
        },

        restoreWidths() {
            const saved = localStorage.getItem(
                'pagBorrowersTableWidths'
            );

            if (!saved) {
                return;
            }

            try {
                const widths = JSON.parse(saved);

                this.columnWidths = {
                    ...this.columnWidths,
                    ...widths,
                };
            } catch (error) {
                localStorage.removeItem(
                    'pagBorrowersTableWidths'
                );
            }
        },

        saveWidths() {
            localStorage.setItem(
                'pagBorrowersTableWidths',
                JSON.stringify(
                    this.columnWidths
                )
            );
        },

        startResize(event, column) {
            event.preventDefault();

            this.resizingColumn = column;

            this.resizeStartX =
                event.clientX;

            this.resizeStartWidth =
                this.columnWidths[column];

            document.documentElement.classList.add(
                'select-none'
            );

            document.body.style.cursor =
                'col-resize';

            const handleMouseMove = (moveEvent) => {
                const difference =
                    moveEvent.clientX -
                    this.resizeStartX;

                const minimum =
                    this.minimumWidth(column);

                this.columnWidths[column] =
                    Math.max(
                        minimum,
                        this.resizeStartWidth +
                            difference
                    );
            };

            const handleMouseUp = () => {
                this.saveWidths();

                document.documentElement.classList.remove(
                    'select-none'
                );

                document.body.style.cursor = '';

                document.removeEventListener(
                    'mousemove',
                    handleMouseMove
                );

                document.removeEventListener(
                    'mouseup',
                    handleMouseUp
                );

                this.resizingColumn = null;
            };

            document.addEventListener(
                'mousemove',
                handleMouseMove
            );

            document.addEventListener(
                'mouseup',
                handleMouseUp
            );
        },

        toggleSelectionMode() {
            this.selectionMode =
                !this.selectionMode;

            if (!this.selectionMode) {
                this.clearSelection();
            }
        },

        normalizeId(id) {
            return String(id);
        },

        toggleSelect(id) {
            const normalizedId =
                this.normalizeId(id);

            if (
                this.selected.includes(
                    normalizedId
                )
            ) {
                this.selected =
                    this.selected.filter(
                        item =>
                            item !== normalizedId
                    );

                return;
            }

            this.selected.push(
                normalizedId
            );
        },

        isSelected(id) {
            return this.selected.includes(
                this.normalizeId(id)
            );
        },

        getPageIds() {
            return Array.from(
                this.$root.querySelectorAll(
                    '.borrower-select'
                )
            ).map(
                checkbox =>
                    this.normalizeId(
                        checkbox.value
                    )
            );
        },

        allVisibleSelected() {
            const ids = this.getPageIds();

            if (ids.length === 0) {
                return false;
            }

            return ids.every(
                id =>
                    this.selected.includes(id)
            );
        },

        someVisibleSelected() {
            const ids = this.getPageIds();

            if (ids.length === 0) {
                return false;
            }

            const selectedCount =
                ids.filter(
                    id =>
                        this.selected.includes(id)
                ).length;

            return (
                selectedCount > 0 &&
                selectedCount < ids.length
            );
        },

        toggleSelectAll() {
            const ids = this.getPageIds();

            if (ids.length === 0) {
                return;
            }

            if (this.allVisibleSelected()) {
                this.selected =
                    this.selected.filter(
                        id =>
                            !ids.includes(id)
                    );

                return;
            }

            ids.forEach((id) => {
                if (
                    !this.selected.includes(id)
                ) {
                    this.selected.push(id);
                }
            });
        },

        clearSelection() {
            this.selected = [];
        },

        getActionLabel() {
            if (
                this.columnWidths.action >= 190
            ) {
                return 'Buku Sudah Dikembalikan';
            }

            if (
                this.columnWidths.action >= 130
            ) {
                return 'Kembalikan';
            }

            return 'Kembali';
        },

        getActionTextSize() {
            if (
                this.columnWidths.action >= 150
            ) {
                return 'text-[10px]';
            }

            if (
                this.columnWidths.action >= 110
            ) {
                return 'text-[9px]';
            }

            return 'text-[8px]';
        },

        getActionPadding() {
            if (
                this.columnWidths.action >= 160
            ) {
                return 'px-2.5 py-1.5';
            }

            if (
                this.columnWidths.action >= 110
            ) {
                return 'px-2 py-1.5';
            }

            return 'px-1.5 py-1';
        },

        getActionLayout() {
            if (
                this.columnWidths.action >= 170
            ) {
                return 'flex-row';
            }

            return 'flex-col';
        },

        getFinishedLabel() {
            if (
                this.columnWidths.action >= 120
            ) {
                return 'Selesai';
            }

            return '✓';
        },

        resetColumnWidths() {
            this.columnWidths = {
                select: 45,
                no: 55,
                borrower: 160,
                employeeNumber: 120,
                status: 110,
                category: 100,
                bookId: 100,
                title: 190,
                copyId: 115,
                loanDate: 110,
                returnedDate: 120,
                action: 220,
            };

            this.saveWidths();
        },
    }));
});