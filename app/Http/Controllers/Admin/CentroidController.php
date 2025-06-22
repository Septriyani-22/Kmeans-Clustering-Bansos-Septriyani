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
        });

        return view('admin.centroid.index', compact('centroids', 'mappings', 'penduduks', 'convertedPenduduks', 'convertedMappings', 'distanceResults'));
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

    /**
     * Get the nilai_kriteria based on the type and value.
     *
     * @param string $tipe
     * @param mixed $value
     * @return mixed|null
     */

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
        // Define initial centroids based on Tabel 3.8 Centroid Awal
        $c1Centroid = (object)['usia' => 4, 'jumlah_tanggungan' => 3, 'kondisi_rumah' => 3, 'status_kepemilikan' => 2, 'jumlah_penghasilan' => 4];
        $c2Centroid = (object)['usia' => 4, 'jumlah_tanggungan' => 4, 'kondisi_rumah' => 2, 'status_kepemilikan' => 1, 'jumlah_penghasilan' => 4];
        $c3Centroid = (object)['usia' => 4, 'jumlah_tanggungan' => 3, 'kondisi_rumah' => 1, 'status_kepemilikan' => 1, 'jumlah_penghasilan' => 2];
    
        $penduduks = Penduduk::all();
        $distanceResults = [];
        $c1Distances = [];
        $c2Distances = [];
        $c3Distances = [];
    
        $convertedPenduduks = $penduduks->map(function($penduduk) {
            return [
                'id' => $penduduk->id,
                'nama' => $penduduk->nama,
                'usia' => $this->getNilaiKriteria('Usia', $penduduk->usia) ?? 0,
                'jumlah_tanggungan' => $this->getNilaiKriteria('Tanggungan', $penduduk->tanggungan) ?? 0,
                'kondisi_rumah' => $this->getNilaiKriteria('Kondisi Rumah', strtolower($penduduk->kondisi_rumah)) ?? 0,
                'status_kepemilikan' => $this->getNilaiKriteria('Status Kepemilikan', strtolower($penduduk->status_kepemilikan)) ?? 0,
                'jumlah_penghasilan' => $this->getNilaiKriteria('Penghasilan', $penduduk->penghasilan) ?? 0,
            ];
        });
    
        foreach ($convertedPenduduks as $penduduk) {
            $c1Distance = sqrt(
                pow($penduduk['usia'] - floatval($c1Centroid->usia), 2) +
                pow($penduduk['jumlah_tanggungan'] - floatval($c1Centroid->jumlah_tanggungan), 2) +
                pow($penduduk['kondisi_rumah'] - floatval($c1Centroid->kondisi_rumah), 2) +
                pow($penduduk['status_kepemilikan'] - floatval($c1Centroid->status_kepemilikan), 2) +
                pow($penduduk['jumlah_penghasilan'] - floatval($c1Centroid->jumlah_penghasilan), 2)
            );
            $c1Distance = is_nan($c1Distance) ? 0 : $c1Distance;
            $c1Distances[$penduduk['id']] = number_format($c1Distance, 9, '.', '');
    
            $c2Distance = sqrt(
                pow($penduduk['usia'] - floatval($c2Centroid->usia), 2) +
                pow($penduduk['jumlah_tanggungan'] - floatval($c2Centroid->jumlah_tanggungan), 2) +
                pow($penduduk['kondisi_rumah'] - floatval($c2Centroid->kondisi_rumah), 2) +
                pow($penduduk['status_kepemilikan'] - floatval($c2Centroid->status_kepemilikan), 2) +
                pow($penduduk['jumlah_penghasilan'] - floatval($c2Centroid->jumlah_penghasilan), 2)
            );
            $c2Distance = is_nan($c2Distance) ? 0 : $c2Distance;
            $c2Distances[$penduduk['id']] = number_format($c2Distance, 9, '.', '');
    
            $c3Distance = sqrt(
                pow($penduduk['usia'] - floatval($c3Centroid->usia), 2) +
                pow($penduduk['jumlah_tanggungan'] - floatval($c3Centroid->jumlah_tanggungan), 2) +
                pow($penduduk['kondisi_rumah'] - floatval($c3Centroid->kondisi_rumah), 2) +
                pow($penduduk['status_kepemilikan'] - floatval($c3Centroid->status_kepemilikan), 2) +
                pow($penduduk['jumlah_penghasilan'] - floatval($c3Centroid->jumlah_penghasilan), 2)
            );
            $c3Distance = is_nan($c3Distance) ? 0 : $c3Distance;
            $c3Distances[$penduduk['id']] = number_format($c3Distance, 9, '.', '');
    
            $distances = [$c1Distance, $c2Distance, $c3Distance];
            $minDistance = min($distances);
            $clusterIndex = array_keys($distances, $minDistance)[0];
            $cluster = 'C' . ($clusterIndex + 1);
            $distanceResults[] = [
                'penduduk' => (object)['id' => $penduduk['id'], 'nama' => $penduduk['nama']],
                'c1_distance' => $c1Distances[$penduduk['id']],
                'c2_distance' => $c2Distances[$penduduk['id']],
                'c3_distance' => $c3Distances[$penduduk['id']],
                'min_distance' => number_format($minDistance, 9, '.', ''),
                'cluster' => $cluster
            ];
        }
    
        session([
            'distanceResults' => $distanceResults,
            'c1Distances' => $c1Distances,
            'c2Distances' => $c2Distances,
            'c3Distances' => $c3Distances
        ]);
    
        return redirect()->route('admin.centroid.index')
            ->with('success', 'Distance calculation completed successfully.');
    }
}