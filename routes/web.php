<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, DashboardController, BukuController,
    PeminjamanController, MemberController, LandingController,
    UserController, SettingController, WishlistController
};

// Landing
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// ── Admin / Petugas ──
Route::middleware(['auth','admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Buku
    Route::resource('buku', BukuController::class)->except(['show']);
    Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');

    // Peminjaman
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/',                              [PeminjamanController::class, 'index'])->name('index');
        Route::get('/create',                        [PeminjamanController::class, 'create'])->name('create');
        Route::post('/',                             [PeminjamanController::class, 'store'])->name('store');
        Route::patch('/{peminjaman}/approve',           [PeminjamanController::class, 'approve'])->name('approve');
        Route::patch('/{peminjaman}/reject',             [PeminjamanController::class, 'reject'])->name('reject');
        Route::patch('/{peminjaman}/kembalikan',         [PeminjamanController::class, 'kembalikan'])->name('kembalikan');
        Route::patch('/{peminjaman}/perpanjang',         [PeminjamanController::class, 'perpanjang'])->name('perpanjang');
        Route::patch('/{peminjaman}/approve-perpanjang', [PeminjamanController::class, 'approvePerpanjang'])->name('approvePerpanjang');
        Route::patch('/{peminjaman}/reject-perpanjang',  [PeminjamanController::class, 'rejectPerpanjang'])->name('rejectPerpanjang');
        Route::delete('/{peminjaman}',                   [PeminjamanController::class, 'destroy'])->name('destroy');
    });

    // User management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',                          [UserController::class, 'index'])->name('index');
        Route::get('/create',                    [UserController::class, 'create'])->name('create');
        Route::post('/',                         [UserController::class, 'store'])->name('store');
        Route::get('/{user}',                    [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit',               [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}',                    [UserController::class, 'update'])->name('update');
        Route::delete('/{user}',                 [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/suspend',          [UserController::class, 'suspend'])->name('suspend');
        Route::patch('/{user}/activate',         [UserController::class, 'activate'])->name('activate');
        Route::patch('/{user}/toggle-borrow',    [UserController::class, 'toggleBorrow'])->name('toggleBorrow');
        Route::patch('/{user}/reset-violation',  [UserController::class, 'resetViolation'])->name('resetViolation');
    });

    // Settings
    Route::get('/settings',  [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// ── Member ──
Route::middleware(['auth','member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard',                    [MemberController::class, 'dashboard'])->name('dashboard');
    Route::get('/katalog',                      [MemberController::class, 'katalog'])->name('katalog');
    Route::get('/katalog/{buku}',               [MemberController::class, 'detailBuku'])->name('detail_buku');
    Route::get('/pinjam/{buku}',                [MemberController::class, 'pinjam'])->name('pinjam');
    Route::post('/pinjam/{buku}',               [MemberController::class, 'storePinjam'])->name('pinjam.store');
    Route::get('/riwayat',                      [MemberController::class, 'riwayat'])->name('riwayat');
    Route::patch('/kembalikan/{peminjaman}',       [MemberController::class, 'kembalikan'])->name('kembalikan');
    Route::post('/perpanjang/{peminjaman}',         [MemberController::class, 'requestPerpanjang'])->name('perpanjang.request');
    Route::post('/notif/read',                      [MemberController::class, 'markNotifRead'])->name('notif.read');
    // Wishlist
    Route::get('/wishlist',                         [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/{buku}/toggle',          [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{buku}',               [WishlistController::class, 'remove'])->name('wishlist.remove');
});
