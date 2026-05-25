@extends('layouts.app')

@section('title', 'Dashboard NFC - Absensi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home"></i> Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard NFC</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="mdi mdi-nfc"></i> Dashboard Absensi NFC
                    <a href="{{ route('nfc.scan') }}" class="btn btn-primary float-right">
                        <i class="mdi mdi-qrcode-scan"></i> Scan NFC
                    </a>
                </h4>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-gradient-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Kartu Aktif</h5>
                                <h2 class="display-4">{{ $kartuAktif }}</h2>
                                <p class="card-text">Terdaftar & aktif</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-gradient-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Peserta</h5>
                                <h2 class="display-4">{{ $totalPeserta }}</h2>
                                <p class="card-text">Terdaftar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-gradient-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Absensi Hari Ini</h5>
                                <h2 class="display-4">{{ $absensiHariIni }}</h2>
                                <p class="card-text">Total scan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-gradient-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">Masuk/Keluar</h5>
                                <h3 class="display-5">{{ $absensiMasuk }} / {{ $absensiKeluar }}</h3>
                                <p class="card-text">Status hari ini</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="mdi mdi-chart-line"></i> Kartu Terdaftar
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Serial Number</th>
                                                <th>Nama Kartu</th>
                                                <th>Jenis</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="kartu-list">
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="mdi mdi-clock"></i> Riwayat Absensi Hari Ini
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Nama</th>
                                                <th>Status</th>
                                                <th>Kartu</th>
                                            </tr>
                                        </thead>
                                        <tbody id="riwayat-list">
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="mdi mdi-timer-sand"></i> Statistik Real-time
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="card-text mb-0">Update setiap 30 detik</p>
                                <div id="realtime-stats">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let updateInterval = null;

        function loadKartu() {
            fetch('/api/nfc/kartu')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('kartu-list');
                    if (data.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Belum ada kartu terdaftar</td></tr>';
                    } else {
                        tbody.innerHTML = data.data.map(k => `
                            <tr>
                                <td><code>${k.serial_number}</code></td>
                                <td>${k.nama_kartu}</td>
                                <td><span class="badge badge-${k.jenis === 'peserta' ? 'primary' : (k.jenis === 'dosen' ? 'warning' : 'secondary')}">${k.jenis}</span></td>
                                <td>
                                    <span class="badge badge-${k.is_active ? 'success' : 'danger'}">
                                        ${k.is_active ? 'Aktif' : 'Non-Aktif'}
                                    </span>
                                </td>
                            </tr>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading kartu:', error);
                    document.getElementById('kartu-list').innerHTML = `
                        <tr><td colspan="4" class="text-center text-danger">Gagal memuat data kartu</td></tr>
                    `;
                });
        }

        function loadRiwayat() {
            fetch('/api/nfc/riwayat')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('riwayat-list');
                    if (data.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Belum ada absensi hari ini</td></tr>';
                    } else {
                        tbody.innerHTML = data.data.map(a => `
                            <tr>
                                <td>${a.waktu_scan ? a.waktu_scan : '-'}</td>
                                <td>${a.peserta ? a.peserta.nama : (a.kartu.nama_kartu)}</td>
                                <td>
                                    <span class="badge badge-${a.status === 'masuk' ? 'success' : 'danger'}">
                                        ${a.status === 'masuk' ? 'MASUK ✓' : 'KELUAR ○'}
                                    </span>
                                </td>
                                <td><code>${a.kartu.serial_number.substring(0, 20)}...</code></td>
                            </tr>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading riwayat:', error);
                    document.getElementById('riwayat-list').innerHTML = `
                        <tr><td colspan="4" class="text-center text-danger">Gagal memuat riwayat</td></tr>
                    `;
                });
        }

        function updateRealtimeStats() {
            fetch('/api/nfc/kartu')
                .then(response => response.json())
                .then(data => {
                    const kartuAktif = data.data.filter(k => k.is_active).length;
                    document.querySelector('.bg-gradient-primary h2').textContent = kartuAktif;
                })
                .catch(console.error);
        }

        function initRealtime() {
            loadKartu();
            loadRiwayat();
            updateRealtimeStats();

            // Update setiap 30 detik
            updateInterval = setInterval(() => {
                loadKartu();
                loadRiwayat();
                updateRealtimeStats();
            }, 30000);
        }

        $(document).ready(function() {
            initRealtime();
        });

        // Cleanup saat halaman navigasi
        window.addEventListener('beforeunload', function() {
            if (updateInterval) {
                clearInterval(updateInterval);
            }
        });
    </script>
@endpush