@extends('layouts.admin')
@section('title', 'Edit Data Penduduk')

@section('content')
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:32px 40px; max-width:900px; margin:0 auto;">
    <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Edit Data Penduduk</h1>

    <form action="{{ route('admin.penduduk.update', $penduduk->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.penduduk._form', ['penduduk' => $penduduk])

        <button type="submit" style="background:#2563eb; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem;">Update</button>
        <a href="{{ route('admin.penduduk.index') }}" style="background:#888fa6; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none; margin-left:10px;">Batal</a>
    </form>
</div>
@endsection
