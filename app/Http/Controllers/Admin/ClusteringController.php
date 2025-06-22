<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\Centroid;
use App\Models\Iterasi;
use App\Models\HasilKmeans;
use App\Models\Kriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MappingCentroid;

class ClusteringController extends Controller
{
    public function index()
    {
        $centroids = Centroid::all();
        $mappings = MappingCentroid::all();
        $penduduks = Penduduk::all();
        $distanceResults = session('distanceResults', []);

        $convertedPenduduks = $penduduks->map(function($penduduk) {
            return [
                'id' => $penduduk->id,
                'nama' => $penduduk->nama,
                'usia' => $this->getNilaiKriteria('Usia', $penduduk->usia),
                'jumlah_tanggungan' => $this->getNilaiKriteria('Tanggungan', $penduduk->tanggungan),
                'kondisi_rumah' => $this->getNilaiKriteria('Kondisi Rumah', strtolower($penduduk->kondisi_rumah)),
                'status_kepemilikan' => $this->getNilaiKriteria('Status Kepemilikan', strtolower($penduduk->status_kepemilikan)),
                'jumlah_penghasilan' => $this->getNilaiKriteria('Penghasilan', $penduduk->penghasilan),
            ];
        });

        // Convert mappings to use convertedPenduduks data
        $convertedMappings = $mappings->map(function($mapping) use ($convertedPenduduks) {
            $penduduk = $convertedPenduduks->firstWhere('id', $mapping->data_ke);
            if (!$penduduk) {
                return null; // or handle error
            }
            return [
                'id' => $mapping->id,
                'data_ke' => $mapping->data_ke,
                'nama_penduduk' => $penduduk['nama'],
                'cluster' => $mapping->cluster,
                'usia' => $penduduk['usia'],
                'jumlah_tanggungan' => $penduduk['jumlah_tanggungan'],
                'kondisi_rumah' => $penduduk['kondisi_rumah'],
                'status_kepemilikan' => $penduduk['status_kepemilikan'],
                'jumlah_penghasilan' => $penduduk['jumlah_penghasilan']
            ];
        })->filter(); // filter out nulls

        return view('admin.clustering.index', compact('centroids', 'mappings', 'penduduks', 'convertedPenduduks', 'convertedMappings', 'distanceResults'));
    }

