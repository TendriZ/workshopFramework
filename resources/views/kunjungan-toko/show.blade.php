@extends('layouts.app')

@section('title', 'Detail Toko - Kunjungan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home"></i> Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('kunjungan.index') }}">Daftar Toko</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Toko</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="mdi mdi-store"></i> Detail Toko</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Barcode ID</label>
                            <input type="text" class="form-control" value="{{ $toko->barcode }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Toko</label>
                            <input type="text" class="form-control" value="{{ $toko->nama_toko }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>Alamat</label>
                    <textarea class="form-control" rows="2" readonly>{{ $toko->alamat ?? '-' }}</textarea>
                </div>

                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="mdi mdi-map-marker"></i> Koordinat Lokasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Latitude</label>
                                <input type="text" class="form-control" value="{{ number_format($toko->latitude, 6) }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Longitude</label>
                                <input type="text" class="form-control" value="{{ number_format($toko->longitude, 6) }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Accuracy</label>
                                <input type="text" class="form-control" value="{{ number_format($toko->accuracy, 1) }}m" readonly>
                            </div>
                        </div>

                        <div class="mt-2">
                            <a href="https://www.google.com/maps?q={{ $toko->latitude }},{{ $toko->longitude }}"
                               target="_blank" class="btn btn-outline-primary">
                                <i class="mdi mdi-google-maps"></i> Lihat di Google Maps
                            </a>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('kunjungan.edit', $toko->barcode) }}" class="btn btn-warning">
                        <i class="mdi mdi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('kunjungan.cetak-barcode', $toko->barcode) }}"
                       class="btn btn-info" target="_blank">
                        <i class="mdi mdi-barcode"></i> Cetak Barcode
                    </a>
                    <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection