<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $fillable = ['id_user','judul','pesan','tipe','dibaca','url'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public static function kirim(int $userId, string $judul, string $pesan, string $tipe = 'info', ?string $url = null): self
    {
        return self::create([
            'id_user' => $userId,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'tipe'    => $tipe,
            'url'     => $url,
        ]);
    }
}
