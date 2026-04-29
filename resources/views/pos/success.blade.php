@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-0">Pembayaran Berhasil</h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <img src="data:image/png;base64,{{ $qr_code_base64 }}" alt="QR Code Resi" class="img-fluid" style="border: 1px solid #ccc; padding: 10px; border-radius: 8px;">
                    <p class="mt-2 text-muted">Scan QR Code ini untuk melihat detil pesanan</p>
                </div>
                
                <hr>
                
                <h5 class="mb-3">Rincian Pesanan</h5>
                <p><strong>ID Penjualan:</strong> {{ $penjualan->id_penjualan }}</p>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered text-left">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total:</th>
                                <th>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <a href="{{ route('pos.index') }}" class="btn btn-primary mt-3">Kembali ke POS</a>
            </div>
        </div>
    </div>
</div>
@endsection