    public function proses(Request $request)
    {
        try {
            return $this->calculateDistances();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function getNilaiKriteria($tipe, $value)
    {
        $kriteria = Kriteria::where('nama', $tipe)->first();
        if (!$kriteria) return null;

        $nilaiKriteria = $kriteria->nilaiKriteria()
            ->where(function($query) use ($value) {
                if (is_numeric($value)) {
                    $query->where('nilai_min', '<=', $value)
                          ->where('nilai_max', '>=', $value);
                } else {
                    $query->where('nama', 'like', '%' . $value . '%');
                }
            })
            ->first();

        return $nilaiKriteria ? $nilaiKriteria->nilai : null;
    }
    
    public function calculateDistances()
    {
        $penduduks = Penduduk::all();
        $mappings = MappingCentroid::with('penduduk')->get();
        $centroids = [];
        $features = ['usia', 'tanggungan', 'kondisi_rumah', 'status_kepemilikan', 'penghasilan'];
    
        // Convert all penduduk data to their numeric kriteria values first
        $convertedPenduduks = $penduduks->mapWithKeys(function($p) {
            return [$p->id => (object)[
                'id' => $p->id,
                'nama' => $p->nama,
                'usia' => $this->getNilaiKriteria('Usia', $p->usia) ?? 0,
                'tanggungan' => $this->getNilaiKriteria('Tanggungan', $p->tanggungan) ?? 0,
                'kondisi_rumah' => $this->getNilaiKriteria('Kondisi Rumah', strtolower($p->kondisi_rumah)) ?? 0,
                'status_kepemilikan' => $this->getNilaiKriteria('Status Kepemilikan', strtolower($p->status_kepemilikan)) ?? 0,
                'penghasilan' => $this->getNilaiKriteria('Penghasilan', $p->penghasilan) ?? 0,
            ]];
        });

        $mappedPenduksByCluster = $mappings->groupBy('cluster');

        for ($i = 1; $i <= 3; $i++) {
            $clusterName = 'C' . $i;
            $centroid = ['nama_centroid' => $clusterName];
            
            if (isset($mappedPenduksByCluster[$clusterName]) && $mappedPenduksByCluster[$clusterName]->isNotEmpty()) {
                $mappedPenduks = $mappedPenduksByCluster[$clusterName];
                $count = $mappedPenduks->count();

                foreach ($features as $feature) {
                    $total = $mappedPenduks->sum(function($mapping) use ($feature, $convertedPenduduks) {
                        return $convertedPenduduks[$mapping->data_ke]->$feature ?? 0;
                    });
                    $centroid[$feature] = $count > 0 ? $total / $count : 0;
                }
            } else {
                // If no mapping, initialize with zeros
                foreach ($features as $feature) {
                    $centroid[$feature] = 0;
                }
            }
            $centroids[$clusterName] = (object)$centroid;
        }

        $distanceResults = [];

        foreach ($convertedPenduduks as $penduduk) {
            $distances = [];
            foreach ($centroids as $clusterName => $centroidData) {
                $distance = sqrt(
                    pow($penduduk->usia - $centroidData->usia, 2) +
                    pow($penduduk->tanggungan - $centroidData->tanggungan, 2) +
                    pow($penduduk->kondisi_rumah - $centroidData->kondisi_rumah, 2) +
                    pow($penduduk->status_kepemilikan - $centroidData->status_kepemilikan, 2) +
                    pow($penduduk->penghasilan - $centroidData->penghasilan, 2)
                );
                $distances[$clusterName] = $distance;
            }
            
            $minDistance = min($distances);
            $cluster = array_search($minDistance, $distances);

            $distanceResults[] = [
                'penduduk' => (object)['id' => $penduduk->id, 'nama' => $penduduk->nama],
                'c1_distance' => number_format($distances['C1'], 9, '.', ''),
                'c2_distance' => number_format($distances['C2'], 9, '.', ''),
                'c3_distance' => number_format($distances['C3'], 9, '.', ''),
                'min_distance' => number_format($minDistance, 9, '.', ''),
                'cluster' => $cluster,
                'distances' => $distances // For other controllers
            ];
        }
    
        // Hapus hasil lama sebelum memasukkan yang baru
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        HasilKmeans::truncate();
        Centroid::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Simpan centroid baru
        foreach ($centroids as $clusterName => $centroidData) {
            $centroidModel = Centroid::create([
                'nama_centroid' => $clusterName,
                'usia' => $centroidData->usia,
                'tanggungan_num' => $centroidData->tanggungan,
                'kondisi_rumah' => $centroidData->kondisi_rumah,
                'status_kepemilikan' => $centroidData->status_kepemilikan,
                'penghasilan_num' => $centroidData->penghasilan,
                'tahun' => date('Y'),
                'periode' => 1, // Atur sesuai kebutuhan
            ]);
            $centroids[$clusterName]->id = $centroidModel->id;
        }

        // Simpan hasil ke database
        foreach ($distanceResults as $result) {
            HasilKmeans::create([
                'penduduk_id' => $result['penduduk']->id,
                'centroid_id' => $centroids[$result['cluster']]->id,
                'cluster' => (int) substr($result['cluster'], 1),
                'jarak' => $result['min_distance'],
                'iterasi' => 1, // Iterasi pertama
                'tahun' => date('Y'),
                'periode' => 1, // Atur sesuai kebutuhan
            ]);
        }

        session(['distanceResults' => $distanceResults]);
    
        return redirect()->route('admin.clustering.index')
            ->with('success', 'Perhitungan jarak berhasil diselesaikan.');
    }

    public function reset()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            DB::table('hasil_kmeans')->truncate();
            DB::table('mapping_centroids')->truncate();
            DB::table('centroids')->truncate();
            DB::table('iterasi')->truncate();
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            session()->forget(['distanceResults', 'centroids', 'iterasi', 'hasilKmeans']);
            
            return redirect()->route('admin.clustering.index')
                ->with('success', 'Semua data perhitungan berhasil direset');
        } catch (\Exception $e) {
            Log::error('Error resetting calculations: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mereset data: ' . $e->getMessage());
        }
    }
}