<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Eksemplar - PAG Library</title>
</head>

<body>

    <h1>Tambah Eksemplar Buku</h1>

    <a href="{{ route('book-copies.index') }}">
        ← Kembali ke Daftar Eksemplar
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

    <form action="{{ route('book-copies.store') }}" method="POST">

        @csrf

        <div>

            <label for="book_id">
                Buku
            </label>

            <br>

            <select name="book_id" id="book_id" required>

                <option value="">
                    -- Pilih Buku --
                </option>

                @foreach ($books as $book)

                    <option
                        value="{{ $book->book_id }}"
                        {{ old('book_id') == $book->book_id ? 'selected' : '' }}
                    >
                        {{ $book->title }}
                    </option>

                @endforeach

            </select>

        </div>

        <br>

        <div>

            <label for="copy_code">
                Kode Eksemplar
            </label>

            <br>

            <input
                type="text"
                id="copy_code"
                name="copy_code"
                value="{{ old('copy_code') }}"
                placeholder="Contoh: BK-CLN-004"
                required
            >

        </div>

        <br>

        <div>

            <label for="condition">
                Kondisi
            </label>

            <br>

            <select name="condition" id="condition" required>

                <option value="">
                    -- Pilih Kondisi --
                </option>

                <option
                    value="baik"
                    {{ old('condition') === 'baik' ? 'selected' : '' }}
                >
                    Baik
                </option>

                <option
                    value="rusak ringan"
                    {{ old('condition') === 'rusak ringan' ? 'selected' : '' }}
                >
                    Rusak Ringan
                </option>

                <option
                    value="rusak berat"
                    {{ old('condition') === 'rusak berat' ? 'selected' : '' }}
                >
                    Rusak Berat
                </option>

            </select>

        </div>

        <br>

        <div>

            <label for="status">
                Status
            </label>

            <br>

            <select name="status" id="status" required>

                <option value="">
                    -- Pilih Status --
                </option>

                <option
                    value="tersedia"
                    {{ old('status') === 'tersedia' ? 'selected' : '' }}
                >
                    Tersedia
                </option>

                <option
                    value="dipinjam"
                    {{ old('status') === 'dipinjam' ? 'selected' : '' }}
                >
                    Dipinjam
                </option>

                <option
                    value="rusak"
                    {{ old('status') === 'rusak' ? 'selected' : '' }}
                >
                    Rusak
                </option>

                <option
                    value="hilang"
                    {{ old('status') === 'hilang' ? 'selected' : '' }}
                >
                    Hilang
                </option>

            </select>

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
            Simpan Eksemplar
        </button>

        <a href="{{ route('book-copies.index') }}">
            Batal
        </a>

    </form>

</body>

</html>