@extends('layouts.app')

@section('title', 'Payment Gateway - Pesanan Lunas')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Vendor - Pesanan Lunas</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pesanan dengan Status Bayar Lunas</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Vendor</th>
                                <th>Customer</th>
                                <th>Metode Bayar</th>
                                <th>Total</th>
                                <th>Detail Item</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->idpesanan }}</td>
                                    <td>{{ $order->vendor?->nama_vendor }}</td>
                                    <td>{{ $order->nama }}</td>
                                    <td>{{ strtoupper(str_replace('_', ' ', $order->metode_bayar ?? '-')) }}</td>
                                    <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td>
                                        <ul class="mb-0 ps-3">
                                            @foreach($order->detailPesanans as $detail)
                                                <li>
                                                    {{ $detail->menu?->nama_menu }} - {{ $detail->jumlah }} x Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                                    = Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada pesanan lunas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
