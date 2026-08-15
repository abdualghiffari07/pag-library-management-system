<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;

class ReportController extends Controller
{
public function index()
{
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Statistik Buku
    |--------------------------------------------------------------------------
    */

    $totalBooks = Book::count();

    $totalCopies = BookCopy::count();


    /*
    |--------------------------------------------------------------------------
    | Statistik Eksemplar
    |--------------------------------------------------------------------------
    */

    $availableCopies = BookCopy::where(
        'status',
        'available'
    )->count();

    $borrowedCopies = BookCopy::where(
        'status',
        'borrowed'
    )->count();


    /*
    |--------------------------------------------------------------------------
    | Statistik Peminjaman
    |--------------------------------------------------------------------------
    */

    $totalLoans = Loan::count();

    $activeLoans = Loan::where(
        'status',
        'borrowed'
    )->count();


    /*
    |--------------------------------------------------------------------------
    | Peminjaman Terlambat
    |--------------------------------------------------------------------------
    */

    $overdueLoans = Loan::where(
        'status',
        'borrowed'
    )
    ->whereDate(
        'due_date',
        '<',
        now()
    )
    ->count();


    /*
    |--------------------------------------------------------------------------
    | Kirim data ke view
    |--------------------------------------------------------------------------
    */

    return view(
        'dashboard.report',
        compact(
            'user',
            'totalBooks',
            'totalCopies',
            'availableCopies',
            'borrowedCopies',
            'overdueLoans',
            'totalLoans',
            'activeLoans'
        )
    );
}
}
