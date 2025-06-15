<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Centroid;
use App\Models\Penduduk;
use App\Models\Kriteria;
use App\Http\Controllers\Admin\ClusteringController;
use App\Models\MappingCentroid;
use App\Http\Controllers\Admin\MappingCentroidController;
use Illuminate\Support\Facades\DB;
use App\Models\HasilKmeans;

class CentroidController extends Controller
{
    protected $clusteringController;

    public function __construct(ClusteringController $clusteringController)
    {
        $this->clusteringController = $clusteringController;
    }

    private function getNilaiKriteria($tipe, $value)
    {
        // Get parent kriteria
        $kriteria = Kriteria::where('nama', $tipe)->first();
        if (!$kriteria) return null;

        // Get nilai kriteria based on value
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

    public function index()
    {
        $centroids = Centroid::all();
        $mappings = MappingCentroid::all();
        $penduduks = Penduduk::all();
        $distanceResults = session('distanceResults', []);

        // Convert raw data to numerical values
        $convertedPenduduks = $penduduks->map(function($penduduk) {
            return [
                'id' => $penduduk->id,
                'nama' => $penduduk->nama,
                'usia' => $this->getNilaiKriteria('Usia', $penduduk->usia),
                'jumlah_tanggungan' => $this->getNilaiKriteria('Jumlah Tanggungan', $penduduk->jumlah_tanggungan),
                'kondisi_rumah' => $this->getNilaiKriteria('Kondisi Rumah', strtolower($penduduk->kondisi_rumah)),
                'status_kepemilikan' => $this->getNilaiKriteria('Status Kepemilikan', strtolower($penduduk->status_kepemilikan)),
                'jumlah_penghasilan' => $this->getNilaiKriteria('Penghasilan', $penduduk->jumlah_penghasilan),
                'usia_raw' => $penduduk->usia,
                'jumlah_tanggungan_raw' => $penduduk->jumlah_tanggungan,
                'kondisi_rumah_raw' => $penduduk->kondisi_rumah,
                'status_kepemilikan_raw' => $penduduk->status_kepemilikan,
                'jumlah_penghasilan_raw' => $penduduk->jumlah_penghasilan
            ];
        });

        return view('admin.centroid.index', compact('centroids', 'mappings', 'penduduks', 'convertedPenduduks', 'distanceResults'));
    }

    public function create()
    {
        return view('admin.centroid.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'usia' => 'required|numeric',
            'tanggungan_num' => 'required|numeric',
            'kondisi_rumah' => 'required',
            'status_kepemilikan' => 'required',
            'penghasilan_num' => 'required|numeric'
        ]);

        Centroid::create([
            'usia' => $request->usia,
            'tanggungan_num' => $request->tanggungan_num,
            'kondisi_rumah' => $request->kondisi_rumah,
            'status_kepemilikan' => $request->status_kepemilikan,
            'penghasilan_num' => $request->penghasilan_num,
            'tahun' => date('Y'),
            'periode' => 1
        ]);

        return redirect()->route('admin.centroid.index')
            ->with('success', 'Centroid berhasil ditambahkan');
    }

    public function update(Request $request, Centroid $centroid)
    {
        $request->validate([
            'usia' => 'required|numeric',
            'tanggungan_num' => 'required|numeric',
            'kondisi_rumah' => 'required',
            'status_kepemilikan' => 'required',
            'penghasilan_num' => 'required|numeric'
        ]);

        $centroid->update([
            'usia' => $request->usia,
            'tanggungan_num' => $request->tanggungan_num,
            'kondisi_rumah' => $request->kondisi_rumah,
            'status_kepemilikan' => $request->status_kepemilikan,
            'penghasilan_num' => $request->penghasilan_num
        ]);

        return redirect()->route('admin.centroid.index')
            ->with('success', 'Centroid berhasil diperbarui');
    }

    public function destroy(Centroid $centroid)
    {
        $centroid->delete();

        return redirect()->route('admin.centroid.index')
            ->with('success', 'Centroid berhasil dihapus');
    }

    public function edit(Centroid $centroid)
    {
        return view('admin.centroid.edit', compact('centroid'));
    }

    public function storeMapping(Request $request)
    {
        $request->validate([
            'data_ke' => 'required|exists:penduduks,id',
            'cluster' => 'required|in:C1,C2,C3'
        ]);

        $penduduk = Penduduk::findOrFail($request->data_ke);

        MappingCentroid::create([
            'data_ke' => $penduduk->id,
            'nama_penduduk' => $penduduk->nama,
            'cluster' => $request->cluster,
            'usia' => $penduduk->usia,
            'jumlah_tanggungan' => $penduduk->jumlah_tanggungan,
            'kondisi_rumah' => $penduduk->kondisi_rumah,
            'status_kepemilikan' => $penduduk->status_kepemilikan,
            'jumlah_penghasilan' => $penduduk->jumlah_penghasilan
        ]);

        return response()->json(['success' => true]);
    }

