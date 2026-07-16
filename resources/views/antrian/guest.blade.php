@extends('layouts.auth')

@section('title', 'Pendaftaran Antrian')

@section('content')
    <div class="row w-100 mx-0">
        <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
                <div class="brand-logo text-center mb-4">
                    <h3>Sistem Antrian</h3>
                    <p class="text-muted">Silakan daftar untuk mendapatkan nomor antrian</p>
                </div>

                <h4 class="font-weight-light text-center mb-4">Form Pendaftaran</h4>

                <form id="antrianForm" class="pt-3">
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-lg" id="nama" name="nama"
                               placeholder="Masukkan nama lengkap" required>
                        <small class="form-text text-muted">Contoh: Budi Santoso</small>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                            DAFTAR ANTRIAN
                        </button>
                    </div>
                </form>

                <div id="successMessage" class="mt-4 text-center" style="display: none;">
                    <div class="alert alert-success">
                        <h5>Pendaftaran Berhasil!</h5>
                        <p>Nomor antrian Anda: <strong id="nomorAntrian" class="display-4"></strong></p>
                        <p>Nama: <strong id="namaTamu"></strong></p>
                        <p class="mb-0">Halaman nomor antrian personal telah terbuka di tab baru.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let eventSource = null;
        let reconnectAttempts = 0;
        const maxReconnectAttempts = 5;
        let isFormDisabled = false;

        // Function untuk connect ke SSE
        function connectSSE() {
            if (eventSource) {
                eventSource.close();
            }

            console.log('Connecting to SSE...');
            eventSource = new EventSource('/antrian/stream');

            eventSource.addEventListener('queue-update', function(event) {
                const data = JSON.parse(event.data);
                console.log('SSE Update received:', data);
                updateWaitingCount(data);
                reconnectAttempts = 0; // Reset reconnect counter on successful update
            });

            eventSource.addEventListener('connection-status', function(event) {
                const data = JSON.parse(event.data);
                console.log('Connection status:', data.status);

                if (data.status === 'disconnected') {
                    console.log('SSE disconnected, attempting reconnect...');
                    attemptReconnect();
                }
            });

            eventSource.addEventListener('error', function(error) {
                console.error('SSE Error:', error);
                eventSource.close();

                if (reconnectAttempts < maxReconnectAttempts) {
                    reconnectAttempts++;
                    console.log(`Reconnect attempt ${reconnectAttempts}/${maxReconnectAttempts}`);
                    setTimeout(connectSSE, 3000);
                } else {
                    console.error('Max reconnect attempts reached');
                    showSSError();
                }
            });

            eventSource.addEventListener('open', function() {
                console.log('SSE Connection established');
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

        // Function untuk show SSE error notification
        function showSSError() {
            // Show warning tapi tidak block form
            console.warn('SSE connection failed. Form masih bisa digunakan tapi tanpa real-time update.');
        }

        // Update waiting count di halaman
        function updateWaitingCount(data) {
            const waiting = data.waiting || [];
            const waitingCountElement = document.getElementById('waitingCount');

            if (waitingCountElement) {
                waitingCountElement.textContent = waiting.length;
            }

            // Show notifikasi jika ada antrian baru masuk (bukan dari form ini)
            if (waiting.length > 0 && !isFormDisabled) {
                const latestAntrian = waiting[waiting.length - 1];
                showNewAntrianNotification(latestAntrian);
            }
        }

        // Show notifikasi antrian baru
        function showNewAntrianNotification(antrian) {
            // Cek apakah notifikasi sudah pernah ditampilkan untuk antrian ini
            const notifiedAntrians = JSON.parse(sessionStorage.getItem('notifiedAntrians') || '[]');
            if (notifiedAntrians.includes(antrian.id)) {
                return;
            }

            // Tampilkan notifikasi toast
            const toast = Swal.fire({
                icon: 'info',
                title: 'Antrian Baru Terdaftar',
                text: `Nomor ${antrian.nomor_antrian} - ${antrian.nama}`,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            // Simpan ID antrian yang sudah dinotifikasi
            notifiedAntrians.push(antrian.id);
            sessionStorage.setItem('notifiedAntrians', JSON.stringify(notifiedAntrians));
        }

        // Initialize SSE connection saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            connectSSE();

            // Tambahkan waiting count info ke halaman
            const formTitle = document.querySelector('.auth-form-light h4');
            if (formTitle) {
                const waitingInfo = document.createElement('p');
                waitingInfo.className = 'text-muted mb-4';
                waitingInfo.id = 'waitingCount';
                waitingInfo.innerHTML = '<small>Antrian menunggu: 0</small>';
                formTitle.parentNode.insertBefore(waitingInfo, formTitle.nextSibling);
            }
        });

        // Form submit handler
        document.getElementById('antrianForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const nama = document.getElementById('nama').value;
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            isFormDisabled = true;
            btn.disabled = true;
            btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memproses...';

            try {
                const response = await fetch('/guest/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('[name="_token"]').value
                    },
                    body: JSON.stringify({ nama: nama })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('nomorAntrian').textContent = data.nomor_antrian;
                    document.getElementById('namaTamu').textContent = data.nama;

                    document.getElementById('successMessage').style.display = 'block';
                    document.getElementById('antrianForm').style.display = 'none';

                    window.open('/guest/queue/' + data.id, '_blank');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `
                            <p>Nomor antrian <strong>${data.nomor_antrian}</strong> telah dibuat</p>
                            <p class="mb-0">Silakan tunggu nomor Anda dipanggil</p>
                            <small class="text-muted">Halaman nomor antrian personal telah terbuka di tab baru</small>
                        `,
                        confirmButtonText: 'OK',
                        didClose: () => {
                            // Reset form setelah user klik OK
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan saat mendaftar',
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan koneksi. Silakan coba lagi.',
                    confirmButtonText: 'OK'
                });
            } finally {
                isFormDisabled = false;
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });

        // Cleanup SSE connection saat halaman ditutup
        window.addEventListener('beforeunload', function() {
            if (eventSource) {
                eventSource.close();
            }
        });
    </script>
@endsection
@push('styles')
    <style>
        .swal2-toast {
            padding: 15px 20px !important;
        }
    </style>
@endpush