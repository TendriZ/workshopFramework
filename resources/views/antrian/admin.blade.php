@extends('layouts.app')

@section('title', 'Dashboard Antrian - Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard Antrian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Dashboard Antrian Real-Time</h4>
                <p class="card-description">Kelola antrian dengan notifikasi real-time</p>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Menunggu</h5>
                                <h2 id="waitingCount" class="display-4">0</h2>
                                <p class="mb-0">Antrian aktif</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Sedang Dipanggil</h5>
                                <h2 id="currentNumber" class="display-4">-</h2>
                                <p class="mb-0" id="currentName">Belum ada</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title">Terlewat</h5>
                                <h2 id="skippedCount" class="display-4">0</h2>
                                <p class="mb-0">Double-click untuk panggil</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Antrian Menunggu</h5>
                                <button type="button" id="btnCallNext" class="btn btn-success btn-sm float-right">
                                    <i class="mdi mdi-play"></i> Panggil Berikutnya
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nomor</th>
                                                <th>Nama</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="waitingList">
                                            <tr>
                                                <td colspan="3" class="text-center">Memuat...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Antrian Terlewat</h5>
                                <small class="text-muted">Double-click untuk memanggil ulang</small>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nomor</th>
                                                <th>Nama</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        <tbody id="skippedList">
                                            <tr>
                                                <td colspan="3" class="text-center">Tidak ada</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const eventSource = new EventSource('/antrian/stream');

        eventSource.addEventListener('queue-update', function(event) {
            const data = JSON.parse(event.data);
            updateUI(data);
        });

        eventSource.onerror = function(error) {
            console.error('SSE Error:', error);
        };

        function updateUI(data) {
            const waiting = data.waiting || [];
            const called = data.called || null;
            const skipped = data.skipped || [];

            document.getElementById('waitingCount').textContent = waiting.length;
            document.getElementById('skippedCount').textContent = skipped.length;

            if (called) {
                document.getElementById('currentNumber').textContent = called.nomor_antrian;
                document.getElementById('currentName').textContent = called.nama;
            } else {
                document.getElementById('currentNumber').textContent = '-';
                document.getElementById('currentName').textContent = 'Belum ada';
            }

            const waitingList = document.getElementById('waitingList');
            if (waiting.length === 0) {
                waitingList.innerHTML = '<tr><td colspan="3" class="text-center">Tidak ada antrian</td></tr>';
            } else {
                waitingList.innerHTML = waiting.map(a => `
                    <tr>
                        <td><strong>${a.nomor_antrian}</strong></td>
                        <td>${a.nama}</td>
                        <td>
                            <button onclick="callAntrian(${a.id})" class="btn btn-success btn-sm">
                                <i class="mdi mdi-play"></i> Panggil
                            </button>
                            <button onclick="skipAntrian(${a.id})" class="btn btn-danger btn-sm">
                                <i class="mdi mdi-skip-next"></i> Skip
                            </button>
                        </td>
                    </tr>
                `).join('');
            }

            const skippedList = document.getElementById('skippedList');
            if (skipped.length === 0) {
                skippedList.innerHTML = '<tr><td colspan="3" class="text-center">Tidak ada</td></tr>';
            } else {
                skippedList.innerHTML = skipped.map(a => `
                    <tr ondblclick="callAntrian(${a.id})" style="cursor: pointer;" title="Double-click untuk panggil">
                        <td><strong>${a.nomor_antrian}</strong></td>
                        <td>${a.nama}</td>
                        <td><small>${new Date(a.created_at).toLocaleTimeString()}</small></td>
                    </tr>
                `).join('');
            }
        }

        async function callAntrian(id) {
            try {
                const response = await fetch('/admin/antrian/call', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('[name="_token"]').value
                    },
                    body: JSON.stringify({ id: id, loket: '1' })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan koneksi'
                });
            }
        }

        async function skipAntrian(id) {
            const result = await Swal.fire({
                title: 'Lewati antrian ini?',
                text: 'Antrian akan dipindahkan ke daftar terlewat',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lewati',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('/admin/antrian/skip', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('[name="_token"]').value
                        },
                        body: JSON.stringify({ id: id })
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dilewati',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        }

        document.getElementById('btnCallNext').addEventListener('click', async function() {
            try {
                const response = await fetch('/admin/antrian/call-next', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('[name="_token"]').value
                    },
                    body: JSON.stringify({ loket: '1' })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Info',
                        text: data.message
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan koneksi'
                });
            }
        });

        let lastCalled = null;
        eventSource.addEventListener('queue-update', function(event) {
            const data = JSON.parse(event.data);
            const called = data.called || null;

            if (called && (!lastCalled || called.id !== lastCalled.id)) {
                lastCalled = called;
            }
        });
    </script>
@endsection