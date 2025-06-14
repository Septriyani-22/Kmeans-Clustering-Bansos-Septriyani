<?php

use App\Http\Controllers\Admin\PendudukController as AdminPendudukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DataHasilController as AdminDataHasilController;
use App\Http\Controllers\Admin\LaporanHasilController as AdminLaporanHasilController;
use App\Http\Controllers\Admin\UserController as AdminUserController; 
use App\Http\Controllers\Admin\KriteriaController as AdminKriteriaController;
use App\Http\Controllers\Admin\CentroidController;
use App\Http\Controllers\Admin\ClusteringController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HasilKmeansController;
use App\Http\Controllers\Admin\MappingCentroidController;

use App\Http\Controllers\KepalaDesa\PendudukController as KepalaDesaPendudukController;
use App\Http\Controllers\KepalaDesa\DataHasilController as KepalaDesaDataHasilController;
use App\Http\Controllers\KepalaDesa\LaporanHasilController as KepalaDesaLaporanHasilController;
use App\Http\Controllers\KepalaDesa\UserController as KepalaDesaUserController; 
use App\Http\Controllers\KepalaDesa\KriteriaController as KepalaDesaKriteriaController;
use App\Http\Controllers\KepalaDesa\CentroidController as KepalaDesaCentroidController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/search/result', [App\Http\Controllers\SearchController::class, 'search'])->name('search.result');

Route::get('/informasi', function () {
    return view('informasi');
})->name('informasi');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::resource('users', AdminUserController::class);
    Route::put('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
    
    // Kriteria
    Route::prefix('kriteria')->name('kriteria.')->group(function () {
        Route::get('/', [AdminKriteriaController::class, 'index'])->name('index');
        Route::get('/create', [AdminKriteriaController::class, 'create'])->name('create');
        Route::post('/', [AdminKriteriaController::class, 'store'])->name('store');
        Route::get('/{kriteria}/edit', [AdminKriteriaController::class, 'edit'])->name('edit');
        Route::put('/{kriteria}', [AdminKriteriaController::class, 'update'])->name('update');
        Route::delete('/{kriteria}', [AdminKriteriaController::class, 'destroy'])->name('destroy');
    });
    
    // Penduduk
    Route::post('penduduk/import', [AdminPendudukController::class, 'import'])->name('penduduk.import');
    Route::get('penduduk/export', [AdminPendudukController::class, 'export'])->name('penduduk.export');
    Route::get('/penduduk/format', [App\Http\Controllers\Admin\PendudukController::class, 'format'])->name('penduduk.format');
    Route::get('penduduk/cetak', [AdminPendudukController::class, 'cetak'])->name('penduduk.cetak');
    Route::resource('penduduk', AdminPendudukController::class)->except(['show']);
    Route::get('penduduk/autocomplete', [AdminPendudukController::class, 'autocomplete'])->name('penduduk.autocomplete');

    // Data Hasil
    Route::get('datahasil', [AdminDataHasilController::class, 'index'])->name('datahasil.index');
    Route::get('datahasil/export', [AdminDataHasilController::class, 'export'])->name('datahasil.export');
    Route::get('datahasil/proses', [AdminDataHasilController::class, 'proses'])->name('datahasil.proses');
    
    // Laporan Hasil
    Route::get('laporanhasil', [AdminLaporanHasilController::class, 'index'])->name('laporanhasil.index');
    Route::get('laporanhasil/export', [AdminLaporanHasilController::class, 'export'])->name('laporanhasil.export');

    // Centroid
    Route::resource('centroid', CentroidController::class);

    // Clustering
    Route::prefix('clustering')->name('clustering.')->group(function () {
        Route::get('/', [ClusteringController::class, 'index'])->name('index');
        Route::post('/proses', [ClusteringController::class, 'proses'])->name('proses');
        Route::get('/reset', [ClusteringController::class, 'reset'])->name('reset');
    });

    // Hasil Kmeans
    Route::get('/hasil-kmeans', [HasilKmeansController::class, 'index'])->name('hasil-kmeans.index');
    Route::get('/hasil-kmeans/print', [HasilKmeansController::class, 'print'])->name('hasil-kmeans.print');
    Route::get('/hasil-kmeans/export', [HasilKmeansController::class, 'export'])->name('hasil-kmeans.export');

    Route::post('/penduduk/mass-update', [App\Http\Controllers\Admin\PendudukController::class, 'massUpdate'])->name('penduduk.mass-update');
    Route::post('/penduduk/mass-delete', [App\Http\Controllers\Admin\PendudukController::class, 'massDelete'])->name('penduduk.mass-delete');

    // Penduduk Routes
    Route::prefix('penduduk')->name('penduduk.')->group(function () {
        Route::get('/', [AdminPendudukController::class, 'index'])->name('index');
        Route::get('/create', [AdminPendudukController::class, 'create'])->name('create');
        Route::post('/', [AdminPendudukController::class, 'store'])->name('store');
        Route::get('/{penduduk}/edit', [AdminPendudukController::class, 'edit'])->name('edit');
        Route::put('/{penduduk}', [AdminPendudukController::class, 'update'])->name('update');
        Route::delete('/{penduduk}', [AdminPendudukController::class, 'destroy'])->name('destroy');
        Route::post('/import', [AdminPendudukController::class, 'import'])->name('import');
        Route::get('/export', [AdminPendudukController::class, 'export'])->name('export');
        Route::get('/print', [AdminPendudukController::class, 'print'])->name('print');
        Route::get('/template', [AdminPendudukController::class, 'template'])->name('template');
        Route::post('/clear-clusters', [AdminPendudukController::class, 'clearClusters'])->name('clear-clusters');
    });

    // Mapping Centroid Routes
    Route::resource('mapping-centroid', MappingCentroidController::class);
    Route::post('mapping-centroid/store-from-distance', [MappingCentroidController::class, 'storeFromDistanceResults'])
        ->name('mapping-centroid.store-from-distance');

    Route::get('/centroid/mapping/{mapping}/edit', [MappingCentroidController::class, 'edit'])->name('centroid.mapping.edit');
    Route::put('/centroid/mapping/{mapping}', [MappingCentroidController::class, 'update'])->name('centroid.mapping.update');
    Route::delete('/centroid/mapping/{mapping}', [MappingCentroidController::class, 'destroy'])->name('centroid.mapping.destroy');
    Route::post('/centroid/mapping/store-from-distance', [MappingCentroidController::class, 'storeFromDistance'])->name('centroid.mapping.store-from-distance');
});

