<?php

namespace App\Exports;

use App\Models\HasilKmeans;
use Maatwebsite\Excel\Concerns\FromCollection;

class HasilKmeansExport implements FromCollection
{
    public function collection()
    {
        return HasilKmeans::all();
    }
    public function export()
    {
        return Excel::download(new HasilKmeansExport, 'hasil_kmeans.xlsx');
    }
}