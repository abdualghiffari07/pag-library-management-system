<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Lokasi - PAG Library</title>
</head>

<body>

    <h1>Daftar Lokasi</h1>

    <a href="{{ route('locations.create') }}">
        Tambah Lokasi
    </a>

    <br><br>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    @if ($locations->count() > 0)

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Lokasi</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($locations as $location)

                    <tr>

                        <td>
                            {{ $location->location_id }}
                        </td>

                        <td>
                            {{ $location->location_name }}
                        </td>

                        <td>
                            {{ $location->description ?? '-' }}
                        </td>

                        <td>

                            <a href="{{ route('locations.show', $location->location_id) }}">
                                Detail
                            </a>

                            |

                            <a href="{{ route('locations.edit', $location->location_id) }}">
                                Edit
                            </a>

                            |

                            <form
                                action="{{ route('locations.destroy', $location->location_id) }}"
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

        <p>Belum ada data lokasi.</p>

    @endif

</body>
</html>