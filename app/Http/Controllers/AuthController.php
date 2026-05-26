<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('dashboard')
                : redirect()->route('member.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return $user->isAdmin()
                ? redirect()->route('dashboard')
                : redirect()->route('member.dashboard');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput()->with('tab', 'login');
    }

    public function showRegister()
    {
        return Auth::check() ? redirect()->route('member.dashboard') : redirect()->route('login');
    }

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:50|unique:user,username',
            'email'        => 'nullable|email|unique:user,email',
            'password'     => 'required|min:6|confirmed',
        ], [
            'username.unique'    => 'Username sudah digunakan.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('tab', 'register');
        }

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'level'        => 'member',
        ]);

        Auth::login($user);
        return redirect()->route('member.dashboard')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->nama_lengkap . '.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }
}
