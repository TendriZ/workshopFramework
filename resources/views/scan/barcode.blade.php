@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Scanner Barcode Label (Praktikum 1)</h2>
    <div class="row mt-4">
        <div class="col-md-6">
            <div id="reader" width="600px"></div>
        </div>
        <div class="col-md-6">
            <div id="resultCard" class="card d-none">
                <div class="card-header bg-success text-white">
                    Berhasil Ditemukan!
                </div>
                <div class="card-body">
                    <p><strong>ID Barang:</strong> <span id="res-id">-</span></p>
                    <p><strong>Nama:</strong> <span id="res-nama">-</span></p>
                    <p><strong>Harga:</strong> <span id="res-harga">-</span></p>
                </div>
            </div>
            <!-- Pesan error sementara bila tidak ditemukan di DB -->
            <div id="errorCard" class="alert alert-danger d-none mt-2">
            </div>
        </div>
    </div>
</div>

<!-- Library HTML5 QR Code (Supports barcode Code128) -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    // Inisialisasi Audio beep
    const beepAudio = new Audio("{{ asset('audio/beep.mp3') }}");
    let scanner = null;

    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan scan a.k.a Clear scanner
        scanner.clear();
        
        // Bunyikan beep pendek
        beepAudio.currentTime = 0;
        beepAudio.play().catch(e => console.log('Audio Play Error:', e));

        // Panggil API pencaharian Barang menggunakan AJAX
        $('#errorCard').addClass('d-none');
        
        $.ajax({
            url: "{{ route('pos.cari') }}",
            type: "GET",
            data: { kode: decodedText },
            success: function(res) {
                if(res.status === 'success') {
                    $('#res-id').text(res.data.id_barang);
                    $('#res-nama').text(res.data.nama);
                    $('#res-harga').text('Rp ' + res.data.harga.toLocaleString('id-ID'));
                    $('#resultCard').removeClass('d-none');
                }
            },
            error: function(err) {
                $('#errorCard').text('Kode terbaca ("' + decodedText + '"), namun tidak ditemukan di Database.').removeClass('d-none');
            }
        });
    }

    function onScanFailure(error) {
        // Abaikan pesan error gagal deteksi terus menerus.
    }

    $(document).ready(function() {
        scanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 150} },
            /* verbose= */ false
        );
        scanner.render(onScanSuccess, onScanFailure);
    });
</script>
@endsection
