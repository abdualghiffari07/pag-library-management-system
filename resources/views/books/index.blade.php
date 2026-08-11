<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Buku - PAG Library</title>
</head>
<body>

    <h1>Daftar Buku</h1>

    <a href="{{ route('books.create') }}">
        Tambah Buku
    </a>

    <br><br>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    @if ($books->count() > 0)

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Asal</th>
                    <th>Tahun Terbit</th>
                    <th>Status</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($books as $book)

                    <tr>
                        <td>{{ $book->book_id }}</td>

                        <td>
                            {{ $book->title }}
                        </td>

                        <td>
                            {{ $book->origin ?? '-' }}
                        </td>

                        <td>
                            {{ $book->publication_year ?? '-' }}
                        </td>

                        <td>
                            {{ $book->status }}
                        </td>

                        <td>
                            {{ $book->location->location_name ?? '-' }}
                        </td>

                        <td>
                            <a href="{{ route('books.show', $book->book_id) }}">
                                Detail
                            </a>

                            |

                            <a href="{{ route('books.edit', $book->book_id) }}">
                                Edit
                            </a>

                            |

                            <form
                                action="{{ route('books.destroy', $book->book_id) }}"
                                method="POST"
                                style="display:inline;"
                            >

                                @csrf
                                @method('DELETE')

                                <button type="submit">
                                    Hapus
                                </button>

                            </form>
                        </td>
                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <p>Belum ada data buku.</p>

    @endif

</body>
</html>