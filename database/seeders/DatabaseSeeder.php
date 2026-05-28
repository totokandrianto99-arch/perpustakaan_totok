<?php

namespace Database\Seeders;

use App\Models\{Setting, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default settings
        foreach (Setting::defaults() as $key => $s) {
            Setting::firstOrCreate(['key' => $key], ['value' => $s['value'], 'label' => $s['label']]);
        }

        // Admin default
        User::firstOrCreate(['username' => 'admin'], [
            'nama_lengkap' => 'Administrator',
            'email'        => 'admin@perpustakaan.com',
            'password'     => Hash::make('admin123'),
            'level'        => 'admin',
            'status'       => 'active',
            'can_borrow'   => false,
        ]);
    }
}
