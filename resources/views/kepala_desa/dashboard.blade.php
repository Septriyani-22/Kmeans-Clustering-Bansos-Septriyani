@extends('layouts.kepala_desa')

@section('title', 'Dashboard - BANSOS KMEANS')

@section('content')
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:32px 40px; text-align:center; max-width:700px; margin:0 auto;">
    <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Dashboard</h1>
    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:100px; height:100px; margin-bottom:20px; border-radius:50%; background:none;">
    <div style="margin-top:18px; font-size:1.15rem; color:#888fa6;">
        SISTEM OPTIMALISASI PENYALURAN BANTUAN SOSIAL<br>
        MENGGUNAKAN K-MEANS CLUSTERING BERDASARKAN SEGMENTASI<br>
        DATA SOSIAL DAN EKONOMI <br>
        (STUDI KASUS DESA TANJUNG SERANG KECAMATAN KAYUAGUNG)
    </div>
</div>
@endsection
