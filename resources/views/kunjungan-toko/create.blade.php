@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Input Titik Awal Toko</h2>
    <div class="card mt-3">
        <div class="card-header bg-primary text-white">Data Toko Baru</div>
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

            <form action="{{ route('kunjungan.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Barcode ID (Max 8 Char)</label>
                    <input type="text" name="barcode" class="form-control" required placeholder="Contoh: TK001" maxlength="8">
                </div>
                <div class="mb-3">
                    <label>Nama Toko</label>
                    <input type="text" name="nama_toko" class="form-control" required placeholder="Contoh: Toko Jaya Abadi">
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Latitude</label>
                        <input type="number" step="any" name="latitude" id="lat" class="form-control" required readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Longitude</label>
                        <input type="number" step="any" name="longitude" id="lng" class="form-control" required readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Accuracy (Meter)</label>
                        <input type="number" step="any" name="accuracy" id="acc" class="form-control" required readonly>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="button" id="btn-geoloc" class="btn btn-secondary">Get Location (Geoloc)</button>
                    <button type="submit" id="btn-submit" class="btn btn-primary" disabled>Simpan Data</button>
                    <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
                <span id="loc-status" class="text-muted ms-2 mt-2 d-block"></span>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi dari Lampiran 1 Modul
    function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
        return new Promise((resolve, reject) => {
            let bestResult = null;
            const startTime = Date.now();
            let statusText = document.getElementById('loc-status');
            
            statusText.innerText = "Mencari koordinat terbaik... Mohon tunggu.";
            
            const watchId = navigator.geolocation.watchPosition(
                (position) => {
                    const acc = position.coords.accuracy;
                    
                    // Simpan hasil terbaik sejauh ini
                    if (!bestResult || acc < bestResult.coords.accuracy) {
                        bestResult = position;
                        statusText.innerText = `Akurasi saat ini: ${acc.toFixed(2)} meter...`;
                    }
                    
                    // Kalau sudah cukup akurat, berhenti
                    if (acc <= targetAccuracy) {
                        navigator.geolocation.clearWatch(watchId);
                        resolve(bestResult);
                    }
                    
                    // Kalau timeout, pakai hasil terbaik yang ada
                    if (Date.now() - startTime >= maxWait) {
                        navigator.geolocation.clearWatch(watchId);
                        if (bestResult) resolve(bestResult);
                        else reject(new Error("Timeout, tidak dapat posisi"));
                    }
                },
                (error) => reject(error),
                { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
            );
            
            // Timeout cadangan bila watchPosition nge-hang
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
            const pos = await getAccuratePosition(50, 15000); // Target akurasi 50m, max wait 15 dtk
            
            document.getElementById('lat').value = pos.coords.latitude;
            document.getElementById('lng').value = pos.coords.longitude;
            document.getElementById('acc').value = pos.coords.accuracy;
            
            document.getElementById('loc-status').innerText = `Lokasi didapatkan dengan akurasi: ${pos.coords.accuracy.toFixed(2)}m. Siap Disimpan!`;
            document.getElementById('loc-status').className = "text-success ms-2 mt-2 d-block";
            
            document.getElementById('btn-submit').disabled = false;
        } catch (error) {
            document.getElementById('loc-status').innerText = `Gagal: ${error.message}`;
            document.getElementById('loc-status').className = "text-danger ms-2 mt-2 d-block";
        } finally {
            this.disabled = false;
        }
    });
</script>
@endpush