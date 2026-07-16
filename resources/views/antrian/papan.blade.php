@extends('layouts.auth')

@section('title', 'Papan Antrian')

@section('content')
<div class="container-fluid h-100">
    <div class="row h-100">
        <div class="col-12">
            <div class="card border-0 shadow h-100">
                <div class="card-body text-center py-5">
                    <h3 class="mb-3">Papan Antrian</h3>

                    <div id="soundActivation" class="mb-4">
                        <button id="btnActivateSound" class="btn btn-info btn-lg">
                            <i class="mdi mdi-volume-up"></i> Aktifkan Suara
                        </button>
                        <p class="text-muted mt-2">Klik tombol ini untuk mengaktifkan notifikasi suara (diperlukan sekali)</p>
                    </div>

                    <div class="my-5">
                        <p class="text-muted mb-2">Nomor Sedang Dipanggil</p>
                        <div id="currentNumber" class="display-1 font-weight-bold text-primary">
                            ---
                        </div>
                        <div id="currentName" class="h3 mt-3">
                            Menunggu antrian...
                        </div>
                        <div id="loketInfo" class="badge badge-secondary mt-2" style="display: none;">
                            Loket <span id="loketNumber">1</span>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6 mx-auto">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Antrian Menunggu</h5>
                                </div>
                                <div class="card-body">
                                    <ul id="waitingList" class="list-unstyled">
                                        <li class="text-muted">Memuat antrian...</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <audio id="bellSound" preload="auto">
                        <source src="/audio/dingdong.mp3" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        let soundEnabled = false;
        let lastCalledId = null;
        let eventSource = null;
        let reconnectAttempts = 0;
        const maxReconnectAttempts = 10;

        document.getElementById('btnActivateSound').addEventListener('click', function() {
            soundEnabled = true;
            document.getElementById('soundActivation').style.display = 'none';

            // Set user preference di localStorage
            localStorage.setItem('soundEnabled', 'true');

            Swal.fire({
                icon: 'success',
                title: 'Suara Diaktifkan!',
                text: 'Notifikasi suara akan berbunyi saat nomor dipanggil',
                timer: 2000,
                showConfirmButton: false
            });
        });

        // Load sound preference dari localStorage
        if (localStorage.getItem('soundEnabled') === 'true') {
            soundEnabled = true;
            document.getElementById('soundActivation').style.display = 'none';
        }

        // Function untuk connect ke SSE
        function connectSSE() {
            if (eventSource) {
                eventSource.close();
            }

            console.log('[PAPAN] Connecting to SSE...');
            eventSource = new EventSource('/antrian/stream');

            eventSource.addEventListener('queue-update', function(event) {
                const data = JSON.parse(event.data);
                console.log('[PAPAN] SSE Update received:', data);
                updateUI(data);
                reconnectAttempts = 0; // Reset reconnect counter
            });

            eventSource.addEventListener('connection-status', function(event) {
                const data = JSON.parse(event.data);
                console.log('[PAPAN] Connection status:', data.status);

                if (data.status === 'disconnected') {
                    console.log('[PAPAN] SSE disconnected, attempting reconnect...');
                    attemptReconnect();
                } else if (data.status === 'closed') {
                    console.log('[PAPAN] SSE closed normally');
                }
            });

            eventSource.addEventListener('error', function(error) {
                console.error('[PAPAN] SSE Error:', error);
                eventSource.close();

                if (reconnectAttempts < maxReconnectAttempts) {
                    reconnectAttempts++;
                    const delay = reconnectAttempts * 1000; // Exponential backoff
                    console.log(`[PAPAN] Reconnect attempt ${reconnectAttempts}/${maxReconnectAttempts} in ${delay}ms`);
                    setTimeout(connectSSE, delay);
                } else {
                    console.error('[PAPAN] Max reconnect attempts reached');
                    showReconnectFailedNotification();
                }
            });

            eventSource.addEventListener('open', function() {
                console.log('[PAPAN] SSE Connection established');
                reconnectAttempts = 0;
                hideReconnectFailedNotification();
            });
        }

        // Function untuk reconnect SSE
        function attemptReconnect() {
            if (reconnectAttempts < maxReconnectAttempts) {
                reconnectAttempts++;
                setTimeout(connectSSE, 3000);
            }
        }

        // Function untuk show reconnect failed notification
        function showReconnectFailedNotification() {
            const existingToast = document.querySelector('.swal2-container');
            if (existingToast) return;

            Swal.fire({
                icon: 'warning',
                title: 'Koneksi Terputus',
                text: 'Gagal terhubung ke server. Memuat ulang halaman...',
                timer: 3000,
                showConfirmButton: false,
                didClose: () => {
                    location.reload();
                }
            });
        }

        // Function untuk hide reconnect failed notification
        function hideReconnectFailedNotification() {
            // Reset UI state if needed
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

        function updateUI(data) {
            const waiting = data.waiting || [];
            const called = data.called || null;
            const skipped = data.skipped || [];

            // Update current number (yang sedang dipanggil)
            if (called) {
                const numberEl = document.getElementById('currentNumber');
                const nameEl = document.getElementById('currentName');
                const loketInfoEl = document.getElementById('loketInfo');
                const loketNumberEl = document.getElementById('loketNumber');

                // Cek apakah called berubah (new antrian dipanggil)
                if (called.id !== lastCalledId) {
                    lastCalledId = called.id;

                    // Update display dengan animasi
                    numberEl.style.opacity = '0';
                    setTimeout(() => {
                        numberEl.textContent = called.nomor_antrian;
                        nameEl.textContent = called.nama;
                        numberEl.style.opacity = '1';

                        if (called.loket) {
                            loketNumberEl.textContent = called.loket;
                            loketInfoEl.style.display = 'inline-block';
                        } else {
                            loketInfoEl.style.display = 'none';
                        }

                        // Play notification sound
                        if (soundEnabled) {
                            playNotification(called.nomor_antrian, called.nama, called.loket || '1');
                        }

                        // Highlight animation
                        numberEl.classList.add('text-success');
                        setTimeout(() => {
                            numberEl.classList.remove('text-success');
                        }, 2000);
                    }, 200);
                }
            } else {
                document.getElementById('currentNumber').textContent = '---';
                document.getElementById('currentName').textContent = 'Menunggu antrian...';
                document.getElementById('loketInfo').style.display = 'none';
                lastCalledId = null;
            }

            // Update waiting list
            const waitingListEl = document.getElementById('waitingList');
            if (waiting.length === 0) {
                waitingListEl.innerHTML = '<li class="text-muted">Tidak ada antrian</li>';
            } else {
                const displayCount = Math.min(waiting.length, 5);
                const items = waiting.slice(0, displayCount).map((a, index) => `
                    <li class="mb-2 ${index === 0 ? 'font-weight-bold text-primary' : ''}">
                        ${a.nomor_antrian} - ${a.nama}
                    </li>
                `).join('');

                let moreText = '';
                if (waiting.length > displayCount) {
                    moreText = `<li class="text-muted small">... dan ${waiting.length - displayCount} antrian lainnya</li>`;
                }

                waitingListEl.innerHTML = items + moreText;
            }
        }

        function playNotification(nomor, nama, loket) {
            const audio = document.getElementById('bellSound');

            if ('speechSynthesis' in window) {
                // Cancel any ongoing speech
                window.speechSynthesis.cancel();

                // Play bell sound first
                audio.currentTime = 0;
                audio.play().catch(e => console.error('Audio play error:', e));

                // Set up speech synthesis to play after bell
                audio.onended = function() {
                    const message = new SpeechSynthesisUtterance(
                        `Ting tong. Nomor antrian ${nomor}. ${nama}. Silakan masuk ke ruang dokter meta. Loket ${loket}.`
                    );

                    message.lang = 'id-ID';
                    message.rate = 0.85;
                    message.pitch = 1.0;
                    message.volume = 1.0;

                    window.speechSynthesis.speak(message);
                };
            } else {
                // Fallback: hanya play bell sound
                audio.currentTime = 0;
                audio.play().catch(e => console.error('Audio play error:', e));
            }
        }

        // Visibility API: Handle ketika tab tidak aktif
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                console.log('[PAPAN] Tab hidden, SSE masih aktif di background');
            } else {
                console.log('[PAPAN] Tab visible, SSE masih aktif');
                // Refresh data ketika tab kembali aktif
                // Data akan terupdate otomatis lewat SSE
            }
        });
    </script>
@endpush