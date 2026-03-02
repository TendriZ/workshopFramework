<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::latest('timestamp')->get();
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'harga' => 'required|integer|min:0',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'harga' => 'required|integer|min:0',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Cetak Tag Harga PDF - TnJ no 108 (5 kolom x 8 baris = 40 label per halaman)
     */
    public function cetakTagHarga(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|string|exists:barang,id_barang',
            'start_x' => 'required|integer|min:1|max:5',
            'start_y' => 'required|integer|min:1|max:8',
        ]);

        $barangs = Barang::whereIn('id_barang', $request->ids)->get();
        $startX = (int) $request->start_x;
        $startY = (int) $request->start_y;

        $pdf = Pdf::loadView('barang.tag-harga-pdf', compact('barangs', 'startX', 'startY'))
            ->setPaper([0, 0, 609.449, 864.567]) // 215mm x 305mm in points (TnJ 108)
            ->setWarnings(false);

        return $pdf->stream('tag_harga_' . now()->format('Ymd_His') . '.pdf');
    }
}
