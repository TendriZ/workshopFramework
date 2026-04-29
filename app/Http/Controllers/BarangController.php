<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

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
        $barang->query()->where('id_barang', $barang->id_barang)->delete();

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Cetak Tag Harga PDF - TnJ no 108 (5 kolom x 8 baris = 40 label per halaman)
     */
    public function cetakTagHarga(Request $request) {
        $validated = $request->validate([
            'x_start' => 'required|integer|min:1|max:5',
            'y_start' => 'required|integer|min:1|max:8',
            'ids'     => 'required|array|min:1',
            'ids.*'   => 'required|string|exists:barang,id_barang',
        ]);

        // Fetch selected barangs and convert to array format for the template
        $barangs = Barang::query()->whereIn('id_barang', $validated['ids'], 'and', false)->get();
        $generator = new BarcodeGeneratorPNG();
        $items = $barangs->map(function ($b) use ($generator) {
            return [
                'id_barang' => $b->id_barang,
                'nama'      => $b->nama,
                'harga'     => $b->harga,
                'timestamp' => $b->timestamp,
                'barcode'   => base64_encode($generator->getBarcode($b->id_barang, $generator::TYPE_CODE_128)),
            ];
        })->toArray();

        $x_start = (int) $validated['x_start'];
        $y_start = (int) $validated['y_start'];

        // 222mm x 185mm in points (1mm = 2.83465pt)
        $pdf = Pdf::loadView('barang.tag-harga-pdf', compact('items', 'x_start', 'y_start'))
            ->setPaper([0, 0, 629.29, 524.41])
            ->setWarnings(false);

        return $pdf->stream('label.pdf');
    }
}
