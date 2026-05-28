<?php

namespace App\Console\Commands;

use App\Models\{Peminjaman, Notifikasi, User};
use Carbon\Carbon;
use Illuminate\Console\Command;

class DetectOverdue extends Command
{
    protected $signature   = 'perpustakaan:detect-overdue';
    protected $description = 'Deteksi peminjaman overdue dan kirim notifikasi reminder';

    public function handle(): void
    {
        $today = Carbon::today();

        // ── 1. Tandai overdue ──
        $overdue = Peminjaman::whereIn('status', ['borrowed'])
            ->whereNotNull('due_date')
            ->whereNull('return_date')
            ->where('due_date', '<', $today)
            ->get();

        foreach ($overdue as $p) {
            $lateDays = (int) $today->diffInDays($p->due_date);
            $p->update(['status' => 'overdue', 'late_days' => $lateDays]);

            // Cek apakah notif overdue sudah dikirim hari ini
            $alreadyNotified = \App\Models\Notifikasi::where('id_user', $p->id_user)
                ->where('tipe', 'danger')
                ->whereDate('created_at', $today)
                ->where('pesan', 'like', "%{$p->buku->judul}%")
                ->exists();

            if (!$alreadyNotified) {
                Notifikasi::create([
                    'id_user' => $p->id_user,
                    'judul'   => 'Peminjaman Terlambat!',
                    'pesan'   => "Buku \"{$p->buku->judul}\" terlambat {$lateDays} hari. Segera kembalikan.",
                    'tipe'    => 'danger',
                    'url'     => route('member.riwayat'),
                ]);
            }

            if ($p->user) $p->user->recalculateViolations();
        }

        // ── 2. Update late_days yang sudah overdue ──
        Peminjaman::where('status', 'overdue')
            ->whereNull('return_date')
            ->each(function ($p) use ($today) {
                $lateDays = (int) $today->diffInDays($p->due_date);
                $p->update(['late_days' => $lateDays]);
                if ($p->user) $p->user->recalculateViolations();
            });

        // ── 3. Reminder H-2 ──
        $reminder2 = Peminjaman::where('status', 'borrowed')
            ->whereDate('due_date', $today->copy()->addDays(2))
            ->get();

        foreach ($reminder2 as $p) {
            Notifikasi::create([
                'id_user' => $p->id_user,
                'judul'   => 'Pengingat Pengembalian',
                'pesan'   => "Buku \"{$p->buku->judul}\" harus dikembalikan dalam 2 hari ({$p->due_date->format('d M Y')}).",
                'tipe'    => 'warning',
                'url'     => route('member.riwayat'),
            ]);
        }

        // ── 4. Reminder hari H ──
        $reminderH = Peminjaman::where('status', 'borrowed')
            ->whereDate('due_date', $today)
            ->get();

        foreach ($reminderH as $p) {
            Notifikasi::create([
                'id_user' => $p->id_user,
                'judul'   => 'Batas Pengembalian Hari Ini',
                'pesan'   => "Buku \"{$p->buku->judul}\" harus dikembalikan hari ini!",
                'tipe'    => 'warning',
                'url'     => route('member.riwayat'),
            ]);
        }

        $this->info("Overdue: {$overdue->count()} | Reminder H-2: {$reminder2->count()} | Reminder H: {$reminderH->count()}");
    }
}
