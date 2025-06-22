<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendudukDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $penduduk = $user->penduduk;
        return view('penduduk.dashboard', compact('user', 'penduduk'));
    }

    public function edit()
    {
        $user = Auth::user();
        $penduduk = $user->penduduk;

        // Tolak edit jika profil sudah terkunci
        if ($penduduk && $penduduk->is_profile_complete) {
            return redirect()->route('penduduk.dashboard')->with('error', 'Profil Anda sudah final dan hanya bisa diubah oleh Admin.');
        }

        return view('penduduk.edit', compact('user', 'penduduk'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $penduduk = $user->penduduk;

        // Tolak update jika profil sudah terkunci (pengamanan ganda)
        if ($penduduk && $penduduk->is_profile_complete) {
            return redirect()->route('penduduk.dashboard')->with('error', 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nik' => 'required|string|size:16|unique:penduduk,nik,' . optional($penduduk)->id,
            'tahun' => 'required|integer',
            'jenis_kelamin' => 'required|in:L,P',
            'usia' => 'required|integer',
            'rt' => 'required|integer',
            'tanggungan' => 'required|integer',
            'kondisi_rumah' => 'required|in:baik,cukup,kurang',
            'status_kepemilikan' => 'required|in:hak milik,numpang,sewa',
            'penghasilan' => 'required|numeric',
        ]);

        // Update tabel users
        $user->update([
            'name' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
        ]);
        
        // Update tabel penduduk, pastikan nama juga terupdate
        $pendudukData = $request->except('email', 'username', '_token', '_method');
        $pendudukData['nama'] = $request->nama;
        $penduduk->update($pendudukData);

        return redirect()->route('penduduk.dashboard')->with('success', 'Data diri berhasil diperbarui.');
    }

    public function lockProfile()
    {
        $penduduk = Auth::user()->penduduk;
        
        if ($penduduk) {
            $penduduk->update(['is_profile_complete' => true]);
            return redirect()->route('penduduk.dashboard')->with('success', 'Profil Anda telah berhasil dikunci.');
        }

        return redirect()->route('penduduk.dashboard')->with('error', 'Gagal menemukan data penduduk.');
    }
} 