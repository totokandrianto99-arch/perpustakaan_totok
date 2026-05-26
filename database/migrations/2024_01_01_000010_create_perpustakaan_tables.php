<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('username')->unique();
            $table->string('nama_lengkap');
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->enum('level', ['admin', 'petugas', 'member'])->default('member');
            $table->timestamps();
        });

        Schema::create('buku', function (Blueprint $table) {
            $table->id('id_buku');
            $table->string('judul');
            $table->string('pengarang');
            $table->year('tahun_terbit');
            $table->integer('stok')->default(0);
            $table->timestamps();
        });

        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('id_pinjam');
            $table->foreignId('id_user')->nullable()->constrained('user', 'id_user')->onDelete('set null');
            $table->string('nama_peminjam');
            $table->date('tanggal_pinjam');
            $table->foreignId('id_buku')->constrained('buku', 'id_buku')->onDelete('cascade');
            $table->integer('jumlah_pinjam');
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
        Schema::dropIfExists('buku');
        Schema::dropIfExists('user');
    }
};
