<?php

namespace App\Http\Controllers;

use App\Models\{Buku, Peminjaman, User};

class LandingController extends Controller
{
    public function index()
    {
        return view('landing', [
            'totalBuku'    => Buku::count(),
            'totalMember'  => User::members()->count(),
            'totalPinjam'  => Peminjaman::where('status','returned')->count(),
            'bukuTerbaru'  => Buku::orderByDesc('total_dipinjam')->get(),
        ]);
    }
}
