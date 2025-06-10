<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\HasilKmeans;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class DataHasilController extends Controller
{
    public function index(Request $request)
    {
        $tahunList = Penduduk::select('tahun')->distinct()->pluck('tahun');
        $periodeList = Penduduk::select('periode')->distinct()->pluck('periode');
        $hasil_akhir = null;

        if ($request->tahun && $request->periode) {
            $penduduks = Penduduk::where('tahun', $request->tahun)
                ->where('periode', $request->periode)
                ->get();

            if ($penduduks->count() > 0) {
                $hasil_kmeans = HasilKmeans::where('tahun', $request->tahun)
                    ->where('periode', $request->periode)
                    ->get();

                if ($hasil_kmeans->count() == 0) {
                    $hasil_akhir = $this->prosesKmeansDanSimpan($penduduks, $request->tahun, $request->periode);
                } else {
                    $hasil_akhir = $hasil_kmeans->map(function($row) {
                        return [
                            'nama' => $row->nama,
                            'periode' => $row->periode,
                            'hasil' => $row->hasil,
                        ];
                    });
                }
            }
        }

        $hasil = HasilKmeans::paginate(10);

        return view('kepala_desa.data_hasil', compact('hasil', 'tahunList', 'periodeList', 'hasil_akhir'));
    }


    public function export(Request $request)
    {
        // TODO: Tambahkan logika ekspor sesuai kebutuhan
    }

    public function proses()
    {
        $penduduks = Penduduk::all();
        $tahun = now()->year;
        $periode = 1;

        $this->prosesKmeansDanSimpan($penduduks, $tahun, $periode);
        return redirect()->route('kepala_desa.datahasil.index')->with('success', 'Proses K-Means selesai!');
    }

    private function prosesKmeansDanSimpan($penduduks, $tahun, $periode)
    {
        if (!$tahun || !$periode) {
            throw new \Exception('Tahun dan Periode harus diisi!');
        }

        // 1. Mapping data ke bentuk numerik (penghasilan & tanggungan)
        $data = [];
        foreach ($penduduks as $p) {
            $data[] = [
                'model' => $p,
                'penghasilan_num' => $this->penghasilanToNum($p->penghasilan),
                'tanggungan_num' => is_numeric($p->jumlah_tanggungan_keluarga) ? (int)$p->jumlah_tanggungan_keluarga : ($p->jumlah_tanggungan_keluarga == '5 lebih' ? 5 : 1),
            ];
        }

        // 2. Inisialisasi centroid: ekstrim penghasilan
        usort($data, fn($a, $b) => $a['penghasilan_num'] <=> $b['penghasilan_num']);
        $centroids = [
            1 => [
                'penghasilan_num' => $data[0]['penghasilan_num'],
                'tanggungan_num' => $data[0]['tanggungan_num'],
            ],
            2 => [
                'penghasilan_num' => $data[count($data)-1]['penghasilan_num'],
                'tanggungan_num' => $data[count($data)-1]['tanggungan_num'],
            ],
        ];

        $max_iter = 10;
        $assignments = [];
        for ($iter = 0; $iter < $max_iter; $iter++) {
            // 3. Assign ke centroid terdekat
            $clusters = [1 => [], 2 => []];
            foreach ($data as $idx => $row) {
                $dist1 = pow($row['penghasilan_num'] - $centroids[1]['penghasilan_num'], 2) + pow($row['tanggungan_num'] - $centroids[1]['tanggungan_num'], 2);
                $dist2 = pow($row['penghasilan_num'] - $centroids[2]['penghasilan_num'], 2) + pow($row['tanggungan_num'] - $centroids[2]['tanggungan_num'], 2);
                $cluster = $dist1 < $dist2 ? 1 : 2;
                $clusters[$cluster][] = $idx;
                $assignments[$idx] = $cluster;
            }
            // 4. Update centroid
            foreach ([1,2] as $c) {
                if (count($clusters[$c]) > 0) {
                    $centroids[$c]['penghasilan_num'] = array_sum(array_map(fn($i) => $data[$i]['penghasilan_num'], $clusters[$c])) / count($clusters[$c]);
                    $centroids[$c]['tanggungan_num'] = array_sum(array_map(fn($i) => $data[$i]['tanggungan_num'], $clusters[$c])) / count($clusters[$c]);
                }
            }
        }

        // 5. Tentukan cluster termiskin (rata-rata penghasilan terendah, tanggungan terbanyak)
        $avg = [1 => 0, 2 => 0];
        foreach ([1,2] as $c) {
            $ph = array_map(fn($i) => $data[$i]['penghasilan_num'], $clusters[$c]);
            $tg = array_map(fn($i) => $data[$i]['tanggungan_num'], $clusters[$c]);
            $avg[$c] = (count($ph) > 0 ? array_sum($ph)/count($ph) : 0) - (count($tg) > 0 ? array_sum($tg)/count($tg) : 0)*0.1; // penghasilan lebih berat
        }
        $clusterLayak = $avg[1] < $avg[2] ? 1 : 2;

        // 6. Simpan hasil
        $hasil_akhir = [];
        foreach ($data as $idx => $row) {
            $cluster = $assignments[$idx];
            $hasil = [
                'nama' => $row['model']->nama,
                'periode' => $periode,
                'hasil' => $cluster == $clusterLayak ? 'Mendapatkan Bantuan' : 'Tidak Mendapatkan Bantuan',
            ];
            $hasil_akhir[] = $hasil;
            HasilKmeans::create([
                'nik' => $row['model']->nik,
                'nama' => $row['model']->nama,
                'cluster' => $cluster,
                'tahun' => $tahun,
                'periode' => $periode,
                'hasil' => $hasil['hasil'],
            ]);
        }
        return collect($hasil_akhir);
    }

    private function umurToNum($umur)
    {
        return match (true) {
            str_contains($umur, '30 kebawah') => 1,
            str_contains($umur, '31-40') => 2,
            str_contains($umur, '41-60') => 3,
            str_contains($umur, '60 Keatas') => 4,
            default => 0,
        };
    }

    private function penghasilanToNum($penghasilan)
    {
        return match (true) {
            str_contains($penghasilan, 'Kurang Dari 500') => 1,
            str_contains($penghasilan, '500 s/d 1 juta') => 2,
            str_contains($penghasilan, 'Lebih Dari 1 juta') => 3,
            str_contains($penghasilan, 'Lebih Dari 2 juta') => 4,
            default => 0,
        };
    }
}
