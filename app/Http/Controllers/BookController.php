<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookController extends Controller
{

// Search buku
public function search(Request $request)
{
    $search = trim($request->query('q', ''));

    if ($search === '') {
        return response()->json([]);
    }

    $books = Book::with([
        'authors',
        'location',
    ])
        ->where('status', 'public')
        ->where(function ($query) use ($search) {
            $query->where('book_code', 'like', "%{$search}%")
                ->orWhere('cat_no', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('publisher', 'like', "%{$search}%")
                ->orWhereHas('authors', function ($authorQuery) use ($search) {
                    $authorQuery->where(
                        'author_name',
                        'like',
                        "%{$search}%"
                    );
                });
        })
        ->orderBy('title')
        ->limit(8)
        ->get();

    return response()->json(
        $books->map(function ($book) {
            return [
                'book_id' => $book->book_id,
                'book_code' => $book->book_code,
                'cat_no' => $book->cat_no,
                'title' => $book->title,
                'author' => $book->authors
                    ->pluck('author_name')
                    ->join(', '),
                'publisher' => $book->publisher,
                'location' => $book->location->location_name ?? '-',
                'url' => route('books.edit', [
                    'book_id' => $book->book_id,
                ]),
            ];
        })
    );
}
// Tambah buku
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
            'location' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:510',
            'qty' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ], $this->bookValidationMessages());

        try {
            DB::transaction(function () use ($validated) {
                // Simpan atau cari lokasi
                $location = Location::firstOrCreate([
                    'location_name' => trim($validated['location']),
                ]);

                // Simpan data buku
                $book = Book::create([
                    'book_code' => $validated['book_no'],
                    'cat_no' => $validated['cat_no'],
                    'location_id' => $location->location_id,
                    'title' => $validated['title'],
                    'publisher' => $validated['publisher'],
                    'description' => $validated['description'] ?? null,
                    'status' => 'public',
                ]);

                // Simpan author
                $author = Author::firstOrCreate([
                    'author_name' => trim($validated['author']),
                ]);

                $book->authors()->attach($author->author_id);

                // Buat eksemplar buku
                for ($i = 1; $i <= $validated['qty']; $i++) {
                    BookCopy::create([
                        'book_id' => $book->book_id,
                        'copy_code' => $book->book_code . '-' . str_pad(
                            $i,
                            3,
                            '0',
                            STR_PAD_LEFT
                        ),
                        'condition' => 'Baik',
                        'status' => 'Tersedia',
                    ]);
                }
            });

            return redirect()
                ->route('data-buku')
                ->with('success', 'Buku berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }
    }

    // Cek Book No
    public function checkBookNo(Request $request)
    {
        $bookNo = trim($request->query('book_no', ''));

        if ($bookNo === '') {
            return response()->json([
                'exists' => false,
                'valid' => false,
                'message' => 'Book No. wajib diisi.',
            ]);
        }

        $exists = Book::where('book_code', $bookNo)->exists();

        return response()->json([
            'exists' => $exists,
            'valid' => !$exists,
            'message' => $exists
                ? 'Book No. sudah digunakan.'
                : 'Book No. tersedia.',
        ]);
    }

    // Edit buku
    public function edit(string $book_id)
    {
        $book = Book::with([
            'authors',
            'copies',
            'location',
        ])
            ->where('book_id', $book_id)
            ->first();

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with('error', 'Buku tidak ditemukan.');
        }

        $totalQty = $book->copies->count();

        $borrowedCopies = $book->copies
            ->where('status', 'Dipinjam')
            ->count();

        $availableCopies = $book->copies
            ->where('status', 'Tersedia')
            ->count();

        // Peminjaman aktif
        $activeLoans = LoanDetail::with([
            'loan',
            'bookCopy',
        ])
            ->where('book_id', $book->book_id)
            ->whereNull('returned_date')
            ->whereHas('loan', function ($query) {
                $query->where('status', 'borrowed');
            })
            ->orderBy('loan_detail_id')
            ->get();

        return view('pages.tables.books.edit-books', [
            'title' => 'Edit Buku',
            'book' => $book,
            'totalQty' => $totalQty,
            'borrowedCopies' => $borrowedCopies,
            'availableCopies' => $availableCopies,
            'activeLoans' => $activeLoans,
        ]);
    }

    // Pinjam buku
    public function borrow(Request $request, string $book_id)
    {
        $validated = $request->validate([
            'borrower_name' => 'required|string|max:255',
            'nopek' => 'nullable|string|max:100',
            'loan_date' => 'required|date',
        ], [
            'borrower_name.required' => 'Nama peminjam wajib diisi.',
            'borrower_name.max' => 'Nama peminjam terlalu panjang.',
            'nopek.max' => 'No. Pekerja terlalu panjang.',
            'loan_date.required' => 'Tanggal peminjaman wajib diisi.',
            'loan_date.date' => 'Tanggal peminjaman tidak valid.',
        ]);

        $book = Book::where('book_id', $book_id)->first();

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with('error', 'Buku tidak ditemukan.');
        }

        try {
            DB::transaction(function () use ($book, $validated) {
                // Cari eksemplar tersedia
                $copy = $book->copies()
                    ->whereRaw("LOWER(status) = 'tersedia'")
                    ->orderBy('copy_id')
                    ->lockForUpdate()
                    ->first();

                if (!$copy) {
                    throw new \Exception(
                        'Tidak ada eksemplar buku yang tersedia.'
                    );
                }

                // Buat data peminjaman
                $loan = Loan::create([
                    'borrower_name' => trim($validated['borrower_name']),
                    'nopek' => !empty($validated['nopek'])
                        ? trim($validated['nopek'])
                        : null,
                    'loan_date' => $validated['loan_date'],
                    'due_date' => now()->addDays(7)->toDateString(),
                    'status' => 'borrowed',
                    'returned_date' => null,
                    'notes' => null,
                ]);

                // Buat detail peminjaman
                LoanDetail::create([
                    'loan_id' => $loan->loan_id,
                    'book_id' => $book->book_id,
                    'copy_id' => $copy->copy_id,
                    'returned_date' => null,
                    'condition' => $copy->condition,
                    'fine' => 0,
                    'notes' => null,
                ]);

                // Ubah status eksemplar
                $copy->update([
                    'status' => 'Dipinjam',
                ]);
            });

            return redirect()
                ->route('books.edit', [
                    'book_id' => $book->book_id,
                ])
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
                    'Gagal meminjam buku: ' . $e->getMessage()
                );
        }
    }

    // Kembalikan buku
    public function returnBook(
        string $book_id,
        string $loan_detail_id
    ) {
        $book = Book::where('book_id', $book_id)->first();

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with('error', 'Buku tidak ditemukan.');
        }

        try {
            DB::transaction(function () use ($book, $loan_detail_id) {
                // Cari peminjaman aktif
                $loanDetail = LoanDetail::with([
                    'loan',
                    'bookCopy',
                ])
                    ->where('loan_detail_id', $loan_detail_id)
                    ->where('book_id', $book->book_id)
                    ->whereNull('returned_date')
                    ->whereHas('loan', function ($query) {
                        $query->where('status', 'borrowed');
                    })
                    ->lockForUpdate()
                    ->first();

                if (!$loanDetail) {
                    throw new \Exception(
                        'Data peminjaman aktif tidak ditemukan.'
                    );
                }

                $today = now()->toDateString();

                // Tandai detail sebagai dikembalikan
                $loanDetail->update([
                    'returned_date' => $today,
                ]);

                // Eksemplar kembali tersedia
                if ($loanDetail->bookCopy) {
                    $loanDetail->bookCopy->update([
                        'status' => 'Tersedia',
                    ]);
                }

                // Cek apakah masih ada detail aktif
                $loan = $loanDetail->loan;

                $activeDetails = $loan->loanDetails()
                    ->whereNull('returned_date')
                    ->count();

                // Jika semua buku sudah dikembalikan
                if ($activeDetails === 0) {
                    $loan->update([
                        'status' => 'returned',
                        'returned_date' => $today,
                    ]);
                }
            });

            return redirect()
                ->route('books.edit', [
                    'book_id' => $book->book_id,
                ])
                ->with(
                    'success',
                    'Buku berhasil dikembalikan.'
                );
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Gagal mengembalikan buku: ' . $e->getMessage()
                );
        }
    }

    // Update buku
    public function update(
        Request $request,
        string $book_id
    ) {
        $book = Book::where('book_id', $book_id)->firstOrFail();

        $validated = $request->validate([
            'book_no' => [
                'required',
                'string',
                'max:100',
                Rule::unique('books', 'book_code')
                    ->ignore($book->book_id, 'book_id'),
            ],
            'cat_no' => 'required|string|max:200',
            'location' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:510',
            'qty' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ], $this->bookValidationMessages());

        try {
            DB::transaction(function () use ($book, $validated) {
                // Simpan atau cari lokasi
                $location = Location::firstOrCreate([
                    'location_name' => trim($validated['location']),
                ]);

                // Update data buku
                $book->update([
                    'book_code' => $validated['book_no'],
                    'cat_no' => $validated['cat_no'],
                    'location_id' => $location->location_id,
                    'title' => $validated['title'],
                    'publisher' => $validated['publisher'],
                    'description' => $validated['description'] ?? null,
                ]);

                // Update author
                $author = Author::firstOrCreate([
                    'author_name' => trim($validated['author']),
                ]);

                $book->authors()->sync([
                    $author->author_id,
                ]);

                // Update jumlah eksemplar
                $currentQty = $book->copies()->count();
                $newQty = (int) $validated['qty'];

                // Tambah eksemplar
                if ($newQty > $currentQty) {
                    for (
                        $i = $currentQty + 1;
                        $i <= $newQty;
                        $i++
                    ) {
                        BookCopy::create([
                            'book_id' => $book->book_id,
                            'copy_code' => $book->book_code . '-' . str_pad(
                                $i,
                                3,
                                '0',
                                STR_PAD_LEFT
                            ),
                            'condition' => 'Baik',
                            'status' => 'Tersedia',
                        ]);
                    }
                }

                // Kurangi eksemplar
                if ($newQty < $currentQty) {
                    $difference = $currentQty - $newQty;

                    $availableCopies = $book->copies()
                        ->where('status', 'Tersedia')
                        ->orderByDesc('copy_id')
                        ->limit($difference)
                        ->get();

                    if ($availableCopies->count() < $difference) {
                        throw new \Exception(
                            'Jumlah buku tidak dapat dikurangi karena terdapat eksemplar yang sedang dipinjam.'
                        );
                    }

                    foreach ($availableCopies as $copy) {
                        $copy->delete();
                    }
                }
            });

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
                    'Gagal memperbarui buku: ' . $e->getMessage()
                );
        }
    }

    // Hapus buku
    public function destroy(string $book_code)
    {
        $book = Book::where('book_code', $book_code)->first();

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Buku tidak ditemukan.'
                );
        }

        // Cek apakah masih dipinjam
        $borrowedCopies = $book->copies()
            ->whereRaw("LOWER(status) = 'dipinjam'")
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
            // Arsipkan buku, bukan hapus permanen
            $book->update([
                'status' => 'arsip',
            ]);

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
                    'Gagal menghapus buku: ' . $e->getMessage()
                );
        }
    }

    // Pesan validasi
    private function bookValidationMessages(): array
    {
        return [
            'book_no.required' =>
                'Book No. wajib diisi.',

            'book_no.unique' =>
                'Book No. sudah digunakan. Silakan gunakan Book No. lain.',

            'cat_no.required' =>
                'Cat. No. wajib diisi.',

            'location.required' =>
                'Location wajib diisi.',

            'location.max' =>
                'Location terlalu panjang.',

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