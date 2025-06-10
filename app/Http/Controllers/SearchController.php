<?php

namespace App\Http\Controllers;

use App\Models\HasilKmeans;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $nik = $request->get('nik');
        
        $result = HasilKmeans::with('penduduk')
            ->whereHas('penduduk', function($query) use ($nik) {
                $query->where('nik', $nik);
            })
            ->first();

        return view('welcome', compact('result'));
    }
} 