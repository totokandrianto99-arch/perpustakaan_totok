<?php

namespace App\Http\Controllers;

use App\Models\{User, Notifikasi};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::members()->with(['peminjaman']);

        if ($s = $request->search) {
            $q->where(function($query) use ($s) {
                $query->where('nama_lengkap','like',"%$s%")
                      ->orWhere('username','like',"%$s%")
                      ->orWhere('email','like',"%$s%");
            });
        }

        if ($status = $request->status) $q->where('status', $status);

        $users = $q->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.form', ['user' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:50|unique:user,username',
            'email'        => 'nullable|email|unique:user,email',
            'password'     => 'required|min:6',
        ]);

        User::create([
            'nama_lengkap'   => $request->nama_lengkap,
            'username'       => $request->username,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'level'          => 'member',
            'status'         => 'active',
            'can_borrow'     => true,
            'plain_password' => $request->password,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('user.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:50|unique:user,username,'.$user->id_user.',id_user',
            'email'        => 'nullable|email|unique:user,email,'.$user->id_user.',id_user',
            'password'     => 'nullable|min:6',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password']       = Hash::make($request->password);
            $data['plain_password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', "User {$user->nama_lengkap} dihapus.");
    }

    public function show(User $user)
    {
        $user->load(['peminjaman.buku']);
        $stats = [
            'total'    => $user->peminjaman->count(),
            'aktif'    => $user->peminjaman->whereIn('status',['borrowed','overdue'])->count(),
            'overdue'  => $user->peminjaman->where('status','overdue')->count(),
            'returned' => $user->peminjaman->where('status','returned')->count(),
        ];
        return view('user.show', compact('user','stats'));
    }

    public function suspend(Request $request, User $user)
    {
        $request->validate(['suspend_reason' => 'required|string|max:500']);

        $user->update([
            'status'         => 'suspended',
            'suspended_at'   => now(),
            'suspend_reason' => $request->suspend_reason,
            'can_borrow'     => false,
        ]);

        Notifikasi::create([
            'id_user' => $user->id_user,
            'judul'   => 'Akun Ditangguhkan',
            'pesan'   => "Akun Anda ditangguhkan. Alasan: {$request->suspend_reason}",
            'tipe'    => 'danger',
        ]);

        return back()->with('success', "Akun {$user->nama_lengkap} ditangguhkan.");
    }

    public function activate(User $user)
    {
        $user->update([
            'status'           => 'active',
            'suspended_at'     => null,
            'suspend_reason'   => null,
            'can_borrow'       => true,
            'violation_points' => 0,
            'need_admin_approval' => false,
        ]);

        Notifikasi::create([
            'id_user' => $user->id_user,
            'judul'   => 'Akun Diaktifkan',
            'pesan'   => 'Akun Anda telah diaktifkan kembali. Anda dapat meminjam buku.',
            'tipe'    => 'success',
        ]);

        return back()->with('success', "Akun {$user->nama_lengkap} diaktifkan.");
    }

    public function toggleBorrow(User $user)
    {
        $user->update(['can_borrow' => !$user->can_borrow]);
        $status = $user->can_borrow ? 'diizinkan' : 'dilarang';
        return back()->with('success', "Hak pinjam {$user->nama_lengkap} {$status}.");
    }

    public function resetViolation(User $user)
    {
        $user->update([
            'violation_points'    => 0,
            'need_admin_approval' => false,
            'can_borrow'          => true,
        ]);
        return back()->with('success', 'Poin pelanggaran direset.');
    }
}
