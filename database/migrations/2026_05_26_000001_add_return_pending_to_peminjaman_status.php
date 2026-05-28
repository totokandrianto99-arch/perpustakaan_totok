<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah status return_pending pada enum status kolom peminjaman.
        // Ikuti enum terbaru yang sudah ada (lihat upgrade sistem sebelumnya):
        // 'pending','approved','rejected','borrowed','returned','overdue','suspended','perpanjang_pending'
        // Tambahkan 'return_pending' di akhir.
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM(
            'pending','approved','rejected','borrowed','returned',
            'overdue','suspended','perpanjang_pending','return_pending'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Kembalikan tanpa return_pending
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM(
            'pending','approved','rejected','borrowed','returned',
            'overdue','suspended','perpanjang_pending'
        ) NOT NULL DEFAULT 'pending'");
    }
};

