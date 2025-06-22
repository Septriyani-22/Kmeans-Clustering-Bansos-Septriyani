<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class PendudukController extends Controller
{
    public function index()
    {
        $penduduk = Penduduk::with('hasilKmeans')->paginate(20);
        return view('kepala_desa.penduduk.index', compact('penduduk'));
    }
} 