<?php

namespace App\Http\Controllers;

use App\Models\{Peminjaman, Buku, User, Notifikasi, Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $q = Peminjaman::with(['buku','user'])->latest();

        if ($s = $request->search) {
            $q->where(function($query) use ($s) {
                $query->where('nama_peminjam','like',"%$s%")
                      ->orWhereHas('buku', fn($b) => $b->where('judul','like',"%$s%"));
            });
        }

        if ($status = $request->status) $q->where('status', $status);

        $peminjaman = $q->paginate(15)->withQueryString();
        $stats = [
            'pending'            => Peminjaman::where('status','pending')->count(),
            'borrowed'           => Peminjaman::where('status','borrowed')->count(),
            'overdue'            => Peminjaman::where('status','overdue')->count(),
            'returned'           => Peminjaman::where('status','returned')->count(),
            'perpanjang_pending' => Peminjaman::where('status','perpanjang_pending')->count(),
        ];

        return view('peminjaman.index', compact('peminjaman','stats'));
    }

    public function create()
    {
        $bukuList    = Buku::available()->orderBy('judul')->get();
        $userList    = User::members()->active()->orderBy('nama_lengkap')->get();
        $defaultDurasi = Setting::get('durasi_pinjam_default', 7);
        $maxDurasi     = Setting::get('durasi_pinjam_max', 30);
        $maxPinjamAktif = (int) Setting::get('max_pinjam_aktif', 2);

        // Hitung buku aktif per user untuk warning di form
        $pinjamAktifPerUser = Peminjaman::whereIn('status', ['borrowed','overdue','pending'])
            ->selectRaw('id_user, COUNT(*) as total')
            ->groupBy('id_user')
            ->pluck('total', 'id_user');

        return view('peminjaman.form', compact(
            'bukuList','userList','defaultDurasi','maxDurasi','maxPinjamAktif','pinjamAktifPerUser'
        ));
    }

    public function store(Request $request)
    {
        $maxDurasi = (int) Setting::get('durasi_pinjam_max', 30);

        $data = $request->validate([
            'id_user'       => 'required|exists:user,id_user',
            'id_buku'       => 'required|exists:buku,id_buku',
            'jumlah_pinjam' => 'required|integer|min:1',
            'durasi_pinjam' => "required|integer|min:1|max:{$maxDurasi}",
            'catatan_user'  => 'nullable|string|max:500',
        ]);

        $user = User::findOrFail($data['id_user']);
        $buku = Buku::findOrFail($data['id_buku']);

        if (!$user->canBorrowNow()) {
            return back()->withErrors(['id_user' => 'User tidak dapat meminjam saat ini (ada keterlambatan atau akun disuspend).']);
        }

        $maxPinjamAktif = (int) Setting::get('max_pinjam_aktif', 2);
        $pinjamAktif = Peminjaman::where('id_user', $user->id_user)
            ->whereIn('status', ['borrowed','overdue','pending'])
            ->count();
        if ($pinjamAktif >= $maxPinjamAktif) {
            return back()->withErrors(['id_user' => "User sudah memiliki {$pinjamAktif} peminjaman aktif. Harap kembalikan buku terlebih dahulu (maks {$maxPinjamAktif} aktif)."]);
        }

        if ($buku->stok < $data['jumlah_pinjam']) {
            return back()->withErrors(['id_buku' => 'Stok buku tidak mencukupi.']);
        }

        $peminjaman = Peminjaman::create([
            'id_user'       => $user->id_user,
            'nama_peminjam' => $user->nama_lengkap,
            'id_buku'       => $buku->id_buku,
            'jumlah_pinjam' => $data['jumlah_pinjam'],
            'durasi_pinjam' => $data['durasi_pinjam'],
            'tanggal_pinjam'=> Carbon::today(),
            'status'        => 'pending',
            'catatan_user'  => $data['catatan_user'] ?? null,
        ]);

        $this->log('create_peminjaman', 'Peminjaman', $peminjaman->id_pinjam, "Request pinjam buku: {$buku->judul}");

        return redirect()->route('peminjaman.index')->with('success', 'Request peminjaman berhasil dibuat!');
    }

    public function approve(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['catatan_admin' => 'nullable|string|max:500']);

        if ($peminjaman->status !== 'pending') {
            return back()->withErrors(['msg' => 'Status tidak valid untuk disetujui.']);
        }

        $buku = $peminjaman->buku;
        if ($buku->stok < $peminjaman->jumlah_pinjam) {
            return back()->withErrors(['msg' => 'Stok tidak mencukupi.']);
        }

        $buku->decrement('stok', $peminjaman->jumlah_pinjam);
        $buku->increment('total_dipinjam', $peminjaman->jumlah_pinjam);
        if ($buku->stok === 0) $buku->update(['status' => 'tidak_tersedia']);

        $peminjaman->update([
            'status'        => 'borrowed',
            'tanggal_pinjam'=> Carbon::today(),
            'due_date'      => Carbon::today()->addDays($peminjaman->durasi_pinjam),
            'approved_by'   => Auth::user()->id_user,
            'approved_at'   => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        Notifikasi::create([
            'id_user' => $peminjaman->id_user,
            'judul'   => 'Peminjaman Disetujui',
            'pesan'   => "Peminjaman buku \"{$buku->judul}\" telah disetujui. Harap dikembalikan sebelum {$peminjaman->due_date->format('d M Y')}.",
            'tipe'    => 'success',
            'url'     => route('member.riwayat'),
        ]);

        $this->log('approve_peminjaman', 'Peminjaman', $peminjaman->id_pinjam, "Approve pinjam: {$buku->judul}");

        return back()->with('success', 'Peminjaman disetujui!');
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['catatan_admin' => 'required|string|max:500']);

        $peminjaman->update([
            'status'        => 'rejected',
            'catatan_admin' => $request->catatan_admin,
            'approved_by'   => Auth::user()->id_user,
            'approved_at'   => now(),
        ]);

        Notifikasi::create([
            'id_user' => $peminjaman->id_user,
            'judul'   => 'Peminjaman Ditolak',
            'pesan'   => "Peminjaman buku \"{$peminjaman->buku->judul}\" ditolak. Alasan: {$request->catatan_admin}",
            'tipe'    => 'danger',
        ]);

        $this->log('reject_peminjaman', 'Peminjaman', $peminjaman->id_pinjam, "Reject pinjam: {$peminjaman->buku->judul}");

        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function kembalikan(Request $request, Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status, ['borrowed','overdue'])) {
            return back()->withErrors(['msg' => 'Status tidak valid.']);
        }

        $lateDays = $peminjaman->calculateLateDays();
        $penalty  = $lateDays > 0 ? $peminjaman->calculatePenalty() : 0;

        $peminjaman->update([
            'status'      => 'returned',
            'return_date' => Carbon::today(),
            'late_days'   => $lateDays,
            'penalty'     => $penalty,
        ]);

        $buku = $peminjaman->buku;
        $buku->increment('stok', $peminjaman->jumlah_pinjam);
        $buku->update(['status' => 'tersedia']);

        if ($peminjaman->user) {
            $peminjaman->user->recalculateViolations();
        }

        $this->log('kembalikan', 'Peminjaman', $peminjaman->id_pinjam, "Kembalikan: {$buku->judul}");

        return back()->with('success', 'Buku berhasil dikembalikan!' . ($lateDays > 0 ? " Terlambat {$lateDays} hari." : ''));
    }

    public function konfirmasiKembalikan(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['catatan_admin' => 'nullable|string|max:500']);

        if ($peminjaman->status !== 'return_pending') {
            return back()->withErrors(['msg' => 'Status tidak valid untuk konfirmasi pengembalian.']);
        }

        $lateDays = $peminjaman->calculateLateDays();
        $penalty  = $lateDays > 0 ? $peminjaman->calculatePenalty() : 0;

        $peminjaman->update([
            'status'      => 'returned',
            'return_date' => Carbon::today(),
            'late_days'   => $lateDays,
            'penalty'     => $penalty,
            'catatan_admin' => $request->catatan_admin,
            'approved_by' => Auth::user()->id_user,
            'approved_at' => now(),
        ]);

        $buku = $peminjaman->buku;
        $buku->increment('stok', $peminjaman->jumlah_pinjam);
        $buku->update(['status' => 'tersedia']);

        if ($peminjaman->user) {
            $peminjaman->user->recalculateViolations();
        }

        Notifikasi::create([
            'id_user' => $peminjaman->id_user,
            'judul'   => 'Pengembalian Disetujui',
            'pesan'   => "Peminjaman buku \"{$buku->judul}\" telah dikonfirmasi sebagai dikembalikan." . ($lateDays > 0 ? " Terlambat {$lateDays} hari." : ''),
            'tipe'    => 'success',
            'url'     => route('member.riwayat'),
        ]);

        $this->log('konfirmasi_kembalikan', 'Peminjaman', $peminjaman->id_pinjam, "Konfirmasi kembalikan: {$buku->judul}");

        return back()->with('success', 'Pengembalian dikonfirmasi!' . ($lateDays > 0 ? " Terlambat {$lateDays} hari." : ''));
    }


    public function approvePerpanjang(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['catatan_admin' => 'nullable|string|max:500']);

        if ($peminjaman->status !== 'perpanjang_pending') {
            return back()->with('error', 'Tidak ada request perpanjangan.');
        }

        $durasi = $peminjaman->perpanjang_durasi ?? 7;
        $newDue = $peminjaman->due_date->addDays($durasi);

        $peminjaman->update([
            'status'             => 'borrowed',
            'due_date'           => $newDue,
            'late_days'          => 0,
            'catatan_admin'      => $request->catatan_admin,
            'perpanjang_durasi'  => null,
            'perpanjang_catatan' => null,
            'perpanjang_prev_status' => null,
        ]);

        Notifikasi::create([
            'id_user' => $peminjaman->id_user,
            'judul'   => 'Perpanjangan Disetujui',
            'pesan'   => "Perpanjangan {$durasi} hari untuk buku \"{$peminjaman->buku->judul}\" disetujui. Jatuh tempo baru: {$newDue->format('d M Y')}.",
            'tipe'    => 'success',
            'url'     => route('member.riwayat'),
        ]);

        $this->log('approve_perpanjang', 'Peminjaman', $peminjaman->id_pinjam, "Approve perpanjang {$durasi} hari");

        return back()->with('success', "Perpanjangan {$durasi} hari disetujui.");
    }

    public function rejectPerpanjang(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['catatan_admin' => 'required|string|max:500']);

        if ($peminjaman->status !== 'perpanjang_pending') {
            return back()->with('error', 'Tidak ada request perpanjangan.');
        }

        $prevStatus = $peminjaman->perpanjang_prev_status ?? 'borrowed';

        $peminjaman->update([
            'status'             => $prevStatus,
            'catatan_admin'      => $request->catatan_admin,
            'perpanjang_durasi'  => null,
            'perpanjang_catatan' => null,
            'perpanjang_prev_status' => null,
        ]);

        Notifikasi::create([
            'id_user' => $peminjaman->id_user,
            'judul'   => 'Perpanjangan Ditolak',
            'pesan'   => "Perpanjangan buku \"{$peminjaman->buku->judul}\" ditolak. Alasan: {$request->catatan_admin}",
            'tipe'    => 'danger',
            'url'     => route('member.riwayat'),
        ]);

        $this->log('reject_perpanjang', 'Peminjaman', $peminjaman->id_pinjam, 'Reject perpanjang');

        return back()->with('success', 'Request perpanjangan ditolak.');
    }

    public function perpanjang(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['durasi_tambah' => 'required|integer|min:1|max:14']);

        if (!in_array($peminjaman->status, ['borrowed','overdue'])) {
            return back()->withErrors(['msg' => 'Tidak dapat diperpanjang.']);
        }

        $peminjaman->update([
            'due_date'      => $peminjaman->due_date->addDays($request->durasi_tambah),
            'status'        => 'borrowed',
            'late_days'     => 0,
        ]);

        $this->log('perpanjang', 'Peminjaman', $peminjaman->id_pinjam, "Perpanjang {$request->durasi_tambah} hari");

        return back()->with('success', "Peminjaman diperpanjang {$request->durasi_tambah} hari.");
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'borrowed') {
            $peminjaman->buku->increment('stok', $peminjaman->jumlah_pinjam);
        }
        $peminjaman->delete();
        return back()->with('success', 'Data peminjaman dihapus.');
    }

    private function log(string $action, string $model, int $id, string $desc): void
    {
        \DB::table('activity_log')->insert([
            'id_user'     => Auth::user()?->id_user,
            'action'      => $action,
            'model'       => $model,
            'model_id'    => $id,
            'description' => $desc,
            'ip_address'  => request()->ip(),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
