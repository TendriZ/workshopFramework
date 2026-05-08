@extends('layouts.app')

@section('content')
<div class="container">
    <h2>List Toko (Titik Awal)</h2>
    <a href="{{ route('kunjungan.create') }}" class="btn btn-primary mb-3">Input Titik Awal (Toko Baru)</a>
    <a href="{{ route('kunjungan.scan') }}" class="btn btn-success mb-3">Menu Kunjungan (Scanner Sales)</a>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-primary">
            <tr>
                <th>Barcode</th>
                <th>Nama Toko</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Accuracy</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tokos as $t)
            <tr>
                <td>{{ $t->barcode }}</td>
                <td>{{ $t->nama_toko }}</td>
                <td>{{ $t->latitude }}</td>
                <td>{{ $t->longitude }}</td>
                <td>{{ $t->accuracy }}</td>
                <td>
                    <a href="{{ route('kunjungan.cetak-barcode', $t->barcode) }}" class="btn btn-sm btn-info text-white" target="_blank">Cetak Barcode</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data titik toko.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection