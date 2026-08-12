<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Lokasi - PAG Library</title>
</head>

<body>

    <h1>Edit Lokasi</h1>

    <a href="{{ route('locations.index') }}">
        ← Kembali ke Daftar Lokasi
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
        action="{{ route('locations.update', $location->location_id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div>
            <label for="location_name">
                Nama Lokasi
            </label>

            <br>

            <input
                type="text"
                id="location_name"
                name="location_name"
                value="{{ old('location_name', $location->location_name) }}"
                required
                maxlength="150"
            >
        </div>

        <br>

        <div>
            <label for="description">
                Deskripsi
            </label>

            <br>

            <textarea
                id="description"
                name="description"
                rows="5"
                cols="50"
            >{{ old('description', $location->description) }}</textarea>
        </div>

        <br>

        <button type="submit">
            Simpan Perubahan
        </button>

        <a href="{{ route('locations.index') }}">
            Batal
        </a>

    </form>

</body>
</html>