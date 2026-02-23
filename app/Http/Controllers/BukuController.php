<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->latest()->get();
        return view('buku.index', compact('bukus'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:buku,kode',
            'judul' => 'required|string|max:500',
            'pengarang' => 'required|string|max:200',
            'idkategori' => 'required|exists:kategori,idkategori',
        ]);

        Buku::create($request->all());

        return redirect()->route('buku.index')
                         ->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Buku $buku)
    {
        return view('buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $kategoris = Kategori::all();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:buku,kode,' . $buku->idbuku . ',idbuku',
            'judul' => 'required|string|max:500',
            'pengarang' => 'required|string|max:200',
            'idkategori' => 'required|exists:kategori,idkategori',
        ]);

        $buku->update($request->all());

        return redirect()->route('buku.index')
                         ->with('success', 'Buku berhasil diupdate!');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')
                         ->with('success', 'Buku berhasil dihapus!');
    }
}
