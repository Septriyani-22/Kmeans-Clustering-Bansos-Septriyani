<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Centroid;
use App\Models\Hasil;
use App\Models\Iterasi;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClusteringController extends Controller
{
    public function index()
    {
        $penduduks = Penduduk::all();
        $centroids = Centroid::all();
        $iterasi = Iterasi::orderBy('iterasi', 'desc')->first();
        $hasil = Hasil::with('penduduk')->get();

        return view('admin.clustering.index', compact('penduduks', 'centroids', 'iterasi', 'hasil'));
    }

    public function process()
    {
        try {
            DB::beginTransaction();

            // Clear previous results
            Hasil::truncate();
            Iterasi::truncate();
            Centroid::truncate();

            // Get all penduduk
            $penduduks = Penduduk::all();
            if ($penduduks->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data penduduk yang dapat diproses');
            }

            // Initialize centroids
            $initialCentroids = Centroid::getInitialCentroids();
            foreach ($initialCentroids as $centroid) {
                Centroid::create($centroid);
            }

            $maxIterations = 100;
            $iteration = 0;
            $isConverged = false;

            while (!$isConverged && $iteration < $maxIterations) {
                $iteration++;
                $isConverged = true;

                // Assign clusters
                foreach ($penduduks as $penduduk) {
                    $minDistance = PHP_FLOAT_MAX;
                    $nearestCluster = null;

                    foreach (Centroid::all() as $centroid) {
                        $distance = $centroid->calculateDistance($penduduk);
                        if ($distance < $minDistance) {
                            $minDistance = $distance;
                            $nearestCluster = $centroid->cluster;
                        }
                    }

                    // Save result
                    Hasil::create([
                        'penduduk_id' => $penduduk->id,
                        'cluster' => $nearestCluster,
                        'jarak' => $minDistance,
                        'iterasi' => $iteration
                    ]);

                    // Update penduduk cluster
                    $penduduk->cluster = $nearestCluster;
                    $penduduk->save();
                }

                // Update centroids
                foreach (Centroid::all() as $centroid) {
                    $clusterPenduduks = Penduduk::where('cluster', $centroid->cluster)->get();
                    
                    if ($clusterPenduduks->isNotEmpty()) {
                        $oldCentroid = $centroid->toArray();
                        $centroid->updateCentroid($clusterPenduduks);
                        
                        // Check convergence
                        if ($oldCentroid['usia'] != $centroid->usia ||
                            $oldCentroid['tanggungan'] != $centroid->tanggungan ||
                            $oldCentroid['kondisi_rumah'] != $centroid->kondisi_rumah ||
                            $oldCentroid['status_kepemilikan'] != $centroid->status_kepemilikan ||
                            $oldCentroid['penghasilan'] != $centroid->penghasilan) {
                            $isConverged = false;
                        }
                    }
                }

                // Save iteration
                foreach (Centroid::all() as $centroid) {
                    Iterasi::create([
                        'iterasi' => $iteration,
                        'cluster' => $centroid->cluster,
                        'usia' => $centroid->usia,
                        'tanggungan' => $centroid->tanggungan,
                        'kondisi_rumah' => $centroid->kondisi_rumah,
                        'status_kepemilikan' => $centroid->status_kepemilikan,
                        'penghasilan' => $centroid->penghasilan
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.clustering.index')->with('success', 'Proses clustering berhasil');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            DB::beginTransaction();

            // Reset all clustering data
            Hasil::truncate();
            Iterasi::truncate();
            Centroid::truncate();
            Penduduk::query()->update(['cluster' => null]);

            DB::commit();
            return redirect()->route('admin.clustering.index')->with('success', 'Data clustering berhasil direset');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 