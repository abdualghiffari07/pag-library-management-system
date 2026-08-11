<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - PAG Library</title>
</head>

<body>

```
<h1>Tambah Buku</h1>

<a href="{{ route('books.index') }}">← Kembali ke Daftar Buku</a>

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

<form action="{{ route('books.store') }}" method="POST">
    @csrf

    <div>
        <label for="title">Judul Buku</label>
        <br>
        <input
            type="text"
            id="title"
            name="title"
            value="{{ old('title') }}"
            required
        >
    </div>

    <br>

    <div>
        <label for="origin">Asal</label>
        <br>
        <input
            type="text"
            id="origin"
            name="origin"
            value="{{ old('origin') }}"
        >
    </div>

    <br>

    <div>
        <label for="cover">Cover</label>
        <br>
        <input
            type="text"
            id="cover"
            name="cover"
            value="{{ old('cover') }}"
            placeholder="Contoh: books/laravel.jpg"
        >
    </div>

    <br>

    <div>
        <label for="publication_year">Tahun Terbit</label>
        <br>
        <input
            type="number"
            id="publication_year"
            name="publication_year"
            value="{{ old('publication_year') }}"
            min="1000"
            max="2100"
        >
    </div>

    <br>

    <div>
        <label for="location_id">Lokasi</label>
        <br>

        <select name="location_id" id="location_id">
            <option value="">-- Pilih Lokasi --</option>

            @foreach ($locations as $location)
                <option
                    value="{{ $location->location_id }}"
                    {{ old('location_id') == $location->location_id ? 'selected' : '' }}
                >
                    {{ $location->location_name }}
                </option>
            @endforeach
        </select>
    </div>

    <br>

    <div>
        <label for="status">Status</label>
        <br>

        <select name="status" id="status" required>
            <option value="public" {{ old('status') == 'public' ? 'selected' : '' }}>
                Public
            </option>

            <option value="arsip" {{ old('status') == 'arsip' ? 'selected' : '' }}>
                Arsip
            </option>
        </select>
    </div>

    <br>

    <div>
        <label for="description">Deskripsi</label>
        <br>

        <textarea
            id="description"
            name="description"
            rows="5"
            cols="50"
        >{{ old('description') }}</textarea>
    </div>

    <br>

    <div>
        <label for="authors">Penulis</label>
        <br>

        <select name="authors[]" id="authors" multiple>
            @foreach ($authors as $author)
                <option
                    value="{{ $author->author_id }}"
                    {{ in_array($author->author_id, old('authors', [])) ? 'selected' : '' }}
                >
                    {{ $author->author_name }}
                </option>
            @endforeach
        </select>

        <br>
        <small>
            Gunakan Ctrl + klik untuk memilih lebih dari satu penulis.
        </small>
    </div>

    <br>

    <div>
        <label for="categories">Kategori</label>
        <br>

        <select name="categories[]" id="categories" multiple>
            @foreach ($categories as $category)
                <option
                    value="{{ $category->category_id }}"
                    {{ in_array($category->category_id, old('categories', [])) ? 'selected' : '' }}
                >
                    {{ $category->category_name }}
                </option>
            @endforeach
        </select>

        <br>
        <small>
            Gunakan Ctrl + klik untuk memilih lebih dari satu kategori.
        </small>
    </div>

    <br>

    <button type="submit">
        Simpan Buku
    </button>

    <a href="{{ route('books.index') }}">
        Batal
    </a>

</form>
```

</body>
</html>
