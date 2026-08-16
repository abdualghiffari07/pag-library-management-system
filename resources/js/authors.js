document.addEventListener('DOMContentLoaded', () => {

    /* =====================================================
       AUTHOR SEARCH
    ====================================================== */

    const searchInput =
        document.getElementById('author-search');

    const tableBody =
        document.getElementById('authors-table-body');


    if (searchInput && tableBody) {

        searchInput.addEventListener('input', () => {

            const keyword =
                searchInput.value
                    .trim()
                    .toLowerCase();


            const rows =
                tableBody.querySelectorAll('.author-row');


            let visibleRows = 0;


            rows.forEach(row => {

                const text =
                    row.textContent.toLowerCase();


                const matched =
                    text.includes(keyword);


                row.style.display =
                    matched ? '' : 'none';


                if (matched) {
                    visibleRows++;
                }

            });


            /* =================================================
               NO SEARCH RESULT
            ================================================== */

            let noResult =
                tableBody.querySelector('.author-search-empty');


            if (
                keyword !== '' &&
                visibleRows === 0
            ) {

                if (!noResult) {

                    noResult =
                        document.createElement('tr');

                    noResult.className =
                        'author-search-empty';

                    noResult.innerHTML = `
                        <td colspan="5">
                            <div class="authors-empty">
                                <div class="empty-icon">⌕</div>

                                <h3>
                                    Penulis tidak ditemukan
                                </h3>

                                <p>
                                    Tidak ada penulis yang
                                    cocok dengan pencarian
                                    "${escapeHtml(keyword)}".
                                </p>
                            </div>
                        </td>
                    `;

                    tableBody.appendChild(noResult);
                }

            } else {

                if (noResult) {
                    noResult.remove();
                }

            }

        });

    }


    /* =====================================================
       DELETE CONFIRMATION
    ====================================================== */

    const deleteForms =
        document.querySelectorAll(
            '.delete-author-form'
        );


    deleteForms.forEach(form => {

        form.addEventListener('submit', event => {

            const authorName =
                form
                    .closest('.author-row')
                    ?.querySelector('.author-name')
                    ?.textContent
                    ?.trim();


            const message = authorName
                ? `Apakah Anda yakin ingin menghapus penulis "${authorName}"?`
                : 'Apakah Anda yakin ingin menghapus penulis ini?';


            const confirmed =
                window.confirm(message);


            if (!confirmed) {
                event.preventDefault();
            }

        });

    });


    /* =====================================================
       SIDEBAR TOGGLE
    ====================================================== */

    const sidebar =
        document.getElementById('sidebar');

    const sidebarToggle =
        document.getElementById('sidebar-toggle');

    const sidebarOverlay =
        document.getElementById('sidebar-overlay');


    if (
        sidebar &&
        sidebarToggle &&
        sidebarOverlay
    ) {

        sidebarToggle.addEventListener(
            'click',
            () => {

                sidebar.classList.toggle('open');

                sidebarOverlay.classList.toggle(
                    'active'
                );

            }
        );


        sidebarOverlay.addEventListener(
            'click',
            () => {

                sidebar.classList.remove('open');

                sidebarOverlay.classList.remove(
                    'active'
                );

            }
        );

    }


    /* =====================================================
       HTML ESCAPE
    ====================================================== */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent = value;

        return div.innerHTML;

    }

});