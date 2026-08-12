<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Eksemplar - PAG Library</title>
</head>

<body>

    <h1>Daftar Eksemplar Buku</h1>

    <a href="{{ route('book-copies.create') }}">
        + Tambah Eksemplar
    </a>

    <hr>

    @if (session('success'))
        <p>
            {{ session('success') }}
        </p>
    @endif

    @if ($copies->count() > 0)

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Kode Eksemplar</th>
                    <th>Judul Buku</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($copies as $index => $copy)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $copy->copy_code }}
                        </td>

                        <td>
                            {{ $copy->book->title ?? '-' }}
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

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <p>
            Belum ada eksemplar buku.
        </p>

    @endif

</body>

</html>