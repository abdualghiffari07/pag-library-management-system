<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Eksemplar - PAG Library</title>
</head>

<body>

    <h1>Edit Eksemplar Buku</h1>

    <a href="{{ route('books.show', $copy->book_id) }}">
        ← Kembali ke Detail Buku
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

    <form
        action="{{ route('book-copies.update', $copy->copy_id) }}"
        method="POST"
    >

        @csrf

        @method('PUT')

        <div>

            <label>
                Buku
            </label>

            <br>

            <input
                type="text"
                value="{{ $copy->book->title ?? '-' }}"
                readonly
            >

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
                value="{{ old('copy_code', $copy->copy_code) }}"
                required
            >

        </div>

        <br>

        <div>

            <label for="condition">
                Kondisi
            </label>

            <br>

            <select
                name="condition"
                id="condition"
                required
            >

                <option value="">
                    -- Pilih Kondisi --
                </option>

                <option
                    value="baik"
                    {{ old('condition', $copy->condition) === 'baik' ? 'selected' : '' }}
                >
                    Baik
                </option>

                <option
                    value="rusak ringan"
                    {{ old('condition', $copy->condition) === 'rusak ringan' ? 'selected' : '' }}
                >
                    Rusak Ringan
                </option>

                <option
                    value="rusak berat"
                    {{ old('condition', $copy->condition) === 'rusak berat' ? 'selected' : '' }}
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

            <select
                name="status"
                id="status"
                required
            >

                <option value="">
                    -- Pilih Status --
                </option>

                <option
                    value="tersedia"
                    {{ old('status', $copy->status) === 'tersedia' ? 'selected' : '' }}
                >
                    Tersedia
                </option>

                <option
                    value="dipinjam"
                    {{ old('status', $copy->status) === 'dipinjam' ? 'selected' : '' }}
                >
                    Dipinjam
                </option>

                <option
                    value="rusak"
                    {{ old('status', $copy->status) === 'rusak' ? 'selected' : '' }}
                >
                    Rusak
                </option>

                <option
                    value="hilang"
                    {{ old('status', $copy->status) === 'hilang' ? 'selected' : '' }}
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
            >{{ old('notes', $copy->notes) }}</textarea>

        </div>

        <br>

        <button type="submit">
            Simpan Perubahan
        </button>

        <a href="{{ route('books.show', $copy->book_id) }}">
            Batal
        </a>

    </form>

</body>

</html>