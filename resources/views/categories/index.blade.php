<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Kategori - PAG Library</title>
</head>

<body>

    <h1>Daftar Kategori</h1>

    <a href="{{ route('categories.create') }}">
        Tambah Kategori
    </a>

    <br><br>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    @if ($categories->count() > 0)

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($categories as $category)

                    <tr>
                        <td>
                            {{ $category->category_id }}
                        </td>

                        <td>
                            {{ $category->category_name }}
                        </td>

                        <td>

                            <a href="{{ route('categories.show', $category->category_id) }}">
                                Detail
                            </a>

                            |

                            <a href="{{ route('categories.edit', $category->category_id) }}">
                                Edit
                            </a>

                            |

                            <form
                                action="{{ route('categories.destroy', $category->category_id) }}"
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

        <p>Belum ada data kategori.</p>

    @endif

</body>
</html>