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
        $riwayatPengajuan = $penduduk ? $penduduk->riwayatPengajuan()->latest()->get() : collect();
        return view('penduduk.dashboard', compact('user', 'penduduk', 'riwayatPengajuan'));
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
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'usia' => 'required|integer',
            'rt' => 'required|integer',
            'tanggungan' => 'required|integer',
            'kondisi_rumah' => 'required|in:baik,cukup,kurang',
            'status_kepemilikan' => 'required|in:hak milik,numpang,sewa',
            'penghasilan' => 'required|numeric',
            // Validasi file upload
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sktm_file' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
            'bukti_kepemilikan_file' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
            'slip_gaji_file' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
            'foto_rumah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan data lama untuk log
        $dataLama = $penduduk->only([
            'nik','nama','tanggal_lahir','jenis_kelamin','usia','rt','tanggungan','kondisi_rumah','status_kepemilikan','penghasilan',
            'ktp_photo','sktm_file','bukti_kepemilikan_file','slip_gaji_file','foto_rumah'
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

        // Handle file upload
        $fileFields = [
            'ktp_photo',
            'sktm_file',
            'bukti_kepemilikan_file',
            'slip_gaji_file',
            'foto_rumah',
        ];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($penduduk->$field) {
                    \Storage::disk('public')->delete($penduduk->$field);
                }
                $file = $request->file($field);
                $path = $file->store('penduduk', 'public');
                $pendudukData[$field] = $path;
            }
        }

        $penduduk->update($pendudukData);

        // Simpan data baru untuk log
        $dataBaru = $penduduk->only([
            'nik','nama','tanggal_lahir','jenis_kelamin','usia','rt','tanggungan','kondisi_rumah','status_kepemilikan','penghasilan',
            'ktp_photo','sktm_file','bukti_kepemilikan_file','slip_gaji_file','foto_rumah'
        ]);

        // Log riwayat pengajuan super detail
        $penduduk->riwayatPengajuan()->create([
            'aksi' => 'Update Data',
            'keterangan' => 'User melakukan update data diri',
            'status' => 'diajukan',
            'data_lama' => json_encode($dataLama),
            'data_baru' => json_encode($dataBaru),
        ]);

        return redirect()->route('penduduk.dashboard')->with('success', 'Data diri berhasil diperbarui.');
    }

    public function lockProfile()
    {
        $penduduk = Auth::user()->penduduk;
        
        if ($penduduk) {
            $dataLama = $penduduk->only(['is_profile_complete']);
            $penduduk->update(['is_profile_complete' => true]);
            $dataBaru = $penduduk->only(['is_profile_complete']);
            $penduduk->riwayatPengajuan()->create([
                'aksi' => 'Kunci Profil',
                'keterangan' => 'User mengunci profil',
                'status' => 'dikunci',
                'data_lama' => json_encode($dataLama),
                'data_baru' => json_encode($dataBaru),
            ]);
            return redirect()->route('penduduk.dashboard')->with('success', 'Profil Anda telah berhasil dikunci.');
        }

        return redirect()->route('penduduk.dashboard')->with('error', 'Gagal menemukan data penduduk.');
    }
} 