<?php

namespace App\Exports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PendudukExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Penduduk::select([
            'nik', 'nama', 'tahun', 'jenis_kelamin', 'usia', 'rt', 'tanggungan',
            'penghasilan', 'kondisi_rumah', 'status_kepemilikan'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Tahun',
            'Jenis Kelamin',
            'Usia',
            'RT',
            'Tanggungan',
            'Penghasilan',
            'Kondisi Rumah',
            'Status Kepemilikan'
        ];
    }
}
