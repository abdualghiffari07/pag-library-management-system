document.addEventListener('alpine:init', () => {
    Alpine.data('borrowersTable', () => ({
        selectionMode: false,
        selected: [],

        detailOpen: false,
        detail: {},

        resizingColumn: null,
        resizeStartX: 0,
        resizeStartWidth: 0,

        columnWidths: {
            select: 44,
            no: 55,
            borrower: 165,
            employeeNumber: 145,
            status: 110,
            category: 115,
            bookId: 115,
            title: 220,
            copyId: 130,
            loanDate: 120,
            returnedDate: 130,
            action: 250
        },

        defaultWidths: {
            select: 44,
            no: 55,
            borrower: 165,
            employeeNumber: 145,
            status: 110,
            category: 115,
            bookId: 115,
            title: 220,
            copyId: 130,
            loanDate: 120,
            returnedDate: 130,
            action: 250
        },

        minimumWidths: {
            select: 40,
            no: 45,
            borrower: 100,
            employeeNumber: 90,
            status: 90,
            category: 85,
            bookId: 80,
            title: 110,
            copyId: 90,
            loanDate: 95,
            returnedDate: 105,
            action: 210
        },

        init() {
            this.loadColumnWidths();
        },

        // Table width
        getTableWidth() {
            return Object.entries(this.columnWidths)
                .reduce((total, [column, width]) => {
                    if (
                        column === 'select' &&
                        !this.selectionMode
                    ) {
                        return total;
                    }

                    return total + Number(width);
                }, 0);
        },

        // Selection
        toggleSelectionMode() {
            this.selectionMode =
                !this.selectionMode;

            if (!this.selectionMode) {
                this.clearSelection();
            }
        },

        clearSelection() {
            this.selected = [];
        },

        isSelected(id) {
            return this.selected.includes(
                String(id)
            );
        },

        toggleSelect(id) {
            id = String(id);

            if (this.isSelected(id)) {
                this.selected =
                    this.selected.filter(
                        selectedId =>
                            selectedId !== id
                    );

                return;
            }

            this.selected.push(id);
        },

        visibleIds() {
            return Array.from(
                document.querySelectorAll(
                    '.borrower-select'
                )
            ).map(input =>
                String(input.value)
            );
        },

        toggleSelectAll() {
            const ids =
                this.visibleIds();

            const allSelected =
                ids.length > 0 &&
                ids.every(id =>
                    this.selected.includes(id)
                );

            if (allSelected) {
                this.selected =
                    this.selected.filter(
                        id => !ids.includes(id)
                    );

                return;
            }

            ids.forEach(id => {
                if (
                    !this.selected.includes(id)
                ) {
                    this.selected.push(id);
                }
            });
        },

        allVisibleSelected() {
            const ids =
                this.visibleIds();

            return (
                ids.length > 0 &&
                ids.every(id =>
                    this.selected.includes(id)
                )
            );
        },

        someVisibleSelected() {
            const ids =
                this.visibleIds();

            if (!ids.length) {
                return false;
            }

            const selectedCount =
                ids.filter(id =>
                    this.selected.includes(id)
                ).length;

            return (
                selectedCount > 0 &&
                selectedCount < ids.length
            );
        },

        // Resize
        startResize(event, column) {
            if (
                !Object.prototype.hasOwnProperty.call(
                    this.columnWidths,
                    column
                )
            ) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            this.resizingColumn =
                column;

            this.resizeStartX =
                event.clientX;

            this.resizeStartWidth =
                Number(
                    this.columnWidths[column]
                );

            document.body.style.cursor =
                'col-resize';

            document.body.style.userSelect =
                'none';

            const handleMove = moveEvent => {
                if (
                    this.resizingColumn
                    !== column
                ) {
                    return;
                }

                const difference =
                    moveEvent.clientX
                    - this.resizeStartX;

                const minimum =
                    this.minimumWidths[
                        column
                    ] ?? 60;

                const newWidth =
                    Math.max(
                        minimum,
                        this.resizeStartWidth
                        + difference
                    );

                this.columnWidths[column] =
                    Math.round(newWidth);
            };

            const handleUp = () => {
                this.saveColumnWidths();

                this.resizingColumn =
                    null;

                document.body.style.cursor =
                    '';

                document.body.style.userSelect =
                    '';

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

        resetColumnWidth(column) {
            if (
                !Object.prototype.hasOwnProperty.call(
                    this.defaultWidths,
                    column
                )
            ) {
                return;
            }

            this.columnWidths[column] =
                this.defaultWidths[column];

            this.saveColumnWidths();
        },

        resetAllColumnWidths() {
            this.columnWidths = {
                ...this.defaultWidths
            };

            this.saveColumnWidths();
        },

        saveColumnWidths() {
            localStorage.setItem(
                'pagBorrowersTableWidths',
                JSON.stringify(
                    this.columnWidths
                )
            );
        },

        loadColumnWidths() {
            const saved =
                localStorage.getItem(
                    'pagBorrowersTableWidths'
                );

            if (!saved) {
                return;
            }

            try {
                const widths =
                    JSON.parse(saved);

                this.columnWidths = {
                    ...this.columnWidths,
                    ...widths
                };
            } catch (error) {
                console.error(
                    'Gagal membaca ukuran kolom:',
                    error
                );

                localStorage.removeItem(
                    'pagBorrowersTableWidths'
                );
            }
        },

        // Action layout
        getActionLayout() {
            return 'flex-row';
        },

        getActionTextSize() {
            return this.columnWidths.action >= 180
                ? 'text-[10px]'
                : 'text-[9px]';
        },

        getActionPadding() {
            return this.columnWidths.action >= 180
                ? 'px-2 py-1.5'
                : 'px-1 py-1.5';
        },

        getActionLabel() {
            if (
                this.columnWidths.action >= 180
            ) {
                return 'Kembalikan';
            }

            return 'Kembali';
        },

        getFinishedLabel() {
            return 'Selesai';
        },

        // Detail
        openDetail(data) {
            this.detail = {
                name: data.name || '-',
                identity: data.identity || '-',
                category: data.category || '-',
                status: data.status || '-',
                bookId: data.bookId || '-',
                title: data.title || '-',
                copyId: data.copyId || '-',
                loanDate: data.loanDate || '-',
                returnedDate:
                    data.returnedDate || '-'
            };

            this.detailOpen = true;

            document.body.classList.add(
                'overflow-hidden'
            );
        },

        closeDetail() {
            this.detailOpen = false;

            document.body.classList.remove(
                'overflow-hidden'
            );
        }
    }));
});