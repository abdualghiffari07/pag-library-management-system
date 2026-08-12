<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Buku - PAG Library</title>
</head>

<body>

    <h1>Detail Buku</h1>

    <a href="{{ route('books.index') }}">
        ← Kembali ke Daftar Buku
    </a>

    <hr>

        @if (session('success'))
        <p>
            {{ session('success') }}
        </p>
        @endif

        @if (session('error'))
            <p>
                {{ session('error') }}
            </p>
        @endif


    <h2>{{ $book->title }}</h2>

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID Buku</th>
            <td>{{ $book->book_id }}</td>
        </tr>

        <tr>
            <th>Judul</th>
            <td>{{ $book->title }}</td>
        </tr>

        <tr>
            <th>Asal</th>
            <td>{{ $book->origin ?? '-' }}</td>
        </tr>

        <tr>
            <th>Tahun Terbit</th>
            <td>{{ $book->publication_year ?? '-' }}</td>
        </tr>

        <tr>
            <th>Status Buku</th>
            <td>{{ $book->status ?? '-' }}</td>
        </tr>

        <tr>
            <th>Deskripsi</th>
            <td>{{ $book->description ?? '-' }}</td>
        </tr>

    </table>

    <br>

    <h2>Daftar Eksemplar</h2>

    @if ($book->copies->count() > 0)

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Eksemplar</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($book->copies as $index => $copy)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $copy->copy_code }}
                        </td>

                        <td>
                            {{ $copy->condition }}
                        </td>

                        <td>
                            @if ($copy->status === 'tersedia')
                                Tersedia
                            @elseif ($copy->status === 'dipinjam')
                                Dipinjam
                            @elseif ($copy->status === 'rusak')
                                Rusak
                            @elseif ($copy->status === 'hilang')
                                Hilang
                            @else
                                {{ $copy->status }}
                            @endif
                        </td>

                        <td>
                            {{ $copy->notes ?? '-' }}
                        </td>

                        <td>

                            <a href="{{ route('book-copies.edit', $copy->copy_id) }}">
                                Edit
                            </a>

                            <form
                                action="{{ route('book-copies.destroy', $copy->copy_id) }}"
                                method="POST"
                                style="display: inline;"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus eksemplar ini?')"
                                >
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <p>
            Belum ada eksemplar untuk buku ini.
        </p>

    @endif

</body>

</html>