<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function barcode()
    {
        return view('scan.barcode');
    }

    public function qr()
    {
        return view('scan.qr');
    }

    public function getPesanan($idpesanan)
    {
        $pesanan = \App\Models\Pesanan::with('detailPesanans.menu')->find($idpesanan);

        if (!$pesanan) {
            return response()->json(['status' => 'error', 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        $items = [];
        foreach($pesanan->detailPesanans as $detail) {
            $items[] = [
                'nama_menu' => $detail->menu->nama_menu ?? 'Unknown',
                'jumlah' => $detail->jumlah,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'idpesanan' => $pesanan->idpesanan,
                'status_bayar' => $pesanan->status_bayar,
                'total' => $pesanan->total,
                'items' => $items
            ]
        ]);
    }
}
