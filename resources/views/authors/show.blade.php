<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Penulis - PAG Library</title>
</head>

<body>

    <h1>Detail Penulis</h1>

    <a href="{{ route('authors.index') }}">
        ← Kembali ke Daftar Penulis
    </a>

    <hr>

    <p>
        <strong>ID:</strong>
        {{ $author->author_id }}
    </p>

    <p>
        <strong>Nama Penulis:</strong>
        {{ $author->author_name }}
    </p>

    <hr>

    <h2>Daftar Buku</h2>

    @if ($author->books->count() > 0)

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Buku</th>
                    <th>Tahun Terbit</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($author->books as $book)

                    <tr>
                        <td>
                            {{ $book->book_id }}
                        </td>

                        <td>
                            {{ $book->title }}
                        </td>

                        <td>
                            {{ $book->publication_year ?? '-' }}
                        </td>
                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <p>
            Penulis ini belum memiliki buku.
        </p>

    @endif

</body>

</html>