<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VisitorAdminController;
use App\Http\Controllers\VisitorGuestController;
use App\Models\Book;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
        'email' => [
            'required',
            'email',
        ],
        'password' => [
            'required',
            'string',
        ],
    ]);

    if (!Auth::attempt($credentials)) {
        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

    $user = Auth::user();

    $user->loadMissing([
        'role',
        'visitor',
    ]);

    // Status akun
    if (!$user->is_active) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()
            ->withErrors([
                'email' => 'Akun Anda sudah tidak aktif.',
            ])
            ->onlyInput('email');
    }

    // Role
    if (!$user->role) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()
            ->withErrors([
                'email' => 'Role akun tidak ditemukan.',
            ])
            ->onlyInput('email');
    }

    $request->session()->regenerate();

    // Admin
    if ($user->role->role_name === 'admin') {
        return redirect()->route('dashboard');
    }

    // Pekerja
    if ($user->role->role_name === 'pekerja') {
        if (
            !$user->visitor
            || $user->visitor->visitor_category !== 'pekerja'
        ) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'Data pekerja tidak ditemukan.',
                ])
                ->onlyInput('email');
        }

        if (!$user->visitor->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'Data pengunjung Anda sudah dinonaktifkan. Silakan hubungi administrator.',
                ])
                ->onlyInput('email');
        }

        return redirect()->route('worker.dashboard');
    }

    // Role lain
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return back()
        ->withErrors([
            'email' => 'Akun Anda tidak memiliki akses ke sistem.',
        ])
        ->onlyInput('email');
})->name('login.process');

// Pendaftaran pengunjung
Route::get('/visitor-register', function () {
    return view('pages.landing-page.visitor-register');
})->name('visitors.register');

Route::post(
    '/visitors',
    [VisitorGuestController::class, 'store']
)->name('visitors.store');

Route::post(
    '/visitors/check-in',
    [VisitorGuestController::class, 'checkIn']
)->name('visitors.checkin');

// User login
Route::middleware('auth')->group(function () {

    // Foto profil pekerja
    Route::get('/worker/profile-photo', function () {
        $user = Auth::user();

        $user->loadMissing([
            'role',
            'visitor',
        ]);

        abort_unless(
            $user->role
            && $user->role->role_name === 'pekerja',
            403
        );

        abort_unless(
            $user->is_active
            && $user->visitor
            && $user->visitor->is_active,
            403
        );

        $profilePhoto = $user->visitor->profile_photo;

        abort_if(
            !$profilePhoto,
            404
        );

        abort_unless(
            Storage::disk('local')->exists($profilePhoto),
            404
        );

        return response()->file(
            Storage::disk('local')->path($profilePhoto),
            [
                'Cache-Control' => 'private, max-age=3600',
            ]
        );
    })->name('worker.profile-photo');

    // Dashboard pekerja
    Route::get('/worker', function () {
        $user = Auth::user();

        $user->loadMissing([
            'role',
            'visitor',
        ]);

        abort_unless(
            $user->role
            && $user->role->role_name === 'pekerja',
            403
        );

        if (
            !$user->is_active
            || !$user->visitor
            || !$user->visitor->is_active
        ) {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()
                ->route('landing')
                ->withErrors([
                    'email' => 'Akun Anda sudah tidak aktif.',
                ]);
        }

        $books = Book::where('status', 'public')
            ->with([
                'authors',
                'location',
                'copies',
            ])
            ->orderBy('title')
            ->get();

        return view(
            'pages.landing-page.worker-dashboard',
            [
                'user' => $user,
                'books' => $books,
            ]
        );
    })->name('worker.dashboard');

    // Form ganti password
    Route::get('/worker/change-password', function () {
        $user = Auth::user();

        $user->loadMissing([
            'role',
            'visitor',
        ]);

        abort_unless(
            $user->role
            && $user->role->role_name === 'pekerja',
            403
        );

        if (
            !$user->is_active
            || !$user->visitor
            || !$user->visitor->is_active
        ) {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()
                ->route('landing')
                ->withErrors([
                    'email' => 'Akun Anda sudah tidak aktif.',
                ]);
        }

        return view(
            'pages.landing-page.change-password',
            [
                'user' => $user,
            ]
        );
    })->name('worker.password.edit');

    // Simpan password
    Route::post('/worker/change-password', function (Request $request) {
        $user = Auth::user();

        $user->loadMissing([
            'role',
            'visitor',
        ]);

        abort_unless(
            $user->role
            && $user->role->role_name === 'pekerja',
            403
        );

        if (
            !$user->is_active
            || !$user->visitor
            || !$user->visitor->is_active
        ) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('landing')
                ->withErrors([
                    'email' => 'Akun Anda sudah tidak aktif.',
                ]);
        }

        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'current_password.required' =>
                'Password saat ini wajib diisi.',

            'password.required' =>
                'Password baru wajib diisi.',

            'password.min' =>
                'Password baru minimal 8 karakter.',

            'password.confirmed' =>
                'Konfirmasi password baru tidak sesuai.',
        ]);

        // Cek password saat ini
        if (!Hash::check(
            $validated['current_password'],
            $user->password_hash
        )) {
            return back()
                ->withErrors([
                    'current_password' =>
                        'Password saat ini tidak sesuai.',
                ]);
        }

        // Password baru tidak boleh sama
        if (Hash::check(
            $validated['password'],
            $user->password_hash
        )) {
            return back()
                ->withErrors([
                    'password' =>
                        'Password baru tidak boleh sama dengan password saat ini.',
                ]);
        }

        $user->password_hash = Hash::make(
            $validated['password']
        );

        $user->must_change_password = false;
        $user->save();

        $request->session()->regenerate();

        return redirect()
            ->route('worker.dashboard')
            ->with(
                'success',
                'Password berhasil diubah.'
            );
    })->name('worker.password.update');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    })->name('logout');
});

