<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\Builder\Builder;

class PosController extends Controller
{
    public function index()
    {
        return view('ajax-exercise.pos');
    }

    public function cariBarang(Request $request)
    {
        $barang = Barang::query()->find($request->kode);

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Barang tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Barang ditemukan',
            'data' => $barang,
        ]);
    }

    public function bayar(Request $request)
    {
        $items = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|string|exists:barang,id_barang',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.subtotal' => 'required|integer|min:0',
            'total' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $penjualan = Penjualan::create([
                'total' => $request->total,
            ]);

            foreach ($request->items as $item) {
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Pembayaran berhasil disimpan',
                'data' => [
                    'id_penjualan' => $penjualan->id_penjualan,
                    'total' => $penjualan->total,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Gagal menyimpan pembayaran',
            ], 500);
        }
    }

    public function success($id_penjualan)
    {
        $penjualan = Penjualan::query()->where('id_penjualan', '=', $id_penjualan)->firstOrFail();
        $details = PenjualanDetail::query()->where('id_penjualan', '=', $id_penjualan)
            ->join('barang', 'penjualan_detail.id_barang', '=', 'barang.id_barang')
            ->select('barang.nama as nama', 'penjualan_detail.jumlah as qty', 'barang.harga', 'penjualan_detail.subtotal')
            ->get();

        $data = [
            'id_penjualan'   => $penjualan->id_penjualan,
            'timestamp'      => $penjualan->created_at ? $penjualan->created_at->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
            'items'          => $details->toArray(),
            'total'          => $penjualan->total,
            'payment_status' => 'PAID'
        ];

        $qrCode = new \Endroid\QrCode\QrCode(
            data: json_encode($data),
            size: 200,
            margin: 10
        );

        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);

        $qr_code_base64 = base64_encode($result->getString());

        return view('pos.success', compact('penjualan', 'details', 'qr_code_base64'));
    }
}
