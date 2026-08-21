<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\LoanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    // =========================================================
    // TAMBAH BUKU
    // =========================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_no' => [
                'required',
                'string',
                'max:100',
                Rule::unique('books', 'book_code'),
            ],

            'cat_no' => 'required|string|max:200',

            'rack' => 'required|string|max:255',

            'title' => 'required|string|max:255',

            'author' => 'required|string|max:255',

            'publisher' => 'required|string|max:510',

            'qty' => 'required|integer|min:1',

            'description' => 'nullable|string',
        ], $this->bookValidationMessages());

        try {

            DB::transaction(function () use ($validated) {

                // =================================================
                // SIMPAN DATA BUKU
                // =================================================

                $book = Book::create([
                    'book_code' => $validated['book_no'],
                    'cat_no' => $validated['cat_no'],
                    'rack' => $validated['rack'],
                    'title' => $validated['title'],
                    'publisher' => $validated['publisher'],
                    'description' => $validated['description'] ?? null,
                    'status' => 'public',
                ]);

                // =================================================
                // SIMPAN AUTHOR
                // =================================================

                $author = Author::firstOrCreate([
                    'author_name' => trim($validated['author']),
                ]);

                $book->authors()->attach($author->author_id);

                // =================================================
                // BUAT EKSEMPLAR BUKU
                // =================================================

                for ($i = 1; $i <= $validated['qty']; $i++) {

                    BookCopy::create([
                        'book_id' => $book->book_id,

                        'copy_code' =>
                            $book->book_code .
                            '-' .
                            str_pad($i, 3, '0', STR_PAD_LEFT),

                        'condition' => 'Baik',

                        'status' => 'Tersedia',
                    ]);
                }
            });

            return redirect()
                ->route('data-buku')
                ->with(
                    'success',
                    'Buku berhasil ditambahkan.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal menambahkan buku: ' .
                    $e->getMessage()
                );
        }
    }


    // =========================================================
    // CEK BOOK NO.
    // =========================================================

    public function checkBookNo(Request $request)
    {
        $bookNo = trim(
            $request->query('book_no', '')
        );

        if ($bookNo === '') {

            return response()->json([
                'exists' => false,
                'valid' => false,
                'message' => 'Book No. wajib diisi.',
            ]);
        }

        $exists = Book::where(
            'book_code',
            $bookNo
        )->exists();

        return response()->json([
            'exists' => $exists,

            'valid' => !$exists,

            'message' => $exists
                ? 'Book No. sudah digunakan.'
                : 'Book No. tersedia.',
        ]);
    }


    // =========================================================
    // EDIT BUKU
    // =========================================================

    public function edit(string $book_id)
    {
        $book = Book::with([
            'authors',
            'copies',
        ])
            ->where('book_id', $book_id)
            ->first();

        if (!$book) {

            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Buku tidak ditemukan.'
                );
        }

        // =====================================================
        // JUMLAH EKSEMPLAR
        // =====================================================

        $totalQty = $book->copies->count();

        $borrowedCopies = $book->copies
            ->where('status', 'Dipinjam')
            ->count();

        $availableCopies = $book->copies
            ->where('status', 'Tersedia')
            ->count();


        // =====================================================
        // PEMINJAMAN AKTIF
        // =====================================================

        $activeLoans = LoanDetail::with([
            'loan',
            'bookCopy',
        ])
            ->where(
                'book_id',
                $book->book_id
            )
            ->whereNull('returned_date')
            ->whereHas('loan', function ($query) {

                $query->where(
                    'status',
                    'borrowed'
                );
            })
            ->orderBy('loan_detail_id')
            ->get();


        return view(
            'pages.tables.books.edit-books',
            [
                'title' => 'Edit Buku',

                'book' => $book,

                'totalQty' => $totalQty,

                'borrowedCopies' => $borrowedCopies,

                'availableCopies' => $availableCopies,

                'activeLoans' => $activeLoans,
            ]
        );
    }


    // =========================================================
    // PINJAM BUKU
    // =========================================================

    public function borrow(
        Request $request,
        string $book_id
    ) {

        $validated = $request->validate([

            'borrower_name' =>
                'required|string|max:255',

            'nopek' =>
                'nullable|string|max:100',

            'loan_date' =>
                'required|date',

        ], [

            'borrower_name.required' =>
                'Nama peminjam wajib diisi.',

            'borrower_name.max' =>
                'Nama peminjam terlalu panjang.',

            'nopek.max' =>
                'No. Pekerja terlalu panjang.',

            'loan_date.required' =>
                'Tanggal peminjaman wajib diisi.',

            'loan_date.date' =>
                'Tanggal peminjaman tidak valid.',
        ]);


        $book = Book::where(
            'book_id',
            $book_id
        )->first();


        if (!$book) {

            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Buku tidak ditemukan.'
                );
        }


        try {

            DB::transaction(
                function () use (
                    $book,
                    $validated
                ) {

                    // =========================================
                    // CARI EKSEMPLAR TERSEDIA
                    // =========================================

                    $copy = $book->copies()
                        ->whereRaw(
                            "LOWER(status) = 'tersedia'"
                        )
                        ->orderBy('copy_id')
                        ->lockForUpdate()
                        ->first();


                    if (!$copy) {

                        throw new \Exception(
                            'Tidak ada eksemplar buku yang tersedia.'
                        );
                    }


                    // =========================================
                    // BUAT DATA PEMINJAMAN
                    // =========================================

                    $loan = Loan::create([

                        'borrower_name' =>
                            trim(
                                $validated['borrower_name']
                            ),

                        'nopek' =>
                            !empty($validated['nopek'])
                                ? trim(
                                    $validated['nopek']
                                )
                                : null,

                        'loan_date' =>
                            $validated['loan_date'],

                        'due_date' =>
                            now()
                                ->addDays(7)
                                ->toDateString(),

                        'status' =>
                            'borrowed',

                        'returned_date' =>
                            null,

                        'notes' =>
                            null,
                    ]);


                    // =========================================
                    // DETAIL PEMINJAMAN
                    // =========================================

                    LoanDetail::create([

                        'loan_id' =>
                            $loan->loan_id,

                        'book_id' =>
                            $book->book_id,

                        'copy_id' =>
                            $copy->copy_id,

                        'returned_date' =>
                            null,

                        'condition' =>
                            $copy->condition,

                        'fine' =>
                            0,

                        'notes' =>
                            null,
                    ]);


                    // =========================================
                    // UBAH STATUS EKSEMPLAR
                    // =========================================

                    $copy->update([
                        'status' => 'Dipinjam',
                    ]);
                }
            );


            return redirect()
                ->route(
                    'books.edit',
                    [
                        'book_id' =>
                            $book->book_id,
                    ]
                )
                ->with(
                    'success',
                    'Buku berhasil dipinjam oleh ' .
                    $validated['borrower_name'] .
                    '.'
                );


        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal meminjam buku: ' .
                    $e->getMessage()
                );
        }
    }


    // =========================================================
    // KEMBALIKAN BUKU
    // =========================================================

    public function returnBook(
        string $book_id,
        string $loan_detail_id
    ) {

        $book = Book::where(
            'book_id',
            $book_id
        )->first();


        if (!$book) {

            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Buku tidak ditemukan.'
                );
        }


        try {

            DB::transaction(
                function () use (
                    $book,
                    $loan_detail_id
                ) {

                    // =========================================
                    // CARI DETAIL PEMINJAMAN YANG BENAR
                    // =========================================

                    $loanDetail = LoanDetail::with([
                        'loan',
                        'bookCopy',
                    ])
                        ->where(
                            'loan_detail_id',
                            $loan_detail_id
                        )
                        ->where(
                            'book_id',
                            $book->book_id
                        )
                        ->whereNull(
                            'returned_date'
                        )
                        ->whereHas(
                            'loan',
                            function ($query) {

                                $query->where(
                                    'status',
                                    'borrowed'
                                );
                            }
                        )
                        ->lockForUpdate()
                        ->first();


                    if (!$loanDetail) {

                        throw new \Exception(
                            'Data peminjaman aktif tidak ditemukan.'
                        );
                    }


                    $today =
                        now()->toDateString();


                    // =========================================
                    // DETAIL -> DIKEMBALIKAN
                    // =========================================

                    $loanDetail->update([

                        'returned_date' =>
                            $today,
                    ]);


                    // =========================================
                    // EKSEMPLAR -> TERSEDIA
                    // =========================================

                    if ($loanDetail->bookCopy) {

                        $loanDetail
                            ->bookCopy
                            ->update([
                                'status' =>
                                    'Tersedia',
                            ]);
                    }


                    // =========================================
                    // CEK PEMINJAMAN LAIN
                    // =========================================

                    $loan =
                        $loanDetail->loan;


                    $activeDetails =
                        $loan
                            ->loanDetails()
                            ->whereNull(
                                'returned_date'
                            )
                            ->count();


                    // =========================================
                    // JIKA SEMUA SUDAH DIKEMBALIKAN
                    // =========================================

                    if ($activeDetails === 0) {

                        $loan->update([

                            'status' =>
                                'returned',

                            'returned_date' =>
                                $today,
                        ]);
                    }
                }
            );


            return redirect()
                ->route(
                    'books.edit',
                    [
                        'book_id' =>
                            $book->book_id,
                    ]
                )
                ->with(
                    'success',
                    'Buku berhasil dikembalikan.'
                );


        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal mengembalikan buku: ' .
                    $e->getMessage()
                );
        }
    }


    // =========================================================
    // UPDATE BUKU
    // =========================================================

    public function update(
        Request $request,
        string $book_id
    ) {

        $book = Book::where(
            'book_id',
            $book_id
        )->firstOrFail();


        $validated = $request->validate([

            'book_no' => [

                'required',

                'string',

                'max:100',

                Rule::unique(
                    'books',
                    'book_code'
                )->ignore(
                    $book->book_id,
                    'book_id'
                ),
            ],

            'cat_no' =>
                'required|string|max:200',

            'rack' =>
                'required|string|max:255',

            'title' =>
                'required|string|max:255',

            'author' =>
                'required|string|max:255',

            'publisher' =>
                'required|string|max:510',

            'qty' =>
                'required|integer|min:1',

            'description' =>
                'nullable|string',

        ], $this->bookValidationMessages());


        try {

            DB::transaction(
                function () use (
                    $book,
                    $validated
                ) {

                    // =========================================
                    // UPDATE DATA BUKU
                    // =========================================

                    $book->update([

                        'book_code' =>
                            $validated['book_no'],

                        'cat_no' =>
                            $validated['cat_no'],

                        'rack' =>
                            $validated['rack'],

                        'title' =>
                            $validated['title'],

                        'publisher' =>
                            $validated['publisher'],

                        'description' =>
                            $validated['description']
                                ?? null,
                    ]);


                    // =========================================
                    // UPDATE AUTHOR
                    // =========================================

                    $author =
                        Author::firstOrCreate([
                            'author_name' =>
                                trim(
                                    $validated['author']
                                ),
                        ]);


                    $book->authors()->sync([
                        $author->author_id,
                    ]);


                    // =========================================
                    // UPDATE JUMLAH EKSEMPLAR
                    // =========================================

                    $currentQty =
                        $book->copies()->count();

                    $newQty =
                        (int) $validated['qty'];


                    // =========================================
                    // TAMBAH EKSEMPLAR
                    // =========================================

                    if (
                        $newQty >
                        $currentQty
                    ) {

                        for (
                            $i = $currentQty + 1;
                            $i <= $newQty;
                            $i++
                        ) {

                            BookCopy::create([

                                'book_id' =>
                                    $book->book_id,

                                'copy_code' =>
                                    $book->book_code .
                                    '-' .
                                    str_pad(
                                        $i,
                                        3,
                                        '0',
                                        STR_PAD_LEFT
                                    ),

                                'condition' =>
                                    'Baik',

                                'status' =>
                                    'Tersedia',
                            ]);
                        }
                    }


                    // =========================================
                    // KURANGI EKSEMPLAR
                    // =========================================

                    if (
                        $newQty <
                        $currentQty
                    ) {

                        $difference =
                            $currentQty -
                            $newQty;


                        $availableCopies =
                            $book->copies()
                                ->where(
                                    'status',
                                    'Tersedia'
                                )
                                ->orderByDesc(
                                    'copy_id'
                                )
                                ->limit(
                                    $difference
                                )
                                ->get();


                        if (
                            $availableCopies->count()
                            <
                            $difference
                        ) {

                            throw new \Exception(
                                'Jumlah buku tidak dapat dikurangi karena terdapat eksemplar yang sedang dipinjam.'
                            );
                        }


                        foreach (
                            $availableCopies
                            as $copy
                        ) {

                            $copy->delete();
                        }
                    }
                }
            );


            return redirect()
                ->route('data-buku')
                ->with(
                    'success',
                    'Buku berhasil diperbarui.'
                );


        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal memperbarui buku: ' .
                    $e->getMessage()
                );
        }
    }


    // =========================================================
    // HAPUS BUKU
    // =========================================================
    //
    // Tombol tetap bernama "Hapus",
    // tetapi buku sebenarnya hanya menjadi "arsip".
    //
    // =========================================================

    public function destroy(
        string $book_code
    ) {

        $book = Book::where(
            'book_code',
            $book_code
        )->first();


        if (!$book) {

            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Buku tidak ditemukan.'
                );
        }


        // =============================================
        // CEK BUKU SEDANG DIPINJAM
        // =============================================

        $borrowedCopies =
            $book->copies()
                ->whereRaw(
                    "LOWER(status) = 'dipinjam'"
                )
                ->count();


        if ($borrowedCopies > 0) {

            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Buku tidak dapat dihapus karena masih memiliki eksemplar yang sedang dipinjam.'
                );
        }


        try {

            // =============================================
            // BUKU TIDAK DIHAPUS DARI DATABASE
            // HANYA DIUBAH MENJADI ARSIP
            // =============================================

            $book->update([
                'status' => 'arsip',
            ]);


            // =============================================
            // BERSIHKAN SESSION PEMINJAM
            // =============================================

            session()->forget(
                "book_borrowers.{$book->book_id}"
            );


            return redirect()
                ->route('data-buku')
                ->with(
                    'success',
                    'Buku "' .
                    $book->book_code .
                    '" berhasil dihapus.'
                );


        } catch (\Throwable $e) {

            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Gagal menghapus buku: ' .
                    $e->getMessage()
                );
        }
    }


    // =========================================================
    // PESAN VALIDASI
    // =========================================================

    private function bookValidationMessages(): array
    {
        return [

            'book_no.required' =>
                'Book No. wajib diisi.',

            'book_no.unique' =>
                'Book No. sudah digunakan. Silakan gunakan Book No. lain.',

            'cat_no.required' =>
                'Cat. No. wajib diisi.',

            'rack.required' =>
                'Rak wajib diisi.',

            'rack.max' =>
                'Nama rak terlalu panjang.',

            'title.required' =>
                'Judul buku wajib diisi.',

            'author.required' =>
                'Author wajib diisi.',

            'publisher.required' =>
                'Publisher wajib diisi.',

            'qty.required' =>
                'Jumlah buku wajib diisi.',

            'qty.integer' =>
                'Jumlah buku harus berupa angka.',

            'qty.min' =>
                'Jumlah buku minimal 1.',
        ];
    }
}