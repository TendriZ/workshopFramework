<?php

namespace App\Http\Controllers;

use App\Models\LokasiToko;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;

class KunjunganTokoController extends Controller
{
    public function index()
    {
        $tokos = LokasiToko::orderBy('nama_toko', 'ASC')->get();
        return view('kunjungan-toko.index', compact('tokos'));
    }

    public function create()
    {
        return view('kunjungan-toko.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|max:8|unique:lokasi_toko,barcode',
            'nama_toko' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'required|numeric',
        ]);

        LokasiToko::create($request->all());

        return redirect()->route('kunjungan.index')->with('success', 'Lokasi toko berhasil ditambahkan!');
    }

    public function show($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);
        return view('kunjungan-toko.show', compact('toko'));
    }

    public function edit($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);
        return view('kunjungan-toko.edit', compact('toko'));
    }

    public function update(Request $request, $barcode)
    {
        $request->validate([
            'barcode' => 'required|string|max:8|unique:lokasi_toko,barcode,' . $barcode . ',barcode',
            'nama_toko' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'required|numeric',
        ]);

        $toko = LokasiToko::findOrFail($barcode);
        $toko->update($request->all());

        return redirect()->route('kunjungan.index')->with('success', 'Lokasi toko berhasil diperbarui!');
    }

    public function destroy($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);
        $toko->delete();

        return redirect()->route('kunjungan.index')->with('success', 'Lokasi toko berhasil dihapus!');
    }

    public function cetakBarcode($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);

        $qrCode = new QrCode($toko->barcode);
        $writer = new SvgWriter();
        $qrResult = $writer->write($qrCode);
        $qrBase64 = base64_encode($qrResult->getString());

        return view('kunjungan-toko.cetak-barcode', compact('toko', 'qrBase64'));
    }

    public function scanVisit()
    {
        return view('kunjungan-toko.scan');
    }

    public function apiToko($barcode)
    {
        $toko = LokasiToko::where('barcode', $barcode)->first();

        if ($toko) {
            return response()->json([
                'status' => 'success',
                'data' => $toko
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Toko tidak ditemukan'
            ], 404);
        }
    }
}