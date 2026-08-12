<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Penulis - PAG Library</title>
</head>

<body>

    <h1>Edit Penulis</h1>

    <a href="{{ route('authors.index') }}">
        ← Kembali ke Daftar Penulis
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
        action="{{ route('authors.update', $author->author_id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div>
            <label for="author_name">
                Nama Penulis
            </label>

            <br>

            <input
                type="text"
                id="author_name"
                name="author_name"
                value="{{ old('author_name', $author->author_name) }}"
                required
            >
        </div>

        <br>

        <button type="submit">
            Simpan Perubahan
        </button>

        <a href="{{ route('authors.index') }}">
            Batal
        </a>

    </form>

</body>

</html>