<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $role = $user->role->role_name;

        /*
        |--------------------------------------------------------------------------
        | Statistik Buku
        |--------------------------------------------------------------------------
        */

        $totalBooks = Book::count();

        $totalCopies = BookCopy::count();

        $availableCopies = BookCopy::where(
            'status',
            'tersedia'
        )->count();

        $borrowedCopies = BookCopy::where(
            'status',
            'dipinjam'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Statistik Peminjaman
        |--------------------------------------------------------------------------
        */

        $totalLoans = Loan::count();

        $activeLoans = Loan::whereIn(
            'status',
            ['borrowed', 'overdue']
        )->count();

        $overdueLoans = Loan::where(
            'status',
            'overdue'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Dashboard berdasarkan Role
        |--------------------------------------------------------------------------
        */

        if ($role === 'member') {

            $myLoans = Loan::with([
                'loanDetails.book',
                'loanDetails.bookCopy',
            ])
                ->where('user_id', $user->user_id)
                ->orderByDesc('loan_id')
                ->get();

            return view('dashboard.member', compact(
                'user',
                'myLoans'
            ));
        }


        if ($role === 'admin') {

            return view('dashboard.admin', compact(
                'user',
                'totalBooks',
                'totalCopies',
                'availableCopies',
                'borrowedCopies',
                'totalLoans',
                'activeLoans',
                'overdueLoans'
            ));
        }


        if ($role === 'librarian') {

            return view('dashboard.librarian', compact(
                'user',
                'totalBooks',
                'totalCopies',
                'availableCopies',
                'borrowedCopies',
                'totalLoans',
                'activeLoans',
                'overdueLoans'
            ));
        }


        abort(403);
    }
}