// Admin
Route::middleware('admin')->group(function () {

    // Dashboard
    Route::get(
        '/dashboard',
        [ReportController::class, 'index']
    )->name('dashboard');

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

        return view(
            'pages.tables.books.books-data',
            [
                'title' => 'Data Buku',
                'books' => $books,
            ]
        );
    })->name('data-buku');

    Route::get(
        '/books/create',
        [BookController::class, 'create']
    )->name('books.create');

    Route::post(
        '/books',
        [BookController::class, 'store']
    )->name('books.store');

    Route::get(
        '/books/check-book-id',
        [BookController::class, 'checkBookId']
    )->name('books.check-book-id');

    Route::get(
        '/books/check-book-no',
        [BookController::class, 'checkBookNo']
    )->name('books.check-book-no');

    Route::get(
        '/books-search',
        [BookController::class, 'search']
    )->name('books.search');

    Route::post(
        '/books/{book_id}/borrow',
        [BookController::class, 'borrow']
    )->name('books.borrow');

    Route::post(
        '/books/{book_id}/return/{loan_detail_id}',
        [BookController::class, 'returnBook']
    )->name('books.return');

    Route::get(
        '/books/{book_id}/edit',
        [BookController::class, 'edit']
    )->name('books.edit');

    Route::put(
        '/books/{book_id}',
        [BookController::class, 'update']
    )->name('books.update');

    Route::delete(
        '/books/bulk-delete',
        [BookController::class, 'bulkDestroy']
    )->name('books.bulk-destroy');

    Route::delete(
        '/books/{book_id}',
        [BookController::class, 'destroy']
    )->name('books.destroy');

    // Penulis
    Route::get(
        '/authors',
        [AuthorController::class, 'index']
    )->name('authors');

    Route::get(
        '/authors/create',
        [AuthorController::class, 'create']
    )->name('authors.create');

    Route::post(
        '/authors',
        [AuthorController::class, 'store']
    )->name('authors.store');

    Route::get(
        '/authors/{id}/edit',
        [AuthorController::class, 'edit']
    )->name('authors.edit');

    Route::put(
        '/authors/{id}',
        [AuthorController::class, 'update']
    )->name('authors.update');

    Route::delete(
        '/authors/bulk/delete',
        [AuthorController::class, 'bulkDestroy']
    )->name('authors.bulk-destroy');

    Route::delete(
        '/authors/{id}',
        [AuthorController::class, 'destroy']
    )->name('authors.destroy');

    // Equipment
    Route::get(
        '/equipment',
        [EquipmentController::class, 'index']
    )->name('equipment.index');

    Route::get(
        '/equipment/create',
        [EquipmentController::class, 'create']
    )->name('equipment.create');

    Route::post(
        '/equipment',
        [EquipmentController::class, 'store']
    )->name('equipment.store');

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

    // Lokasi
    Route::get(
        '/locations',
        [LocationController::class, 'index']
    )->name('locations.index');

    Route::get(
        '/locations/create',
        [LocationController::class, 'create']
    )->name('locations.create');

    Route::post(
        '/locations',
        [LocationController::class, 'store']
    )->name('locations.store');

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
    Route::get(
        '/borrowers',
        [BorrowerController::class, 'index']
    )->name('borrowers.index');

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
    Route::get(
        '/visitors',
        [VisitorAdminController::class, 'index']
    )->name('visitors');

    Route::get(
        '/visitors/checkins/{checkin}/selfie',
        [VisitorAdminController::class, 'selfie']
    )
        ->name('visitors.selfie')
        ->whereNumber('checkin');

    Route::get(
        '/visitors/{visitor}/details',
        [VisitorAdminController::class, 'detail']
    )
        ->name('visitors.details')
        ->whereNumber('visitor');

    Route::get(
        '/visitors/{visitor}/profile-photo',
        [VisitorAdminController::class, 'profilePhoto']
    )
        ->name('visitors.profile-photo')
        ->whereNumber('visitor');

    Route::patch(
        '/visitors/{visitor}/status',
        [VisitorAdminController::class, 'toggleStatus']
    )
        ->name('visitors.status')
        ->whereNumber('visitor');

    Route::delete(
        '/visitors/{visitor}',
        [VisitorAdminController::class, 'destroy']
    )
        ->name('visitors.destroy')
        ->whereNumber('visitor');
});