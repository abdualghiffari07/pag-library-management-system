<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VisitorController;
use App\Models\Book;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing
Route::get('/', function () {
    return view('pages.landing-page.landing', [
        'totalBooks' => Book::where('status', 'public')->count(),
        'totalVisitors' => Visitor::count(),
    ]);
})->name('landing');

// Login
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (!Auth::attempt($credentials)) {
        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

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
})->name('login.process');

// Pengunjung landing
Route::get('/visitor-register', function () {
    return view('pages.landing-page.visitor-register');
})->name('visitors.register');

Route::post('/visitors', [VisitorController::class, 'store'])
    ->name('visitors.store');

Route::post('/visitors/check-in', [VisitorController::class, 'checkIn'])
    ->name('visitors.checkin');

// Admin
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

    // Buku
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

    Route::get('/books/create', [BookController::class, 'create'])
        ->name('books.create');

    Route::post('/books', [BookController::class, 'store'])
        ->name('books.store');

    Route::get('/books/check-book-id', [BookController::class, 'checkBookId'])
        ->name('books.check-book-id');

    Route::get('/books/check-book-no', [BookController::class, 'checkBookNo'])
        ->name('books.check-book-no');

    Route::get('/books-search', [BookController::class, 'search'])
        ->name('books.search');

    Route::post('/books/{book_id}/borrow', [BookController::class, 'borrow'])
        ->name('books.borrow');

    Route::post(
        '/books/{book_id}/return/{loan_detail_id}',
        [BookController::class, 'returnBook']
    )->name('books.return');

    Route::get('/books/{book_id}/edit', [BookController::class, 'edit'])
        ->name('books.edit');

    Route::put('/books/{book_id}', [BookController::class, 'update'])
        ->name('books.update');

    Route::delete('/books/bulk-delete', [BookController::class, 'bulkDestroy'])
        ->name('books.bulk-destroy');

    Route::delete('/books/{book_id}', [BookController::class, 'destroy'])
        ->name('books.destroy');

    // Penulis
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
    // Hapus penulis terpilih
    Route::delete('/authors/bulk/delete', [AuthorController::class, 'bulkDestroy'])
    ->name('authors.bulk-destroy');

    // Equipment
    Route::get('/equipment', [EquipmentController::class, 'index'])
        ->name('equipment.index');

    Route::get('/equipment/create', [EquipmentController::class, 'create'])
        ->name('equipment.create');

    Route::post('/equipment', [EquipmentController::class, 'store'])
        ->name('equipment.store');

    Route::get(
        '/equipment/{equipment_id}/edit',
        [EquipmentController::class, 'edit']
    )->name('equipment.edit');

    Route::put(
        '/equipment/{equipment_id}',
        [EquipmentController::class, 'update']
    )->name('equipment.update');

    Route::delete(
        '/equipment/{equipment_id}',
        [EquipmentController::class, 'destroy']
    )->name('equipment.destroy');

    // Location
    Route::get('/locations', [LocationController::class, 'index'])
        ->name('locations.index');

    Route::get('/locations/create', [LocationController::class, 'create'])
        ->name('locations.create');

    Route::post('/locations', [LocationController::class, 'store'])
        ->name('locations.store');

    Route::get(
        '/locations/{location}/edit',
        [LocationController::class, 'edit']
    )->name('locations.edit');

    Route::put(
        '/locations/{location}',
        [LocationController::class, 'update']
    )->name('locations.update');

    Route::delete(
        '/locations/{location}',
        [LocationController::class, 'destroy']
    )->name('locations.destroy');

    // Daftar peminjam
    Route::get('/borrowers', [BorrowerController::class, 'index'])
        ->name('borrowers.index');

    Route::post(
        '/borrowers/bulk/return',
        [BorrowerController::class, 'bulkReturn']
    )->name('borrowers.bulk-return');

    Route::post(
        '/borrowers/{loan_detail_id}/return',
        [BorrowerController::class, 'returnBook']
    )->name('borrowers.return');

    Route::delete(
        '/borrowers/bulk/delete',
        [BorrowerController::class, 'bulkDestroy']
    )->name('borrowers.bulk-destroy');

    Route::delete(
        '/borrowers/{loan_detail_id}',
        [BorrowerController::class, 'destroy']
    )->name('borrowers.destroy');

    // Daftar pengunjung
    Route::get('/visitors', function (Request $request) {
        $search = trim(
            $request->input('search', '')
        );

        $visitors = Visitor::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where(
                            'visitor_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'employee_number',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'visitor_category',
                            'like',
                            "%{$search}%"
                        );
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return view('pages.tables.Visitors.visitors', [
            'title' => 'Daftar Pengunjung',
            'visitors' => $visitors,
            'search' => $search,
        ]);
    })->name('visitors');

    Route::delete('/visitors/{visitor_id}', function ($visitor_id) {
        $visitor = Visitor::where(
            'visitor_id',
            $visitor_id
        )->first();

        if (!$visitor) {
            return redirect()
                ->route('visitors')
                ->with(
                    'error',
                    'Data pengunjung tidak ditemukan.'
                );
        }

        $visitor->delete();

        return redirect()
            ->route('visitors')
            ->with(
                'success',
                'Data pengunjung berhasil dihapus.'
            );
    })->name('visitors.destroy');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    })->name('logout');
});