<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username','nama_lengkap','email','password','plain_password','level',
        'status','violation_points','need_admin_approval','can_borrow',
        'phone','address','avatar','suspended_at','suspend_reason',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'need_admin_approval' => 'boolean',
        'can_borrow'          => 'boolean',
        'suspended_at'        => 'datetime',
    ];

    public function getAuthIdentifierName() { return 'id_user'; }
    public function getAuthPassword()       { return $this->password; }
    public function getAuthIdentifier()     { return $this->id_user; }

    // ── Roles ──
    public function isAdmin()   { return in_array($this->level, ['admin', 'petugas']); }
    public function isMember()  { return $this->level === 'member'; }
    public function isSuspended() { return $this->status === 'suspended'; }
    public function isActive()    { return $this->status === 'active'; }

    // ── Violation level ──
    public function violationLevel(): string
    {
        $pts = $this->violation_points;
        if ($pts >= 5) return 'suspended';
        if ($pts >= 3) return 'warning';
        return 'normal';
    }

    public function canBorrowNow(): bool
    {
        if (!$this->can_borrow || $this->isSuspended()) return false;
        // Level 1: ada overdue = tidak bisa pinjam
        if ($this->peminjaman()->where('status', 'overdue')->exists()) return false;
        return true;
    }

    // ── Recalculate violation points & status ──
    public function recalculateViolations(): void
    {
        $maxLate = $this->peminjaman()
            ->where('status', 'overdue')
            ->max('late_days') ?? 0;

        // Hitung total poin dari semua peminjaman overdue
        $totalPoints = 0;
        $this->peminjaman()->where('status', 'overdue')->each(function ($p) use (&$totalPoints) {
            if ($p->late_days > 14)       $totalPoints += 3;
            elseif ($p->late_days >= 8)   $totalPoints += 2;
            elseif ($p->late_days >= 1)   $totalPoints += 1;
        });

        $this->violation_points    = $totalPoints;
        $this->need_admin_approval = $totalPoints >= 3;
        $this->can_borrow          = $maxLate === 0;

        if ($totalPoints >= 5 && $this->status !== 'suspended') {
            $this->status       = 'suspended';
            $this->suspended_at = now();
        }

        $this->save();
    }

    // ── Relationships ──
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_user', 'id_user');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'id_user', 'id_user');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_user', 'id_user');
    }

    public function unreadNotifikasi()
    {
        return $this->notifikasi()->where('dibaca', false);
    }

    // ── Scopes ──
    public function scopeMembers(Builder $q)   { return $q->where('level', 'member'); }
    public function scopeSuspended(Builder $q) { return $q->where('status', 'suspended'); }
    public function scopeActive(Builder $q)    { return $q->where('status', 'active'); }
}
