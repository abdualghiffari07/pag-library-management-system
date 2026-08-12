<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Penulis - PAG Library</title>
</head>

<body>

    <h1>Daftar Penulis</h1>

    <a href="{{ route('authors.create') }}">
        Tambah Penulis
    </a>

    <br><br>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    @if ($authors->count() > 0)

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Penulis</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($authors as $author)

                    <tr>

                        <td>
                            {{ $author->author_id }}
                        </td>

                        <td>
                            {{ $author->author_name }}
                        </td>

                        <td>

                            <a href="{{ route('authors.show', $author->author_id) }}">
                                Detail
                            </a>

                            |

                            <a href="{{ route('authors.edit', $author->author_id) }}">
                                Edit
                            </a>

                            |

                            <form
                            action="{{ route('authors.destroy', $author->author_id) }}"
                            method="POST"
                            style="display:inline;"
                            >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus penulis ini?')"
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

        <p>Belum ada data penulis.</p>

    @endif

</body>
</html>