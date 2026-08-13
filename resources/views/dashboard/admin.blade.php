<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - PAG Library</title>
</head>

<body>

    <h1>Dashboard Admin</h1>

    <p>
        Selamat datang,
        <strong>{{ $user->name }}</strong>
    </p>

    <hr>

    <h2>Statistik Perpustakaan</h2>

    <ul>
        <li>
            Total Judul Buku:
            <strong>{{ $totalBooks }}</strong>
        </li>

        <li>
            Total Eksemplar:
            <strong>{{ $totalCopies }}</strong>
        </li>

        <li>
            Eksemplar Tersedia:
            <strong>{{ $availableCopies }}</strong>
        </li>

        <li>
            Eksemplar Dipinjam:
            <strong>{{ $borrowedCopies }}</strong>
        </li>

        <li>
            Total Peminjaman:
            <strong>{{ $totalLoans }}</strong>
        </li>

        <li>
            Peminjaman Aktif:
            <strong>{{ $activeLoans }}</strong>
        </li>

        <li>
            Peminjaman Terlambat:
            <strong>{{ $overdueLoans }}</strong>
        </li>
    </ul>

    <hr>

    <h2>Menu</h2>

    <ul>

        <li>
            <a href="{{ route('books.index') }}">
                Buku
            </a>
        </li>

        <li>
            <a href="{{ route('book-copies.index') }}">
                Eksemplar Buku
            </a>
        </li>

        <li>
            <a href="{{ route('authors.index') }}">
                Penulis
            </a>
        </li>

        <li>
            <a href="{{ route('categories.index') }}">
                Kategori
            </a>
        </li>

        <li>
            <a href="{{ route('locations.index') }}">
                Lokasi
            </a>
        </li>

        <li>
            <a href="{{ route('loans.index') }}">
                Peminjaman
            </a>
        </li>

    </ul>

    <hr>

    <form method="POST" action="{{ route('logout') }}">

        @csrf

        <button type="submit">
            Logout
        </button>

    </form>

</body>

</html>