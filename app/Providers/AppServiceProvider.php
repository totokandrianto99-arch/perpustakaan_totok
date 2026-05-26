<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\{User, Buku, Peminjaman};

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Custom route model binding untuk primary key non-standar
        Route::bind('user', fn($value) => User::where('id_user', $value)->firstOrFail());
        Route::bind('buku', fn($value) => Buku::where('id_buku', $value)->firstOrFail());
        Route::bind('peminjaman', fn($value) => Peminjaman::where('id_pinjam', $value)->firstOrFail());
    }
}
