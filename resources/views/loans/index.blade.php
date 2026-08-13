<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Peminjaman - PAG Library</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background-color: #f5f7fa;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* HEADER */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #111827;
        }

        .page-subtitle {
            margin-top: 6px;
            color: #6b7280;
            font-size: 14px;
        }

        /* BUTTON */
        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        .btn-secondary {
            background-color: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background-color: #d1d5db;
        }

        /* FILTER */
        .filter-card {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .filter-title {
            margin: 0 0 15px;
            font-size: 16px;
            font-weight: 700;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 15px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: white;
            font-size: 14px;
            outline: none;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 8px;
        }

        /* ALERT */
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        /* TABLE */
        .table-card {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        thead {
            background-color: #f9fafb;
        }

        th {
            padding: 14px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        td {
            padding: 15px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            vertical-align: middle;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .number {
            color: #6b7280;
            font-weight: 600;
            width: 60px;
        }

        .loan-id {
            font-weight: 700;
            color: #111827;
        }

        .user-name {
            font-weight: 600;
            color: #111827;
        }

        .user-nopek {
            margin-top: 3px;
            color: #9ca3af;
            font-size: 12px;
        }

        .date {
            white-space: nowrap;
            color: #374151;
        }

        .book-count {
            font-weight: 600;
            text-align: center;
        }

        .fine {
            font-weight: 600;
            white-space: nowrap;
        }

        /* STATUS */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-borrowed {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .badge-overdue {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .badge-returned {
            background-color: #dcfce7;
            color: #15803d;
        }

        .action {
            white-space: nowrap;
        }

        .btn-detail {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 7px;
            background-color: #eff6ff;
            color: #2563eb;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-detail:hover {
            background-color: #dbeafe;
        }

        /* EMPTY */
        .empty {
            text-align: center;
            padding: 50px 20px;
            color: #6b7280;
        }

        .empty-title {
            margin-bottom: 5px;
            font-size: 16px;
            font-weight: 600;
            color: #374151;
        }

        /* PAGINATION */
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 13px;
            color: #6b7280;
        }

        .pagination {
            display: flex;
            gap: 5px;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
        }

        .pagination a {
            color: #374151;
            background-color: white;
        }

        .pagination a:hover {
            background-color: #f3f4f6;
        }

        .pagination .active {
            background-color: #2563eb;
            border-color: #2563eb;
            color: white;
        }

        .pagination .disabled {
            color: #d1d5db;
            background-color: #f9fafb;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            body {
                padding: 20px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                width: 100%;
            }

            .filter-actions .btn {
                flex: 1;
                text-align: center;
            }

            .pagination-wrapper {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="container">

    {{-- HEADER --}}
    <div class="page-header">

        <div>
            <h1 class="page-title">
                Daftar Peminjaman
            </h1>

            <div class="page-subtitle">
                Kelola data peminjaman buku perpustakaan
            </div>
        </div>

        <a href="{{ route('loans.create') }}" class="btn btn-primary">
            + Tambah Peminjaman
        </a>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- FILTER --}}
    <div class="filter-card">

        <h2 class="filter-title">
            Filter Peminjaman
        </h2>

        <form action="{{ route('loans.index') }}" method="GET">

            <div class="filter-grid">

                {{-- SEARCH --}}
                <div class="form-group">

                    <label for="search">
                        Cari Peminjam
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        placeholder="Nama atau Nopek..."
                        value="{{ request('search') }}"
                    >

                </div>


                {{-- STATUS --}}
                <div class="form-group">

                    <label for="status">
                        Status
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="form-control"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="borrowed"
                            {{ request('status') == 'borrowed' ? 'selected' : '' }}
                        >
                            Dipinjam
                        </option>

                        <option
                            value="overdue"
                            {{ request('status') == 'overdue' ? 'selected' : '' }}
                        >
                            Terlambat
                        </option>

                        <option
                            value="returned"
                            {{ request('status') == 'returned' ? 'selected' : '' }}
                        >
                            Dikembalikan
                        </option>

                    </select>

                </div>


                {{-- TANGGAL --}}
                <div class="form-group">

                    <label for="loan_date">
                        Tanggal Pinjam
                    </label>

                    <input
                        type="date"
                        id="loan_date"
                        name="loan_date"
                        class="form-control"
                        value="{{ request('loan_date') }}"
                    >

                </div>


                {{-- BUTTON --}}
                <div class="filter-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('loans.index') }}"
                        class="btn btn-secondary"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- TABLE --}}
    <div class="table-card">

        @if ($loans->count() > 0)

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>No</th>
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

                                {{-- NOMOR URUT --}}
                                <td class="number">
                                    {{ $loans->firstItem() + $loop->index }}
                                </td>


                                {{-- ID --}}
                                <td class="loan-id">
                                    #{{ $loan->loan_id }}
                                </td>


                                {{-- PEMINJAM --}}
                                <td>

                                    <div class="user-name">
                                        {{ $loan->user->name ?? '-' }}
                                    </div>

                                    <div class="user-nopek">
                                        {{ $loan->user->nopek ?? '-' }}
                                    </div>

                                </td>


                                {{-- TANGGAL PINJAM --}}
                                <td class="date">
                                    {{ \Carbon\Carbon::parse($loan->loan_date)->format('d-m-Y') }}
                                </td>


                                {{-- JATUH TEMPO --}}
                                <td class="date">
                                    {{ \Carbon\Carbon::parse($loan->due_date)->format('d-m-Y') }}
                                </td>


                                {{-- JUMLAH BUKU --}}
                                <td class="book-count">
                                    {{ $loan->loan_details_count }}
                                </td>


                                {{-- TOTAL DENDA --}}
                                <td class="fine">

                                    Rp{{ number_format(
                                        $loan->loanDetails->sum('fine'),
                                        2,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if ($loan->status === 'borrowed')

                                        <span class="badge badge-borrowed">
                                            Dipinjam
                                        </span>

                                    @elseif ($loan->status === 'overdue')

                                        <span class="badge badge-overdue">
                                            Terlambat
                                        </span>

                                    @elseif ($loan->status === 'returned')

                                        <span class="badge badge-returned">
                                            Dikembalikan
                                        </span>

                                    @else

                                        <span class="badge">
                                            {{ $loan->status }}
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="action">

                                    <a
                                        href="{{ route('loans.show', $loan->loan_id) }}"
                                        class="btn-detail"
                                    >
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            <div class="pagination-wrapper">

                <div>
                    Menampilkan
                    <strong>{{ $loans->firstItem() }}</strong>
                    sampai
                    <strong>{{ $loans->lastItem() }}</strong>
                    dari
                    <strong>{{ $loans->total() }}</strong>
                    peminjaman
                </div>

                <div class="pagination">

                    @if ($loans->onFirstPage())

                        <span class="disabled">
                            ‹
                        </span>

                    @else

                        <a href="{{ $loans->previousPageUrl() }}">
                            ‹
                        </a>

                    @endif


                    @foreach ($loans->getUrlRange(1, $loans->lastPage()) as $page => $url)

                        @if ($page == $loans->currentPage())

                            <span class="active">
                                {{ $page }}
                            </span>

                        @else

                            <a href="{{ $url }}">
                                {{ $page }}
                            </a>

                        @endif

                    @endforeach


                    @if ($loans->hasMorePages())

                        <a href="{{ $loans->nextPageUrl() }}">
                            ›
                        </a>

                    @else

                        <span class="disabled">
                            ›
                        </span>

                    @endif

                </div>

            </div>

        @else

            <div class="empty">

                <div class="empty-title">
                    Tidak ada data peminjaman
                </div>

                <div>
                    Belum ada peminjaman yang sesuai dengan filter.
                </div>

            </div>

        @endif

    </div>

</div>

</body>
</html>