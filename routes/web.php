<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\BookCopyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;


/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {

        $user = Auth::user();

        // Hanya ADMIN yang diperbolehkan login
        if (!$user->role || $user->role->role_name !== 'admin') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'Akun Anda tidak memiliki akses ke sistem.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    return back()
        ->withErrors([
            'email' => 'Email atau password salah.',
        ])
        ->onlyInput('email');

})->name('login.process');


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');

})->name('logout');


/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('books.index');
})
    ->middleware(['role:admin', 'no-cache'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Laporan Admin
|--------------------------------------------------------------------------
*/

Route::get('/laporan', [ReportController::class, 'index'])
    ->middleware(['role:admin', 'no-cache'])
    ->name('reports.index');


/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/

Route::get('/admin-test', function () {

    return 'Anda berhasil masuk sebagai ADMIN.';

})->middleware('role:admin');


/*
|--------------------------------------------------------------------------
| Admin & Librarian
|--------------------------------------------------------------------------
*/

Route::middleware(['role:admin', 'no-cache'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Buku
    |--------------------------------------------------------------------------
    */

    Route::resource('books', BookController::class)
        ->except([
            'index',
            'show'
        ]);


    /*
    |--------------------------------------------------------------------------
    | Eksemplar Buku
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'book-copies',
        BookCopyController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Penulis
    |--------------------------------------------------------------------------
    */

    Route::resource('authors', AuthorController::class)
        ->except([
            'index',
            'show'
        ]);


    /*
    |--------------------------------------------------------------------------
    | Kategori
    |--------------------------------------------------------------------------
    */

    Route::resource('categories', CategoryController::class)
        ->except([
            'index',
            'show'
        ]);


    /*
    |--------------------------------------------------------------------------
    | Lokasi
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'locations',
        LocationController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Peminjaman
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'loans',
        LoanController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Pengembalian Buku
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/loan-details/{loanDetail}/return',
        [LoanController::class, 'returnBookDetail']
    )->name('loan-details.return');

});


/*
|--------------------------------------------------------------------------
| Admin, Librarian & Member - Read Only
|--------------------------------------------------------------------------
*/

Route::middleware(['role:admin', 'no-cache'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Buku
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/books',
        [BookController::class, 'index']
    )->name('books.index');


    Route::get(
        '/books/{book}',
        [BookController::class, 'show']
    )->name('books.show');


    /*
    |--------------------------------------------------------------------------
    | Penulis
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/authors',
        [AuthorController::class, 'index']
    )->name('authors.index');


    Route::get(
        '/authors/{author}',
        [AuthorController::class, 'show']
    )->name('authors.show');


    /*
    |--------------------------------------------------------------------------
    | Kategori
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/categories',
        [CategoryController::class, 'index']
    )->name('categories.index');


    Route::get(
        '/categories/{category}',
        [CategoryController::class, 'show']
    )->name('categories.show');

});