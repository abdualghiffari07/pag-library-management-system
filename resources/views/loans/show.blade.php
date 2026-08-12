<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Peminjaman - PAG Library</title>
</head>

<body>

    <h1>Detail Peminjaman</h1>

    <a href="{{ route('loans.index') }}">
        ← Kembali ke Daftar Peminjaman
    </a>

    <hr>

    <h2>Informasi Peminjaman</h2>

        @if ($loan->status !== 'returned')

        <hr>

        <h2>Pengembalian Buku</h2>

        <form
            action="{{ route('loans.return', $loan->loan_id) }}"
            method="POST"
        >

            @csrf

            <div>
                <label for="returned_date">
                    Tanggal Pengembalian
                </label>

                <br>

                <input
                    type="date"
                    id="returned_date"
                    name="returned_date"
                    value="{{ date('Y-m-d') }}"
                    required
                >
            </div>

            <br>

            <button type="submit">
                Kembalikan Buku
            </button>

        </form>

    @endif

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID Peminjaman</th>
            <td>{{ $loan->loan_id }}</td>
        </tr>

        <tr>
            <th>Peminjam</th>
            <td>
                {{ $loan->user->name ?? '-' }}
            </td>
        </tr>

        <tr>
            <th>Nopek</th>
            <td>
                {{ $loan->user->nopek ?? '-' }}
            </td>
        </tr>

        <tr>
            <th>Tanggal Pinjam</th>
            <td>
                {{ $loan->loan_date?->format('d-m-Y') ?? '-' }}
            </td>
        </tr>

        <tr>
            <th>Jatuh Tempo</th>
            <td>
                {{ $loan->due_date?->format('d-m-Y') ?? '-' }}
            </td>
        </tr>

        <tr>
            <th>Tanggal Dikembalikan</th>
            <td>
                {{ $loan->returned_date?->format('d-m-Y') ?? '-' }}
            </td>
        </tr>

        <tr>
            <th>Status</th>
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
        </td>
        </tr>

        <tr>
            <th>Catatan</th>
            <td>
                {{ $loan->notes ?? '-' }}
            </td>
        </tr>

    </table>

    <br>

    <h2>Buku yang Dipinjam</h2>

    @if ($loan->loanDetails->count() > 0)

        <table border="1" cellpadding="10" cellspacing="0">

    <thead>
        <tr>
            <th>No</th>
            <th>ID Buku</th>
            <th>Judul Buku</th>
            <th>Status</th>
            <th>Tanggal Dikembalikan</th>
            <th>Kondisi</th>
            <th>Denda</th>
            <th>Catatan</th>
            <th>Aksi</th>
        </tr>
    </thead>

            <tbody>

        @foreach ($loan->loanDetails as $index => $detail)

            <tr>

                <td>
                    {{ $index + 1 }}
                </td>

                <td>
                    {{ $detail->book->book_id ?? '-' }}
                </td>

                <td>
                    {{ $detail->book->title ?? '-' }}
                </td>

                {{-- STATUS --}}
                <td>
                    @if ($detail->returned_date)
                        Dikembalikan
                    @else
                        Dipinjam
                    @endif
                </td>

                {{-- TANGGAL DIKEMBALIKAN --}}
                <td>
                    {{ $detail->returned_date?->format('d-m-Y') ?? '-' }}
                </td>

                {{-- KONDISI --}}
                <td>
                    {{ $detail->condition ?? '-' }}
                </td>

                {{-- DENDA --}}
                <td>
                    Rp{{ number_format($detail->fine ?? 0, 2, ',', '.') }}
                </td>

                {{-- CATATAN --}}
                <td>
                    {{ $detail->notes ?? '-' }}
                </td>

                {{-- AKSI --}}
                <td>

                    @if (!$detail->returned_date)

                        <form
                            action="{{ route('loan-details.return', $detail->loan_detail_id) }}"
                            method="POST"
                        >

                            @csrf

                            <div>
                                <label>
                                    Tanggal Pengembalian
                                </label>

                                <br>

                                <input
                                    type="date"
                                    name="returned_date"
                                    value="{{ date('Y-m-d') }}"
                                    required
                                >
                            </div>

                            <br>

                            <div>
                                <label>
                                    Kondisi
                                </label>

                                <br>

                                <select name="condition" required>
                                    <option value="">
                                        -- Pilih Kondisi --
                                    </option>

                                    <option value="Baik">
                                        Baik
                                    </option>

                                    <option value="Rusak Ringan">
                                        Rusak Ringan
                                    </option>

                                    <option value="Rusak Berat">
                                        Rusak Berat
                                    </option>

                                    <option value="Hilang">
                                        Hilang
                                    </option>
                                </select>
                            </div>

                            <br>

                            <div>
                                <label>
                                    Catatan
                                </label>

                                <br>

                                <textarea
                                    name="notes"
                                    rows="3"
                                    cols="30"
                                ></textarea>
                            </div>

                            <br>

                            <button type="submit">
                                Kembalikan Buku
                            </button>

                        </form>

                    @else

                        Sudah Dikembalikan

                    @endif
                    
                <br>

                <h3>Total Denda</h3>

                <p>
                    <strong>
                        Rp{{ number_format($loan->loanDetails->sum('fine'), 2, ',', '.') }}
                    </strong>
                </p>

                </td>

            </tr>

        @endforeach

    </tbody>

        </table>

    @else

        <p>
            Tidak ada buku dalam transaksi ini.
        </p>

    @endif

</body>
</html>