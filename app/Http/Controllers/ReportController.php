<?php

namespace App\Http\Controllers;

use App\Models\BookCopy;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $totalBooks = BookCopy::whereHas('book', function ($query) {
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

        $visitors = Loan::whereNotNull('nopek')
            ->where('nopek', '!=', '')
            ->distinct('nopek')
            ->count('nopek');

        /*
        |--------------------------------------------------------------------------
        | PERIODE GRAFIK
        |--------------------------------------------------------------------------
        */

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : now();

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : now()->startOfYear();

        if ($startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        /*
        |--------------------------------------------------------------------------
        | MODE GRAFIK
        |--------------------------------------------------------------------------
        */

        $daysDifference = $startDate->diffInDays($endDate);

        $groupByDay = $daysDifference <= 31;

        /*
        |--------------------------------------------------------------------------
        | DATA PEMINJAMAN
        |--------------------------------------------------------------------------
        */

        if ($groupByDay) {
            $loanResults = Loan::select(
                    DB::raw('CAST(loan_date AS DATE) as period'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereNotNull('loan_date')
                ->whereBetween('loan_date', [
                    $startDate->startOfDay(),
                    $endDate->endOfDay()
                ])
                ->groupBy(DB::raw('CAST(loan_date AS DATE)'))
                ->orderBy(DB::raw('CAST(loan_date AS DATE)'))
                ->get();
        } else {
            $loanResults = Loan::select(
                    DB::raw('YEAR(loan_date) as year'),
                    DB::raw('MONTH(loan_date) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereNotNull('loan_date')
                ->whereBetween('loan_date', [
                    $startDate->startOfDay(),
                    $endDate->endOfDay()
                ])
                ->groupBy(
                    DB::raw('YEAR(loan_date)'),
                    DB::raw('MONTH(loan_date)')
                )
                ->orderBy(DB::raw('YEAR(loan_date)'))
                ->orderBy(DB::raw('MONTH(loan_date)'))
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | DATA PENGUNJUNG
        |--------------------------------------------------------------------------
        */

        if ($groupByDay) {
            $visitorResults = Loan::select(
                    DB::raw('CAST(loan_date AS DATE) as period'),
                    DB::raw('COUNT(DISTINCT nopek) as total')
                )
                ->whereNotNull('loan_date')
                ->whereNotNull('nopek')
                ->where('nopek', '!=', '')
                ->whereBetween('loan_date', [
                    $startDate->startOfDay(),
                    $endDate->endOfDay()
                ])
                ->groupBy(DB::raw('CAST(loan_date AS DATE)'))
                ->orderBy(DB::raw('CAST(loan_date AS DATE)'))
                ->get();
        } else {
            $visitorResults = Loan::select(
                    DB::raw('YEAR(loan_date) as year'),
                    DB::raw('MONTH(loan_date) as month'),
                    DB::raw('COUNT(DISTINCT nopek) as total')
                )
                ->whereNotNull('loan_date')
                ->whereNotNull('nopek')
                ->where('nopek', '!=', '')
                ->whereBetween('loan_date', [
                    $startDate->startOfDay(),
                    $endDate->endOfDay()
                ])
                ->groupBy(
                    DB::raw('YEAR(loan_date)'),
                    DB::raw('MONTH(loan_date)')
                )
                ->orderBy(DB::raw('YEAR(loan_date)'))
                ->orderBy(DB::raw('MONTH(loan_date)'))
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | LABEL DAN DATA CHART
        |--------------------------------------------------------------------------
        */

        $chartMonths = [];
        $loanData = [];
        $visitorData = [];

        if ($groupByDay) {
            $cursor = $startDate->copy()->startOfDay();

            while ($cursor->lte($endDate)) {
                $chartMonths[] = $cursor->format('d M');
                $loanData[] = 0;
                $visitorData[] = 0;

                $cursor->addDay();
            }

            foreach ($loanResults as $row) {
                $date = Carbon::parse($row->period)->format('Y-m-d');

                $index = $startDate->copy()
                    ->startOfDay()
                    ->diffInDays(Carbon::parse($date));

                if (isset($loanData[$index])) {
                    $loanData[$index] = (int) $row->total;
                }
            }

            foreach ($visitorResults as $row) {
                $date = Carbon::parse($row->period)->format('Y-m-d');

                $index = $startDate->copy()
                    ->startOfDay()
                    ->diffInDays(Carbon::parse($date));

                if (isset($visitorData[$index])) {
                    $visitorData[$index] = (int) $row->total;
                }
            }
        } else {
            $cursor = $startDate->copy()->startOfMonth();

            while ($cursor->lte($endDate)) {
                $chartMonths[] = $cursor->translatedFormat('M Y');
                $loanData[] = 0;
                $visitorData[] = 0;

                $cursor->addMonth();
            }

            foreach ($loanResults as $row) {
                $index = $startDate->copy()
                    ->startOfMonth()
                    ->diffInMonths(
                        Carbon::create(
                            $row->year,
                            $row->month,
                            1
                        )
                    );

                if (isset($loanData[$index])) {
                    $loanData[$index] = (int) $row->total;
                }
            }

            foreach ($visitorResults as $row) {
                $index = $startDate->copy()
                    ->startOfMonth()
                    ->diffInMonths(
                        Carbon::create(
                            $row->year,
                            $row->month,
                            1
                        )
                    );

                if (isset($visitorData[$index])) {
                    $visitorData[$index] = (int) $row->total;
                }
            }
        }

        return view('pages.tables.report.report', compact(
            'totalBooks',
            'borrowedBooks',
            'availableBooks',
            'visitors',
            'chartMonths',
            'loanData',
            'visitorData',
            'startDate',
            'endDate',
            'groupByDay'
        ));
    }
}