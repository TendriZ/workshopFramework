@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Scanner Kunjungan Toko (Sales)</h2>
    <div class="alert alert-info">Arahkan kamera ke Barcode/QR Code Toko untuk validasi jarak Radius Kunjungan.</div>
    
    <div class="row">
        <div class="col-md-5">
            <div id="reader" width="600px"></div>
            <button id="btn-rescan" class="btn btn-primary mt-3 d-none">Scan Ulang</button>
        </div>
        <div class="col-md-7">
            <div id="resultCard" class="card d-none">
                <div class="card-header bg-dark text-white">
                    Data Validasi Kunjungan
                </div>
                <div class="card-body">
                    <h5>Info Toko (DB):</h5>
                    <ul>
                        <li>Toko: <span id="res-toko">-</span></li>
                        <li>Lat: <span id="res-db-lat">-</span> | Lng: <span id="res-db-lng">-</span></li>
                        <li>Akurasi Toko: <span id="res-db-acc">-</span>m</li>
                    </ul>
                    
                    <h5>Posisi HP Sales (Realtime):</h5>
                    <div id="sales-loc-loading" class="text-warning fw-bold d-none">Sedang Mengunci SInyal GPS HP...</div>
                    <ul id="sales-loc-wrapper" class="d-none">
                        <li>Lat: <span id="res-hp-lat">-</span> | Lng: <span id="res-hp-lng">-</span></li>
                        <li>Akurasi HP: <span id="res-hp-acc">-</span>m</li>
                    </ul>

                    <hr>
                    <h4>Perhitungan Threshold (Haversine):</h4>
                    <ul>
                        <li>Max Jarak Absolute (Tetapan): <strong><span id="res-thresh-base">300</span>m</strong></li>
                        <li>Threshold Efektif (Base + AccToko + AccHP): <strong><span id="res-thresh-eff">-</span>m</strong></li>
                        <li>Jarak Aktual Sales -> Toko: <strong><span id="res-actual-dist" class="text-primary">-</span>m</strong></li>
                    </ul>
                    
                    <div id="final-decision" class="p-3 text-center mt-3 fs-3 fw-bold rounded"></div>
                </div>
            </div>
            
            <div id="errorCard" class="alert alert-danger d-none mt-2"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    const beepAudio = new Audio("{{ asset('audio/beep.mp3') }}");
    const BASE_THRESHOLD = 300; // Radius makismum diijinkan dalam meter
    let scanner = null;

    // Pseudocode Haversine javascript
    function haversine(lat1, lng1, lat2, lng2) {
        const R = 6371000; // Radius Bumi dalam meter
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat/2)*Math.sin(dLat/2) + 
                  Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * 
                  Math.sin(dLng/2)*Math.sin(dLng/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return Math.floor(R * c);
    }

    // Fungsi Akurasi dari Module
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
                        else reject(new Error("Timeout"));
                    }
                },
                (error) => reject(error),
                { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
            );
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
        
        beepAudio.currentTime = 0;
        beepAudio.play().catch(e => {});

        $('#errorCard').addClass('d-none');

        // 1. Dapatkan Data Toko dari DB API
        $.ajax({
            url: "/kunjungan-toko/api/" + decodedText,
            type: "GET",
            success: async function(res) {
                if (res.status === 'success') {
                    let dbToko = res.data;
                    
                    $('#res-toko').text(dbToko.nama_toko + " (" + dbToko.barcode + ")");
                    $('#res-db-lat').text(dbToko.latitude);
                    $('#res-db-lng').text(dbToko.longitude);
                    let dbAcc = parseFloat(dbToko.accuracy);
                    $('#res-db-acc').text(dbAcc.toFixed(2));

                    // 2. Ambil Geolocation Sales Sekarang!
                    $('#sales-loc-loading').removeClass('d-none');
                    $('#sales-loc-wrapper').addClass('d-none');
                    $('#final-decision').removeClass('bg-success bg-danger text-white').text("Mengkalkulasi...");
                    
                    try {
                        let pos = await getAccuratePosition(50, 15000);
                        let hpLat = pos.coords.latitude;
                        let hpLng = pos.coords.longitude;
                        let hpAcc = pos.coords.accuracy;

                        $('#sales-loc-loading').addClass('d-none');
                        $('#sales-loc-wrapper').removeClass('d-none');
                        
                        $('#res-hp-lat').text(hpLat);
                        $('#res-hp-lng').text(hpLng);
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
                        if (jarakAktual <= effThreshold) {
                            $('#final-decision').text("DITERIMA ✓").addClass("bg-success text-white");
                        } else {
                            $('#final-decision').text("DITOLAK X (TERLALU JAUH)").addClass("bg-danger text-white");
                        }

                    } catch(err) {
                        $('#sales-loc-loading').addClass('d-none');
                        $('#errorCard').text("Gagal mengambil GPS HP Sales: " + err.message).removeClass('d-none');
                    }

                }
            },
            error: function() {
                 $('#resultCard').addClass('d-none');
                 $('#errorCard').text("Barcode (" + decodedText + ") Bukan Milik Toko Terdaftar!").removeClass('d-none');
            }
        });
    }

    function onScanFailure(error) {}

    $(document).ready(function() {
        initScanner();
        $('#btn-rescan').click(function(){ initScanner(); });
    });
</script>
@endpush