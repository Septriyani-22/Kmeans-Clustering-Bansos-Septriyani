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
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminHasilKmeansController;

use App\Http\Controllers\KepalaDesa\PendudukController as KepalaDesaPendudukController;
use App\Http\Controllers\KepalaDesa\DataHasilController as KepalaDesaDataHasilController;
use App\Http\Controllers\KepalaDesa\LaporanHasilController as KepalaDesaLaporanHasilController;
use App\Http\Controllers\KepalaDesa\UserController as KepalaDesaUserController; 
use App\Http\Controllers\KepalaDesa\KriteriaController as KepalaDesaKriteriaController;
use App\Http\Controllers\KepalaDesa\CentroidController as KepalaDesaCentroidController;
use App\Http\Controllers\KepalaDesa\DashboardController as KepalaDesaDashboardController;
use App\Http\Controllers\KepalaDesa\HasilKmeansController as KepalaDesaHasilKmeansController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PendudukDashboardController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/search/result', [SearchController::class, 'search'])->name('search.result');

Route::get('/informasi', function () {
    return view('informasi');
})->name('informasi');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('penduduk', AdminPendudukController::class)->except(['show']);
    Route::resource('centroid', CentroidController::class);
    Route::resource('hasil-kmeans', HasilKmeansController::class)->except(['show']);
    Route::resource('mapping-centroid', MappingCentroidController::class);
    Route::post('/calculate-distances', [HasilKmeansController::class, 'calculateDistances'])->name('calculate.distances');
    Route::get('/print/{cluster}', [HasilKmeansController::class, 'print'])->name('print.cluster');
});

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
    Route::get('/centroid', [CentroidController::class, 'index'])->name('centroid.index');
    Route::post('/centroid', [CentroidController::class, 'store'])->name('centroid.store');
    Route::put('/centroid/{centroid}', [CentroidController::class, 'update'])->name('centroid.update');
    Route::delete('/centroid/{centroid}', [CentroidController::class, 'destroy'])->name('centroid.destroy');
    Route::post('/centroid/calculate', [CentroidController::class, 'calculateDistances'])->name('centroid.calculate');

    // Mapping routes under centroid
    Route::post('/centroid/mapping', [CentroidController::class, 'storeMapping'])->name('centroid.mapping.store');
    Route::put('/centroid/mapping/{mapping}', [CentroidController::class, 'updateMapping'])->name('centroid.mapping.update');
    Route::delete('/centroid/mapping/{mapping}', [CentroidController::class, 'destroyMapping'])->name('centroid.mapping.destroy');

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
    Route::get('mapping-centroid', [MappingCentroidController::class, 'index'])->name('mapping-centroid.index');
    Route::get('mapping-centroid/create', [MappingCentroidController::class, 'create'])->name('mapping-centroid.create');
    Route::post('mapping-centroid', [MappingCentroidController::class, 'store'])->name('mapping-centroid.store');
    Route::put('mapping-centroid/{id}', [MappingCentroidController::class, 'update'])->name('mapping-centroid.update');
    Route::delete('mapping-centroid/{id}', [MappingCentroidController::class, 'destroy'])->name('mapping-centroid.destroy');
    Route::get('mapping-centroid/get-penduduk/{id}', [MappingCentroidController::class, 'getPendudukData'])->name('mapping-centroid.get-penduduk');
});

// Routes untuk Kepala Desa
Route::middleware(['auth', 'role:kepala_desa'])->prefix('kepala_desa')->name('kepala_desa.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\KepalaDesa\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/hasil-kmeans', [App\Http\Controllers\KepalaDesa\HasilKmeansController::class, 'index'])->name('hasil-kmeans.index');
    Route::get('/hasil-kmeans/{id}', [App\Http\Controllers\KepalaDesa\HasilKmeansController::class, 'show'])->name('hasil-kmeans.show');
    
    // Penduduk routes
    Route::get('/penduduk', [App\Http\Controllers\KepalaDesa\PendudukController::class, 'index'])->name('penduduk.index');
    Route::get('/penduduk/{id}', [App\Http\Controllers\KepalaDesa\PendudukController::class, 'show'])->name('penduduk.show');
});

Route::middleware(['auth', 'role:penduduk'])->prefix('dashboard')->group(function () {
    Route::get('/', [PendudukDashboardController::class, 'index'])->name('penduduk.dashboard');
    Route::get('/profile/edit', [PendudukDashboardController::class, 'edit'])->name('penduduk.profile.edit');
    Route::put('/profile/update', [PendudukDashboardController::class, 'update'])->name('penduduk.profile.update');
    Route::post('/profile/lock', [PendudukDashboardController::class, 'lockProfile'])->name('penduduk.profile.lock');
});