<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Peminjaman - PAG Library</title>
</head>

<body>

    <h1>Daftar Peminjaman</h1>

    <a href="{{ route('loans.create') }}">
        Tambah Peminjaman
    </a>

    <br><br>

    <form action="{{ route('loans.index') }}" method="GET">

        <label for="search">
            Cari Peminjam
        </label>

        <input
            type="text"
            id="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Nama atau Nopek"
        >

        <br><br>

        <label for="status">
            Status
        </label>

        <select name="status" id="status">

            <option value="">
                -- Semua Status --
            </option>

            <option
                value="borrowed"
                {{ request('status') === 'borrowed' ? 'selected' : '' }}
            >
                Dipinjam
            </option>

            <option
                value="overdue"
                {{ request('status') === 'overdue' ? 'selected' : '' }}
            >
                Terlambat
            </option>

            <option
                value="returned"
                {{ request('status') === 'returned' ? 'selected' : '' }}
            >
                Dikembalikan
            </option>

        </select>

        <br><br>

        <label for="loan_date">
            Tanggal Pinjam
        </label>

        <input
            type="date"
            id="loan_date"
            name="loan_date"
            value="{{ request('loan_date') }}"
        >

        <br><br>

        <button type="submit">
            Cari
        </button>

        <a href="{{ route('loans.index') }}">
            Reset
        </a>

    </form>

    <br>


    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>

        <br>
    @endif

    @if ($loans->count() > 0)

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Peminjam</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Jumlah Buku</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($loans as $loan)

                    <tr>

                        {{-- ID --}}
                        <td>
                            {{ $loan->loan_id }}
                        </td>

                        {{-- PEMINJAM --}}
                        <td>

                            {{ $loan->user->name ?? '-' }}

                            <br>

                            <small>
                                {{ $loan->user->nopek ?? '-' }}
                            </small>

                        </td>

                        {{-- TANGGAL PINJAM --}}
                        <td>
                            {{ $loan->loan_date?->format('d-m-Y') ?? '-' }}
                        </td>

                        {{-- JATUH TEMPO --}}
                        <td>
                            {{ $loan->due_date?->format('d-m-Y') ?? '-' }}
                        </td>

                        {{-- JUMLAH BUKU --}}
                        <td>
                            {{ $loan->loan_details_count }}
                        </td>

                        {{-- TOTAL DENDA --}}
                        <td>
                            Rp{{ number_format($loan->loanDetails->sum('fine'), 2, ',', '.') }}
                        </td>

                        {{-- STATUS --}}
                        <td>

                            @if ($loan->status === 'borrowed')

                                Dipinjam

                            @elseif ($loan->status === 'overdue')

                                Terlambat

                            @elseif ($loan->status === 'returned')

                                Dikembalikan

                            @else

                                {{ $loan->status }}

                            @endif

                            @if ($loans->hasPages())

                                <br>

                                {{ $loans->links() }}

                            @endif

                        </td>

                        {{-- AKSI --}}
                        <td>

                            <a href="{{ route('loans.show', $loan->loan_id) }}">
                                Detail
                            </a>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <p>
            Belum ada data peminjaman.
        </p>

    @endif

</body>

</html>