<?php

namespace App\Exports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PendudukExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Penduduk::select([
            'nik', 'nama', 'jenis_kelamin', 'umur', 'rt_rw', 'tanggungan',
            'penghasilan', 'kondisi_rumah', 'status_kepemilikan_rumah'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'NIK', 'Nama', 'Jenis Kelamin', 'Umur', 'RT/RW', 'Tanggungan',
            'Penghasilan', 'Kondisi Rumah', 'Status Kepemilikan Rumah'
        ];
    }
}
