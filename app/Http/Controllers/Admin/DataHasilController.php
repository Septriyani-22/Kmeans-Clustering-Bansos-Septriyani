<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HasilKmeans;
use App\Models\Penduduk;
use App\Models\Centroid;
use Illuminate\Support\Facades\DB;

class DataHasilController extends Controller
{
    public function index()
    {
        $hasil = HasilKmeans::with(['penduduk', 'centroid'])->get();
        return view('admin.datahasil.index', compact('hasil'));
    }

    public function export()
    {
        $hasil = HasilKmeans::with(['penduduk', 'centroid'])->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="hasil_clustering.csv"',
        ];

        $callback = function() use ($hasil) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'NIK',
                'Nama',
                'Penghasilan',
                'Tanggungan',
                'Cluster',
                'Status'
            ]);

            // Data
            foreach ($hasil as $row) {
                fputcsv($file, [
                    $row->penduduk->nik,
                    $row->penduduk->nama,
                    $row->penduduk->penghasilan,
                    $row->penduduk->tanggungan,
                    $row->cluster,
                    $row->hasil
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function proses()
    {
        $penduduks = Penduduk::all();
        if ($penduduks->count() == 0) {
            return redirect()->route('admin.clustering')->with('error', 'Data penduduk kosong!');
        }

        // Kosongkan hasil lama
        HasilKmeans::truncate();

        // Mapping data ke bentuk numerik
        $data = [];
        foreach ($penduduks as $p) {
            $data[] = [
                'model' => $p,
                'penghasilan_num' => $this->penghasilanToNum($p->penghasilan),
                'tanggungan_num' => is_numeric($p->tanggungan) ? (int)$p->tanggungan : ($p->tanggungan == '5 lebih' ? 5 : 1),
            ];
        }

        // Inisialisasi centroid dari database
        $centroids = Centroid::all();
        if ($centroids->count() < 2) {
            return redirect()->route('admin.clustering')->with('error', 'Minimal 2 centroid diperlukan!');
        }

        $centroidArr = [];
        foreach ($centroids as $i => $c) {
            $centroidArr[$i+1] = [
                'penghasilan_num' => $c->penghasilan_num,
                'tanggungan_num' => $c->tanggungan_num,
            ];
        }

        $max_iter = 10;
        $assignments = [];
        for ($iter = 0; $iter < $max_iter; $iter++) {
            $clusters = [];
            foreach ($centroidArr as $c => $v) $clusters[$c] = [];
            foreach ($data as $idx => $row) {
                $minDist = null; $minC = null;
                foreach ($centroidArr as $c => $cent) {
                    $dist = pow($row['penghasilan_num'] - $cent['penghasilan_num'], 2) + pow($row['tanggungan_num'] - $cent['tanggungan_num'], 2);
                    if ($minDist === null || $dist < $minDist) {
                        $minDist = $dist; $minC = $c;
                    }
                }
                $clusters[$minC][] = $idx;
                $assignments[$idx] = $minC;
            }
            // Update centroid
            foreach ($centroidArr as $c => $v) {
                if (count($clusters[$c]) > 0) {
                    $centroidArr[$c]['penghasilan_num'] = array_sum(array_map(fn($i) => $data[$i]['penghasilan_num'], $clusters[$c])) / count($clusters[$c]);
                    $centroidArr[$c]['tanggungan_num'] = array_sum(array_map(fn($i) => $data[$i]['tanggungan_num'], $clusters[$c])) / count($clusters[$c]);
                }
            }
        }

        // Tentukan cluster layak (rata-rata penghasilan terendah, tanggungan terbanyak)
        $avg = [];
        foreach ($centroidArr as $c => $v) {
            $ph = array_map(fn($i) => $data[$i]['penghasilan_num'], $clusters[$c]);
            $tg = array_map(fn($i) => $data[$i]['tanggungan_num'], $clusters[$c]);
            $avg[$c] = (count($ph) > 0 ? array_sum($ph)/count($ph) : 0) - (count($tg) > 0 ? array_sum($tg)/count($tg) : 0)*0.1;
        }
        $clusterLayak = array_keys($avg, min($avg))[0];

        // Simpan hasil
        foreach ($data as $idx => $row) {
            $cluster = $assignments[$idx];
            $hasil = $cluster == $clusterLayak ? 'Layak' : 'Tidak Layak';
            HasilKmeans::create([
                'penduduk_id' => $row['model']->id,
                'centroid_id' => $centroids[$cluster-1]->id,
                'jarak' => $minDist,
                'cluster' => $cluster,
                'hasil' => $hasil,
            ]);
        }

        return redirect()->route('admin.clustering')->with('success', 'Clustering selesai!');
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
