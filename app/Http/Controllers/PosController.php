<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        return view('ajax-exercise.pos');
    }

    public function cariBarang(Request $request)
    {
        $barang = Barang::find($request->kode);

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
}
