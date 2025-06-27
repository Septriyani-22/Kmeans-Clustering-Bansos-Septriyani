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
                    <p class="text-muted text-center">{{ '@' . $user->username }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>NIK</b> <a class="float-right">{{ $penduduk->nik ?? 'Belum diisi' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $user->email ?? 'Belum diisi' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Tanggal Lahir</b> <a class="float-right">{{ $penduduk->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y') : 'Belum diisi' }}</a>
                        </li>
                    </ul>

                    @if(!$penduduk->is_profile_complete)
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('penduduk.profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit mr-1"></i> Update Data
                            </a>

                            <form action="{{ route('penduduk.profile.lock') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin? Setelah disubmit, data tidak bisa diubah lagi oleh Anda.');">
                                @csrf
                                <button type="submit" class="btn btn-success" {{-- Tombol disable jika NIK kosong --}} @if(empty($penduduk->nik)) disabled title="Harap lengkapi NIK Anda terlebih dahulu." @endif>
                                    <i class="fas fa-check-circle mr-1"></i> Submit Final & Kunci Profil
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-success text-center mt-3">
                            <i class="fas fa-lock mr-1"></i> Profil Anda sudah final dan telah dikirim. Hubungi Admin untuk melakukan perubahan.
                        </div>
                    @endif
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

    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-alt mr-1"></i> Dokumen & Foto</h3>
                </div>
                <div class="card-body">
                    <strong>Foto KK:</strong><br>
                    @if($penduduk->kk_photo)
                        <img src="{{ asset('storage/'.$penduduk->kk_photo) }}" alt="Foto KK" width="120">
                    @else
                        <span class="text-muted">Belum diupload</span>
                    @endif
                    <hr>
                    <strong>Surat Keterangan Tidak Mampu:</strong><br>
                    @if($penduduk->sktm_file)
                        <a href="{{ asset('storage/'.$penduduk->sktm_file) }}" target="_blank">Lihat File</a>
                    @else
                        <span class="text-muted">Belum diupload</span>
                    @endif
                    <hr>
                    <strong>Bukti Status Kepemilikan Rumah:</strong><br>
                    @if($penduduk->bukti_kepemilikan_file)
                        <a href="{{ asset('storage/'.$penduduk->bukti_kepemilikan_file) }}" target="_blank">Lihat File</a>
                    @else
                        <span class="text-muted">Belum diupload</span>
                    @endif
                    <hr>
                    <strong>Slip Gaji:</strong><br>
                    @if($penduduk->slip_gaji_file)
                        <a href="{{ asset('storage/'.$penduduk->slip_gaji_file) }}" target="_blank">Lihat File</a>
                    @else
                        <span class="text-muted">Belum diupload</span>
                    @endif
                    <hr>
                    <strong>Foto Kondisi Rumah:</strong><br>
                    @if($penduduk->foto_rumah)
                        <img src="{{ asset('storage/'.$penduduk->foto_rumah) }}" alt="Foto Rumah" width="120">
                    @else
                        <span class="text-muted">Belum diupload</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-award mr-1"></i> Status Penerima Bantuan</h3>
                </div>
                <div class="card-body">
                    @if($penduduk->hasilKmeans)
                        @if($penduduk->hasilKmeans->cluster == 1)
                            <div class="alert alert-success">
                                <b>Selamat!</b> Kamu termasuk kategori <b>penerima bantuan</b> (Cluster Membutuhkan) periode {{ $penduduk->hasilKmeans->periode ?? '-' }}<br>
                                <span class="text-muted">Cluster: 1 (Membutuhkan)</span>
                            </div>
                        @elseif($penduduk->hasilKmeans->cluster == 3)
                            <div class="alert alert-warning">
                                <b>Info:</b> Kamu termasuk <b>prioritas sedang</b> (Cluster 3). Bantuan akan diberikan jika kuota masih tersedia.<br>
                                <span class="text-muted">Cluster: 3 (Prioritas Sedang)</span>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <b>Maaf,</b> kamu <b>tidak termasuk penerima bantuan</b> periode ini.<br>
                                <span class="text-muted">Cluster: 2 (Tidak Membutuhkan)</span>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">Belum ada hasil penetapan penerima bantuan untuk periode berjalan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-info" id="riwayat-pengajuan">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-1"></i> Riwayat Pengajuan Data</h3>
                </div>
                <div class="card-body p-0">
                    @if($riwayatPengajuan->count())
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatPengajuan as $riwayat)
                                        <tr>
                                            <td>{{ $riwayat->created_at->format('d-m-Y H:i') }}</td>
                                            <td>{{ $riwayat->keterangan }}</td>
                                            <td>{{ ucfirst($riwayat->status) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">Belum ada riwayat pengajuan data.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 