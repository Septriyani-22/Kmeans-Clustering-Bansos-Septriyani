<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Centroid;
use App\Models\HasilKmeans;
use App\Models\Kriteria;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClusteringController extends Controller
{
    private $kriteria;

    public function __construct()
    {
        $this->kriteria = Kriteria::where('is_aktif', true)->get();
    }

    public function index()
    {
        $centroids = Centroid::all();
        $normalizedData = $this->normalizeData();

        return view('admin.clustering.index', compact('centroids', 'normalizedData'));
    }

    private function normalizeData()
    {
        $penduduk = Penduduk::all();
        $normalizedData = [];

        foreach ($penduduk as $p) {
            $normalized = Centroid::normalizeValues(
                $p->penghasilan,
                $p->tanggungan,
                $p->usia,
                $p->kondisi_rumah,
                $p->status_kepemilikan
            );
            $nearestCentroid = $this->findNearestCentroid(
                $normalized['penghasilan_normal'],
                $normalized['tanggungan_normal'],
                $normalized['usia_normal'],
                $normalized['kondisi_rumah_normal'],
                $normalized['status_kepemilikan_normal']
            );

            $normalizedData[] = [
                'nik' => $p->nik,
                'nama' => $p->nama,
                'penghasilan_normal' => $normalized['penghasilan_normal'],
                'tanggungan_normal' => $normalized['tanggungan_normal'],
                'usia_normal' => $normalized['usia_normal'],
                'kondisi_rumah_normal' => $normalized['kondisi_rumah_normal'],
                'status_kepemilikan_normal' => $normalized['status_kepemilikan_normal'],
                'cluster' => $nearestCentroid ? $nearestCentroid->nama_centroid : 'N/A'
            ];
        }

        return $normalizedData;
    }

    private function findNearestCentroid($penghasilanNormal, $tanggunganNormal, $usiaNormal, $kondisiRumahNormal, $statusKepemilikanNormal)
    {
        $centroids = Centroid::all();
        $minDistance = PHP_FLOAT_MAX;
        $nearestCentroid = null;

        foreach ($centroids as $centroid) {
            $distance = sqrt(
                pow($penghasilanNormal - $centroid->penghasilan_num, 2) +
                pow($tanggunganNormal - $centroid->tanggungan_num, 2) +
                pow($usiaNormal - $centroid->usia_num, 2) +
                pow($kondisiRumahNormal - $centroid->kondisi_rumah_num, 2) +
                pow($statusKepemilikanNormal - $centroid->status_kepemilikan_num, 2)
            );

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearestCentroid = $centroid;
            }
        }

        return $nearestCentroid;
    }

    public function proses()
    {
        try {
            // Clear previous results
            HasilKmeans::truncate();

            // Validate that we have criteria
            if ($this->kriteria->isEmpty()) {
                throw new \Exception('Tidak ada data kriteria yang aktif. Silakan tambahkan data kriteria terlebih dahulu.');
            }

            $penduduk = Penduduk::all();
            $currentCentroids = Centroid::all()->keyBy('id')->toArray();

            if (empty($currentCentroids)) {
                throw new \Exception('Tidak ada data centroid. Silakan tambahkan data centroid terlebih dahulu.');
            }

            $maxIterations = 100; // Maximum iterations to prevent infinite loops
            $convergenceThreshold = 0.001; // Minimum change in centroids to consider convergence

            for ($iteration = 0; $iteration < $maxIterations; $iteration++) {
                $clusters = []; // To store assigned data points for this iteration
                $totalDistanceChange = 0;

                // Assignment Step: Assign each data point to the nearest centroid
                foreach ($penduduk as $p) {
                    $normalized = Centroid::normalizeValues(
                        $p->penghasilan,
                        $p->tanggungan,
                        $p->usia,
                        $p->kondisi_rumah,
                        $p->status_kepemilikan
                    );

                    $nearestCentroidId = null;
                    $minDistance = PHP_FLOAT_MAX;

                    foreach ($currentCentroids as $centroidId => $centroid) {
                        $distance = sqrt(
                            pow($normalized['penghasilan_normal'] - $centroid['penghasilan_num'], 2) +
                            pow($normalized['tanggungan_normal'] - $centroid['tanggungan_num'], 2) +
                            pow($normalized['usia_normal'] - $centroid['usia_num'], 2) +
                            pow($normalized['kondisi_rumah_normal'] - $centroid['kondisi_rumah_num'], 2) +
                            pow($normalized['status_kepemilikan_normal'] - $centroid['status_kepemilikan_num'], 2)
                        );

                        if ($distance < $minDistance) {
                            $minDistance = $distance;
                            $nearestCentroidId = $centroidId;
                        }
                    }

                    if ($nearestCentroidId !== null) {
                        $clusters[$nearestCentroidId][] = $p;
                    } else {
                        // Handle case where no nearest centroid is found (shouldn't happen with valid centroids)
                        throw new \Exception('Tidak dapat menemukan centroid terdekat untuk penduduk: ' . $p->nama);
                    }
                }

                $newCentroids = [];
                $converged = true;

                // Update Step: Recalculate centroids based on assigned data points
                foreach ($currentCentroids as $centroidId => $oldCentroid) {
                    if (!isset($clusters[$centroidId])) {
                        // If a cluster has no assigned points, keep its centroid unchanged
                        $newCentroids[$centroidId] = $oldCentroid;
                        continue;
                    }

                    $clusterPoints = $clusters[$centroidId];
                    $count = count($clusterPoints);

                    $sumPenghasilan = 0;
                    $sumTanggungan = 0;
                    $sumUsia = 0;
                    $sumKondisiRumah = 0;
                    $sumStatusKepemilikan = 0;

                    foreach ($clusterPoints as $p) {
                        $sumPenghasilan += $p->penghasilan;
                        $sumTanggungan += $p->tanggungan;
                        $sumUsia += $p->usia;
                        $sumKondisiRumah += Centroid::mapKondisiRumah($p->kondisi_rumah); // Use mapped values
                        $sumStatusKepemilikan += Centroid::mapStatusKepemilikan($p->status_kepemilikan); // Use mapped values
                    }

                    // Calculate new means
                    $newPenghasilan = $count > 0 ? $sumPenghasilan / $count : $oldCentroid['penghasilan_num'];
                    $newTanggungan = $count > 0 ? $sumTanggungan / $count : $oldCentroid['tanggungan_num'];
                    $newUsia = $count > 0 ? $sumUsia / $count : $oldCentroid['usia_num'];
                    $newKondisiRumah = $count > 0 ? $sumKondisiRumah / $count : $oldCentroid['kondisi_rumah_num'];
                    $newStatusKepemilikan = $count > 0 ? $sumStatusKepemilikan / $count : $oldCentroid['status_kepemilikan_num'];

                    $newCentroids[$centroidId] = [
                        'id' => $oldCentroid['id'],
                        'nama_centroid' => $oldCentroid['nama_centroid'],
                        'penghasilan_num' => $newPenghasilan,
                        'tanggungan_num' => $newTanggungan,
                        'usia_num' => $newUsia,
                        'kondisi_rumah_num' => $newKondisiRumah,
                        'status_kepemilikan_num' => $newStatusKepemilikan,
                        'tahun' => $oldCentroid['tahun'], // Preserve other fields
                        'periode' => $oldCentroid['periode'],
                        'keterangan' => $oldCentroid['keterangan'],
                    ];

                    // Check for convergence: compare old and new centroid positions
                    $change = sqrt(
                        pow($newPenghasilan - $oldCentroid['penghasilan_num'], 2) +
                        pow($newTanggungan - $oldCentroid['tanggungan_num'], 2) +
                        pow($newUsia - $oldCentroid['usia_num'], 2) +
                        pow($newKondisiRumah - $oldCentroid['kondisi_rumah_num'], 2) +
                        pow($newStatusKepemilikan - $oldCentroid['status_kepemilikan_num'], 2)
                    );

                    if ($change > $convergenceThreshold) {
                        $converged = false;
                    }
                }

                // Update centroids in the database for the next iteration (or final storage)
                foreach ($newCentroids as $centroidId => $data) {
                    Centroid::where('id', $centroidId)->update([ 
                        'penghasilan_num' => $data['penghasilan_num'],
                        'tanggungan_num' => $data['tanggungan_num'],
                        'usia_num' => $data['usia_num'],
                        'kondisi_rumah_num' => $data['kondisi_rumah_num'],
                        'status_kepemilikan_num' => $data['status_kepemilikan_num'],
                    ]);
                }

                $currentCentroids = $newCentroids; // Update current centroids for next iteration

                if ($converged) {
                    break; // K-Means converged
                }
            }

            // After convergence, save final results to HasilKmeans
            foreach ($penduduk as $p) {
                $normalized = Centroid::normalizeValues(
                    $p->penghasilan,
                    $p->tanggungan,
                    $p->usia,
                    $p->kondisi_rumah,
                    $p->status_kepemilikan
                );

                $nearestCentroidId = null;
                $minDistance = PHP_FLOAT_MAX;
                $finalCentroid = null;

                foreach ($currentCentroids as $centroidId => $centroid) {
                    $distance = sqrt(
                        pow($normalized['penghasilan_normal'] - $centroid['penghasilan_num'], 2) +
                        pow($normalized['tanggungan_normal'] - $centroid['tanggungan_num'], 2) +
                        pow($normalized['usia_normal'] - $centroid['usia_num'], 2) +
                        pow($normalized['kondisi_rumah_normal'] - $centroid['kondisi_rumah_num'], 2) +
                        pow($normalized['status_kepemilikan_normal'] - $centroid['status_kepemilikan_num'], 2)
                    );

                    if ($distance < $minDistance) {
                        $minDistance = $distance;
                        $nearestCentroidId = $centroidId;
                        $finalCentroid = $centroid;
                    }
                }

                if (!$finalCentroid) {
                    throw new \Exception('Tidak dapat menemukan centroid terdekat untuk penduduk: ' . $p->nama);
                }

                // Calculate scores using mapped numerical values directly from Penduduk or Centroid helpers
                $skorPenghasilan = $this->calculatePenghasilanScore($p->penghasilan);
                $skorTanggungan = $this->calculateTanggunganScore($p->tanggungan);
                $skorKondisiRumah = $this->calculateKondisiRumahScore($p->kondisi_rumah);
                $skorStatusKepemilikan = $this->calculateStatusKepemilikanScore($p->status_kepemilikan);
                $skorUsia = $this->calculateUsiaScore($p->usia);

                $totalSkor = $skorPenghasilan + $skorTanggungan + $skorKondisiRumah + $skorStatusKepemilikan + $skorUsia;

                // Adjust threshold to 45 for more balanced results
                $kelayakan = $totalSkor >= 45 ? 'Layak' : 'Tidak Layak';

                // Ensure cluster name is set and not empty
                $clusterName = $finalCentroid['nama_centroid'];
                if (empty($clusterName)) {
                    throw new \Exception('Nama centroid tidak boleh kosong untuk centroid ID: ' . $finalCentroid['id']);
                }

                // Create the HasilKmeans record with all required fields
                HasilKmeans::create([
                    'penduduk_id' => $p->id,
                    'centroid_id' => $finalCentroid['id'],
                    'cluster' => $clusterName,
                    'skor_kelayakan' => $totalSkor,
                    'skor_penghasilan' => $skorPenghasilan,
                    'skor_tanggungan' => $skorTanggungan,
                    'skor_kondisi_rumah' => $skorKondisiRumah,
                    'skor_status_kepemilikan' => $skorStatusKepemilikan,
                    'skor_usia' => $skorUsia,
                    'kelayakan' => $kelayakan,
                    'nik' => $p->nik,
                    'nama' => $p->nama,
                    'tahun' => $p->tahun,
                    'periode' => date('Y') // Assuming current year as period, adjust if needed
                ]);
            }

            return redirect()->route('admin.hasil-kmeans.index')->with('success', 'Proses clustering berhasil dilakukan');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function calculatePenghasilanScore($penghasilan)
    {
        $kriteria = $this->kriteria->where('nama_kriteria', 'Penghasilan')->first();
        if (!$kriteria) {
            return 0;
        }

        $penghasilanNum = floatval($penghasilan);

        // Apply scoring logic based on thesis Tabel 3.6 (lower income = higher score)
        if ($penghasilanNum >= 1000000 && $penghasilanNum <= 2000000) {
            return $kriteria->nilai; // Corresponds to Value 4 (highest need/score)
        } elseif ($penghasilanNum > 2000000 && $penghasilanNum <= 3000000) {
            return $kriteria->nilai * 0.75; // Corresponds to Value 3
        } elseif ($penghasilanNum > 3000000 && $penghasilanNum <= 4000000) {
            return $kriteria->nilai * 0.5; // Corresponds to Value 2
        } elseif ($penghasilanNum > 4000000) {
            return $kriteria->nilai * 0.25; // Corresponds to Value 1 (lowest need/score)
        } else { // Handle cases less than 1,000,000, assuming high need
            return $kriteria->nilai; 
        }
    }

    private function calculateTanggunganScore($tanggungan)
    {
        $kriteria = $this->kriteria->where('nama_kriteria', 'Tanggungan')->first();
        if (!$kriteria) {
            return 0;
        }

        $tanggunganNum = intval($tanggungan);

        // Apply scoring logic based on thesis (higher dependents = higher score)
        if ($tanggunganNum >= 5) {
            return $kriteria->nilai;
        } elseif ($tanggunganNum >= 3) {
            return $kriteria->nilai * 0.8;
        } elseif ($tanggunganNum >= 2) {
            return $kriteria->nilai * 0.6;
        } else { // tanggungan = 1
            return $kriteria->nilai * 0.4;
        }
    }

    private function calculateKondisiRumahScore($kondisiRumah)
    {
        $kriteria = $this->kriteria->where('nama_kriteria', 'Kondisi Rumah')->first();
        if (!$kriteria) {
            return 0;
        }

        // Use the mapped numerical value from Centroid model
        $kondisiRumahMapped = Centroid::mapKondisiRumah($kondisiRumah);

        switch ($kondisiRumahMapped) {
            case 3: // Kurang (3)
                return $kriteria->nilai;
            case 2: // Cukup (2)
                return $kriteria->nilai * 0.6;
            case 1: // Baik (1)
                return $kriteria->nilai * 0.3;
            default:
                return 0;
        }
    }

    private function calculateStatusKepemilikanScore($statusKepemilikan)
    {
        $kriteria = $this->kriteria->where('nama_kriteria', 'Status Kepemilikan Rumah')->first();
        if (!$kriteria) {
            return 0;
        }

        // Use the mapped numerical value from Centroid model
        $statusKepemilikanMapped = Centroid::mapStatusKepemilikan($statusKepemilikan);

        switch ($statusKepemilikanMapped) {
            case 1: // Hak Milik (1)
                return $kriteria->nilai * 0.3; 
            case 2: // Numpang (2)
                return $kriteria->nilai; 
            case 3: // Sewa (3)
                return $kriteria->nilai * 0.7; // Assuming 'sewa' is medium need between numpang and hak milik
            default:
                return 0;
        }
    }

    private function calculateUsiaScore($usia)
    {
        $kriteria = $this->kriteria->where('nama_kriteria', 'Umur')->first();
        if (!$kriteria) {
            return 0;
        }

        // Use the mapped numerical value from Centroid model
        $usiaMapped = Centroid::mapUsia($usia);

        switch ($usiaMapped) {
            case 4: // >46 Tahun (4)
                return $kriteria->nilai; 
            case 3: // 36-45 Tahun (3)
                return $kriteria->nilai * 0.75;
            case 2: // 25-35 Tahun (2)
                return $kriteria->nilai * 0.5;
            case 1: // 15-25 Tahun (1)
                return $kriteria->nilai * 0.25;
            default:
                return 0;
        }
    }

    public function reset()
    {
        HasilKmeans::truncate();
        return redirect()->route('admin.hasil-kmeans.index')->with('success', 'Data hasil clustering berhasil direset.');
    }
} 