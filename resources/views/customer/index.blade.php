@extends('layouts.app')

@section('title', 'Data Customer')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Customer</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Data Customer</h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Daftar Customer</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Provinsi</th>
                            <th>Kota / Kecamatan</th>
                            <th>Kodepos</th>
                            <th>Foto (Blob)</th>
                            <th>Foto (Path)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $c)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $c->nama }}</td>
                            <td>{{ $c->alamat }}</td>
                            <td>{{ $c->provinsi }}</td>
                            <td>{{ $c->kota }} / {{ $c->kecamatan }}</td>
                            <td>{{ $c->kodepos_kelurahan }}</td>
                            <td>
                                @if($c->foto_blob)
                                    <img src="data:image/png;base64,{{ base64_encode($c->foto_blob) }}" alt="Foto" width="50">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($c->foto_path)
                                    <img src="{{ asset($c->foto_path) }}" alt="Foto" width="50">
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection