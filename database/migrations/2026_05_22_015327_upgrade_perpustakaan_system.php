<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{Schema, DB};

return new class extends Migration
{
    public function up(): void
    {
        // ── USER upgrades ──
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user','status'))             $table->enum('status',['active','suspended','banned'])->default('active')->after('level');
            if (!Schema::hasColumn('user','violation_points'))   $table->integer('violation_points')->default(0)->after('level');
            if (!Schema::hasColumn('user','need_admin_approval'))$table->boolean('need_admin_approval')->default(false)->after('level');
            if (!Schema::hasColumn('user','can_borrow'))         $table->boolean('can_borrow')->default(true)->after('level');
            if (!Schema::hasColumn('user','phone'))              $table->string('phone')->nullable();
            if (!Schema::hasColumn('user','address'))            $table->text('address')->nullable();
            if (!Schema::hasColumn('user','avatar'))             $table->string('avatar')->nullable();
            if (!Schema::hasColumn('user','suspended_at'))       $table->timestamp('suspended_at')->nullable();
            if (!Schema::hasColumn('user','suspend_reason'))     $table->text('suspend_reason')->nullable();
        });

        // ── BUKU upgrades ──
        Schema::table('buku', function (Blueprint $table) {
            if (!Schema::hasColumn('buku','penerbit'))       $table->string('penerbit')->nullable();
            if (!Schema::hasColumn('buku','kategori'))       $table->enum('kategori',['Novel','Pendidikan','Referensi','Komik','Ensiklopedia','Majalah','Lainnya'])->default('Lainnya');
            if (!Schema::hasColumn('buku','sinopsis'))       $table->text('sinopsis')->nullable();
            if (!Schema::hasColumn('buku','lokasi_rak'))     $table->string('lokasi_rak')->nullable();
            if (!Schema::hasColumn('buku','total_dipinjam')) $table->integer('total_dipinjam')->default(0);
            if (!Schema::hasColumn('buku','status'))         $table->enum('status',['tersedia','tidak_tersedia'])->default('tersedia');
        });

        // ── PEMINJAMAN: expand enum dulu, lalu update data lama ──
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('dipinjam','dikembalikan','pending','approved','rejected','borrowed','returned','overdue','suspended') NOT NULL DEFAULT 'pending'");
        DB::statement("UPDATE peminjaman SET status = 'borrowed' WHERE status = 'dipinjam'");
        DB::statement("UPDATE peminjaman SET status = 'returned' WHERE status = 'dikembalikan'");
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending','approved','rejected','borrowed','returned','overdue','suspended') NOT NULL DEFAULT 'pending'");

        Schema::table('peminjaman', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman','due_date'))      $table->date('due_date')->nullable();
            if (!Schema::hasColumn('peminjaman','return_date'))   $table->date('return_date')->nullable();
            if (!Schema::hasColumn('peminjaman','durasi_pinjam')) $table->integer('durasi_pinjam')->default(7);
            if (!Schema::hasColumn('peminjaman','late_days'))     $table->integer('late_days')->default(0);
            if (!Schema::hasColumn('peminjaman','penalty'))       $table->integer('penalty')->default(0);
            if (!Schema::hasColumn('peminjaman','catatan_admin')) $table->text('catatan_admin')->nullable();
            if (!Schema::hasColumn('peminjaman','catatan_user'))  $table->text('catatan_user')->nullable();
            if (!Schema::hasColumn('peminjaman','approved_by'))   $table->unsignedBigInteger('approved_by')->nullable();
            if (!Schema::hasColumn('peminjaman','approved_at'))   $table->timestamp('approved_at')->nullable();
        });

        // ── NOTIFIKASI ──
        if (!Schema::hasTable('notifikasi')) {
            Schema::create('notifikasi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_user');
                $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
                $table->string('judul');
                $table->text('pesan');
                $table->enum('tipe',['info','warning','danger','success'])->default('info');
                $table->boolean('dibaca')->default(false);
                $table->string('url')->nullable();
                $table->timestamps();
            });
        }

        // ── SETTINGS ──
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('label')->nullable();
                $table->timestamps();
            });
        }

        // ── ACTIVITY LOG ──
        if (!Schema::hasTable('activity_log')) {
            Schema::create('activity_log', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_user')->nullable();
                $table->string('action');
                $table->string('model')->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->text('description')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('notifikasi');
    }
};