    public function updateMapping(Request $request, MappingCentroid $mapping)
    {
        $request->validate([
            'data_ke' => 'required|exists:penduduks,id',
            'cluster' => 'required|in:C1,C2,C3'
        ]);

        $penduduk = Penduduk::findOrFail($request->data_ke);

        $mapping->update([
            'data_ke' => $penduduk->id,
            'nama_penduduk' => $penduduk->nama,
            'cluster' => $request->cluster,
            'usia' => $penduduk->usia,
            'jumlah_tanggungan' => $penduduk->jumlah_tanggungan,
            'kondisi_rumah' => $penduduk->kondisi_rumah,
            'status_kepemilikan' => $penduduk->status_kepemilikan,
            'jumlah_penghasilan' => $penduduk->jumlah_penghasilan
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyMapping(MappingCentroid $mapping)
    {
        $mapping->delete();
        return response()->json(['success' => true]);
    }

    public function calculateDistances()
    {
        $centroids = Centroid::all();
        $penduduks = Penduduk::all();
        $distanceResults = [];

        foreach ($penduduks as $penduduk) {
            $distances = [];
            foreach ($centroids as $centroid) {
                // Convert string values to numeric
                $usiaPenduduk = floatval($penduduk->usia);
                $usiaCentroid = floatval($centroid->usia);
                
                // Gunakan nilai sebenarnya untuk tanggungan
                $tanggunganPenduduk = floatval($penduduk->jumlah_tanggungan);
                $tanggunganCentroid = floatval($centroid->tanggungan_num);
                
                $kondisiRumahPenduduk = $this->getNilaiKriteria('Kondisi Rumah', strtolower($penduduk->kondisi_rumah)) ?? 1;
                $kondisiRumahCentroid = $this->getNilaiKriteria('Kondisi Rumah', strtolower($centroid->kondisi_rumah)) ?? 1;
                
                $statusKepemilikanPenduduk = $this->getNilaiKriteria('Status Kepemilikan', strtolower($penduduk->status_kepemilikan)) ?? 1;
                $statusKepemilikanCentroid = $this->getNilaiKriteria('Status Kepemilikan', strtolower($centroid->status_kepemilikan)) ?? 1;
                
                // Gunakan nilai sebenarnya untuk penghasilan
                $penghasilanPenduduk = floatval($penduduk->jumlah_penghasilan);
                $penghasilanCentroid = floatval($centroid->penghasilan_num);

                $distance = sqrt(
                    pow($usiaPenduduk - $usiaCentroid, 2) +
                    pow($tanggunganPenduduk - $tanggunganCentroid, 2) +
                    pow($kondisiRumahPenduduk - $kondisiRumahCentroid, 2) +
                    pow($statusKepemilikanPenduduk - $statusKepemilikanCentroid, 2) +
                    pow($penghasilanPenduduk - $penghasilanCentroid, 2)
                );
                $distances[] = $distance;
            }
            $distanceResults[] = [
                'penduduk' => $penduduk,
                'distances' => $distances
            ];
        }

        session(['distanceResults' => $distanceResults]);

        return redirect()->route('admin.centroid.index')
            ->with('success', 'Jarak berhasil dihitung');
    }

    public function prosesClustering(Request $request, $penduduks, $kriteria)
    {
        try {
            // Inisialisasi centroid awal dengan nilai dari kriteria
            $centroids = [];
            $jumlahCluster = $request->jumlah_cluster;
            
            // Ambil beberapa data penduduk secara acak untuk centroid awal
            $randomPenduduks = $penduduks->random($jumlahCluster);
            
            foreach ($randomPenduduks as $index => $penduduk) {
                $centroids[] = [
                    'usia' => floatval($penduduk->usia),
                    'tanggungan_num' => floatval($penduduk->jumlah_tanggungan),
                    'kondisi_rumah' => $this->getNilaiKriteria('Kondisi Rumah', strtolower($penduduk->kondisi_rumah)) ?? 1,
                    'status_kepemilikan' => $this->getNilaiKriteria('Status Kepemilikan', strtolower($penduduk->status_kepemilikan)) ?? 1,
                    'penghasilan_num' => floatval($penduduk->jumlah_penghasilan),
                    'tahun' => date('Y'),
                    'periode' => 1
                ];
            }
            
            // Simpan centroid awal ke database
            foreach ($centroids as $centroid) {
                Centroid::create($centroid);
            }
            
            // Hitung jarak
            $this->calculateDistances();
            
            return redirect()->route('admin.centroid.index')
                ->with('success', 'Clustering berhasil dilakukan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

    private function tentukanKelayakan($cluster)
    {
        switch ($cluster) {
            case 1:
                return 'Layak';
            case 2:
                return 'Tidak Layak';
            case 3:
                return 'Layak';
            default:
                return 'Tidak Layak';
        }
    }

    private function hitungJarak(
        $usia1, $tanggungan1, $kondisiRumah1, $statusKepemilikan1, $penghasilan1,
        $usia2, $tanggungan2, $kondisiRumah2, $statusKepemilikan2, $penghasilan2
    ) {
        return sqrt(
            pow($usia1 - $usia2, 2) +
            pow($tanggungan1 - $tanggungan2, 2) +
            pow($kondisiRumah1 - $kondisiRumah2, 2) +
            pow($statusKepemilikan1 - $statusKepemilikan2, 2) +
            pow($penghasilan1 - $penghasilan2, 2)
        );
    }

    private function adaPerubahan($centroidLama, $centroidBaru)
    {
        $threshold = 0.0001;
        return abs($centroidLama['usia'] - $centroidBaru['usia']) > $threshold ||
               abs($centroidLama['jumlah_tanggungan'] - $centroidBaru['jumlah_tanggungan']) > $threshold ||
               abs($centroidLama['kondisi_rumah'] - $centroidBaru['kondisi_rumah']) > $threshold ||
               abs($centroidLama['status_kepemilikan'] - $centroidBaru['status_kepemilikan']) > $threshold ||
               abs($centroidLama['penghasilan'] - $centroidBaru['penghasilan']) > $threshold;
    }
} 