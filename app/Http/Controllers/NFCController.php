<?php

namespace App\Http\Controllers;

use App\Models\KartuNfc;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NFCController extends Controller
{
    public function index()
    {
        $kartuAktif = KartuNfc::where('is_active', true)->count();
        $totalPeserta = \App\Models\Peserta::count();
        $absensiHariIni = Absensi::whereDate('created_at', today())->count();
        $absensiMasuk = Absensi::whereDate('created_at', today())->where('status', 'masuk')->count();
        $absensiKeluar = Absensi::whereDate('created_at', today())->where('status', 'keluar')->count();

        return view('nfc.index', compact(
            'kartuAktif',
            'totalPeserta',
            'absensiHariIni',
            'absensiMasuk',
            'absensiKeluar'
        ));
    }

    public function scan()
    {
        return view('nfc.scan');
    }

    public function apiDaftarKartu()
    {
        $kartu = KartuNfc::where('is_active', true)
            ->with('peserta')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $kartu
        ]);
    }

    public function apiAbsen(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|max:50'
        ]);

        $kartu = KartuNfc::with('peserta')
            ->where('serial_number', $request->serial_number)
            ->where('is_active', true)
            ->first();

        if (!$kartu) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartu NFC tidak ditemukan atau tidak aktif'
            ], 404);
        }

        // Cek apakah kartu memiliki peserta terdaftar
        if (!$kartu->peserta) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Kartu terdaftar tapi belum ada peserta yang terhubung'
            ]);
        }

        // Simpan data absensi
        $lastStatus = Absensi::where('kartu_nfc_id', $kartu->id)
            ->latest('waktu_scan')
            ->first();

        $status = 'masuk';
        if ($lastStatus && $lastStatus->status === 'masuk') {
            $status = 'keluar';
        }

        Absensi::create([
            'kartu_nfc_id' => $kartu->id,
            'waktu_scan' => now(),
            'status' => $status,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil direkam',
            'data' => [
                'kartu' => $kartu,
                'peserta' => $kartu->peserta,
                'status' => $status,
                'waktu_scan' => now()->format('H:i:s')
            ]
        ]);
    }

    public function apiRiwayat(Request $request)
    {
        $tanggal = $request->get('tanggal', today());

        $absensi = Absensi::whereDate('created_at', $tanggal)
            ->with(['kartuNfc.peserta'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $absensi,
            'tanggal' => $tanggal
        ]);
    }

    // CRUD Kartu NFC
    public function daftar()
    {
        $kartu = KartuNfc::with('peserta')->orderBy('nama_kartu')->get();
        return view('nfc.daftar', compact('kartu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kartu' => 'required|string|max:100',
            'jenis' => 'required|in:peserta,dosen,staff',
            'nim' => 'nullable|string|max:20|required_if:jenis,peserta',
            'nama' => 'nullable|string|max:100|required_if:jenis,peserta',
            'kelas' => 'nullable|string|max:50'
        ]);

        $kartu = KartuNfc::create([
            'nama_kartu' => $request->nama_kartu,
            'jenis' => $request->jenis,
            'is_active' => true
        ]);

        // Jika jenis adalah peserta, buat juga data peserta
        if ($request->jenis === 'peserta') {
            \App\Models\Peserta::create([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'kartu_nfc_id' => $kartu->id,
                'kelas' => $request->kelas
            ]);
        }

        return redirect()->route('nfc.daftar')
            ->with('success', 'Kartu NFC berhasil didaftarkan');
    }

    public function edit($id)
    {
        $kartu = KartuNfc::findOrFail($id);
        return view('nfc.edit', compact('kartu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kartu' => 'required|string|max:100',
            'jenis' => 'required|in:peserta,dosen,staff',
            'nim' => 'nullable|string|max:20',
            'nama' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:50'
        ]);

        $kartu = KartuNfc::findOrFail($id);
        $kartu->update([
            'nama_kartu' => $request->nama_kartu,
            'jenis' => $request->jenis
        ]);

        return redirect()->route('nfc.daftar')
            ->with('success', 'Kartu NFC berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kartu = KartuNfc::findOrFail($id);

        // Soft delete (tandai is_active = false)
        $kartu->update(['is_active' => false]);

        return redirect()->route('nfc.daftar')
            ->with('success', 'Kartu NFC berhasil dinonaktifkan');
    }

    public function apiScanSerial()
    {
        $request->validate([
            'serial_number' => 'required|string|max:50'
        ]);

        $kartu = KartuNfc::where('serial_number', $request->serial_number)->first();

        if (!$kartu) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Kartu tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => 'found',
            'data' => $kartu
        ]);
    }

    public function apiGetAllKartu()
    {
        $kartu = KartuNfc::with('peserta')->orderBy('nama_kartu')->get();

        return response()->json([
            'status' => 'success',
            'data' => $kartu
        ]);
    }
}