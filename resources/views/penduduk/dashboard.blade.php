@extends('layouts.penduduk')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">Penduduk</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>NIK</b> <a class="float-right">{{ $penduduk->nik ?? 'Belum diisi' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $user->email ?? 'Belum diisi' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Tahun Data</b> <a class="float-right">{{ $penduduk->tahun ?? 'Belum diisi' }}</a>
                        </li>
                    </ul>

                    <a href="{{ route('penduduk.profile.edit') }}" class="btn btn-primary btn-block"><b>Update Data Diri</b></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user mr-1"></i> Data Personal</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-birthday-cake mr-1"></i> Usia</strong>
                    <p class="text-muted">{{ $penduduk->usia ?? 'Belum diisi' }} Tahun</p>
                    <hr>
                    <strong><i class="fas fa-venus-mars mr-1"></i> Jenis Kelamin</strong>
                    <p class="text-muted">{{ $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : ($penduduk->jenis_kelamin == 'P' ? 'Perempuan' : 'Belum diisi') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-home mr-1"></i> Data Keluarga & Tempat Tinggal</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-map-marker-alt mr-1"></i> RT</strong>
                    <p class="text-muted">{{ $penduduk->rt ?? 'Belum diisi' }}</p>
                    <hr>
                    <strong><i class="fas fa-users mr-1"></i> Tanggungan</strong>
                    <p class="text-muted">{{ $penduduk->tanggungan ?? 'Belum diisi' }} Orang</p>
                    <hr>
                    <strong><i class="fas fa-money-bill-wave mr-1"></i> Penghasilan</strong>
                    <p class="text-muted">Rp {{ number_format($penduduk->penghasilan ?? 0, 2, ',', '.') }}</p>
                    <hr>
                    <strong><i class="fas fa-house-user mr-1"></i> Kondisi Rumah</strong>
                    <p class="text-muted text-capitalize">{{ $penduduk->kondisi_rumah ?? 'Belum diisi' }}</p>
                    <hr>
                    <strong><i class="fas fa-file-signature mr-1"></i> Status Kepemilikan</strong>
                    <p class="text-muted text-capitalize">{{ $penduduk->status_kepemilikan ?? 'Belum diisi' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 