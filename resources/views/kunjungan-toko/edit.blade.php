@extends('layouts.app')

@section('title', 'Edit Toko - Kunjungan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home"></i> Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('kunjungan.index') }}">Daftar Toko</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Toko</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">Edit Data Toko</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('kunjungan.update', $toko->barcode) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Barcode ID <span class="text-muted">(tidak dapat diubah)</span></label>
                        <input type="text" name="barcode" class="form-control" value="{{ $toko->barcode }}" readonly>
                        <small class="form-text text-muted">Barcode unik sebagai identifikasi toko</small>
                    </div>

                    <div class="form-group">
                        <label>Nama Toko <span class="text-danger">*</span></label>
                        <input type="text" name="nama_toko" class="form-control" required value="{{ $toko->nama_toko }}">
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3">{{ $toko->alamat ?? '' }}</textarea>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="mdi mdi-map-marker"></i> Koordinat Lokasi</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Latitude <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="latitude" id="lat" class="form-control" required value="{{ $toko->latitude }}">
                                </div>
                                <div class="col-md-4">
                                    <label>Longitude <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="longitude" id="lng" class="form-control" required value="{{ $toko->longitude }}">
                                </div>
                                <div class="col-md-4">
                                    <label>Accuracy (Meter) <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="accuracy" id="acc" class="form-control" required value="{{ $toko->accuracy }}">
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="button" id="btn-geoloc" class="btn btn-secondary">
                                    <i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi Akurat
                                </button>
                                <span id="loc-status" class="text-muted ms-2"></span>
                            </div>

                            <div class="alert alert-info mt-2">
                                <small><strong>Catatan:</strong> Fungsi ini akan mendapatkan lokasi dengan accuracy terbaik (≤50m) secara otomatis. Proses ini membutuhkan waktu sekitar 10-15 detik.</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
            return new Promise((resolve, reject) => {
                let bestResult = null;
                const startTime = Date.now();
                let statusText = document.getElementById('loc-status');

                statusText.innerText = "Mencari koordinat terbaik... Mohon tunggu.";

                const watchId = navigator.geolocation.watchPosition(
                    (position) => {
                        const acc = position.coords.accuracy;

                        if (!bestResult || acc < bestResult.coords.accuracy) {
                            bestResult = position;
                            statusText.innerText = `Akurasi saat ini: ${acc.toFixed(2)} meter...`;
                        }

                        if (acc <= targetAccuracy) {
                            navigator.geolocation.clearWatch(watchId);
                            resolve(bestResult);
                        }

                        if (Date.now() - startTime >= maxWait) {
                            navigator.geolocation.clearWatch(watchId);
                            if (bestResult) resolve(bestResult);
                            else reject(new Error("Timeout, tidak dapat posisi"));
                        }
                    },
                    (error) => reject(error),
                    { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
                );

                setTimeout(() => {
                    navigator.geolocation.clearWatch(watchId);
                    if(bestResult) resolve(bestResult);
                    else reject(new Error("Timeout total."));
                }, maxWait);
            });
        }

        document.getElementById('btn-geoloc').addEventListener('click', async function() {
            try {
                this.disabled = true;
                const pos = await getAccuratePosition(50, 15000);

                document.getElementById('lat').value = pos.coords.latitude;
                document.getElementById('lng').value = pos.coords.longitude;
                document.getElementById('acc').value = pos.coords.accuracy;

                document.getElementById('loc-status').innerText = `Lokasi didapatkan dengan akurasi: ${pos.coords.accuracy.toFixed(2)}m`;
                document.getElementById('loc-status').className = "text-success ms-2 mt-2 d-block";
            } catch (error) {
                document.getElementById('loc-status').innerText = `Gagal: ${error.message}`;
                document.getElementById('loc-status').className = "text-danger ms-2 mt-2 d-block";
            } finally {
                this.disabled = false;
            }
        });
    </script>
@endpush