Route::middleware(['auth', 'kepala_desa'])->prefix('kepala_desa')->name('kepala_desa.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::resource('users', KepalaDesaUserController::class);
    
    // Kriteria
    Route::resource('kriteria', KepalaDesaKriteriaController::class);
    
    // Penduduk
    Route::post('penduduk/import', [KepalaDesaPendudukController::class, 'import'])->name('penduduk.import');
    Route::get('penduduk/export', [KepalaDesaPendudukController::class, 'export'])->name('penduduk.export');
    Route::get('penduduk/format', [KepalaDesaPendudukController::class, 'format'])->name('penduduk.format');
    Route::get('penduduk/cetak', [KepalaDesaPendudukController::class, 'cetak'])->name('penduduk.cetak');
    Route::resource('penduduk', KepalaDesaPendudukController::class)->except(['show']);

    // Data Hasil
    Route::get('datahasil', [KepalaDesaDataHasilController::class, 'index'])->name('datahasil.index');
    Route::get('datahasil/export', [KepalaDesaDataHasilController::class, 'export'])->name('datahasil.export');
    Route::get('datahasil/proses', [KepalaDesaDataHasilController::class, 'proses'])->name('datahasil.proses');
    
    // Laporan Hasil
    Route::get('laporanhasil', [KepalaDesaLaporanHasilController::class, 'index'])->name('laporanhasil.index');
    Route::get('laporanhasil/export', [KepalaDesaLaporanHasilController::class, 'export'])->name('laporanhasil.export');

    // Centroid
    Route::get('centroid', [KepalaDesaCentroidController::class, 'index'])->name('centroid.index');
    Route::get('centroid/create', [KepalaDesaCentroidController::class, 'create'])->name('centroid.create');
    Route::post('centroid', [KepalaDesaCentroidController::class, 'store'])->name('centroid.store');
    Route::delete('centroid/{id}', [KepalaDesaCentroidController::class, 'destroy'])->name('centroid.destroy');
});