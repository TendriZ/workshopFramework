<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    public function stream(Request $request)
    {
        set_time_limit(0);

        return response()->stream(function () {
            while (true) {
                // FIX: Query langsung ke database untuk real-time update
                // Cache dihapus di sini untuk mencegah stale data
                $data = [
                    'waiting' => Antrian::waiting()->get(),
                    'called' => Antrian::called()->first(),
                    'skipped' => Antrian::skipped()->get(),
                    'last_updated' => now()->toIso8601String()
                ];

                echo 'event: queue-update' . PHP_EOL;
                echo 'data: ' . json_encode($data) . PHP_EOL;
                echo PHP_EOL;

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                if (connection_aborted()) {
                    break;
                }

                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no'
        ]);
    }

    public function index()
    {
        return view('antrian.guest');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100'
        ]);

        $nomor = Antrian::generateNomor();

        $antrian = Antrian::create([
            'nomor_antrian' => $nomor,
            'nama' => $validated['nama'],
            'status' => 'waiting'
        ]);

        $this->updateCache();

        return response()->json([
            'success' => true,
            'id' => $antrian->id,
            'nomor_antrian' => $antrian->nomor_antrian,
            'nama' => $antrian->nama
        ]);
    }

    public function queue($id)
    {
        $antrian = Antrian::findOrFail($id);
        return view('antrian.queue', compact('antrian'));
    }

    public function admin()
    {
        return view('antrian.admin');
    }

    public function call(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:antrians,id',
            'loket' => 'nullable|string|max:10'
        ]);

        $antrian = Antrian::findOrFail($validated['id']);
        $antrian->update([
            'status' => 'called',
            'loket' => $validated['loket'] ?? '1',
            'called_at' => now()
        ]);

        $this->updateCache();

        return response()->json([
            'success' => true,
            'message' => "Nomor {$antrian->nomor_antrian} berhasil dipanggil"
        ]);
    }

    public function callNext(Request $request)
    {
        $validated = $request->validate([
            'loket' => 'nullable|string|max:10'
        ]);

        $nextAntrian = Antrian::waiting()->first();

        if (!$nextAntrian) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada antrian yang menunggu'
            ]);
        }

        $nextAntrian->update([
            'status' => 'called',
            'loket' => $validated['loket'] ?? '1',
            'called_at' => now()
        ]);

        $this->updateCache();

        return response()->json([
            'success' => true,
            'message' => "Nomor {$nextAntrian->nomor_antrian} berhasil dipanggil",
            'antrian' => $nextAntrian
        ]);
    }

    public function skip(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:antrians,id'
        ]);

        $antrian = Antrian::findOrFail($validated['id']);
        $antrian->update([
            'status' => 'skipped'
        ]);

        $this->updateCache();

        return response()->json([
            'success' => true,
            'message' => "Nomor {$antrian->nomor_antrian} dilewati"
        ]);
    }

    public function papan()
    {
        return view('antrian.papan');
    }

    private function updateCache()
    {
        // Cache TTL dikurangi dari 60 detik ke 5 detik untuk prevent stale data
        // Cache digunakan sebagai optimization, bukan single source of truth
        Cache::put('antrian_data', [
            'waiting' => Antrian::waiting()->get(),
            'called' => Antrian::called()->first(),
            'skipped' => Antrian::skipped()->get(),
            'last_updated' => now()->toIso8601String()
        ], 5);
    }
}