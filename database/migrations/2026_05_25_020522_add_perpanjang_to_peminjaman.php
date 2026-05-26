<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{Schema, DB};

return new class extends Migration
{
    public function up(): void
    {
        // Update enum status tambah perpanjang_pending
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM(
            'pending','approved','rejected','borrowed','returned',
            'overdue','suspended','perpanjang_pending'
        ) NOT NULL DEFAULT 'pending'");

        Schema::table('peminjaman', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman', 'perpanjang_durasi'))
                $table->integer('perpanjang_durasi')->nullable()->after('late_days');
            if (!Schema::hasColumn('peminjaman', 'perpanjang_catatan'))
                $table->text('perpanjang_catatan')->nullable()->after('perpanjang_durasi');
            if (!Schema::hasColumn('peminjaman', 'perpanjang_prev_status'))
                $table->string('perpanjang_prev_status')->nullable()->after('perpanjang_catatan');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['perpanjang_durasi', 'perpanjang_catatan', 'perpanjang_prev_status']);
        });
    }
};
