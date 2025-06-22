<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->role === 'kepala_desa') {
                return redirect('/kepala_desa/dashboard');
            } elseif ($user->role === 'penduduk') {
                return redirect('/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Akses tidak diizinkan untuk role ini.',
                ]);
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    /**
     * Tampilkan halaman register.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:admin,kepala_desa,penduduk',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'penduduk') {
            $user->penduduk()->create([
                'nama' => $user->name,
                // Kolom lain akan null secara default
            ]);
        }

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'kepala_desa') {
            return redirect('/kepala_desa/dashboard');
        } elseif ($user->role === 'penduduk') {
            return redirect('/dashboard');
        } else {
            Auth::logout();
            return redirect('/login')->withErrors([
                'role' => 'Role tidak dikenal.',
            ]);
        }
    }

    /**
     * Proses logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}