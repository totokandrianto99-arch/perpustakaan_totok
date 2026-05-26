<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Buku extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'id_buku';

    protected $fillable = [
        'judul','pengarang','penerbit','tahun_terbit','kategori',
        'sinopsis','lokasi_rak','stok','cover','total_dipinjam','status',
    ];

    const KATEGORI = ['Novel','Pendidikan','Referensi','Komik','Ensiklopedia','Majalah','Lainnya'];

    public function isAvailable(): bool
    {
        return $this->stok > 0 && $this->status === 'tersedia';
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku', 'id_buku');
    }

    public function activePeminjaman()
    {
        return $this->peminjaman()->whereIn('status', ['borrowed','overdue']);
    }

    // ── Scopes ──
    public function scopeAvailable(Builder $q)  { return $q->where('stok', '>', 0)->where('status', 'tersedia'); }
    public function scopeByKategori(Builder $q, $kat) { return $q->where('kategori', $kat); }
    public function scopeSearch(Builder $q, $s) {
        return $q->where('judul', 'like', "%$s%")
                 ->orWhere('pengarang', 'like', "%$s%")
                 ->orWhere('penerbit', 'like', "%$s%");
    }
}
