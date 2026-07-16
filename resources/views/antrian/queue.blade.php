@extends('layouts.auth')

@section('title', 'Nomor Antrian - ' . $antrian->nomor_antrian)

@section('content')
    <div class="container mt-5">
        <div class="card border-0 shadow">
            <div class="card-body text-center py-5">
                <h3 class="card-title mb-3">Nomor Antrian Anda</h3>

                <div class="my-4">
                    <div class="display-1 font-weight-bold text-primary">
                        {{ $antrian->nomor_antrian }}
                    </div>
                </div>

                <div class="mb-4">
                    <h4 class="text-muted">{{ $antrian->nama }}</h4>
                    <span class="badge badge-{{ $antrian->status === 'waiting' ? 'warning' : ($antrian->status === 'called' ? 'success' : 'danger') }} badge-pill">
                        @if($antrian->status === 'waiting')
                            Menunggu
                        @elseif($antrian->status === 'called')
                            Sudah Dipanggil
                        @else
                            Terlewat
                        @endif
                    </span>
                </div>

                @if($antrian->status === 'called')
                    <div class="alert alert-success">
                        <h5>Silakan Masuk!</h5>
                        <p class="mb-0">Nomor Anda telah dipanggil. Silakan menuju ke loket {{ $antrian->loket ?? '1' }}</p>
                        <p class="mb-0 small text-muted">Dipanggil pada: {{ $antrian->called_at?->format('H:i:s') }}</p>
                    </div>
                @elseif($antrian->status === 'waiting')
                    <div class="alert alert-info">
                        <p class="mb-0">Mohon tunggu sampai nomor Anda dipanggil.</p>
                        <p class="mb-0 small">Status akan diperbarui secara otomatis.</p>
                    </div>
                @endif

                <div id="realtimeStatus" class="mt-4" style="display: none;">
                    <div class="text-center">
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                        <span class="ml-2">Menerima update...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="/guest" class="btn btn-outline-primary">
                <i class="mdi mdi-arrow-left"></i> Kembali ke Form Pendaftaran
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const antrianId = {{ $antrian->id }};
        let eventSource = null;
        let reconnectAttempts = 0;
        const maxReconnectAttempts = 10;

        // Function untuk connect ke SSE
        function connectSSE() {
            if (eventSource) {
                eventSource.close();
            }

            console.log('[QUEUE] Connecting to SSE for antrian ID:', antrianId);
            eventSource = new EventSource('/antrian/stream');

            eventSource.addEventListener('queue-update', function(event) {
                const data = JSON.parse(event.data);
                console.log('[QUEUE] SSE Update received:', data);
                updateUI(data);
                reconnectAttempts = 0; // Reset reconnect counter
            });

            eventSource.addEventListener('connection-status', function(event) {
                const data = JSON.parse(event.data);
                console.log('[QUEUE] Connection status:', data.status);

                if (data.status === 'disconnected') {
                    console.log('[QUEUE] SSE disconnected, attempting reconnect...');
                    attemptReconnect();
                }
            });

            eventSource.addEventListener('error', function(error) {
                console.error('[QUEUE] SSE Error:', error);
                eventSource.close();

                if (reconnectAttempts < maxReconnectAttempts) {
                    reconnectAttempts++;
                    const delay = reconnectAttempts * 1000; // Exponential backoff
                    console.log(`[QUEUE] Reconnect attempt ${reconnectAttempts}/${maxReconnectAttempts} in ${delay}ms`);
                    setTimeout(connectSSE, delay);
                } else {
                    console.error('[QUEUE] Max reconnect attempts reached');
                }
            });

            eventSource.addEventListener('open', function() {
                console.log('[QUEUE] SSE Connection established');
                reconnectAttempts = 0;
            });
        }

        // Function untuk reconnect SSE
        function attemptReconnect() {
            if (reconnectAttempts < maxReconnectAttempts) {
                reconnectAttempts++;
                setTimeout(connectSSE, 3000);
            }
        }

        function updateUI(data) {
            const waitingList = data.waiting || [];
            const calledList = data.called ? [data.called] : [];
            const skippedList = data.skipped || [];

            // Check jika antrian ini ada di waiting
            const waitingAntrian = waitingList.find(a => a.id === antrianId);
            // Check jika antrian ini sudah dipanggil
            const calledAntrian = calledList.find(a => a.id === antrianId);
            // Check jika antrian ini dilewati
            const skippedAntrian = skippedList.find(a => a.id === antrianId);

            if (calledAntrian) {
                // Reload halaman jika status berjadi called
                window.location.reload();
            } else if (skippedAntrian) {
                // Update UI untuk skipped status
                const statusBadge = document.querySelector('.badge');
                const alertBox = document.querySelector('.alert');

                if (statusBadge) {
                    statusBadge.className = 'badge badge-danger badge-pill';
                    statusBadge.textContent = 'Terlewat';
                }

                if (alertBox) {
                    alertBox.className = 'alert alert-danger';
                    alertBox.innerHTML = `
                        <h5>Nomor Antrian Dilewati</h5>
                        <p class="mb-0">Mohon maaf, nomor antrian Anda telah dilewati.</p>
                        <p class="mb-0 small text-muted">Silakan daftar ulang.</p>
                    `;
                }
            }
        }

        // Initialize SSE connection saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            connectSSE();
        });

        // Cleanup SSE connection saat halaman ditutup
        window.addEventListener('beforeunload', function() {
            if (eventSource) {
                eventSource.close();
            }
        });
    </script>
@endpush