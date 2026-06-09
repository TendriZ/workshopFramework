@extends('layouts.app')

@section('title', 'Daftar Toko - Kunjungan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kunjungan.index') }}">Daftar Toko</a></li>
    <li class="breadcrumb-item active" aria-current="page">Daftar Toko</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Daftar Lokasi Toko</h4>
                <p class="card-description">Kelola lokasi toko untuk sistem pelacakan kunjungan sales</p>

                <div class="text-right mb-3">
                    <a href="{{ route('kunjungan.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah Toko Baru
                    </a>
                    <a href="{{ route('kunjungan.scan') }}" class="btn btn-success ml-2">
                        <i class="mdi mdi-qrcode-scan"></i> Scanner Kunjungan
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Nama Toko</th>
                                <th>Alamat</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Accuracy</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($tokos->count() > 0)
                                @foreach($tokos as $toko)
                                    <tr>
                                        <td><strong>{{ $toko->barcode }}</strong></td>
                                        <td>{{ $toko->nama_toko }}</td>
                                        <td>{{ $toko->alamat ?? '-' }}</td>
                                        <td>{{ number_format($toko->latitude, 6) }}</td>
                                        <td>{{ number_format($toko->longitude, 6) }}</td>
                                        <td>{{ number_format($toko->accuracy, 1) }}m</td>
                                        <td>
                                            <a href="{{ route('kunjungan.cetak-barcode', $toko->barcode) }}" class="btn btn-info btn-sm" target="_blank">
                                                <i class="mdi mdi-barcode"></i> Cetak
                                            </a>
                                            <a href="{{ route('kunjungan.edit', $toko->barcode) }}" class="btn btn-warning btn-sm">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('kunjungan.destroy', $toko->barcode) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="mdi mdi-delete"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Belum ada data toko. Silakan tambah toko baru.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3">
                    <small><strong>Informasi:</strong>
                    Dekatkan tombol <strong>Cetak Barcode</strong> untuk menghasilkan QR Code yang bisa dipindai sales dengan HP.</small>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function loadToko() {
            fetch('/api/toko')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('table tbody');
                    if (data.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada data toko.</td></tr>';
                    } else {
                        tbody.innerHTML = data.data.map(t => `
                            <tr>
                                <td><strong>${t.barcode}</strong></td>
                                <td>${t.nama_toko}</td>
                                <td>${t.alamat ?? '-' }</td>
                                <td>{{ number_format(t.latitude, 6) }}</td>
                                <td>{{ number_format(t.longitude, 6) }}</td>
                                <td>{{ number_format(t.accuracy, 1) }}m</td>
                                <td>
                                    <a href="{{ route('kunjungan.cetak-barcode', t.barcode) }}" class="btn btn-info btn-sm" target="_blank">
                                        <i class="mdi mdi-barcode"></i> Cetak
                                    </a>
                                    <a href="{{ route('kunjungan.edit', t.barcode) }}" class="btn btn-warning btn-sm">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('kunjungan.destroy', t.barcode) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm">
                                            <i class="mdi mdi-delete"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        `).join('');
                    }
                })
                .catch(error => {
                    document.querySelector('table tbody').innerHTML = `
                        <tr><td colspan="7" class="text-center text-danger">Gagal memuat data toko.</td></tr>
                    `;
                });
        }

        // Load data saat halaman dibuka
        document.addEventListener('DOMContentLoaded', function() {
            loadToko();
            // Refresh data setiap 5 detik
            setInterval(loadToko, 5000);
        });
    </script>
@endsection