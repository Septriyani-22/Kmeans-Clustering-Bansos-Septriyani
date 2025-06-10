<?php

namespace App\Exports;
use App\Exports\LaporanHasilExport;

use App\Models\HasilKmeans;
use Maatwebsite\Excel\Concerns\FromArray;

class LaporanHasilExport implements FromArray
{
    public function array(): array
    {
        $summary = HasilKmeans::select('cluster')
            ->selectRaw('count(*) as total')
            ->groupBy('cluster')
            ->pluck('total', 'cluster')->toArray();

        $result = [['Cluster', 'Jumlah Penerima']];
        foreach ($summary as $cluster => $total) {
            $result[] = [$cluster, $total];
        }
        return $result;
    }
    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new LaporanHasilExport, 'laporan_hasil.xlsx');
    }
}
