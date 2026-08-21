<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use App\Models\Book;

// LANDING PAGE

Route::get('/', function () {
    return view('landing');
})->name('landing');


// LOGIN
Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {

        $user = Auth::user();

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

// HALAMAN SETELAH LOGIN
Route::middleware('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages.dashboard.ecommerce', [
            'title' => 'E-commerce Dashboard'
        ]);
    })->name('dashboard');


    // Profile
    Route::get('/profile', function () {
        return view('pages.profile', [
            'title' => 'Profile'
        ]);
    })->name('profile');


    // Form
    Route::get('/form-elements', function () {
        return view('pages.form.form-elements', [
            'title' => 'Form Elements'
        ]);
    })->name('form-elements');


    // Basic Tables
    Route::get('/basic-tables', function () {
        return view('pages.tables.basic-tables', [
            'title' => 'Basic Tables'
        ]);
    })->name('basic-tables');

// Data Buku
Route::get('/books-data', function () {

    $books = Book::where('status', 'public')
        ->with([
            'authors',
            'copies',
        ])
        ->get();

    return view('pages.tables.books.books-data', [
        'title' => 'Data Buku',
        'books' => $books,
    ]);

})->middleware('admin')->name('data-buku');

    // Tambah Buku
    Route::get('/books/create', function () {
        return view('pages.tables.books.add-books', [
            'title' => 'Tambah Buku',
        ]);
    })->middleware('admin')->name('books.create');

    // Simpan Buku
    Route::post('/books', [BookController::class, 'store'])
        ->middleware('admin')
        ->name('books.store');

    // Cek Book No.
    Route::get('/books/check-book-no', [BookController::class, 'checkBookNo'])
        ->middleware('admin')
        ->name('books.check-book-no');
    //Pinjam dan kembalikan buku
    Route::post('/books/{book_id}/borrow', [BookController::class, 'borrow'])
        ->name('books.borrow');

    Route::post(
        'books/{book_id}/return/{loan_detail_id}',
        [BookController::class, 'returnBook']
    )->name('books.return');
    
    //Edit
    Route::get('/books/{book_id}/edit', [BookController::class, 'edit'])
        ->middleware('admin')
        ->name('books.edit');

    Route::put('/books/{book_id}', [BookController::class, 'update'])
        ->middleware('admin')
        ->name('books.update');

    Route::delete('/books/{book_code}', [BookController::class, 'destroy'])
        ->middleware('admin')
        ->name('books.destroy');

    // Authors
    Route::get('/authors', function () {
        return view('pages.tables.authors', [
            'title' => 'Authors'
        ]);
    })->name('authors');


    // Pengunjung
    Route::get('/visitors', function () {
        return view('pages.tables.visitors', [
            'title' => 'Daftar Pengunjung'
        ]);
    })->name('visitors');

// Logout
Route::post('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

});