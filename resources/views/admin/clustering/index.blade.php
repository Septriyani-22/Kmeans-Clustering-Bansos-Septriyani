@extends('layouts.admin')

@section('title', 'Clustering - BANSOS KMEANS')

@section('content')
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:24px; margin:0 auto;">
    <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Proses Clustering</h1>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 16px; border-radius:6px; margin-bottom:18px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:12px 16px; border-radius:6px; margin-bottom:18px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(500px, 1fr)); gap:24px;">
        <!-- Data Centroid -->
        <div>
            <div style="background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1); overflow:hidden;">
                <div style="background:#f4f6fa; padding:16px; border-bottom:1px solid #e5e7eb;">
                    <h6 style="margin:0; color:#888fa6; font-weight:600;">Data Centroid</h6>
                </div>
                <div style="padding:16px;">
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="background:#f4f6fa; color:#888fa6;">
                                    <th style="padding:12px; text-align:left;">Nama Centroid</th>
                                    <th style="padding:12px; text-align:left;">Penghasilan</th>
                                    <th style="padding:12px; text-align:left;">Tanggungan</th>
                                    <th style="padding:12px; text-align:left;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($centroids as $centroid)
                                <tr style="border-bottom:1px solid #e5e7eb;">
                                    <td style="padding:12px;">{{ $centroid->nama_centroid }}</td>
                                    <td style="padding:12px;">{{ $centroid->penghasilan_formatted }}</td>
                                    <td style="padding:12px;">{{ $centroid->tanggungan }}</td>
                                    <td style="padding:12px;">{{ $centroid->keterangan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proses Clustering -->
        <div>
            <div style="background:#fff; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1); overflow:hidden;">
                <div style="background:#f4f6fa; padding:16px; border-bottom:1px solid #e5e7eb;">
                    <h6 style="margin:0; color:#888fa6; font-weight:600;">Proses Clustering</h6>
                </div>
                <div style="padding:16px;">
                    <form action="{{ route('admin.clustering.proses') }}" method="POST" id="clusteringForm">
                        @csrf
                        <div style="margin-bottom:16px;">
                            <label style="display:block; margin-bottom:8px; color:#4b5563;">Jumlah Iterasi</label>
                            <input type="number" name="iterasi" value="10" min="1" required style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;">
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button type="submit" style="background:#2563eb; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; cursor:pointer;">
                                Mulai Proses
                            </button>
                            <a href="{{ route('admin.clustering.reset') }}" onclick="return confirm('Apakah Anda yakin ingin mereset hasil clustering?')" style="background:#ef4444; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; cursor:pointer; text-decoration:none;">
                                Reset Hasil
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('clusteringForm').addEventListener('submit', function(e) {
    e.preventDefault();
    this.submit();
});
</script>
@endpush
@endsection 