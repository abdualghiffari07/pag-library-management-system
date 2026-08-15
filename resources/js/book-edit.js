document.addEventListener('DOMContentLoaded', () => {

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');



/*
    |--------------------------------------------------------------------------
    | AUTHOR AUTOCOMPLETE
    |--------------------------------------------------------------------------
    */

setupAutocomplete({
    type: 'author',

    searchInput: '#author-search',

    suggestions: '#author-suggestions',

    selectedContainer: '#selected-authors',

    searchUrl: '/authors/search',

    createUrl: '/authors',

    hiddenName: 'authors[]',

    idField: 'author_id',

    nameField: 'author_name',

    placeholder: 'Ketik nama penulis...'
});


    /*
    |--------------------------------------------------------------------------
    | CATEGORY AUTOCOMPLETE
    |--------------------------------------------------------------------------
    */

setupAutocomplete({
    type: 'category',

    searchInput: '#category-search',

    suggestions: '#category-suggestions',

    selectedContainer: '#selected-categories',

    searchUrl: '/categories/search',

    createUrl: '/categories',

    hiddenName: 'categories[]',

    idField: 'category_id',

    nameField: 'category_name',

    placeholder: 'Ketik kategori...'
});

    /*
    |--------------------------------------------------------------------------
    | GENERAL AUTOCOMPLETE FUNCTION
    |--------------------------------------------------------------------------
    */

    function setupAutocomplete(config) {

        const input =
            document.querySelector(config.searchInput);

        const suggestions =
            document.querySelector(config.suggestions);

        const selectedContainer =
            document.querySelector(config.selectedContainer);


        if (!input || !suggestions || !selectedContainer) {
            return;
        }


        let searchTimeout = null;


        /*
        |--------------------------------------------------------------------------
        | INPUT
        |--------------------------------------------------------------------------
        */

        input.addEventListener('input', () => {

            const query = input.value.trim();


            clearTimeout(searchTimeout);


            if (query.length < 1) {

                hideSuggestions();

                return;
            }


            /*
            | Tunggu sebentar sebelum melakukan request
            | agar tidak request setiap karakter.
            */

            searchTimeout = setTimeout(() => {

                searchItems(query);

            }, 250);

        });


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        async function searchItems(query) {

            try {

                const response = await fetch(
                    `${config.searchUrl}?q=${encodeURIComponent(query)}`,
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );


                if (!response.ok) {
                    throw new Error(
                        'Gagal mengambil data.'
                    );
                }


                const items = await response.json();


                renderSuggestions(items, query);


            } catch (error) {

                console.error(
                    'Autocomplete error:',
                    error
                );

                hideSuggestions();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | RENDER SUGGESTIONS
        |--------------------------------------------------------------------------
        */

        function renderSuggestions(items, query) {

            suggestions.innerHTML = '';


            const selectedIds =
                getSelectedIds();


            /*
            | Hanya tampilkan data yang belum dipilih.
            */

            const filteredItems = items.filter(item => {

                return !selectedIds.includes(
                    String(item[config.idField])
                );

            });


            if (filteredItems.length === 0) {

                suggestions.innerHTML = `
                    <div class="autocomplete-empty">
                        Tidak ada hasil ditemukan.
                        <span>
                            Tekan Enter untuk menambahkan baru.
                        </span>
                    </div>
                `;

                showSuggestions();

                return;
            }


            filteredItems.forEach(item => {

                const option =
                    document.createElement('button');

                option.type = 'button';

                option.className =
                    'autocomplete-item';


                option.innerHTML = `
                    <span class="autocomplete-item-name">
                        ${escapeHtml(
                            item[config.nameField]
                        )}
                    </span>
                `;


                option.addEventListener(
                    'mousedown',
                    (event) => {

                        event.preventDefault();

                        addTag(
                            item[config.idField],
                            item[config.nameField]
                        );

                    }
                );


                suggestions.appendChild(option);

            });


            showSuggestions();
        }


        /*
        |--------------------------------------------------------------------------
        | ADD TAG
        |--------------------------------------------------------------------------
        */

        function addTag(id, name) {

            const selectedIds =
                getSelectedIds();


            if (
                selectedIds.includes(
                    String(id)
                )
            ) {

                input.value = '';

                hideSuggestions();

                return;
            }


            const tag =
                document.createElement('div');

            tag.className =
                'selected-tag';

            tag.dataset.id = id;


            tag.innerHTML = `

                <span>
                    ${escapeHtml(name)}
                </span>

                <button
                    type="button"
                    class="remove-tag"
                    aria-label="Hapus"
                >
                    ×
                </button>

                <input
                    type="hidden"
                    name="${config.hiddenName}"
                    value="${id}"
                >

            `;


            const removeButton =
                tag.querySelector('.remove-tag');


            removeButton.addEventListener(
                'click',
                () => {

                    tag.remove();

                }
            );


            selectedContainer.appendChild(tag);


            input.value = '';

            hideSuggestions();

            input.focus();
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE NEW ITEM
        |--------------------------------------------------------------------------
        */

        async function createNewItem(name) {

            try {

                const response = await fetch(
                    config.createUrl,
                    {
                        method: 'POST',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                getCsrfToken()

                        },

                        body: JSON.stringify({

                            [config.type === 'author'
                                ? 'author_name'
                                : 'category_name'
                            ]: name

                        })
                    }
                );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Gagal membuat data.'
                    );

                }


                const item =
                    data[config.type];


                addTag(
                    item[config.idField],
                    item[config.nameField]
                );


            } catch (error) {

                console.error(error);

                alert(
                    'Gagal menambahkan data baru.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ENTER
        |--------------------------------------------------------------------------
        */

        input.addEventListener(
            'keydown',
            (event) => {

                if (event.key !== 'Enter') {
                    return;
                }


                event.preventDefault();


                const value =
                    input.value.trim();


                if (!value) {
                    return;
                }


                /*
                | Jika dropdown memiliki hasil,
                | Enter memilih hasil pertama.
                */

                const firstSuggestion =
                    suggestions.querySelector(
                        '.autocomplete-item'
                    );


                if (firstSuggestion) {

                    firstSuggestion.click();

                    return;
                }


                /*
                | Jika tidak ada hasil,
                | buat data baru.
                */

                createNewItem(value);

            }
        );


        /*
        |--------------------------------------------------------------------------
        | REMOVE EXISTING TAG
        |--------------------------------------------------------------------------
        */

        selectedContainer
            .addEventListener(
                'click',
                (event) => {

                    const button =
                        event.target.closest(
                            '.remove-tag'
                        );


                    if (!button) {
                        return;
                    }


                    const tag =
                        button.closest(
                            '.selected-tag'
                        );


                    if (tag) {
                        tag.remove();
                    }

                }
            );


        /*
        |--------------------------------------------------------------------------
        | CLOSE DROPDOWN
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'click',
            (event) => {

                const wrapper =
                    input.closest(
                        '.tag-input-wrapper'
                    );


                if (
                    wrapper &&
                    !wrapper.contains(event.target)
                ) {

                    hideSuggestions();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | HELPER
        |--------------------------------------------------------------------------
        */

        function getSelectedIds() {

            return Array.from(
                selectedContainer.querySelectorAll(
                    'input[type="hidden"]'
                )
            ).map(
                input => String(input.value)
            );

        }


        function showSuggestions() {

            suggestions.classList.add(
                'is-visible'
            );

        }


        function hideSuggestions() {

            suggestions.classList.remove(
                'is-visible'
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CSRF TOKEN
    |--------------------------------------------------------------------------
    */

    function getCsrfToken() {

        const meta =
            document.querySelector(
                'meta[name="csrf-token"]'
            );


        return meta
            ? meta.getAttribute('content')
            : '';
    }


    /*
    |--------------------------------------------------------------------------
    | HTML ESCAPE
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent = value;

        return div.innerHTML;
    }

/*
|--------------------------------------------------------------------------
| UPDATE BUTTON ANIMATION
|--------------------------------------------------------------------------
*/

const updateForm =
    document.querySelector('.edit-form');

const updateButton =
    document.querySelector('#update-book-button');


if (updateForm && updateButton) {

    updateForm.addEventListener(
        'submit',
        () => {

            updateButton.classList.add(
                'is-loading'
            );

            updateButton.disabled = true;

            const text =
                updateButton.querySelector(
                    '.btn-text'
                );

            if (text) {

                text.textContent =
                    'Menyimpan...';

            }

        }
    );

}

});

