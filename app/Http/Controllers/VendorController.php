<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // Dashboard vendor
    public function index()
    {
        // Ambil data vendor yang sedang login dari session
        $vendorId = session('vendor_id');
        $vendor   = Vendor::with('menus')->findOrFail($vendorId);

        // Ambil pesanan lunas
        $pesanan = Pesanan::with(['detailPesanans.menu'])
            ->where('status_bayar', 1)
            ->where('idvendor', $vendorId)
            ->orderBy('timestamp', 'desc')
            ->get();

        return view('kantin.dashboard', compact('vendor', 'pesanan'));
    }

    // Tambah menu baru
    public function storeMenu(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
        ]);

        Menu::create([
            'idvendor'  => session('vendor_id'), // otomatis pakai vendor yg login
            'nama_menu' => $request->nama_menu,
            'harga'     => $request->harga,
        ]);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    // Hapus menu
    public function destroyMenu($id)
    {
        $menu = Menu::findOrFail($id);

        // Pastikan menu milik vendor yang login
        if ($menu->idvendor != session('vendor_id')) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'Tidak diizinkan menghapus menu ini.');
        }

        $menu->delete();
        return redirect()->route('vendor.dashboard')
            ->with('success', 'Menu berhasil dihapus.');
    }
}