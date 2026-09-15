<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\VisitorCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VisitorAdminController extends Controller
{
    // Daftar pengunjung
    public function index(Request $request)
    {
        $search = trim(
            (string) $request->query('search', '')
        );

        $sort = $request->query(
            'sort',
            'latest'
        );

        if (!in_array(
            $sort,
            ['latest', 'oldest', 'all'],
            true
        )) {
            $sort = 'latest';
        }

        $query = Visitor::query()
            ->select('visitors.*')
            ->selectSub(
                DB::table('loans')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn(
                        'loans.visitor_id',
                        'visitors.visitor_id'
                    ),
                'loans_count'
            )
            ->withCount('checkins')
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $keyword = '%' . $search . '%';

                    $query->where(
                        function ($query) use ($keyword) {
                            $query
                                ->where(
                                    'visitor_name',
                                    'like',
                                    $keyword
                                )
                                ->orWhere(
                                    'employee_number',
                                    'like',
                                    $keyword
                                )
                                ->orWhere(
                                    'phone_number',
                                    'like',
                                    $keyword
                                )
                                ->orWhere(
                                    'visitor_category',
                                    'like',
                                    $keyword
                                );
                        }
                    );
                }
            );

        switch ($sort) {
            case 'oldest':
                $query
                    ->orderBy('visitors.created_at')
                    ->orderBy('visitors.visitor_id');
                break;

            case 'all':
                $query
                    ->orderBy('visitors.visitor_name')
                    ->orderBy('visitors.visitor_id');
                break;

            default:
                $query
                    ->orderByDesc('visitors.created_at')
                    ->orderByDesc('visitors.visitor_id');
                break;
        }

        return view(
            'pages.tables.Visitors.visitors',
            [
                'title' => 'Daftar Pengunjung',

                'visitors' => $query
                    ->paginate(20)
                    ->withQueryString(),

                'search' => $search,
                'sort' => $sort,
            ]
        );
    }

    // Detail pengunjung
    public function detail(
        Request $request,
        Visitor $visitor
    ) {
        $visitPage = max(
            (int) $request->query(
                'visit_page',
                1
            ),
            1
        );

        $loanPage = max(
            (int) $request->query(
                'loan_page',
                1
            ),
            1
        );

        // Kunjungan
        $checkins = $visitor
            ->checkins()
            ->orderByDesc('checked_in_at')
            ->orderByDesc('checkin_id')
            ->paginate(
                6,
                ['*'],
                'visit_page',
                $visitPage
            );

        // Peminjaman
        $loans = DB::table('loans')
            ->join(
                'loan_details',
                'loan_details.loan_id',
                '=',
                'loans.loan_id'
            )
            ->leftJoin(
                'books',
                'books.book_id',
                '=',
                'loan_details.book_id'
            )
            ->leftJoin(
                'book_copies',
                'book_copies.copy_id',
                '=',
                'loan_details.copy_id'
            )
            ->where(
                'loans.visitor_id',
                $visitor->visitor_id
            )
            ->select([
                'loans.loan_id',
                'loans.loan_date',
                'loans.due_date',
                'loans.returned_date as loan_returned_date',
                'loans.status as loan_status',

                'loan_details.loan_detail_id',
                'loan_details.returned_date as detail_returned_date',

                'books.book_identifier',
                'books.book_code',
                'books.title',

                'book_copies.copy_id',
                'book_copies.copy_code',
            ])
            ->orderByDesc('loans.loan_date')
            ->orderByDesc('loan_details.loan_detail_id')
            ->paginate(
                6,
                ['*'],
                'loan_page',
                $loanPage
            );

        // Statistik kunjungan
        $totalVisits = $visitor
            ->checkins()
            ->count();

        $lastVisitAt = $visitor
            ->checkins()
            ->max('checked_in_at');

        $lastVisit = $this->formatDateTime(
            $lastVisitAt
        );

        // Statistik peminjaman
        $totalLoans = DB::table('loans')
            ->where(
                'visitor_id',
                $visitor->visitor_id
            )
            ->count();

        $activeLoans = DB::table('loan_details')
            ->join(
                'loans',
                'loans.loan_id',
                '=',
                'loan_details.loan_id'
            )
            ->where(
                'loans.visitor_id',
                $visitor->visitor_id
            )
            ->whereNull(
                'loan_details.returned_date'
            )
            ->count();

        $lastLoanAt = DB::table('loans')
            ->where(
                'visitor_id',
                $visitor->visitor_id
            )
            ->max('loan_date');

        $lastLoan = $this->formatDateTime(
            $lastLoanAt
        );

        // Riwayat kunjungan
        $visitHistory = $checkins
            ->getCollection()
            ->map(function ($checkin) {
                $checkedInAt = $checkin->checked_in_at
                    ? Carbon::parse(
                        $checkin->checked_in_at
                    )
                    : null;

                return [
                    'id' => $checkin->checkin_id,

                    'date' => $checkedInAt
                        ? $checkedInAt
                            ->locale('id')
                            ->translatedFormat('d M Y')
                        : '-',

                    'time' => $checkedInAt
                        ? $checkedInAt->format('H:i')
                        : '-',

                    'datetime' => $checkedInAt
                        ? $checkedInAt
                            ->locale('id')
                            ->translatedFormat(
                                'd M Y, H:i'
                            )
                        : '-',

                    'photo_url' => route(
                        'visitors.selfie',
                        $checkin->checkin_id
                    ),
                ];
            })
            ->values();

        // Riwayat peminjaman
        $loanHistory = $loans
            ->getCollection()
            ->map(function ($loan) {
                $returnedDate =
                    $loan->detail_returned_date
                    ?: $loan->loan_returned_date;

                $isReturned =
                    !empty($returnedDate)
                    || strtolower(
                        (string) $loan->loan_status
                    ) === 'returned';

                $isOverdue =
                    !$isReturned
                    && !empty($loan->due_date)
                    && Carbon::parse(
                        $loan->due_date
                    )
                        ->endOfDay()
                        ->isPast();

                if ($isReturned) {
                    $status = 'Dikembalikan';
                    $statusKey = 'returned';
                } elseif ($isOverdue) {
                    $status = 'Terlambat';
                    $statusKey = 'overdue';
                } else {
                    $status = 'Dipinjam';
                    $statusKey = 'borrowed';
                }

                return [
                    'id' => $loan->loan_detail_id,

                    'loan_id' => $loan->loan_id,

                    'book_id' => $loan->book_identifier
                        ?: '-',

                    'book_no' => $loan->book_code
                        ?: '-',

                    'title' => $loan->title
                        ?: '-',

                    'copy_id' => $loan->copy_code
                        ?: '-',

                    'loan_date' => $this->formatDate(
                        $loan->loan_date
                    ),

                    'due_date' => $this->formatDate(
                        $loan->due_date
                    ),

                    'returned_date' => $this->formatDate(
                        $returnedDate
                    ),

                    'status' => $status,

                    'status_key' => $statusKey,
                ];
            })
            ->values();

        return response()->json([
            'visitor' => [
                'id' => $visitor->visitor_id,

                'name' => $visitor->visitor_name,

                'category' => $visitor->visitor_category,

                'identity' => $visitor->employee_number,

                'phone' => $visitor->phone_number,

                'is_active' => (bool) $visitor->is_active,

                'registered_at' => $this->formatDateTime(
                    $visitor->created_at
                ),

                'profile_url' => route(
                    'visitors.profile-photo',
                    $visitor->visitor_id
                ),

                'status_url' => route(
                    'visitors.status',
                    $visitor->visitor_id
                ),

                'total_visits' => $totalVisits,

                'total_loans' => $totalLoans,

                'active_loans' => $activeLoans,

                'last_visit' => $lastVisit,

                'last_loan' => $lastLoan,
            ],

            'visits' => [
                'items' => $visitHistory,

                'page' => $checkins
                    ->currentPage(),

                'last_page' => $checkins
                    ->lastPage(),

                'total' => $checkins
                    ->total(),
            ],

            'loans' => [
                'items' => $loanHistory,

                'page' => $loans
                    ->currentPage(),

                'last_page' => $loans
                    ->lastPage(),

                'total' => $loans
                    ->total(),
            ],
        ]);
    }
    // Ubah status
    public function toggleStatus(
        Request $request,
        Visitor $visitor
    ) {
        try {
            $visitor->update([
                'is_active' => !$visitor->is_active,
            ]);

            $visitor->refresh();

            $message = $visitor->is_active
                ? 'Pengunjung berhasil diaktifkan kembali.'
                : 'Pengunjung berhasil dinonaktifkan.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'is_active' => (bool) $visitor->is_active,
                ]);
            }

            return back()->with(
                'success',
                $message
            );
        } catch (\Throwable $e) {
            report($e);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status pengunjung gagal diubah.',
                ], 500);
            }

            return back()->with(
                'error',
                'Status pengunjung gagal diubah.'
            );
        }
    }
    // Foto profil
    public function profilePhoto(
        Visitor $visitor
    ) {
        return $this->servePhoto(
            $visitor->profile_photo,
            'visitors/profiles'
        );
    }

    // Selfie
    public function selfie(
        VisitorCheckin $checkin
    ) {
        return $this->servePhoto(
            $checkin->selfie_path,
            'visitors/checkins'
        );
    }

    // Hapus pengunjung
    public function destroy(
        Visitor $visitor
    ) {
        try {
            $result = DB::transaction(
                function () use ($visitor) {
                    $visitor = Visitor::whereKey(
                        $visitor->getKey()
                    )
                        ->lockForUpdate()
                        ->firstOrFail();

                    $hasLoans = DB::table('loans')
                        ->where(
                            'visitor_id',
                            $visitor->visitor_id
                        )
                        ->exists();

                    $hasCheckins = $visitor
                        ->checkins()
                        ->exists();

                    if (
                        $hasLoans
                        || $hasCheckins
                    ) {
                        return [
                            'deleted' => false,
                            'path' => null,
                        ];
                    }

                    $path = $visitor->profile_photo;

                    $visitor->delete();

                    return [
                        'deleted' => true,
                        'path' => $path,
                    ];
                }
            );

            if (!$result['deleted']) {
                return back()->with(
                    'error',
                    'Pengunjung memiliki riwayat kunjungan atau peminjaman sehingga tidak dapat dihapus.'
                );
            }

            if ($result['path']) {
                try {
                    Storage::disk('local')->delete(
                        $result['path']
                    );
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            return back()->with(
                'success',
                'Data pengunjung berhasil dihapus.'
            );

        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Data pengunjung gagal dihapus. Periksa apakah masih ada relasi yang tersimpan.'
            );
        }
    }

    // Format tanggal
    private function formatDate(
        $date
    ): string {
        if (!$date) {
            return '-';
        }

        return Carbon::parse($date)
            ->locale('id')
            ->translatedFormat('d M Y');
    }

    // Format tanggal waktu
    private function formatDateTime(
        $date
    ): string {
        if (!$date) {
            return '-';
        }

        return Carbon::parse($date)
            ->locale('id')
            ->translatedFormat(
                'd M Y, H:i'
            );
    }

    // Baca foto privat
    private function servePhoto(
        ?string $path,
        string $directory
    ) {
        $pattern =
            '~\A'
            . preg_quote(
                $directory,
                '~'
            )
            . '/[A-Za-z0-9_-]+\.(?:jpe?g|png|webp)\z~i';

        if (
            !$path
            || !preg_match(
                $pattern,
                $path
            )
        ) {
            abort(404);
        }

        $disk = Storage::disk('local');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $mime = $disk->mimeType($path);

        if (!in_array(
            $mime,
            [
                'image/jpeg',
                'image/png',
                'image/webp',
            ],
            true
        )) {
            abort(404);
        }

        return response()->file(
            $disk->path($path),
            [
                'Content-Type' => $mime,

                'Content-Disposition' => 'inline',

                'Cache-Control' =>
                    'private, no-store, max-age=0',

                'X-Content-Type-Options' =>
                    'nosniff',
            ]
        );
    }
}