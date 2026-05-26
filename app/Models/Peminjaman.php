<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_pinjam';

    protected $fillable = [
        'id_user','nama_peminjam','tanggal_pinjam','id_buku','jumlah_pinjam',
        'status','due_date','return_date','durasi_pinjam','late_days','penalty',
        'catatan_admin','catatan_user','approved_by','approved_at',
        'perpanjang_durasi','perpanjang_catatan','perpanjang_prev_status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'due_date'       => 'date',
        'return_date'    => 'date',
        'approved_at'    => 'datetime',
    ];

    const STATUS_LABELS = [
        'pending'            => ['label' => 'Menunggu Approval',   'color' => 'warning'],
        'approved'           => ['label' => 'Disetujui',           'color' => 'info'],
        'rejected'           => ['label' => 'Ditolak',             'color' => 'danger'],
        'borrowed'           => ['label' => 'Dipinjam',            'color' => 'primary'],
        'returned'           => ['label' => 'Dikembalikan',        'color' => 'success'],
        'overdue'            => ['label' => 'Terlambat',           'color' => 'danger'],
        'suspended'          => ['label' => 'Ditangguhkan',        'color' => 'secondary'],
        'perpanjang_pending' => ['label' => 'Req. Perpanjang',     'color' => 'warning'],
    ];

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status]['label'] ?? $this->status;
    }

    public function statusColor(): string
    {
        return self::STATUS_LABELS[$this->status]['color'] ?? 'secondary';
    }

    public function isOverdue(): bool
    {
        return $this->due_date && !$this->return_date && Carbon::today()->gt($this->due_date);
    }

    public function calculateLateDays(): int
    {
        if (!$this->due_date || $this->return_date) return 0;
        return max(0, Carbon::today()->diffInDays($this->due_date, false) * -1);
    }

    public function calculatePenalty(): int
    {
        $rate = (int) (Setting::get('denda_per_hari') ?? 1000);
        return $this->late_days * $rate;
    }

    // ── Relationships ──
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id_user');
    }
}
