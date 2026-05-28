<?php

namespace App\Http\Controllers;

use App\Models\{Buku, Peminjaman, User};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_buku'      => Buku::count(),
            'total_user'      => User::members()->count(),
            'dipinjam'        => Peminjaman::where('status','borrowed')->count(),
            'pending'         => Peminjaman::where('status','pending')->count(),
            'overdue'         => Peminjaman::where('status','overdue')->count(),
            'dikembalikan'    => Peminjaman::where('status','returned')->count(),
            'suspended_users' => User::suspended()->count(),
            'stok_habis'      => Buku::where('stok',0)->count(),
        ];

        // Chart: peminjaman per hari (7 hari terakhir)
        $labels = [];
        $data   = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $labels[] = $day->locale('id')->isoFormat('ddd, D MMM');
            $data[] = Peminjaman::whereDate('tanggal_pinjam', $day)->count();
        }

        // Buku paling dipinjam
        $bukuPopuler = Buku::orderByDesc('total_dipinjam')->take(5)->get();

        // User dengan pelanggaran tertinggi
        $userPelanggaran = User::members()->where('violation_points','>',0)
            ->orderByDesc('violation_points')->take(5)->get();

        // Overdue list
        $overdueList = Peminjaman::with(['buku','user'])
            ->where('status','overdue')
            ->orderByDesc('late_days')
            ->take(10)->get();

        // Return pending list (request pengembalian)
        $returnPendingList = Peminjaman::with(['buku','user'])
            ->where('status','return_pending')
            ->orderByDesc('updated_at')
            ->take(10)->get();

        // Kategori populer
        $kategoriPopuler = Buku::select('kategori', DB::raw('SUM(total_dipinjam) as total'))
            ->groupBy('kategori')->orderByDesc('total')->get();

        return view('dashboard', compact(
            'stats','labels','data','bukuPopuler',
            'userPelanggaran','overdueList','returnPendingList','kategoriPopuler'
        ));
    }
}
