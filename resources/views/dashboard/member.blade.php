<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Dashboard Member - PAG Library</title>
</head>

<body>

    <h1>Dashboard Member</h1>

    <p>
        Selamat datang,
        <strong>{{ $user->name }}</strong>
    </p>

    <hr>

    <h2>Peminjaman Saya</h2>

    @if ($myLoans->count() > 0)

        @foreach ($myLoans as $loan)

            <div>

                <h3>
                    Peminjaman #{{ $loan->loan_id }}
                </h3>

                <p>
                    Tanggal Pinjam:
                    {{ $loan->loan_date?->format('d-m-Y') }}
                </p>

                <p>
                    Jatuh Tempo:
                    {{ $loan->due_date?->format('d-m-Y') }}
                </p>

                <p>
                    Status:
                    <strong>
                        {{ $loan->status }}
                    </strong>
                </p>

                <h4>Buku:</h4>

                <ul>

                    @foreach ($loan->loanDetails as $detail)

                        <li>

                            {{ $detail->book->title }}

                            @if ($detail->bookCopy)

                                — Eksemplar:
                                {{ $detail->bookCopy->copy_code }}

                            @endif

                            @if ($detail->returned_date)

                                — Sudah dikembalikan

                            @else

                                — Belum dikembalikan

                            @endif

                        </li>

                    @endforeach

                </ul>

                <hr>

            </div>

        @endforeach

    @else

        <p>
            Anda belum memiliki riwayat peminjaman.
        </p>

    @endif


    <h2>Menu</h2>

    <ul>

        <li>
            <a href="{{ route('books.index') }}">
                Cari / Lihat Buku
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