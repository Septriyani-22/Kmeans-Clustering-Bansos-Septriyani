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

class ClusteringController extends Controller
{
    public function index()
    {
        $penduduk = Penduduk::all();
        $centroids = Centroid::all();
        $iterasi = Iterasi::all();
        $hasilKmeans = HasilKmeans::with(['penduduk', 'centroid'])->get();
        
        return view('admin.clustering.index', compact('penduduk', 'centroids', 'iterasi', 'hasilKmeans'));
    }

    public function proses(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'jumlah_cluster' => 'required|integer|min:2|max:10'
            ]);

            // Hapus data clustering sebelumnya dengan urutan yang benar
            HasilKmeans::query()->delete();
            Centroid::query()->delete();

            // Ambil data penduduk
            $penduduks = Penduduk::all();
            if ($penduduks->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data penduduk yang tersedia.');
            }

            // Ambil kriteria
            $kriteria = Kriteria::all();
            if ($kriteria->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada kriteria yang tersedia.');
            }

            // Inisialisasi centroid awal dengan nilai dari kriteria
            $centroids = [];
            $jumlahCluster = $request->jumlah_cluster;
            
            // Ambil nilai kriteria untuk setiap atribut
            $usiaKriteria = $kriteria->where('nama', 'Usia')->first();
            $tanggunganKriteria = $kriteria->where('nama', 'Tanggungan')->first();
            $kondisiRumahKriteria = $kriteria->where('nama', 'Kondisi Rumah')->first();
            $statusKepemilikanKriteria = $kriteria->where('nama', 'Status Kepemilikan')->first();
            $penghasilanKriteria = $kriteria->where('nama', 'Penghasilan')->first();

            // Buat centroid awal dengan nilai dari kriteria
            for ($i = 0; $i < $jumlahCluster; $i++) {
                $centroids[] = [
                    'usia' => $this->getNilaiKriteria($usiaKriteria, $i),
                    'tanggungan' => $this->getNilaiKriteria($tanggunganKriteria, $i),
                    'kondisi_rumah' => $this->getNilaiKriteria($kondisiRumahKriteria, $i),
                    'status_kepemilikan' => $this->getNilaiKriteria($statusKepemilikanKriteria, $i),
                    'penghasilan' => $this->getNilaiKriteria($penghasilanKriteria, $i)
                ];
            }

            // Simpan centroid awal ke database
            $centroidIds = [];
            foreach ($centroids as $index => $centroid) {
                $newCentroid = Centroid::create([
                    'nama_centroid' => 'C' . ($index + 1),
                    'usia' => $centroid['usia'],
                    'tanggungan_num' => $centroid['tanggungan'],
                    'kondisi_rumah' => $this->getKondisiRumahText($centroid['kondisi_rumah']),
                    'status_kepemilikan' => $this->getStatusKepemilikanText($centroid['status_kepemilikan']),
                    'penghasilan_num' => $centroid['penghasilan'],
                    'tahun' => date('Y'),
                    'periode' => 1
                ]);
                $centroidIds[] = $newCentroid->id;
            }

            // Proses K-means
            $maxIterasi = 100;
            $iterasi = 0;
            $berubah = true;

            while ($berubah && $iterasi < $maxIterasi) {
                $berubah = false;
                $iterasi++;

                // Hapus hasil clustering sebelumnya untuk iterasi ini
                HasilKmeans::where('iterasi', $iterasi)->delete();

                // Hitung jarak dan tentukan cluster
                foreach ($penduduks as $penduduk) {
                    $jarakMin = PHP_FLOAT_MAX;
                    $clusterTerdekat = 0;

                    for ($i = 0; $i < $jumlahCluster; $i++) {
                        $jarak = $this->hitungJarak(
                            $this->getUsiaValue($penduduk->usia),
                            $this->getTanggunganValue($penduduk->tanggungan),
                            $this->getKondisiRumahValue($penduduk->kondisi_rumah),
                            $this->getStatusKepemilikanValue($penduduk->status_kepemilikan),
                            $this->getPenghasilanValue($penduduk->penghasilan),
                            $centroids[$i]['usia'],
                            $centroids[$i]['tanggungan'],
                            $centroids[$i]['kondisi_rumah'],
                            $centroids[$i]['status_kepemilikan'],
                            $centroids[$i]['penghasilan']
                        );

                        if ($jarak < $jarakMin) {
                            $jarakMin = $jarak;
                            $clusterTerdekat = $i;
                        }
                    }

                    // Simpan hasil clustering
                    HasilKmeans::create([
                        'penduduk_id' => $penduduk->id,
                        'centroid_id' => $centroidIds[$clusterTerdekat],
                        'cluster' => $clusterTerdekat + 1,
                        'jarak' => $jarakMin,
                        'kelayakan' => $this->tentukanKelayakan($clusterTerdekat + 1),
                        'iterasi' => $iterasi,
                        'tahun' => date('Y'),
                        'periode' => 1
                    ]);
                }

                // Update centroid
                $centroidsBaru = array_fill(0, $jumlahCluster, [
                    'usia' => 0,
                    'tanggungan' => 0,
                    'kondisi_rumah' => 0,
                    'status_kepemilikan' => 0,
                    'penghasilan' => 0,
                    'count' => 0
                ]);

                foreach ($penduduks as $penduduk) {
                    $hasil = HasilKmeans::where('penduduk_id', $penduduk->id)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($hasil) {
                        $clusterIndex = $hasil->cluster - 1;
                        $centroidsBaru[$clusterIndex]['usia'] += $this->getUsiaValue($penduduk->usia);
                        $centroidsBaru[$clusterIndex]['tanggungan'] += $this->getTanggunganValue($penduduk->tanggungan);
                        $centroidsBaru[$clusterIndex]['kondisi_rumah'] += $this->getKondisiRumahValue($penduduk->kondisi_rumah);
                        $centroidsBaru[$clusterIndex]['status_kepemilikan'] += $this->getStatusKepemilikanValue($penduduk->status_kepemilikan);
                        $centroidsBaru[$clusterIndex]['penghasilan'] += $this->getPenghasilanValue($penduduk->penghasilan);
                        $centroidsBaru[$clusterIndex]['count']++;
                    }
                }

                // Hitung rata-rata dan cek perubahan
                for ($i = 0; $i < $jumlahCluster; $i++) {
                    if ($centroidsBaru[$i]['count'] > 0) {
                        $centroidsBaru[$i]['usia'] /= $centroidsBaru[$i]['count'];
                        $centroidsBaru[$i]['tanggungan'] /= $centroidsBaru[$i]['count'];
                        $centroidsBaru[$i]['kondisi_rumah'] /= $centroidsBaru[$i]['count'];
                        $centroidsBaru[$i]['status_kepemilikan'] /= $centroidsBaru[$i]['count'];
                        $centroidsBaru[$i]['penghasilan'] /= $centroidsBaru[$i]['count'];

                        // Cek apakah ada perubahan
                        if ($this->adaPerubahan($centroids[$i], $centroidsBaru[$i])) {
                            $berubah = true;
                        }
                    }
                }

                // Update centroid jika ada perubahan
                if ($berubah) {
                    $centroids = $centroidsBaru;
                    
                    // Update centroid di database
                    foreach ($centroids as $index => $centroid) {
                        Centroid::where('nama_centroid', 'C' . ($index + 1))
                            ->update([
                                'usia' => $centroid['usia'],
                                'tanggungan_num' => $centroid['tanggungan'],
                                'kondisi_rumah' => $this->getKondisiRumahText($centroid['kondisi_rumah']),
                                'status_kepemilikan' => $this->getStatusKepemilikanText($centroid['status_kepemilikan']),
                                'penghasilan_num' => $centroid['penghasilan']
                            ]);
                    }
                }
            }

            return redirect()->route('admin.clustering.index')
                ->with('success', 'Proses clustering berhasil dilakukan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function initializeCentroids($jumlahCluster)
    {
        // Get random penduduk records for initial centroids
        $randomPenduduk = Penduduk::inRandomOrder()->take($jumlahCluster)->get();
        
        foreach ($randomPenduduk as $index => $p) {
            Centroid::create([
                'nama_centroid' => 'C' . ($index + 1),
                'usia' => $p->usia,
                'tanggungan_num' => $this->getTanggunganValue($p->tanggungan),
                'kondisi_rumah' => $p->kondisi_rumah,
                'status_kepemilikan' => $p->status_kepemilikan,
                'penghasilan_num' => $this->getPenghasilanValue($p->penghasilan),
                'tahun' => $p->tahun,
                'periode' => 1
            ]);
        }
    }

    public function reset()
    {
        try {
            // Delete all clustering results first (child table)
            HasilKmeans::query()->delete();
            
            // Then delete all centroids (parent table)
            Centroid::query()->delete();
            
            return redirect()->route('admin.clustering.index')
                ->with('success', 'Semua data clustering telah direset.');
        } catch (\Exception $e) {
            return redirect()->route('admin.clustering.index')
                ->with('error', 'Gagal mereset data: ' . $e->getMessage());
        }
    }

    private function convertToNumeric($value)
    {
        if (is_numeric($value)) {
            return (float)$value;
        }
        // Remove any non-numeric characters except decimal point
        $numeric = preg_replace('/[^0-9.]/', '', $value);
        return $numeric ? (float)$numeric : 0;
    }

    private function determineClusterNumber($centroid, $distances)
    {
        // Find the index of the minimum distance
        $minDistanceIndex = array_search(min($distances), $distances);
        
        // Return cluster number (1-based index)
        return $minDistanceIndex + 1;
    }

    public function getUsiaValue($usia)
    {
        if ($usia >= 15 && $usia <= 25) return 1;
        if ($usia >= 26 && $usia <= 35) return 2;
        if ($usia >= 36 && $usia <= 45) return 3;
        if ($usia > 46) return 4;
        return 1; // default
    }

    public function getTanggunganValue($tanggungan)
    {
        if ($tanggungan == 1) return 1;
        if ($tanggungan == 2) return 2;
        if ($tanggungan == 3) return 3;
        if ($tanggungan > 3) return 4;
        return 1; // default
    }

    public function getKondisiRumahValue($kondisiRumah)
    {
        switch (strtolower($kondisiRumah)) {
            case 'baik':
                return 1;
            case 'cukup':
                return 2;
            case 'kurang':
                return 3;
            default:
                return 2; // default cukup
        }
    }

    public function getStatusKepemilikanValue($statusKepemilikan)
    {
        switch (strtolower($statusKepemilikan)) {
            case 'hak milik':
                return 1;
            case 'numpang':
                return 2;
            case 'sewa':
                return 3;
            default:
                return 1; // default hak milik
        }
    }

    public function getPenghasilanValue($penghasilan)
    {
        if ($penghasilan > 4000000) return 1;
        if ($penghasilan >= 3000000 && $penghasilan <= 4000000) return 2;
        if ($penghasilan >= 2000000 && $penghasilan <= 3000000) return 3;
        if ($penghasilan >= 1000000 && $penghasilan <= 2000000) return 4;
        return 4; // default
    }

    public function convertKondisiRumah($kondisi)
    {
        return match(strtolower($kondisi)) {
            'baik' => 3,
            'cukup' => 2,
            'kurang' => 1,
            default => 2
        };
    }

    public function convertStatusKepemilikan($status)
    {
        return match(strtolower($status)) {
            'hak milik' => 3,
            'sewa' => 2,
            'numpang' => 1,
            default => 1
        };
    }

    private function getNilaiKriteria($kriteria, $index)
    {
        if (!$kriteria) return 0;
        
        // Ambil nilai dari kriteria berdasarkan index
        $nilaiKriteria = json_decode($kriteria->nilai, true);
        if (isset($nilaiKriteria[$index])) {
            return $nilaiKriteria[$index]['nilai'];
        }
        
        // Jika tidak ada nilai spesifik, gunakan nilai default
        return $index + 1;
    }

    private function getKondisiRumahText($nilai)
    {
        switch ($nilai) {
            case 1:
                return 'baik';
            case 2:
                return 'cukup';
            case 3:
                return 'kurang';
            default:
                return 'cukup';
        }
    }

    private function getStatusKepemilikanText($nilai)
    {
        switch ($nilai) {
            case 1:
                return 'hak milik';
            case 2:
                return 'numpang';
            case 3:
                return 'sewa';
            default:
                return 'hak milik';
        }
    }

    private function hitungJarak(
        $usia1, $tanggungan1, $kondisiRumah1, $statusKepemilikan1, $penghasilan1,
        $usia2, $tanggungan2, $kondisiRumah2, $statusKepemilikan2, $penghasilan2
    ) {
        // Hitung jarak Euclidean untuk setiap atribut
        $jarakUsia = pow($usia1 - $usia2, 2);
        $jarakTanggungan = pow($tanggungan1 - $tanggungan2, 2);
        $jarakKondisiRumah = pow($kondisiRumah1 - $kondisiRumah2, 2);
        $jarakStatusKepemilikan = pow($statusKepemilikan1 - $statusKepemilikan2, 2);
        $jarakPenghasilan = pow($penghasilan1 - $penghasilan2, 2);

        // Hitung total jarak
        $totalJarak = sqrt(
            $jarakUsia +
            $jarakTanggungan +
            $jarakKondisiRumah +
            $jarakStatusKepemilikan +
            $jarakPenghasilan
        );

        return $totalJarak;
    }

    private function tentukanKelayakan($cluster)
    {
        // Sesuaikan dengan hasil clustering yang diinginkan
        switch ($cluster) {
            case 1: // Cluster 1 (C1) - Sangat membutuhkan bantuan
                return 'Layak';
            case 2: // Cluster 2 (C2) - Tidak membutuhkan bantuan
                return 'Tidak Layak';
            case 3: // Cluster 3 (C3) - Prioritas sedang
                return 'Layak';
            default:
                return 'Tidak Layak';
        }
    }

    private function adaPerubahan($centroidLama, $centroidBaru)
    {
        // Tentukan threshold perubahan
        $threshold = 0.0001;

        // Cek perubahan untuk setiap atribut
        $perubahanUsia = abs($centroidLama['usia'] - $centroidBaru['usia']);
        $perubahanTanggungan = abs($centroidLama['tanggungan'] - $centroidBaru['tanggungan']);
        $perubahanKondisiRumah = abs($centroidLama['kondisi_rumah'] - $centroidBaru['kondisi_rumah']);
        $perubahanStatusKepemilikan = abs($centroidLama['status_kepemilikan'] - $centroidBaru['status_kepemilikan']);
        $perubahanPenghasilan = abs($centroidLama['penghasilan'] - $centroidBaru['penghasilan']);

        // Jika ada perubahan yang melebihi threshold, return true
        return $perubahanUsia > $threshold ||
               $perubahanTanggungan > $threshold ||
               $perubahanKondisiRumah > $threshold ||
               $perubahanStatusKepemilikan > $threshold ||
               $perubahanPenghasilan > $threshold;
    }
}