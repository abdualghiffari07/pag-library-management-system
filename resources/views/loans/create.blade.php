<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Peminjaman - PAG Library</title>
</head>



<body>

    <h1>Tambah Peminjaman</h1>

    <a href="{{ route('loans.index') }}">
        ← Kembali ke Daftar Peminjaman
    </a>

    <hr>

    @if ($errors->any())
        <div>
            <strong>Terjadi kesalahan:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('loans.store') }}" method="POST">

        @csrf

        <div>
            <label for="user_id">
                Peminjam
            </label>

            <br>

            <select name="user_id" id="user_id" required>

                <option value="">
                    -- Pilih Peminjam --
                </option>

                @foreach ($users as $user)

                    <option
                        value="{{ $user->user_id }}"
                        {{ old('user_id') == $user->user_id ? 'selected' : '' }}
                    >
                        {{ $user->name }}
                        ({{ $user->nopek }})
                    </option>

                @endforeach

            </select>
        </div>

        <br>

        <div>
            <label for="loan_date">
                Tanggal Pinjam
            </label>

            <br>

            <input
                type="date"
                id="loan_date"
                name="loan_date"
                value="{{ old('loan_date', date('Y-m-d')) }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="due_date">
                Jatuh Tempo
            </label>

            <br>

            <input
                type="date"
                id="due_date"
                name="due_date"
                value="{{ old('due_date') }}"
                required
            >
        </div>

        <br>

        <div>

            <label>
                Pilih Buku
            </label>

            <br><br>

            @foreach ($books as $book)

                <div>

                    <label>

                        <input
                            type="checkbox"
                            name="books[]"
                            value="{{ $book->book_id }}"
                            {{ in_array($book->book_id, old('books', [])) ? 'checked' : '' }}
                        >

                        {{ $book->title }}

                    </label>

                </div>

            @endforeach

            <br>

            <small>
                Pilih satu atau lebih buku yang akan dipinjam.
            </small>

        </div>

        <br>

        <div>

            <label for="notes">
                Catatan
            </label>

            <br>

            <textarea
                id="notes"
                name="notes"
                rows="5"
                cols="50"
            >{{ old('notes') }}</textarea>

        </div>

        <br>

        <button type="submit">
            Simpan Peminjaman
        </button>

        <a href="{{ route('loans.index') }}">
            Batal
        </a>

    </form>

        <script>
        const loanDate = document.getElementById('loan_date');
        const dueDate = document.getElementById('due_date');

        function updateDueDateMin() {
            dueDate.min = loanDate.value;

            if (dueDate.value && dueDate.value < loanDate.value) {
                dueDate.value = loanDate.value;
            }
        }

        loanDate.addEventListener('change', updateDueDateMin);

        updateDueDateMin();
    </script>

</body>
</html>