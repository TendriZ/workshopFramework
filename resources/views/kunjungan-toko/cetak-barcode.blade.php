<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode Toko - {{ $toko->nama_toko }}</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        .barcode-card {
            border: 2px dashed #000;
            display: inline-block;
            padding: 30px;
            border-radius: 10px;
        }
        .barcode-container { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="barcode-card">
        <h3>Titik Toko: {{ $toko->nama_toko }}</h3>
        <p>Akurasi Pusat: {{ $toko->accuracy }}m</p>
        <div class="barcode-container">
            <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR Code Toko" style="max-height: 200px;">
        </div>
        <h4>ID: <strong>{{ $toko->barcode }}</strong></h4>
    </div>
    
    <script>
        window.print();
    </script>
</body>
</html>