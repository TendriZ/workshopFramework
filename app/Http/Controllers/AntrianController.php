<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AntrianController extends Controller
{
    // Cache key untuk SSE update trigger
    private const SSE_UPDATE_KEY = 'antrian_sse_update';

    public function stream(Request $request)
    {
        // Disable time limit untuk SSE connection
        set_time_limit(0);

        // Set headers untuk SSE
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse();

        return response()->stream(function () {
            // Configure output buffering untuk SSE
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            ob_implicit_flush(true);

            // Set execution time limit to infinite
            set_time_limit(0);

            // Send SSE headers dan initial retry directive
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            // Send retry directive untuk auto-reconnect setiap 3 detik jika connection putus
            echo "retry: 3000\n\n";
            flush();

            // Send initial data immediately
            $initialData = [
                'waiting'      => Antrian::waiting()->get(),
                'called'       => Antrian::called()->first(),
                'skipped'      => Antrian::skipped()->get(),
                'last_updated' => now()->toIso8601String(),
                'timestamp'    => time()
            ];
            $this->sendSSEEvent('queue-update', $initialData);

            $lastSentData = $initialData;
            $iterationCount = 0;
            $maxIterations = 3600; // Max 1 hour (3600 seconds / 1 second per iteration)

            while ($iterationCount < $maxIterations) {
                try {
                    // Check untuk trigger update dari cache (force refresh)
                    $forceUpdate = Cache::get(self::SSE_UPDATE_KEY);
                    $currentData = [
                        'waiting'      => Antrian::waiting()->get(),
                        'called'       => Antrian::called()->first(),
                        'skipped'      => Antrian::skipped()->get(),
                        'last_updated' => now()->toIso8601String(),
                        'timestamp'    => time()
                    ];

                    // Check if data changed - send immediately if changed
                    if ($forceUpdate || $this->hasDataChanged($lastSentData, $currentData)) {
                        $this->sendSSEEvent('queue-update', $currentData);
                        $lastSentData = $currentData;

                        // Clear force update trigger
                        if ($forceUpdate) {
                            Cache::forget(self::SSE_UPDATE_KEY);
                        }

                        // Log update untuk debugging
                        \Log::info('SSE Update sent', [
                            'waiting' => count($currentData['waiting']),
                            'called' => $currentData['called'] ? $currentData['called']->nomor_antrian : null,
                            'skipped' => count($currentData['skipped']),
                            'force' => $forceUpdate ? 'true' : 'false'
                        ]);
                    }
                    // Send heartbeat setiap 15 detik untuk keep connection alive
                    elseif ($iterationCount % 30 === 0) {
                        $this->sendSSEEvent('heartbeat', [
                            'timestamp' => time(),
                            'message' => 'Connection alive'
                        ]);
                    }

                    // Check jika connection terputus
                    if (connection_aborted() || connection_status() !== CONNECTION_NORMAL) {
                        $this->sendSSEEvent('connection-status', [
                            'status' => 'disconnected',
                            'message' => 'Client disconnected'
                        ]);
                        break;
                    }

                    // Sleep sebentar sebelum next iteration (lebih sering check)
                    usleep(300000); // 0.3 detik untuk lebih responsive
                    $iterationCount++;

                } catch (\Exception $e) {
                    // Log error tapi continue stream
                    \Log::error('SSE Stream Error: ' . $e->getMessage());

                    // Send error event ke client
                    $this->sendSSEEvent('error', [
                        'message' => 'Stream error occurred',
                        'type' => get_class($e)
                    ]);

                    sleep(1);
                }
            }

            // Send final disconnect event
            $this->sendSSEEvent('connection-status', [
                'status' => 'closed',
                'message' => 'Stream closed normally'
            ]);
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache, no-transform',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);
    }

    /**
     * Bandingkan dua array data antrian untuk melihat perubahan
     */
    private function hasDataChanged($lastData, $currentData)
    {
        // Quick check: bandingkan jumlah antrian di setiap kategori
        if (count($lastData['waiting']) !== count($currentData['waiting'])) {
            return true;
        }

        if (count($lastData['skipped']) !== count($currentData['skipped'])) {
            return true;
        }

        // Check apakah called antrian berubah
        $lastCalledId = $lastData['called'] ? $lastData['called']['id'] : null;
        $currentCalledId = $currentData['called'] ? $currentData['called']['id'] : null;

        if ($lastCalledId !== $currentCalledId) {
            return true;
        }

        // Bandingkan ID dari antrian waiting
        $lastWaitingIds = $lastData['waiting']->pluck('id')->sort()->values();
        $currentWaitingIds = $currentData['waiting']->pluck('id')->sort()->values();

        if (!$lastWaitingIds->equals($currentWaitingIds)) {
            return true;
        }

        return false;
    }

    /**
     * Send SSE event ke client
     */
    private function sendSSEEvent($event, $data)
    {
        echo "event: {$event}\n";
        echo "data: " . json_encode($data) . "\n\n";

        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();
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

        // Trigger SSE update untuk semua connected clients
        Cache::put(self::SSE_UPDATE_KEY, now()->toIso8601String(), 10);
    }
}