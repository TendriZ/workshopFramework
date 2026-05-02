@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Vendor Scanner QR (Praktikum 2)</h2>
    <div class="row mt-4">
        <div class="col-md-5">
            <div id="reader" width="600px"></div>
            <button id="btn-rescan" class="btn btn-primary d-none mt-3">Scan Lagi</button>
        </div>
        <div class="col-md-7">
            <div id="resultCard" class="card d-none">
                <div class="card-header bg-primary text-white">
                    Data Struk Pembeli
                </div>
                <div class="card-body">
                    <p><strong>ID Penjualan:</strong> <span id="res-id">-</span></p>
                    <p><strong>Status:</strong> <span id="res-status" class="badge bg-success">-</span></p>
                    <p><strong>Waktu:</strong> <span id="res-waktu">-</span></p>
                    <hr>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Menu / Barang</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="res-items">
                        </tbody>
                    </table>
                    <h5><strong>Total Pembayaran:</strong> <span id="res-total"></span></h5>
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
    let scanner = null;

    function initScanner() {
        $('#resultCard').addClass('d-none');
        $('#errorCard').addClass('d-none');
        $('#btn-rescan').addClass('d-none');
        $('#res-items').empty();

        scanner = new Html5QrcodeScanner(
            "reader", 
            { fps: 10, qrbox: {width: 250, height: 250} }, 
            /* verbose= */ false
        );
        scanner.render(onScanSuccess, onScanFailure);
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan scanner
        scanner.clear();
        $('#btn-rescan').removeClass('d-none');
        
        // Bunyikan beep
        beepAudio.currentTime = 0;
        beepAudio.play().catch(e => console.log('Audio Play Error:', e));

        try {
            // Karena QR Code digenerate langsung dari JSON (PosController@success), 
            // kita bisa melakukan parsing javascript secara mandiri tanpa AJAX backend lagi.
            let detail = JSON.parse(decodedText);
            
            $('#res-id').text(detail.id_penjualan);
            $('#res-status').text(detail.payment_status);
            $('#res-waktu').text(detail.timestamp);
            $('#res-total').text('Rp ' + parseInt(detail.total).toLocaleString('id-ID'));
            
            let tbody = '';
            detail.items.forEach(function(item) {
                tbody += `<tr>
                            <td>${item.nama}</td>
                            <td>${item.qty} pcs</td>
                          </tr>`;
            });
            $('#res-items').html(tbody);
            
            $('#resultCard').removeClass('d-none');
        } catch (e) {
             $('#errorCard').text("Format QR Tidak Dikenali. Gagal mem-parsing data.").removeClass('d-none');
        }
    }

    function onScanFailure(error) {
        // Abaikan
    }

    $(document).ready(function() {
        initScanner();

        $('#btn-rescan').click(function(){
            initScanner();
        });
    });
</script>
@endpush
