<?php
namespace App\Imports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none'); // disable auto snake_case header

class PendudukImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        return new Penduduk([
            'nik' => $row['nik'],
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'umur' => $row['umur'],
            'rt_rw' => $row['rt_rw'],
            'tanggungan' => $row['tanggungan'],
            'penghasilan' => $row['penghasilan'],
            'kondisi_rumah' => $row['kondisi_rumah'],
            'status_kepemilikan_rumah' => $row['status_kepemilikan_rumah'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|numeric|digits:12|unique:penduduks,nik',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'umur' => 'required|string',
            'rt_rw' => 'nullable|string|max:20',
            'tanggungan' => 'nullable|string',
            'penghasilan' => 'required|string',
            'kondisi_rumah' => 'required|string',
            'status_kepemilikan_rumah' => 'required|string',
        ];
    }
}
