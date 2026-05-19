@extends('layouts.app')

@section('title', 'Scanner Kunjungan Toko - Sales')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home"></i> Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('kunjungan.index') }}">Daftar Toko</a></li>
    <li class="breadcrumb-item active" aria-current="page">Scanner Kunjungan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Scanner Kunjungan Toko (Sales)</h4>
                <div class="alert alert-info">
                    <i class="mdi mdi-information"></i>
                    Arahkan kamera ke Barcode/QR Code Toko untuk validasi jarak radius kunjungan.
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="mdi mdi-qrcode-scan"></i> Scanner Barcode/QR</h6>
                            </div>
                            <div class="card-body text-center">
                                <div id="reader" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                                <button id="btn-rescan" class="btn btn-primary mt-3 d-none">
                                    <i class="mdi mdi-refresh"></i> Scan Ulang
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div id="resultCard" class="card d-none">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0"><i class="mdi mdi-check-circle"></i> Data Validasi Kunjungan</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6 class="text-primary"><i class="mdi mdi-store"></i> Info Toko (Database):</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Toko:</strong> <span id="res-toko">-</span></li>
                                        <li><strong>Barcode:</strong> <span id="res-barcode">-</span></li>
                                        <li><strong>Koordinat:</strong> <span id="res-db-lat">-</span>, <span id="res-db-lng">-</span></li>
                                        <li><strong>Accuracy Toko:</strong> <span id="res-db-acc">-</span>m</li>
                                    </ul>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-success"><i class="mdi mdi-cellphone"></i> Posisi HP Sales (Realtime):</h6>
                                    <div id="sales-loc-loading" class="alert alert-warning d-none">
                                        <i class="mdi mdi-loading mdi-spin"></i> Sedang mengunci sinyal GPS HP...
                                    </div>
                                    <ul id="sales-loc-wrapper" class="list-unstyled d-none">
                                        <li><strong>Koordinat:</strong> <span id="res-hp-lat">-</span>, <span id="res-hp-lng">-</span></li>
                                        <li><strong>Accuracy HP:</strong> <span id="res-hp-acc">-</span>m</li>
                                    </ul>
                                </div>

                                <hr>

                                <div class="mb-4">
                                    <h6 class="text-warning"><i class="mdi mdi-calculator"></i> Perhitungan Threshold (Haversine):</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Max Jarak Absolut (Tetapan):</strong> <span id="res-thresh-base" class="badge badge-secondary">300</span>m</li>
                                        <li><strong>Threshold Efektif:</strong> <span id="res-thresh-eff" class="badge badge-info">-</span>m
                                            <small class="text-muted">(Base + AccToko + AccHP)</small>
                                        </li>
                                        <li><strong>Jarak Aktual Sales → Toko:</strong> <span id="res-actual-dist" class="badge badge-primary">-</span>m</li>
                                    </ul>
                                </div>

                                <div id="final-decision" class="p-4 text-center mt-3 display-4 font-weight-bold rounded"></div>

                                <div class="alert alert-info mt-3">
                                    <small><strong>Keterangan:</strong> Kunjungan DITERIMA jika jarak aktual ≤ threshold efektif</small>
                                </div>
                            </div>
                        </div>

                        <div id="errorCard" class="alert alert-danger d-none mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const BASE_THRESHOLD = 300; // Radius maksimum yang diizinkan dalam meter
        let scanner = null;

        // Formula Haversine - Lampiran 2
        function haversine(lat1, lng1, lat2, lng2) {
            const R = 6371000; // Radius Bumi dalam meter
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) *
                      Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return Math.floor(R * c);
        }

        // Fungsi Geolocation Akurat - Lampiran 1
        function getAccuratePosition(targetAccuracy = 50, maxWait = 15000) {
            return new Promise((resolve, reject) => {
                let bestResult = null;
                const startTime = Date.now();
                const watchId = navigator.geolocation.watchPosition(
                    (position) => {
                        const acc = position.coords.accuracy;
                        if (!bestResult || acc < bestResult.coords.accuracy) {
                            bestResult = position;
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

                // Timeout cadangan bila watchPosition nge-hang
                setTimeout(() => {
                    navigator.geolocation.clearWatch(watchId);
                    if(bestResult) resolve(bestResult);
                    else reject(new Error("Timeout total."));
                }, maxWait);
            });
        }

        function initScanner() {
            $('#resultCard').addClass('d-none');
            $('#errorCard').addClass('d-none');
            $('#btn-rescan').addClass('d-none');

            scanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 250} },
                false
            );
            scanner.render(onScanSuccess, onScanFailure);
        }

        async function onScanSuccess(decodedText, decodedResult) {
            // Hentikan scanner dulu
            scanner.clear();
            $('#btn-rescan').removeClass('d-none');
            $('#resultCard').removeClass('d-none');

            // Play beep sound
            try {
                const beepAudio = new Audio("{{ asset('audio/beep.mp3') }}");
                beepAudio.currentTime = 0;
                beepAudio.play().catch(e => console.log('Audio play error:', e));
            } catch(e) {
                console.log('Audio initialization error:', e);
            }

            $('#errorCard').addClass('d-none');

            // 1. Dapatkan Data Toko dari DB API
            $.ajax({
                url: "/api/toko/" + decodedText,
                type: "GET",
                success: async function(res) {
                    if (res.status === 'success') {
                        let dbToko = res.data;

                        $('#res-toko').text(dbToko.nama_toko);
                        $('#res-barcode').text(dbToko.barcode);
                        $('#res-db-lat').text(parseFloat(dbToko.latitude).toFixed(6));
                        $('#res-db-lng').text(parseFloat(dbToko.longitude).toFixed(6));
                        let dbAcc = parseFloat(dbToko.accuracy);
                        $('#res-db-acc').text(dbAcc.toFixed(2));

                        // 2. Ambil Geolocation Sales Sekarang!
                        $('#sales-loc-loading').removeClass('d-none');
                        $('#sales-loc-wrapper').addClass('d-none');
                        $('#final-decision').removeClass('bg-success bg-danger bg-warning text-white')
                            .text("Mengkalkulasi...")
                            .addClass('bg-warning text-white');

                        try {
                            let pos = await getAccuratePosition(50, 15000);
                            let hpLat = pos.coords.latitude;
                            let hpLng = pos.coords.longitude;
                            let hpAcc = pos.coords.accuracy;

                            $('#sales-loc-loading').addClass('d-none');
                            $('#sales-loc-wrapper').removeClass('d-none');

                            $('#res-hp-lat').text(hpLat.toFixed(6));
                            $('#res-hp-lng').text(hpLng.toFixed(6));
                            $('#res-hp-acc').text(hpAcc.toFixed(2));

                            // 3. Kalkulasi Formula Radius
                            let jarakAktual = haversine(
                                parseFloat(dbToko.latitude), parseFloat(dbToko.longitude),
                                hpLat, hpLng
                            );

                            let effThreshold = BASE_THRESHOLD + dbAcc + hpAcc;

                            $('#res-thresh-base').text(BASE_THRESHOLD);
                            $('#res-thresh-eff').text(effThreshold.toFixed(2));
                            $('#res-actual-dist').text(jarakAktual);

                            // 4. PUTUSAN!
                            $('#final-decision').removeClass('bg-warning text-white');
                            if (jarakAktual <= effThreshold) {
                                $('#final-decision').text("DITERIMA ✓")
                                    .addClass("bg-success text-white");
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Kunjungan Diterima!',
                                    text: `Jarak ${jarakAktual}m ≤ Threshold ${effThreshold.toFixed(2)}m`,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                $('#final-decision').text("DITOLAK ✗ (TERLALU JAUH)")
                                    .addClass("bg-danger text-white");
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Kunjungan Ditolak!',
                                    text: `Jarak ${jarakAktual}m > Threshold ${effThreshold.toFixed(2)}m`,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }

                        } catch(err) {
                            $('#sales-loc-loading').addClass('d-none');
                            $('#errorCard').text("Gagal mengambil GPS HP Sales: " + err.message).removeClass('d-none');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error Geolocation',
                                text: err.message
                            });
                        }

                    }
                },
                error: function() {
                    $('#resultCard').addClass('d-none');
                    $('#errorCard').text("Barcode (" + decodedText + ") Bukan Milik Toko Terdaftar!").removeClass('d-none');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Barcode Tidak Ditemukan',
                        text: 'Barcode ini tidak terdaftar dalam database.'
                    });
                }
            });
        }

        function onScanFailure(error) {
            // Scanner berjalan terus, ini normal
        }

        $(document).ready(function() {
            initScanner();
            $('#btn-rescan').click(function() {
                initScanner();
            });
        });
    </script>
@endpush