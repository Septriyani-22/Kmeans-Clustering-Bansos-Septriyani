<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendudukDashboardController extends Controller
{
    /**
     * Display the authenticated user's dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $penduduk = $user->penduduk; // Mengambil data penduduk yang berelasi

        return view('penduduk.dashboard', compact('user', 'penduduk'));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        $penduduk = $user->penduduk;

        return view('penduduk.edit', compact('user', 'penduduk'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $penduduk = $user->penduduk;

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nik' => 'required|string|size:16|unique:penduduk,nik,' . $penduduk->id,
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
}
