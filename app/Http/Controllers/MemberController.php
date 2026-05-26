<?php

namespace App\Http\Controllers;

use App\Models\{Buku, Peminjaman, Notifikasi, Setting, Wishlist};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MemberController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // ── Deteksi overdue otomatis ──
        $user->peminjaman()
            ->where('status', 'borrowed')
            ->whereNotNull('due_date')
            ->whereNull('return_date')
            ->where('due_date', '<', now()->toDateString())
            ->each(function ($p) {
                $lateDays = now()->diffInDays($p->due_date);
                $p->update(['status' => 'overdue', 'late_days' => $lateDays]);
            });

        // ── Update late_days yang sudah overdue ──
        $user->peminjaman()
            ->where('status', 'overdue')
            ->whereNull('return_date')
            ->each(function ($p) {
                $lateDays = now()->diffInDays($p->due_date);
                $p->update(['late_days' => $lateDays]);
            });

        // ── Recalculate violations ──
        $user->recalculateViolations();
        $user->refresh();

        $user->load('peminjaman.buku');

        $stats = [
            'aktif'    => $user->peminjaman->whereIn('status',['borrowed','overdue'])->count(),
            'pending'  => $user->peminjaman->where('status','pending')->count(),
            'overdue'  => $user->peminjaman->where('status','overdue')->count(),
            'total'    => $user->peminjaman->count(),
        ];

        $peminjamanAktif = $user->peminjaman()
            ->with('buku')
            ->whereIn('status',['borrowed','overdue','pending'])
            ->latest()->get();

        $notifikasi = $user->notifikasi()->latest()->take(5)->get();

        return view('member.dashboard', compact('stats','peminjamanAktif','notifikasi'));
    }

    public function katalog(Request $request)
    {
        $q = Buku::query();

        if ($s = $request->search)      $q->search($s);
        if ($k = $request->kategori)    $q->byKategori($k);
        if ($request->tersedia === '1') $q->available();

        $sort = $request->sort ?? 'latest';
        match($sort) {
            'az'      => $q->orderBy('judul'),
            'za'      => $q->orderByDesc('judul'),
            'populer' => $q->orderByDesc('total_dipinjam'),
            default   => $q->latest(),
        };

        $bukuList  = $q->paginate(12)->withQueryString();
        $kategori  = Buku::KATEGORI;
        $wishlistIds = Auth::user()->wishlists()->pluck('id_buku');

        return view('member.katalog', compact('bukuList','kategori','wishlistIds'));
    }

    public function detailBuku(Buku $buku)
    {
        return view('member.detail_buku', compact('buku'));
    }

    public function pinjam(Buku $buku)
    {
        $user = Auth::user();

        if (!$user->canBorrowNow()) {
            return back()->with('error', 'Anda tidak dapat meminjam buku saat ini.');
        }

        $defaultDurasi = Setting::get('durasi_pinjam_default', 7);
        $maxDurasi     = Setting::get('durasi_pinjam_max', 30);

        return view('member.pinjam', compact('buku','defaultDurasi','maxDurasi'));
    }

    public function storePinjam(Request $request, Buku $buku)
    {
        $user      = Auth::user();
        $maxDurasi = (int) Setting::get('durasi_pinjam_max', 30);
        $maxPinjam = (int) Setting::get('max_pinjam_aktif', 3);

        if (!$user->canBorrowNow()) {
            return back()->with('error', 'Anda tidak dapat meminjam buku saat ini.');
        }

        // Cek batas maks pinjam aktif
        $aktif = $user->peminjaman()->whereIn('status', ['borrowed','overdue','pending'])->count();
        if ($aktif >= $maxPinjam) {
            return back()->with('error', "Anda sudah memiliki {$aktif} peminjaman aktif. Maksimal {$maxPinjam}.");
        }

        if (!$buku->isAvailable()) {
            return back()->with('error', 'Buku tidak tersedia.');
        }

        $data = $request->validate([
            'jumlah_pinjam' => 'required|integer|min:1|max:' . $buku->stok,
            'durasi_pinjam' => "required|integer|min:1|max:{$maxDurasi}",
            'catatan_user'  => 'nullable|string|max:500',
        ]);

        Peminjaman::create([
            'id_user'       => $user->id_user,
            'nama_peminjam' => $user->nama_lengkap,
            'id_buku'       => $buku->id_buku,
            'jumlah_pinjam' => $data['jumlah_pinjam'],
            'durasi_pinjam' => $data['durasi_pinjam'],
            'tanggal_pinjam'=> Carbon::today(),
            'status'        => 'pending',
            'catatan_user'  => $data['catatan_user'] ?? null,
        ]);

        return redirect()->route('member.riwayat')->with('success', 'Request peminjaman berhasil dikirim! Menunggu persetujuan admin.');
    }

    public function riwayat()
    {
        $peminjaman = Auth::user()->peminjaman()
            ->with('buku')
            ->latest()
            ->paginate(10);

        return view('member.riwayat', compact('peminjaman'));
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->id_user !== Auth::id()) abort(403);
        if (!in_array($peminjaman->status, ['borrowed','overdue'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        $lateDays = $peminjaman->calculateLateDays();
        $peminjaman->update([
            'status'      => 'returned',
            'return_date' => Carbon::today(),
            'late_days'   => $lateDays,
            'penalty'     => $lateDays > 0 ? $peminjaman->calculatePenalty() : 0,
        ]);

        $peminjaman->buku->increment('stok', $peminjaman->jumlah_pinjam);
        $peminjaman->buku->update(['status' => 'tersedia']);
        Auth::user()->recalculateViolations();

        return back()->with('success', 'Buku berhasil dikembalikan!');
    }

    public function requestPerpanjang(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->id_user !== Auth::user()->id_user) abort(403);

        if (!in_array($peminjaman->status, ['borrowed', 'overdue'])) {
            return back()->with('error', 'Peminjaman tidak dapat diperpanjang.');
        }

        if ($peminjaman->status === 'perpanjang_pending') {
            return back()->with('error', 'Sudah ada request perpanjangan yang menunggu.');
        }

        $maxDurasi = (int) \App\Models\Setting::get('durasi_pinjam_max', 30);

        $data = $request->validate([
            'durasi_tambah' => "required|integer|min:1|max:14",
            'catatan_user'  => 'nullable|string|max:300',
        ]);

        $prevStatus = $peminjaman->status;
        $peminjaman->update([
            'status'              => 'perpanjang_pending',
            'perpanjang_durasi'   => $data['durasi_tambah'],
            'perpanjang_catatan'  => $data['catatan_user'] ?? null,
            'perpanjang_prev_status' => $prevStatus,
        ]);

        // Notif admin — kirim ke semua admin/petugas
        \App\Models\User::where('level', 'admin')->orWhere('level', 'petugas')
            ->each(fn($admin) => \App\Models\Notifikasi::create([
                'id_user' => $admin->id_user,
                'judul'   => 'Request Perpanjangan',
                'pesan'   => "{$peminjaman->nama_peminjam} meminta perpanjangan {$data['durasi_tambah']} hari untuk buku \"{$peminjaman->buku->judul}\".",
                'tipe'    => 'warning',
                'url'     => route('peminjaman.index') . '?status=perpanjang_pending',
            ]));

        return back()->with('success', 'Request perpanjangan berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function markNotifRead(Request $request)
    {
        Auth::user()->notifikasi()->where('dibaca', false)->update(['dibaca' => true]);
        return response()->json(['ok' => true]);
    }
}
