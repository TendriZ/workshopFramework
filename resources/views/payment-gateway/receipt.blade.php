<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran / QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 text-center">
        <div class="card shadow-sm mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <h4 class="card-title text-success">Pembayaran Berhasil!</h4>
                <p class="text-muted">Simpan QR Code ini untuk ditunjukkan kepada vendor kantin.</p>
                <hr>
                <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR Code Pesanan" class="img-fluid mb-3" style="max-width: 250px;">
                <h5>ID Pesanan: <strong>{{ $pesanan->idpesanan }}</strong></h5>
                <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">{{ strtoupper($pesanan->status_bayar) }}</span></p>
                <p><strong>Total:</strong> Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                
                <p class="small text-muted mt-3">Link URL halaman ini (<i>{{ url()->current() }}</i>) dapat Anda simpan/bookmark untuk diakses kembali kapan saja meskipun tab ini ditutup.</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('pg.customer') }}" class="btn btn-outline-secondary w-100">Beli Lagi</a>
            </div>
        </div>
    </div>
</body>
</html>
