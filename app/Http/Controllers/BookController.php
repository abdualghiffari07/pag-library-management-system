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
use Illuminate\Support\Carbon;
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
                $query->where('book_identifier', 'like', $keyword)
                    ->orWhere('book_code', 'like', $keyword)
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
                'title' => $book->title ?: $book->book_identifier,
                'description' => $book->book_identifier
                    . ' • '
                    . ($book->book_code ?: '-')
                    . ' • '
                    . ($authors ?: '-'),
                'meta' => $book->equipment?->equipment_name
                    ?? $book->rack
                    ?? '-',
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
        $request->merge([
            'book_identifier' => trim(
                (string) $request->input('book_identifier', '')
            ),
        ]);

        $copyIds = $request->input('copy_ids', []);

        if (is_array($copyIds)) {
            $request->merge([
                'copy_ids' => array_map(
                    fn ($copyId) => trim((string) $copyId),
                    $copyIds
                ),
            ]);
        }

        $validated = $request->validate([
            'book_identifier' => [
                'required',
                'string',
                'max:100',
                Rule::unique('books', 'book_identifier')
                    ->where(function ($query) {
                        return $query->where('status', 'public');
                    }),
            ],
            'book_no' => [
                'nullable',
                'string',
                'max:100',
            ],
            'tag_no' => 'nullable|string|max:100',
            'cat_no' => 'nullable|string|max:200',
            'equipment' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:510',
            'qty' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'remark' => 'nullable|string|max:255',
            'copy_ids' => [
                'nullable',
                'array',
            ],
            'copy_ids.*' => [
                'nullable',
                'string',
                'max:50',
                'distinct',
                Rule::unique('book_copies', 'copy_code'),
            ],
        ], $this->bookValidationMessages());

        $qty = !empty($validated['qty'])
            ? (int) $validated['qty']
            : 0;

        $copyIds = collect(
            $validated['copy_ids'] ?? []
        )
            ->map(fn ($copyId) => trim((string) $copyId))
            ->filter()
            ->values()
            ->all();

        if ($qty > 0 && count($copyIds) !== $qty) {
            return back()
                ->withInput()
                ->withErrors([
                    'copy_ids' =>
                        'Jika QTY diisi, setiap eksemplar wajib memiliki ID.',
                ]);
        }

        try {
            DB::transaction(function () use (
                $validated,
                $qty,
                $copyIds
            ) {
                $equipmentId = null;

                if (!empty($validated['equipment'])) {
                    $equipment = Equipment::firstOrCreate([
                        'equipment_name' => trim(
                            $validated['equipment']
                        ),
                    ]);

                    $equipmentId = $equipment->equipment_id;
                }

                $book = Book::create([
                    'book_identifier' => trim(
                        $validated['book_identifier']
                    ),
                    'book_code' => !empty($validated['book_no'])
                        ? trim($validated['book_no'])
                        : null,
                    'tag_no' => !empty($validated['tag_no'])
                        ? trim($validated['tag_no'])
                        : null,
                    'cat_no' => !empty($validated['cat_no'])
                        ? trim($validated['cat_no'])
                        : null,
                    'equipment_id' => $equipmentId,
                    'title' => !empty($validated['title'])
                        ? trim($validated['title'])
                        : null,
                    'rack' => !empty($validated['location'])
                        ? trim($validated['location'])
                        : null,
                    'remark' => !empty($validated['remark'])
                        ? trim($validated['remark'])
                        : null,
                    'publisher' => !empty($validated['publisher'])
                        ? trim($validated['publisher'])
                        : null,
                    'description' =>
                        $validated['description'] ?? null,
                    'status' => 'public',
                ]);

                if (!empty($validated['author'])) {
                    $author = Author::firstOrCreate([
                        'author_name' => trim(
                            $validated['author']
                        ),
                    ]);

                    $book->authors()->sync([
                        $author->author_id,
                    ]);
                }

                if ($qty > 0) {
                    foreach ($copyIds as $copyId) {
                        BookCopy::create([
                            'book_id' => $book->book_id,
                            'copy_code' => $copyId,
                            'condition' => 'Baik',
                            'status' => 'Tersedia',
                            'notes' => null,
                        ]);
                    }
                }
            });

            return redirect()
                ->route('data-buku')
                ->with(
                    'success',
                    'Buku berhasil ditambahkan.'
                );
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal menambahkan buku: '
                    . $e->getMessage()
                );
        }
    }

    // Cek ID Buku
    public function checkBookId(Request $request)
    {
        $bookId = trim(
            $request->query('book_id', '')
        );

        if ($bookId === '') {
            return response()->json([
                'exists' => false,
                'valid' => false,
                'message' => 'ID Buku wajib diisi.',
            ]);
        }

        $exists = Book::where(
            'book_identifier',
            $bookId
        )
            ->where('status', 'public')
            ->exists();

        return response()->json([
            'exists' => $exists,
            'valid' => !$exists,
            'message' => $exists
                ? 'ID Buku sudah digunakan.'
                : 'ID Buku tersedia.',
        ]);
    }

    // Cek Book No
    public function checkBookNo(Request $request)
    {
        $bookNo = trim(
            $request->query('book_no', '')
        );

        if ($bookNo === '') {
            return response()->json([
                'exists' => false,
                'valid' => true,
                'message' => '',
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
                ->with(
                    'error',
                    'Buku tidak ditemukan.'
                );
        }

        $totalQty = $book->copies->count();

        $borrowedCopies = $book->copies
            ->where('status', 'Dipinjam')
            ->count();

        $availableCopies = $book->copies
            ->where('status', 'Tersedia')
            ->count();

        $activeLoans = LoanDetail::with([
            'loan',
            'bookCopy',
        ])
            ->where('book_id', $book->book_id)
            ->whereNull('returned_date')
            ->whereHas('loan', function ($query) {
                $query->where(
                    'status',
                    'borrowed'
                );
            })
            ->orderBy('loan_detail_id')
            ->get();

        $visitors = Visitor::query()
            ->orderBy('visitor_name')
            ->orderBy('employee_number')
            ->get();

        return view('pages.tables.books.edit-books', [
            'title' => 'Edit Buku',
            'book' => $book,
            'totalQty' => $totalQty,
            'borrowedCopies' => $borrowedCopies,
            'availableCopies' => $availableCopies,
            'activeLoans' => $activeLoans,
            'equipments' => Equipment::orderBy(
                'equipment_name'
            )->get(),
            'authors' => Author::orderBy(
                'author_name'
            )->get(),
            'visitors' => $visitors,
        ]);
    }

    // Pinjam buku
    public function borrow(
        Request $request,
        string $book_id
    ) {
        $validated = $request->validate([
            'visitor_id' => [
                'required',
                'integer',
                'exists:visitors,visitor_id',
            ],
            'loan_date' => [
                'required',
                'date',
            ],
        ], [
            'visitor_id.required' =>
                'Peminjam wajib dipilih dari Daftar Pengunjung.',
            'visitor_id.integer' =>
                'Data peminjam tidak valid.',
            'visitor_id.exists' =>
                'Peminjam tidak terdaftar di Daftar Pengunjung.',
            'loan_date.required' =>
                'Tanggal peminjaman wajib diisi.',
            'loan_date.date' =>
                'Tanggal peminjaman tidak valid.',
        ]);

        $book = Book::where('book_id', $book_id)
            ->where('status', 'public')
            ->first();

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with('error', 'Buku tidak ditemukan.');
        }

        // Peminjam harus benar-benar ada di visitors
        $visitor = Visitor::where(
            'visitor_id',
            $validated['visitor_id']
        )->first();

        if (!$visitor) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Peminjam belum terdaftar di Daftar Pengunjung.'
                );
        }

        try {
            DB::transaction(function () use (
                $book,
                $validated,
                $visitor
            ) {
                $copy = $book->copies()
                    ->whereRaw(
                        'LOWER(status) = ?',
                        ['tersedia']
                    )
                    ->orderBy('copy_id')
                    ->lockForUpdate()
                    ->first();

                if (!$copy) {
                    throw new \Exception(
                        'Tidak ada eksemplar buku yang tersedia.'
                    );
                }

                $loanDate = \Illuminate\Support\Carbon::parse(
                    $validated['loan_date']
                );

                $loan = new Loan();

                // Hubungan ke Daftar Pengunjung
                $loan->visitor_id = $visitor->visitor_id;

                // Snapshot data pengunjung
                $loan->borrower_name = $visitor->visitor_name;

                $loan->nopek = $visitor->employee_number
                    ?: null;

                $loan->loan_date =
                    $loanDate->toDateString();

                $loan->due_date =
                    $loanDate
                        ->copy()
                        ->addDays(7)
                        ->toDateString();

                $loan->status = 'borrowed';
                $loan->returned_date = null;
                $loan->notes = null;

                $loan->save();

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
                    'Buku berhasil dipinjam oleh '
                    . $visitor->visitor_name
                    . '.'
                );
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal meminjam buku: '
                    . $e->getMessage()
                );
        }
    }

    // Kembalikan buku
    public function returnBook(
        string $book_id,
        string $loan_detail_id
    ) {
        $book = Book::find($book_id);

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Buku tidak ditemukan.'
                );
        }

        try {
            DB::transaction(function () use (
                $book,
                $loan_detail_id
            ) {
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
                    ->whereNull('returned_date')
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

                $activeDetails = $loan
                    ->loanDetails()
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
                ->with(
                    'success',
                    'Buku berhasil dikembalikan.'
                );
        } catch (\Throwable $e) {
            return back()
                ->with(
                    'error',
                    'Gagal mengembalikan buku: '
                    . $e->getMessage()
                );
        }
    }

    // Update buku
    public function update(
        Request $request,
        string $book_id
    ) {
        $book = Book::findOrFail($book_id);

        $validated = $request->validate([
            'book_no' => [
                'required',
                'string',
                'max:100',
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
            DB::transaction(function () use (
                $book,
                $validated
            ) {
                $book->update([
                    'book_code' => trim(
                        $validated['book_no']
                    ),
                    'tag_no' => trim(
                        $validated['tag_no'] ?? ''
                    ),
                    'cat_no' => trim(
                        $validated['cat_no']
                    ),
                    'equipment_id' =>
                        $validated['equipment_id'],
                    'title' => trim(
                        $validated['title']
                    ),
                    'publisher' => trim(
                        $validated['publisher']
                    ),
                    'rack' => trim(
                        $validated['location']
                    ),
                    'remark' => trim(
                        $validated['remark'] ?? ''
                    ),
                    'description' =>
                        $validated['description'] ?? null,
                ]);

                $author = Author::firstOrCreate([
                    'author_name' => trim(
                        $validated['author']
                    ),
                ]);

                $book->authors()->sync([
                    $author->author_id,
                ]);

                $currentQty = $book
                    ->copies()
                    ->count();

                $newQty = (int) $validated['qty'];

                if ($newQty > $currentQty) {
                    for (
                        $i = $currentQty + 1;
                        $i <= $newQty;
                        $i++
                    ) {
                        BookCopy::create([
                            'book_id' => $book->book_id,
                            'copy_code' => $book->book_code
                                . '-'
                                . str_pad(
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
                    $difference =
                        $currentQty - $newQty;

                    $availableCopies = $book
                        ->copies()
                        ->where(
                            'status',
                            'Tersedia'
                        )
                        ->orderByDesc('copy_id')
                        ->limit($difference)
                        ->get();

                    if (
                        $availableCopies->count()
                        < $difference
                    ) {
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
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal memperbarui buku: '
                    . $e->getMessage()
                );
        }
    }

    // Hapus banyak buku
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'book_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'book_ids.*' => [
                'required',
                'integer',
                'exists:books,book_id',
            ],
        ], [
            'book_ids.required' =>
                'Pilih minimal satu buku.',
            'book_ids.array' =>
                'Data buku yang dipilih tidak valid.',
            'book_ids.min' =>
                'Pilih minimal satu buku.',
            'book_ids.*.exists' =>
                'Salah satu buku tidak ditemukan.',
        ]);

        $books = Book::whereIn(
            'book_id',
            $validated['book_ids']
        )
            ->where('status', 'public')
            ->get();

        if ($books->isEmpty()) {
            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Tidak ada buku yang dapat dihapus.'
                );
        }

        $deleted = 0;
        $skipped = 0;
        $skippedBooks = [];

        try {
            DB::transaction(function () use (
                $books,
                &$deleted,
                &$skipped,
                &$skippedBooks
            ) {
                foreach ($books as $book) {
                    $hasActiveLoan = LoanDetail::where(
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
                        ->exists();

                    if ($hasActiveLoan) {
                        $skipped++;

                        $skippedBooks[] =
                            $book->book_identifier
                            ?? $book->book_code
                            ?? $book->title;

                        continue;
                    }

                    $book->copies()
                        ->whereRaw(
                            'LOWER(status) = ?',
                            ['dipinjam']
                        )
                        ->update([
                            'status' => 'Tersedia',
                        ]);

                    $book->update([
                        'status' => 'arsip',
                    ]);

                    session()->forget(
                        "book_borrowers.{$book->book_id}"
                    );

                    $deleted++;
                }
            });

            if ($deleted === 0) {
                return redirect()
                    ->route('data-buku')
                    ->with(
                        'error',
                        'Buku yang dipilih tidak dapat dihapus karena masih memiliki peminjaman aktif.'
                    );
            }

            $message =
                $deleted . ' buku berhasil dihapus.';

            if ($skipped > 0) {
                $message .=
                    ' '
                    . $skipped
                    . ' buku tidak dihapus karena masih dipinjam';

                if (!empty($skippedBooks)) {
                    $message .=
                        ': '
                        . implode(', ', $skippedBooks);
                }

                $message .= '.';
            }

            return redirect()
                ->route('data-buku')
                ->with(
                    'success',
                    $message
                );
        } catch (\Throwable $e) {
            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Gagal menghapus buku: '
                    . $e->getMessage()
                );
        }
    }

    // Arsipkan buku
    public function destroy(string $book_id)
    {
        $book = Book::find($book_id);

        if (!$book) {
            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Buku tidak ditemukan.'
                );
        }

        $borrowedCopies = $book->copies()
            ->whereRaw(
                'LOWER(status) = ?',
                ['dipinjam']
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
                    'Buku "'
                    . (
                        $book->book_identifier
                        ?? $book->book_code
                        ?? $book->title
                    )
                    . '" berhasil dihapus.'
                );
        } catch (\Throwable $e) {
            return redirect()
                ->route('data-buku')
                ->with(
                    'error',
                    'Gagal menghapus buku: '
                    . $e->getMessage()
                );
        }
    }

    // Pesan validasi
    private function bookValidationMessages(): array
    {
        return [
            'book_identifier.required' =>
                'ID Buku wajib diisi.',
            'book_identifier.unique' =>
                'ID Buku sudah digunakan. Silakan gunakan ID Buku lain.',
            'book_identifier.max' =>
                'ID Buku terlalu panjang.',

            'book_no.required' =>
                'Book No. wajib diisi.',
            'book_no.unique' =>
                'Book No. sudah digunakan. Silakan gunakan Book No. lain.',
            'book_no.max' =>
                'Book No. terlalu panjang.',

            'tag_no.max' =>
                'Tag No. terlalu panjang.',

            'cat_no.required' =>
                'Cat. No. wajib diisi.',
            'cat_no.max' =>
                'Cat. No. terlalu panjang.',

            'equipment.required' =>
                'Equipment wajib diisi.',
            'equipment.max' =>
                'Equipment terlalu panjang.',

            'equipment_id.required' =>
                'Equipment wajib dipilih.',
            'equipment_id.exists' =>
                'Equipment yang dipilih tidak valid.',

            'location.required' =>
                'Location wajib diisi.',
            'location.max' =>
                'Location terlalu panjang.',

            'title.required' =>
                'Judul buku wajib diisi.',
            'title.max' =>
                'Judul buku terlalu panjang.',

            'author.required' =>
                'Author wajib diisi.',
            'author.max' =>
                'Nama author terlalu panjang.',

            'publisher.required' =>
                'Publisher wajib diisi.',
            'publisher.max' =>
                'Publisher terlalu panjang.',

            'qty.required' =>
                'Jumlah buku wajib diisi.',
            'qty.integer' =>
                'Jumlah buku harus berupa angka.',
            'qty.min' =>
                'Jumlah buku minimal 1.',

            'copy_ids.required' =>
                'ID eksemplar wajib diisi.',
            'copy_ids.array' =>
                'Data ID eksemplar tidak valid.',
            'copy_ids.min' =>
                'Minimal satu ID eksemplar wajib diisi.',
            'copy_ids.*.required' =>
                'Semua ID eksemplar wajib diisi.',
            'copy_ids.*.max' =>
                'ID eksemplar maksimal 50 karakter.',
            'copy_ids.*.distinct' =>
                'ID eksemplar tidak boleh sama.',
            'copy_ids.*.unique' =>
                'ID eksemplar sudah digunakan.',
        ];
    }
}