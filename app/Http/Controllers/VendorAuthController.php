<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorAuthController extends Controller
{
    // Tampilkan form login vendor
    public function showLogin()
    {
        // Kalau sudah login, redirect ke dashboard
        if (session('vendor_id')) {
            return redirect()->route('vendor.dashboard');
        }
        
        $vendors = Vendor::all();

        return view('kantin.login', compact('vendors'));
    }

    // Proses login vendor
    public function login(Request $request)
    {
        $request->validate([
            'idvendor' => 'required',
        ]);

        $vendor = Vendor::where('idvendor', $request->idvendor)->first();

        if (!$vendor) {
            return back()->with('error', 'Vendor tidak ditemukan.');
        }

        // Simpan data vendor ke session
        session([
            'vendor_id'   => $vendor->idvendor,
            'vendor_nama' => $vendor->nama_vendor,
        ]);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Selamat datang, ' . $vendor->nama_vendor . '!');
    }

    // Logout vendor
    public function logout()
    {
        session()->forget(['vendor_id', 'vendor_nama']);
        return redirect()->route('vendor.login')
            ->with('success', 'Berhasil logout.');
    }
}