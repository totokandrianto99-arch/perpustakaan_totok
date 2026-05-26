<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['key','value','label'];

    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $s = self::where('key', $key)->first();
            return $s ? $s->value : $default;
        });
    }

    public static function set(string $key, $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
    }

    public static function defaults(): array
    {
        return [
            'durasi_pinjam_default' => ['value' => '7',    'label' => 'Durasi Pinjam Default (hari)'],
            'durasi_pinjam_max'     => ['value' => '30',   'label' => 'Durasi Pinjam Maksimal (hari)'],
            'denda_aktif'           => ['value' => '0',    'label' => 'Aktifkan Sistem Denda'],
            'denda_per_hari'        => ['value' => '1000', 'label' => 'Denda Per Hari (Rp)'],
            'max_pinjam_aktif'      => ['value' => '3',    'label' => 'Maks Buku Dipinjam Sekaligus'],
            'nama_perpustakaan'     => ['value' => 'Perpustakaan', 'label' => 'Nama Perpustakaan'],
        ];
    }
}
