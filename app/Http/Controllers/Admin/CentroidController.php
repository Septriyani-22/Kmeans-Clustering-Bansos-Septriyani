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

        // Convert raw data to numerical values
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
        $centroids = MappingCentroid::all();
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
            foreach ($centroids as $index => $centroid) {
                $distance = sqrt(
                    pow($penduduk['usia'] - floatval($centroid->usia), 2) +
                    pow($penduduk['jumlah_tanggungan'] - floatval($centroid->jumlah_tanggungan), 2) +
                    pow($penduduk['kondisi_rumah'] - floatval($centroid->kondisi_rumah), 2) +
                    pow($penduduk['status_kepemilikan'] - floatval($centroid->status_kepemilikan), 2) +
                    pow($penduduk['jumlah_penghasilan'] - floatval($centroid->jumlah_penghasilan), 2)
                );
                // Ensure no null values interfere
                $distance = is_nan($distance) ? 0 : $distance;
                // Store distances
                switch($index) {
                    case 0: $c1Distances[$penduduk['id']] = number_format($distance, 9, '.', ''); break;
                    case 1: $c2Distances[$penduduk['id']] = number_format($distance, 9, '.', ''); break;
                    case 2: $c3Distances[$penduduk['id']] = number_format($distance, 9, '.', ''); break;
                }
            }
            // Assign cluster based on minimum distance
            $distances = [$c1Distances[$penduduk['id']], $c2Distances[$penduduk['id']], $c3Distances[$penduduk['id']]];
            $minDistance = min($distances);
            $clusterIndex = array_search($minDistance, $distances);
            $cluster = 'C' . ($clusterIndex + 1);
            $distanceResults[] = [
                'penduduk' => (object)['id' => $penduduk['id'], 'nama' => $penduduk['nama']],
                'c1_distance' => $c1Distances[$penduduk['id']],
                'c2_distance' => $c2Distances[$penduduk['id']],
                'c3_distance' => $c3Distances[$penduduk['id']],
                'min_distance' => $minDistance,
                'cluster' => $cluster
            ];
        }        session([
            'distanceResults' => $distanceResults,
            'c1Distances' => $c1Distances,
            'c2Distances' => $c2Distances,
            'c3Distances' => $c3Distances
        ]);

        return redirect()->route('admin.centroid.index')
            ->with('success', 'Distance calculation completed successfully.');

    }
} 