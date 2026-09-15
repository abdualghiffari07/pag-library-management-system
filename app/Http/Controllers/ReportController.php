<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Buku
        $totalBooks = Book::where('status', 'public')->count();

        $totalBookCopies = BookCopy::whereHas('book', function ($query) {
            $query->where('status', 'public');
        })->count();

        $borrowedBooks = BookCopy::whereHas('book', function ($query) {
            $query->where('status', 'public');
        })
            ->whereRaw('LOWER(status) = ?', ['dipinjam'])
            ->count();

        $availableBooks = BookCopy::whereHas('book', function ($query) {
            $query->where('status', 'public');
        })
            ->whereRaw('LOWER(status) = ?', ['tersedia'])
            ->count();

        // Pengunjung
        $visitors = Visitor::count();

        $workerVisitors = Visitor::where(
            'visitor_category',
            'pekerja'
        )->count();

        $studentVisitors = Visitor::where(
            'visitor_category',
            'mahasiswa'
        )->count();

        $guestVisitors = Visitor::where(
            'visitor_category',
            'tamu'
        )->count();

        $otherVisitors = Visitor::where(
            'visitor_category',
            'lainnya'
        )->count();

        // Pengunjung terbaru
        $recentVisitors = DB::table('visitor_checkins as checkins')
            ->join(
                'visitors as visitors',
                'visitors.visitor_id',
                '=',
                'checkins.visitor_id'
            )
            ->select([
                'visitors.visitor_id',
                'visitors.visitor_name',
                'visitors.visitor_category',
                'visitors.employee_number',
                'visitors.phone_number',
                'visitors.profile_photo',
                'checkins.checkin_id',
                'checkins.checked_in_at',
            ])
            ->whereNotNull('checkins.checked_in_at')
            ->orderByDesc('checkins.checked_in_at')
            ->limit(5)
            ->get();

        // Periode
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : now();

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : now()->startOfYear();

        if ($startDate->gt($endDate)) {
            [$startDate, $endDate] = [
                $endDate,
                $startDate,
            ];
        }

        $rangeStart = $startDate
            ->copy()
            ->startOfDay();

        $rangeEnd = $endDate
            ->copy()
            ->endOfDay();

        $daysDifference = $startDate
            ->diffInDays($endDate);

        $groupByDay = $daysDifference <= 31;

        // Peminjaman
        if ($groupByDay) {
            $loanResults = Loan::select(
                DB::raw('CAST(loan_date AS DATE) as period'),
                DB::raw('COUNT(*) as total')
            )
                ->whereNotNull('loan_date')
                ->whereBetween('loan_date', [
                    $rangeStart,
                    $rangeEnd,
                ])
                ->groupBy(
                    DB::raw('CAST(loan_date AS DATE)')
                )
                ->orderBy(
                    DB::raw('CAST(loan_date AS DATE)')
                )
                ->get();
        } else {
            $loanResults = Loan::select(
                DB::raw('YEAR(loan_date) as year'),
                DB::raw('MONTH(loan_date) as month'),
                DB::raw('COUNT(*) as total')
            )
                ->whereNotNull('loan_date')
                ->whereBetween('loan_date', [
                    $rangeStart,
                    $rangeEnd,
                ])
                ->groupBy(
                    DB::raw('YEAR(loan_date)'),
                    DB::raw('MONTH(loan_date)')
                )
                ->orderBy(
                    DB::raw('YEAR(loan_date)')
                )
                ->orderBy(
                    DB::raw('MONTH(loan_date)')
                )
                ->get();
        }

        // Statistik pengunjung
        if ($groupByDay) {
            $visitorResults = Visitor::select(
                DB::raw('CAST(created_at AS DATE) as period'),
                DB::raw('COUNT(*) as total')
            )
                ->whereNotNull('created_at')
                ->whereBetween('created_at', [
                    $rangeStart,
                    $rangeEnd,
                ])
                ->groupBy(
                    DB::raw('CAST(created_at AS DATE)')
                )
                ->orderBy(
                    DB::raw('CAST(created_at AS DATE)')
                )
                ->get();
        } else {
            $visitorResults = Visitor::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
                ->whereNotNull('created_at')
                ->whereBetween('created_at', [
                    $rangeStart,
                    $rangeEnd,
                ])
                ->groupBy(
                    DB::raw('YEAR(created_at)'),
                    DB::raw('MONTH(created_at)')
                )
                ->orderBy(
                    DB::raw('YEAR(created_at)')
                )
                ->orderBy(
                    DB::raw('MONTH(created_at)')
                )
                ->get();
        }

        // Chart
        $chartMonths = [];
        $loanData = [];
        $visitorData = [];

        if ($groupByDay) {
            $cursor = $startDate
                ->copy()
                ->startOfDay();

            while ($cursor->lte($endDate)) {
                $chartMonths[] = $cursor->format('d M');
                $loanData[] = 0;
                $visitorData[] = 0;

                $cursor->addDay();
            }

            foreach ($loanResults as $row) {
                $date = Carbon::parse($row->period);

                $index = $startDate
                    ->copy()
                    ->startOfDay()
                    ->diffInDays($date);

                if (isset($loanData[$index])) {
                    $loanData[$index] = (int) $row->total;
                }
            }

            foreach ($visitorResults as $row) {
                $date = Carbon::parse($row->period);

                $index = $startDate
                    ->copy()
                    ->startOfDay()
                    ->diffInDays($date);

                if (isset($visitorData[$index])) {
                    $visitorData[$index] = (int) $row->total;
                }
            }
        } else {
            $cursor = $startDate
                ->copy()
                ->startOfMonth();

            while ($cursor->lte($endDate)) {
                $chartMonths[] = $cursor
                    ->translatedFormat('M Y');

                $loanData[] = 0;
                $visitorData[] = 0;

                $cursor->addMonth();
            }

            foreach ($loanResults as $row) {
                $period = Carbon::create(
                    $row->year,
                    $row->month,
                    1
                );

                $index = $startDate
                    ->copy()
                    ->startOfMonth()
                    ->diffInMonths($period);

                if (isset($loanData[$index])) {
                    $loanData[$index] = (int) $row->total;
                }
            }

            foreach ($visitorResults as $row) {
                $period = Carbon::create(
                    $row->year,
                    $row->month,
                    1
                );

                $index = $startDate
                    ->copy()
                    ->startOfMonth()
                    ->diffInMonths($period);

                if (isset($visitorData[$index])) {
                    $visitorData[$index] = (int) $row->total;
                }
            }
        }

        return view(
            'pages.tables.report.report',
            compact(
                'totalBooks',
                'totalBookCopies',
                'borrowedBooks',
                'availableBooks',
                'visitors',
                'workerVisitors',
                'studentVisitors',
                'guestVisitors',
                'otherVisitors',
                'recentVisitors',
                'chartMonths',
                'loanData',
                'visitorData',
                'startDate',
                'endDate',
                'groupByDay'
            )
        );
    }
}