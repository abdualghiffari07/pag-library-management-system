<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->updateOverdueLoans();

        $query = Loan::with([
            'user',
            'loanDetails',
        ])
            ->withCount('loanDetails')
            ->orderByDesc('loan_id');

        // Pencarian nama atau nopek
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('nopek', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal pinjam
        if ($request->filled('loan_date')) {
            $query->whereDate('loan_date', $request->loan_date);
        }

        $loans = $query->paginate(10)->withQueryString();

        return view('loans.index', compact('loans'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('is_active', 1)
            ->orderBy('name')
            ->get();

        $books = Book::where('status', 'public')
            ->whereHas('copies', function ($query) {
                $query->where('status', 'tersedia');
            })
            ->orderBy('title')
            ->get();

        return view('loans.create', compact('users', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,user_id',

            'loan_date' => 'required|date',

            'due_date' => 'required|date|after_or_equal:loan_date',

            'books' => 'required|array|min:1',

            'books.*' => 'integer|exists:books,book_id',

            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {

            $loan = Loan::create([
                'user_id' => $validated['user_id'],
                'loan_date' => $validated['loan_date'],
                'due_date' => $validated['due_date'],
                'returned_date' => null,
                'status' => 'borrowed',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['books'] as $bookId) {

                /*
                * Cari satu eksemplar yang masih tersedia
                */
                $bookCopy = BookCopy::where('book_id', $bookId)
                    ->where('status', 'tersedia')
                    ->lockForUpdate()
                    ->first();

                /*
                * Jika tidak ada eksemplar tersedia
                */
                if (!$bookCopy) {

                    $book = Book::find($bookId);

                    throw ValidationException::withMessages([
                        'books' => "Buku {$book->title} tidak memiliki eksemplar yang tersedia.",
                    ]);
                }

                /*
                * Simpan detail peminjaman
                */
                $loan->loanDetails()->create([
                    'book_id' => $bookCopy->book_id,
                    'copy_id' => $bookCopy->copy_id,
                    'returned_date' => null,
                    'condition' => null,
                    'fine' => 0,
                    'notes' => null,
                ]);

                /*
                * Ubah status eksemplar
                */
                $bookCopy->update([
                    'status' => 'dipinjam',
                ]);
            }
        });

        return redirect()
            ->route('loans.index')
            ->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    // public function returnBook(Request $request, Loan $loan)
    // {
    //     $validated = $request->validate([
    //         'returned_date' => 'required|date',
    //     ]);

    //     $loan->update([
    //         'returned_date' => $validated['returned_date'],
    //         'status' => 'returned',
    //     ]);

    //     return redirect()
    //         ->route('loans.show', $loan->loan_id)
    //         ->with('success', 'Peminjaman berhasil dikembalikan.');
    // }

        public function returnBookDetail(Request $request, LoanDetail $loanDetail)
        {
            $validated = $request->validate([
                'returned_date' => 'required|date',
                'condition' => 'required|string|max:100',
                'notes' => 'nullable|string',
            ]);

            DB::transaction(function () use ($validated, $loanDetail) {

                $loan = $loanDetail->loan;

                $returnedDate = \Carbon\Carbon::parse(
                    $validated['returned_date']
                );

                $dueDate = \Carbon\Carbon::parse(
                    $loan->due_date
                );

                /*
                * Hitung jumlah hari keterlambatan
                */
                $lateDays = 0;

                if ($returnedDate->gt($dueDate)) {
                    $lateDays = $dueDate->diffInDays($returnedDate);
                }

                /*
                * Tarif denda per hari
                */
                $finePerDay = 1000;

                /*
                * Hitung denda
                */
                $fine = $lateDays * $finePerDay;

                /*
                * Simpan data pengembalian
                */
                $loanDetail->update([
                    'returned_date' => $validated['returned_date'],
                    'condition' => $validated['condition'],
                    'fine' => $fine,
                    'notes' => $validated['notes'] ?? null,
                ]);

                /*
                * Kembalikan status eksemplar
                */
                if ($loanDetail->bookCopy) {
                    $loanDetail->bookCopy->update([
                        'status' => 'tersedia',
                        'condition' => strtolower($validated['condition']),
                    ]);
                }

                /*
                * Perbarui status transaksi peminjaman
                */
                $this->updateLoanStatus($loan);
            });

            return redirect()
                ->route('loans.show', $loanDetail->loan_id)
                ->with('success', 'Buku berhasil dikembalikan.');
        }


    private function updateLoanStatus(Loan $loan): void
    {
        // Jika semua buku sudah dikembalikan
        $hasUnreturnedBooks = $loan->loanDetails()
            ->whereNull('returned_date')
            ->exists();

        if (!$hasUnreturnedBooks) {
            $lastReturnedDate = $loan->loanDetails()
                ->whereNotNull('returned_date')
                ->max('returned_date');

            $loan->update([
                'status' => 'returned',
                'returned_date' => $lastReturnedDate,
            ]);

            return;
        }

        // Jika masih ada buku yang belum dikembalikan
        if (now()->startOfDay()->gt($loan->due_date)) {
            $loan->update([
                'status' => 'overdue',
                'returned_date' => null,
            ]);

            return;
        }

        // Jika belum melewati jatuh tempo
        $loan->update([
            'status' => 'borrowed',
            'returned_date' => null,
        ]);
    }
    
    
    private function updateOverdueLoans(): void
        {
            Loan::whereIn('status', ['borrowed', 'overdue'])
                ->whereDate('due_date', '<', now()->toDateString())
                ->whereHas('loanDetails', function ($query) {
                    $query->whereNull('returned_date');
                })
                ->update([
                    'status' => 'overdue',
                ]);
        }


    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load([
            'user',
            'loanDetails.book',
            'loanDetails.bookCopy',
        ]);

        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
