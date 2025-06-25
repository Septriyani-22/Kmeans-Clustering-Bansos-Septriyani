<?php
namespace App\Imports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Facades\Log;

HeadingRowFormatter::default('none'); // disable auto snake_case header

class PendudukImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        try {
            // Skip if NIK is empty
            if (empty($row['nik'])) {
                return null;
            }

            // Clean the data
            $nik = trim($row['nik']);
            $nama = trim($row['nama'] ?? '');
            $tahun = trim($row['tahun'] ?? date('Y'));
            $tanggal_lahir = $tahun ? $tahun . '-01-01' : null;
            $jenis_kelamin = trim($row['jenis_kelamin'] ?? 'L');
            $usia = trim($row['usia'] ?? '0');
            $rt = trim($row['rt'] ?? '1');
            $tanggungan = trim($row['tanggungan'] ?? '1');
            $penghasilan = trim($row['penghasilan'] ?? '0');
            $kondisi_rumah = trim($row['kondisi_rumah'] ?? 'cukup');
            $status_kepemilikan = trim($row['status_kepemilikan'] ?? 'hak milik');

            // Convert numeric values
            $usia = is_numeric($usia) ? (int)$usia : 0;
            $rt = is_numeric($rt) ? (int)$rt : 1;
            $tanggungan = is_numeric($tanggungan) ? (int)$tanggungan : 1;
            $penghasilan = is_numeric($penghasilan) ? (int)$penghasilan : 0;

            // Cek apakah data sudah ada berdasarkan NIK
            $penduduk = Penduduk::where('nik', $nik)->first();

            if ($penduduk) {
                // Update data yang sudah ada
                $penduduk->update([
                    'nama' => $nama,
                    'tanggal_lahir' => $tanggal_lahir,
                    'jenis_kelamin' => $jenis_kelamin,
                    'usia' => $usia,
                    'rt' => $rt,
                    'tanggungan' => $tanggungan,
                    'penghasilan' => $penghasilan,
                    'kondisi_rumah' => $kondisi_rumah,
                    'status_kepemilikan' => $status_kepemilikan,
                ]);
                return null;
            }

            // Buat data baru jika belum ada
            return new Penduduk([
                'nik' => $nik,
                'nama' => $nama,
                'tanggal_lahir' => $tanggal_lahir,
                'jenis_kelamin' => $jenis_kelamin,
                'usia' => $usia,
                'rt' => $rt,
                'tanggungan' => $tanggungan,
                'penghasilan' => $penghasilan,
                'kondisi_rumah' => $kondisi_rumah,
                'status_kepemilikan' => $status_kepemilikan,
            ]);
        } catch (\Exception $e) {
            Log::error('Error importing row: ' . json_encode($row) . ' Error: ' . $e->getMessage());
            return null; // Skip row on error instead of throwing exception
        }
    }
}
