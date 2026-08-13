<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\BookCopyController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


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
| Public
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('role:admin,librarian,member')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('login');
})->name('login');


Route::get('/login', function () {
    return view('login');
})->name('login');


Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');

})->name('login.process');


Route::post('/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');

})->name('logout');


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

Route::middleware('role:admin,librarian')->group(function () {

    // Buku
    // Member tidak boleh create, edit, update, delete
    // sehingga hanya index dan show yang diberikan ke Member di bawah.
    Route::resource('books', BookController::class)
        ->except(['index', 'show']);

    // Eksemplar Buku
    Route::resource('book-copies', BookCopyController::class);

    // Penulis
    Route::resource('authors', AuthorController::class)
        ->except(['index', 'show']);

    // Kategori
    Route::resource('categories', CategoryController::class)
        ->except(['index', 'show']);

    // Lokasi
    Route::resource('locations', LocationController::class);

    // Peminjaman
    Route::resource('loans', LoanController::class);

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

Route::middleware('role:admin,librarian,member')->group(function () {

    // Buku
    Route::get('/books', [BookController::class, 'index'])
        ->name('books.index');

    Route::get('/books/{book}', [BookController::class, 'show'])
        ->name('books.show');

    // Penulis
    Route::get('/authors', [AuthorController::class, 'index'])
        ->name('authors.index');

    Route::get('/authors/{author}', [AuthorController::class, 'show'])
        ->name('authors.show');

    // Kategori
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    Route::get('/categories/{category}', [CategoryController::class, 'show'])
        ->name('categories.show');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');