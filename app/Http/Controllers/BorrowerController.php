<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowerController extends Controller
{
    // Daftar peminjam
    public function index(Request $request)
    {
        $search = trim(
            $request->query('search', '')
        );

        $borrowers = DB::table('loan_details')
            ->join(
                'loans',
                'loan_details.loan_id',
                '=',
                'loans.loan_id'
            )
            ->leftJoin(
                'visitors',
                'loans.visitor_id',
                '=',
                'visitors.visitor_id'
            )
            ->leftJoin(
                'books',
                'loan_details.book_id',
                '=',
                'books.book_id'
            )
            ->leftJoin(
                'book_copies',
                'loan_details.copy_id',
                '=',
                'book_copies.copy_id'
            )
            ->select([
                'loan_details.loan_detail_id',
                'loan_details.returned_date',

                'loans.loan_id',
                'loans.visitor_id',
                'loans.borrower_name',
                'loans.nopek',
                'loans.loan_date',
                'loans.status',

                'visitors.visitor_name',
                'visitors.employee_number',
                'visitors.visitor_category',

                'books.book_identifier',
                'books.book_code',
                'books.title',

                'book_copies.copy_id',
                'book_copies.copy_code',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . $search . '%';

                $query->where(function ($query) use ($keyword) {
                    $query
                        ->where(
                            'visitors.visitor_name',
                            'like',
                            $keyword
                        )
                        ->orWhere(
                            'visitors.employee_number',
                            'like',
                            $keyword
                        )
                        ->orWhere(
                            'visitors.visitor_category',
                            'like',
                            $keyword
                        )
                        ->orWhere(
                            'loans.borrower_name',
                            'like',
                            $keyword
                        )
                        ->orWhere(
                            'loans.nopek',
                            'like',
                            $keyword
                        )
                        ->orWhere(
                            'books.book_identifier',
                            'like',
                            $keyword
                        )
                        ->orWhere(
                            'books.book_code',
                            'like',
                            $keyword
                        )
                        ->orWhere(
                            'books.title',
                            'like',
                            $keyword
                        )
                        ->orWhere(
                            'book_copies.copy_code',
                            'like',
                            $keyword
                        );
                });
            })
            ->orderByDesc('loans.loan_date')
            ->orderByDesc('loan_details.loan_detail_id')
            ->paginate(20)
            ->withQueryString();

        return view('pages.tables.borrowers.index', [
            'title' => 'Daftar Peminjam',
            'borrowers' => $borrowers,
            'search' => $search,
        ]);
    }

    // Kembalikan buku
    public function returnBook(string $loan_detail_id)
    {
        try {
            DB::transaction(function () use ($loan_detail_id) {
                $detail = DB::table('loan_details')
                    ->where(
                        'loan_detail_id',
                        $loan_detail_id
                    )
                    ->lockForUpdate()
                    ->first();

                if (!$detail) {
                    throw new \Exception(
                        'Data peminjaman tidak ditemukan.'
                    );
                }

                if ($detail->returned_date) {
                    throw new \Exception(
                        'Buku sudah dikembalikan.'
                    );
                }

                $today = now()->toDateString();

                DB::table('loan_details')
                    ->where(
                        'loan_detail_id',
                        $loan_detail_id
                    )
                    ->update([
                        'returned_date' => $today,
                    ]);

                if ($detail->copy_id) {
                    DB::table('book_copies')
                        ->where(
                            'copy_id',
                            $detail->copy_id
                        )
                        ->update([
                            'status' => 'Tersedia',
                        ]);
                }

                $activeDetails = DB::table('loan_details')
                    ->where(
                        'loan_id',
                        $detail->loan_id
                    )
                    ->whereNull('returned_date')
                    ->count();

                if ($activeDetails === 0) {
                    DB::table('loans')
                        ->where(
                            'loan_id',
                            $detail->loan_id
                        )
                        ->update([
                            'status' => 'returned',
                            'returned_date' => $today,
                        ]);
                }
            });

            return redirect()
                ->route('borrowers.index')
                ->with(
                    'success',
                    'Buku berhasil dikembalikan.'
                );
        } catch (\Throwable $e) {
            return redirect()
                ->route('borrowers.index')
                ->with(
                    'error',
                    'Gagal mengembalikan buku: '
                    . $e->getMessage()
                );
        }
    }

    // Hapus satu peminjaman
    public function destroy(string $loan_detail_id)
    {
        try {
            DB::transaction(function () use ($loan_detail_id) {
                $detail = DB::table('loan_details')
                    ->where(
                        'loan_detail_id',
                        $loan_detail_id
                    )
                    ->lockForUpdate()
                    ->first();

                if (!$detail) {
                    throw new \Exception(
                        'Data peminjaman tidak ditemukan.'
                    );
                }

                $loanId = $detail->loan_id;

                if (
                    !$detail->returned_date &&
                    $detail->copy_id
                ) {
                    DB::table('book_copies')
                        ->where(
                            'copy_id',
                            $detail->copy_id
                        )
                        ->update([
                            'status' => 'Tersedia',
                        ]);
                }

                DB::table('loan_details')
                    ->where(
                        'loan_detail_id',
                        $loan_detail_id
                    )
                    ->delete();

                $this->syncLoanStatus($loanId);
            });

            return redirect()
                ->route('borrowers.index')
                ->with(
                    'success',
                    'Data peminjaman berhasil dihapus.'
                );
        } catch (\Throwable $e) {
            return redirect()
                ->route('borrowers.index')
                ->with(
                    'error',
                    'Gagal menghapus data peminjaman: '
                    . $e->getMessage()
                );
        }
    }

    // Kembalikan banyak buku
    public function bulkReturn(Request $request)
    {
        $validated = $request->validate([
            'ids' => [
                'required',
                'array',
                'min:1',
            ],
            'ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:loan_details,loan_detail_id',
            ],
        ], [
            'ids.required' =>
                'Pilih minimal satu data peminjaman.',
            'ids.array' =>
                'Data yang dipilih tidak valid.',
            'ids.min' =>
                'Pilih minimal satu data peminjaman.',
            'ids.*.integer' =>
                'Data peminjaman tidak valid.',
            'ids.*.distinct' =>
                'Terdapat data peminjaman yang sama.',
            'ids.*.exists' =>
                'Salah satu data peminjaman tidak ditemukan.',
        ]);

        try {
            $returnedCount = 0;

            DB::transaction(function () use (
                $validated,
                &$returnedCount
            ) {
                $details = DB::table('loan_details')
                    ->whereIn(
                        'loan_detail_id',
                        $validated['ids']
                    )
                    ->lockForUpdate()
                    ->get();

                if ($details->isEmpty()) {
                    throw new \Exception(
                        'Data peminjaman tidak ditemukan.'
                    );
                }

                $today = now()->toDateString();
                $loanIds = [];

                foreach ($details as $detail) {
                    $loanIds[] = $detail->loan_id;

                    // Lewati yang sudah dikembalikan
                    if ($detail->returned_date) {
                        continue;
                    }

                    DB::table('loan_details')
                        ->where(
                            'loan_detail_id',
                            $detail->loan_detail_id
                        )
                        ->update([
                            'returned_date' => $today,
                        ]);

                    if ($detail->copy_id) {
                        DB::table('book_copies')
                            ->where(
                                'copy_id',
                                $detail->copy_id
                            )
                            ->update([
                                'status' => 'Tersedia',
                            ]);
                    }

                    $returnedCount++;
                }

                $loanIds = array_unique(
                    $loanIds
                );

                foreach ($loanIds as $loanId) {
                    $this->syncLoanStatus(
                        $loanId
                    );
                }
            });

            if ($returnedCount === 0) {
                return redirect()
                    ->route('borrowers.index')
                    ->with(
                        'error',
                        'Semua buku yang dipilih sudah dikembalikan.'
                    );
            }

            return redirect()
                ->route('borrowers.index')
                ->with(
                    'success',
                    $returnedCount
                    . ' buku berhasil dikembalikan.'
                );
        } catch (\Throwable $e) {
            return redirect()
                ->route('borrowers.index')
                ->with(
                    'error',
                    'Gagal mengembalikan buku: '
                    . $e->getMessage()
                );
        }
    }

    // Hapus banyak peminjaman
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => [
                'required',
                'array',
                'min:1',
            ],
            'ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:loan_details,loan_detail_id',
            ],
        ], [
            'ids.required' =>
                'Pilih minimal satu data peminjaman.',
            'ids.array' =>
                'Data yang dipilih tidak valid.',
            'ids.min' =>
                'Pilih minimal satu data peminjaman.',
            'ids.*.integer' =>
                'Data peminjaman tidak valid.',
            'ids.*.distinct' =>
                'Terdapat data peminjaman yang sama.',
            'ids.*.exists' =>
                'Salah satu data peminjaman tidak ditemukan.',
        ]);

        try {
            $deletedCount = 0;

            DB::transaction(function () use (
                $validated,
                &$deletedCount
            ) {
                $details = DB::table('loan_details')
                    ->whereIn(
                        'loan_detail_id',
                        $validated['ids']
                    )
                    ->lockForUpdate()
                    ->get();

                if ($details->isEmpty()) {
                    throw new \Exception(
                        'Data peminjaman tidak ditemukan.'
                    );
                }

                $loanIds = [];

                foreach ($details as $detail) {
                    $loanIds[] = $detail->loan_id;

                    if (
                        !$detail->returned_date &&
                        $detail->copy_id
                    ) {
                        DB::table('book_copies')
                            ->where(
                                'copy_id',
                                $detail->copy_id
                            )
                            ->update([
                                'status' => 'Tersedia',
                            ]);
                    }
                }

                $deletedCount = DB::table('loan_details')
                    ->whereIn(
                        'loan_detail_id',
                        $validated['ids']
                    )
                    ->delete();

                $loanIds = array_unique(
                    $loanIds
                );

                foreach ($loanIds as $loanId) {
                    $this->syncLoanStatus(
                        $loanId
                    );
                }
            });

            return redirect()
                ->route('borrowers.index')
                ->with(
                    'success',
                    $deletedCount
                    . ' data peminjaman berhasil dihapus.'
                );
        } catch (\Throwable $e) {
            return redirect()
                ->route('borrowers.index')
                ->with(
                    'error',
                    'Gagal menghapus data peminjaman: '
                    . $e->getMessage()
                );
        }
    }

    // Sinkron status loan
    private function syncLoanStatus($loanId): void
    {
        $remainingDetails = DB::table('loan_details')
            ->where(
                'loan_id',
                $loanId
            )
            ->count();

        if ($remainingDetails === 0) {
            DB::table('loans')
                ->where(
                    'loan_id',
                    $loanId
                )
                ->delete();

            return;
        }

        $activeDetails = DB::table('loan_details')
            ->where(
                'loan_id',
                $loanId
            )
            ->whereNull('returned_date')
            ->count();

        if ($activeDetails === 0) {
            DB::table('loans')
                ->where(
                    'loan_id',
                    $loanId
                )
                ->update([
                    'status' => 'returned',
                    'returned_date' =>
                        now()->toDateString(),
                ]);

            return;
        }

        DB::table('loans')
            ->where(
                'loan_id',
                $loanId
            )
            ->update([
                'status' => 'borrowed',
                'returned_date' => null,
            ]);
    }
}