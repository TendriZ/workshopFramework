<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode Toko - {{ $toko->nama_toko }}</title>
    <link rel="stylesheet" href="{{ asset('template/vendors/mdi/css/materialdesignicons.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            padding: 20px;
        }

        .barcode-card {
            border: 3px dashed #4e73df;
            display: inline-block;
            padding: 40px;
            border-radius: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            max-width: 400px;
            margin: 0 auto;
        }

        .barcode-container {
            margin: 30px 0;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .barcode-container img {
            max-width: 100%;
            height: auto;
        }

        .toko-name {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .accuracy-badge {
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin: 10px 0;
        }

        .barcode-id {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 20px 0;
            background: white;
            color: #4e73df;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .info-section {
            margin-top: 20px;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #4e73df;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .print-btn:hover {
            background: #2e59d9;
        }

        .print-btn:active {
            transform: scale(0.95);
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                margin-top: 0;
            }

            .barcode-card {
                box-shadow: none;
                border-color: #4e73df;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">
        <i class="mdi mdi-printer"></i> Cetak
    </button>

    <div class="barcode-card">
        <i class="mdi mdi-store" style="font-size: 3rem; margin-bottom: 15px;"></i>
        <div class="toko-name">{{ $toko->nama_toko }}</div>
        <div class="accuracy-badge">
            <i class="mdi mdi-crosshairs-gps"></i> Akurasi Pusat: {{ number_format($toko->accuracy, 1) }}m
        </div>

        @if($toko->alamat)
            <div class="info-section">
                <i class="mdi mdi-map-marker"></i> {{ $toko->alamat }}
            </div>
        @endif

        <div class="barcode-container">
            <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR Code Toko">
        </div>

        <div class="barcode-id">
            <i class="mdi mdi-barcode"></i> {{ $toko->barcode }}
        </div>

        <div class="info-section">
            <small>Scan untuk validasi kunjungan sales</small>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>