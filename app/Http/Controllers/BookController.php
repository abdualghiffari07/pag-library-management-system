<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Equipment;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\Location;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    // Global search
    public function search(Request $request)
    {
        $search = trim($request->query('q', ''));

        if ($search === '') {
            return response()->json([]);
        }

        $keyword = "%{$search}%";
        $results = collect();

        $books = Book::with(['authors', 'equipment'])
            ->where('status', 'public')
            ->where(function ($query) use ($keyword) {
                $query->where('book_code', 'like', $keyword)
                    ->orWhere('cat_no', 'like', $keyword)
                    ->orWhere('tag_no', 'like', $keyword)
                    ->orWhere('title', 'like', $keyword)
                    ->orWhere('publisher', 'like', $keyword)
                    ->orWhere('rack', 'like', $keyword)
                    ->orWhereHas('authors', function ($query) use ($keyword) {
                        $query->where('author_name', 'like', $keyword);
                    })
                    ->orWhereHas('equipment', function ($query) use ($keyword) {
                        $query->where('equipment_name', 'like', $keyword);
                    });
            })
            ->orderBy('title')
            ->limit(5)
            ->get();

        foreach ($books as $book) {
            $authors = $book->authors
                ->pluck('author_name')
                ->join(', ');

            $results->push([
                'type' => 'Buku',
                'icon' => 'book',
                'title' => $book->title,
                'description' => $book->book_code
                    ? $book->book_code . ' • ' . ($authors ?: '-')
                    : ($authors ?: '-'),
                'meta' => $book->equipment?->equipment_name ?? $book->rack ?? '-',
                'url' => route('books.edit', [
                    'book_id' => $book->book_id,
                ]),
            ]);
        }

        $authors = Author::where('author_name', 'like', $keyword)
            ->orderBy('author_name')
            ->limit(5)
            ->get();

        foreach ($authors as $author) {
            $results->push([
                'type' => 'Penulis',
                'icon' => 'author',
                'title' => $author->author_name,
                'description' => 'Data penulis',
                'meta' => 'Penulis',
                'url' => '#',
            ]);
        }

        $locations = Location::where('location_name', 'like', $keyword)
            ->orderBy('location_name')
            ->limit(5)
            ->get();

        foreach ($locations as $location) {
            $results->push([
                'type' => 'Lokasi',
                'icon' => 'location',
                'title' => $location->location_name,
                'description' => 'Lokasi penyimpanan buku',
                'meta' => 'Lokasi',
                'url' => '#',
            ]);
        }

        $equipments = Equipment::where('equipment_name', 'like', $keyword)
            ->orderBy('equipment_name')
            ->limit(5)
            ->get();

        foreach ($equipments as $equipment) {
            $results->push([
                'type' => 'Equipment',
                'icon' => 'book',
                'title' => $equipment->equipment_name,
                'description' => 'Jenis equipment',
                'meta' => 'Equipment',
                'url' => '#',
            ]);
        }

        $visitors = Visitor::where(function ($query) use ($keyword) {
            $query->where('visitor_name', 'like', $keyword)
                ->orWhere('employee_number', 'like', $keyword)
                ->orWhere('visitor_category', 'like', $keyword);
        })
            ->orderBy('visitor_name')
            ->limit(5)
            ->get();

        foreach ($visitors as $visitor) {
            $category = match ($visitor->visitor_category) {
                'pekerja' => 'Pekerja',
                'mahasiswa' => 'Mahasiswa',
                'tamu' => 'Tamu',
                default => 'Lainnya',
            };

            $results->push([
                'type' => 'Pengunjung',
                'icon' => 'user',
                'title' => $visitor->visitor_name,
                'description' => $visitor->employee_number
                    ? $category . ' • ' . $visitor->employee_number
                    : $category,
                'meta' => 'Pengunjung',
                'url' => route('visitors', [
                    'search' => $visitor->visitor_name,
                ]),
            ]);
        }

        return response()->json(
            $results->take(15)->values()
        );
    }

    // Form tambah buku
    public function create()
    {
        return view('pages.tables.books.add-books', [
            'title' => 'Tambah Buku',
            'authors' => Author::orderBy('author_name')->get(),
            'equipments' => Equipment::orderBy('equipment_name')->get(),
        ]);
    }

    // Simpan buku
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_no' => [
                'required',
                'string',
                'max:100',
                Rule::unique('books', 'book_code'),
            ],
            'tag_no' => 'nullable|string|max:100',
            'cat_no' => 'required|string|max:200',
            'equipment' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:510',
            'qty' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'remark' => 'nullable|string|max:255',
        ], $this->bookValidationMessages());

        try {
            DB::transaction(function () use ($validated) {
                $equipment = Equipment::firstOrCreate([
                    'equipment_name' => trim($validated['equipment']),
                ]);

                $book = Book::create([
                    'book_code' => trim($validated['book_no']),
                    'tag_no' => trim($validated['tag_no'] ?? ''),
                    'cat_no' => trim($validated['cat_no']),
                    'equipment_id' => $equipment->equipment_id,
                    'title' => trim($validated['title']),
                    'rack' => trim($validated['location']),
                    'remark' => trim($validated['remark'] ?? ''),
                    'publisher' => trim($validated['publisher']),
                    'description' => $validated['description'] ?? null,
                    'status' => 'public',
                ]);

                $author = Author::firstOrCreate([
                    'author_name' => trim($validated['author']),
                ]);

                $book->authors()->sync([
                    $author->author_id,
                ]);

                for ($i = 1; $i <= (int) $validated['qty']; $i++) {
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
                        'notes' => null,
                    ]);
                }
            });

            return redirect()
                ->route('data-buku')
                ->with('success', 'Buku berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return back()
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
            'equipment',
        ])->find($book_id);

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with('error', 'Buku tidak ditemukan.');
        }

        $totalQty = $book->copies->count();
        $borrowedCopies = $book->copies->where('status', 'Dipinjam')->count();
        $availableCopies = $book->copies->where('status', 'Tersedia')->count();

        $activeLoans = LoanDetail::with(['loan', 'bookCopy'])
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
            'equipments' => Equipment::orderBy('equipment_name')->get(),
            'authors' => Author::orderBy('author_name')->get(),
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

        $book = Book::find($book_id);

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with('error', 'Buku tidak ditemukan.');
        }

        try {
            DB::transaction(function () use ($book, $validated) {
                $copy = $book->copies()
                    ->whereRaw('LOWER(status) = ?', ['tersedia'])
                    ->orderBy('copy_id')
                    ->lockForUpdate()
                    ->first();

                if (!$copy) {
                    throw new \Exception(
                        'Tidak ada eksemplar buku yang tersedia.'
                    );
                }

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

                LoanDetail::create([
                    'loan_id' => $loan->loan_id,
                    'book_id' => $book->book_id,
                    'copy_id' => $copy->copy_id,
                    'returned_date' => null,
                    'condition' => $copy->condition,
                    'fine' => 0,
                    'notes' => null,
                ]);

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
            return back()
                ->with('error', 'Gagal meminjam buku: ' . $e->getMessage());
        }
    }

    // Kembalikan buku
    public function returnBook(string $book_id, string $loan_detail_id)
    {
        $book = Book::find($book_id);

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with('error', 'Buku tidak ditemukan.');
        }

        try {
            DB::transaction(function () use ($book, $loan_detail_id) {
                $loanDetail = LoanDetail::with(['loan', 'bookCopy'])
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

                $loanDetail->update([
                    'returned_date' => $today,
                ]);

                if ($loanDetail->bookCopy) {
                    $loanDetail->bookCopy->update([
                        'status' => 'Tersedia',
                    ]);
                }

                $loan = $loanDetail->loan;

                $activeDetails = $loan->loanDetails()
                    ->whereNull('returned_date')
                    ->count();

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
                ->with('success', 'Buku berhasil dikembalikan.');
        } catch (\Throwable $e) {
            return back()
                ->with('error', 'Gagal mengembalikan buku: ' . $e->getMessage());
        }
    }

    // Update buku
    public function update(Request $request, string $book_id)
    {
        $book = Book::findOrFail($book_id);

        $validated = $request->validate([
            'book_no' => [
                'required',
                'string',
                'max:100',
                Rule::unique('books', 'book_code')
                    ->ignore($book->book_id, 'book_id'),
            ],
            'tag_no' => 'nullable|string|max:100',
            'cat_no' => 'required|string|max:200',
            'equipment_id' => [
                'required',
                'integer',
                'exists:equipment,equipment_id',
            ],
            'location' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:510',
            'qty' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'remark' => 'nullable|string|max:255',
        ], $this->bookValidationMessages());

        try {
            DB::transaction(function () use ($book, $validated) {
                $book->update([
                    'book_code' => trim($validated['book_no']),
                    'tag_no' => trim($validated['tag_no'] ?? ''),
                    'cat_no' => trim($validated['cat_no']),
                    'equipment_id' => $validated['equipment_id'],
                    'title' => trim($validated['title']),
                    'publisher' => trim($validated['publisher']),
                    'rack' => trim($validated['location']),
                    'remark' => trim($validated['remark'] ?? ''),
                    'description' => $validated['description'] ?? null,
                ]);

                $author = Author::firstOrCreate([
                    'author_name' => trim($validated['author']),
                ]);

                $book->authors()->sync([
                    $author->author_id,
                ]);

                $currentQty = $book->copies()->count();
                $newQty = (int) $validated['qty'];

                if ($newQty > $currentQty) {
                    for ($i = $currentQty + 1; $i <= $newQty; $i++) {
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
                            'notes' => null,
                        ]);
                    }
                }

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
                ->with('success', 'Buku berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui buku: ' . $e->getMessage());
        }
    }

    // Arsipkan buku
    public function destroy(string $book_id)
    {
        $book = Book::find($book_id);

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with('error', 'Buku tidak ditemukan.');
        }

        $borrowedCopies = $book->copies()
            ->whereRaw('LOWER(status) = ?', ['dipinjam'])
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
            DB::transaction(function () use ($book) {
                $book->update([
                    'status' => 'arsip',
                ]);

                session()->forget(
                    "book_borrowers.{$book->book_id}"
                );
            });

            return redirect()
                ->route('data-buku')
                ->with(
                    'success',
                    'Buku "' . $book->book_code . '" berhasil dihapus.'
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
            'book_no.required' => 'Book No. wajib diisi.',
            'book_no.unique' => 'Book No. sudah digunakan. Silakan gunakan Book No. lain.',
            'tag_no.max' => 'Tag No. terlalu panjang.',
            'cat_no.required' => 'Cat. No. wajib diisi.',
            'equipment.required' => 'Equipment wajib diisi.',
            'equipment_id.required' => 'Equipment wajib dipilih.',
            'equipment_id.exists' => 'Equipment yang dipilih tidak valid.',
            'location.required' => 'Location wajib diisi.',
            'location.max' => 'Location terlalu panjang.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Author wajib diisi.',
            'publisher.required' => 'Publisher wajib diisi.',
            'qty.required' => 'Jumlah buku wajib diisi.',
            'qty.integer' => 'Jumlah buku harus berupa angka.',
            'qty.min' => 'Jumlah buku minimal 1.',
        ];
    }
}