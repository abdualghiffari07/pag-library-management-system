<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Book;
use App\Models\Location;
use App\Models\Equipment;

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EquipmentController;

// Landing Page
Route::get('/', function () {
    return view('pages.landing-page.landing');
})->name('landing');

// Login
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Hanya admin yang dapat masuk
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

// Pengunjung dari landing page
Route::post('/visitors', [VisitorController::class, 'store'])
    ->name('visitors.store');

// Halaman Admin
Route::middleware('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [ReportController::class, 'index'])
        ->name('dashboard');

    // Profile
    Route::get('/profile', function () {
        return view('pages.profile', [
            'title' => 'Profile',
        ]);
    })->name('profile');

    // Form
    Route::get('/form-elements', function () {
        return view('pages.form.form-elements', [
            'title' => 'Form Elements',
        ]);
    })->name('form-elements');

    // Basic Tables
    Route::get('/basic-tables', function () {
        return view('pages.tables.basic-tables', [
            'title' => 'Basic Tables',
        ]);
    })->name('basic-tables');

    // =====================================================
    // DATA BUKU
    // =====================================================

    Route::get('/books-data', function () {
        $books = Book::where('status', 'public')
            ->with([
                'authors',
                'copies',
                'location',
                'equipment',
            ])
            ->get();

        return view('pages.tables.books.books-data', [
            'title' => 'Data Buku',
            'books' => $books,
        ]);
    })->name('data-buku');

    // Tambah Buku
    Route::get('/books/create', function () {
        return view('pages.tables.books.add-books', [
            'title' => 'Tambah Buku',
            'locations' => Location::orderBy('location_name')->get(),
            'equipments' => Equipment::orderBy('equipment_name')->get(),
        ]);
    })->name('books.create');

    // Simpan Buku
    Route::post('/books', [BookController::class, 'store'])
        ->name('books.store');

    // Cek Book No
    Route::get('/books/check-book-no', [BookController::class, 'checkBookNo'])
        ->name('books.check-book-no');

    // Pinjam Buku
    Route::post('/books/{book_id}/borrow', [BookController::class, 'borrow'])
        ->name('books.borrow');

    // Kembalikan Buku
    Route::post(
        '/books/{book_id}/return/{loan_detail_id}',
        [BookController::class, 'returnBook']
    )->name('books.return');

    // Edit Buku
    Route::get('/books/{book_id}/edit', [BookController::class, 'edit'])
        ->name('books.edit');

    // Update Buku
    Route::put('/books/{book_id}', [BookController::class, 'update'])
        ->name('books.update');

    // Hapus Buku
    Route::delete('/books/{book_code}', [BookController::class, 'destroy'])
        ->name('books.destroy');

    // Search Buku
    Route::get('/books-search', [BookController::class, 'search'])
        ->name('books.search');

    // =====================================================
    // AUTHORS
    // =====================================================

    Route::get('/authors', [AuthorController::class, 'index'])
        ->name('authors');

    Route::get('/authors/create', [AuthorController::class, 'create'])
        ->name('authors.create');

    Route::post('/authors', [AuthorController::class, 'store'])
        ->name('authors.store');

    Route::get('/authors/{id}/edit', [AuthorController::class, 'edit'])
        ->name('authors.edit');

    Route::put('/authors/{id}', [AuthorController::class, 'update'])
        ->name('authors.update');

    Route::delete('/authors/{id}', [AuthorController::class, 'destroy'])
        ->name('authors.destroy');

    // =====================================================
    // EQUIPMENT
    // =====================================================

    // Daftar Equipment
    Route::get('/equipment', [EquipmentController::class, 'index'])
        ->name('equipment.index');

    // Form Tambah Equipment
    Route::get('/equipment/create', [EquipmentController::class, 'create'])
        ->name('equipment.create');

    // Simpan Equipment
    Route::post('/equipment', [EquipmentController::class, 'store'])
        ->name('equipment.store');

    // Form Edit Equipment
    Route::get('/equipment/{equipment_id}/edit', [EquipmentController::class, 'edit'])
        ->name('equipment.edit');

    // Update Equipment
    Route::put('/equipment/{equipment_id}', [EquipmentController::class, 'update'])
        ->name('equipment.update');

    // Hapus Equipment
    Route::delete('/equipment/{equipment_id}', [EquipmentController::class, 'destroy'])
        ->name('equipment.destroy');

    // =====================================================
    // DAFTAR PENGUNJUNG
    // =====================================================

    Route::get('/visitors', function (Request $request) {
        $search = $request->input('search');

        $visitors = DB::table('visitors')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where(
                        'visitor_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'employee_number',
                        'like',
                        '%' . $search . '%'
                    );
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.tables.Visitors.visitors', [
            'title' => 'Daftar Pengunjung',
            'visitors' => $visitors,
            'search' => $search,
        ]);
    })->name('visitors');

    // Hapus Pengunjung
    Route::delete('/visitors/{visitor}', function ($visitor) {
        DB::table('visitors')
            ->where('visitor_id', $visitor)
            ->delete();

        return redirect()
            ->route('visitors')
            ->with(
                'success',
                'Data pengunjung berhasil dihapus.'
            );
    })->name('visitors.destroy');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});