<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Kategori - PAG Library</title>
</head>

<body>

    <h1>Edit Kategori</h1>

    <a href="{{ route('categories.index') }}">
        ← Kembali ke Daftar Kategori
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
        action="{{ route('categories.update', $category->category_id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div>
            <label for="category_name">
                Nama Kategori
            </label>

            <br>

            <input
                type="text"
                id="category_name"
                name="category_name"
                value="{{ old('category_name', $category->category_name) }}"
                required
                maxlength="150"
            >
        </div>

        <br>

        <button type="submit">
            Simpan Perubahan
        </button>

        <a href="{{ route('categories.index') }}">
            Batal
        </a>

    </form>

</body>
</html>