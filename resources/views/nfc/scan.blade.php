@extends('layouts.app')

@section('title', 'Scanner NFC - Absensi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home"></i> Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Scanner NFC</li>
@endsection

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="mdi mdi-nfc"></i> Scanner NFC Absensi
                    </h4>
                    <small class="text-white">Dekatkan kartu NFC untuk absensi</small>
                </div>
                <div class="card-body text-center">

                    <div id="nfc-status" class="mb-4 p-3">
                        <i class="mdi mdi-information-outline"></i>
                        <span id="status-text">Klik tombol di bawah untuk mengaktifkan scanner NFC</span>
                    </div>

                    <button id="btn-activate-nfc" class="btn btn-primary btn-lg px-5">
                        <i class="mdi mdi-nfc"></i> Aktifkan Scanner NFC
                    </button>

                    <div id="scanner-container" class="mt-4 d-none">
                        <div class="alert alert-info">
                            <i class="mdi mdi-arrow-down"></i> Dekatkan kartu NFC ke bagian belakang HP
                        </div>
                    </div>

                    <div id="result-container" class="mt-4 d-none">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="text-primary mb-3">
                                    <i class="mdi mdi-check-circle"></i> Hasil Scan
                                </h5>

                                <div class="row text-left">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Serial Number:</strong><br>
                                            <span id="result-serial" class="font-weight-bold"></span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Nama Kartu:</strong><br>
                                            <span id="result-nama"></span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Jenis:</strong><br>
                                            <span id="result-jenis"></span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Status Kartu:</strong><br>
                                            <span id="result-active" class="badge badge-success">Aktif</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>NIM Peserta:</strong><br>
                                            <span id="result-nim"></span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Nama Peserta:</strong><br>
                                            <span id="result-nama-peserta"></span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Kelas:</strong><br>
                                            <span id="result-kelas"></span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Status Absensi:</strong><br>
                                            <span id="result-status" class="display-4"></span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Waktu Scan:</strong><br>
                                            <span id="result-waktu"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="info-alert" class="alert alert-warning mt-4">
                        <h6><i class="mdi mdi-alert"></i> Informasi Penting:</h6>
                        <ul class="mb-0">
                            <li>Web NFC API hanya didukung di <strong>Android Chrome ≥ 89</strong></li>
                            <li>Testing harus dilakukan di HP Android nyata, bukan emulator</li>
                            <li>URL harus menggunakan <strong>HTTPS atau localhost</strong></li>
                            <li>Simpan kartu NFC pada jarak ≤4 cm dari HP</li>
                            <li>Klik tombol "Aktifkan Scanner NFC" untuk memulai scan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let ndef = null;

        document.getElementById('btn-activate-nfc').addEventListener('click', async function() {
            const btn = this;

            // Cek dukungan browser
            if (!('NDEFReader' in window)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Browser Tidak Mendukung',
                    text: 'Web NFC API hanya didukung di Android Chrome versi 89 atau lebih tinggi.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            try {
                btn.disabled = true;
                btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Mengaktifkan NFC...';

                // Buat NDEFReader instance
                ndef = new NDEFReader();
                await ndef.scan();

                // Update UI
                document.getElementById('status-text').textContent = 'NFC aktif! Dekatkan kartu...';
                document.getElementById('nfc-status').className = 'mb-4 p-3 alert alert-info';
                document.getElementById('scanner-container').classList.remove('d-none');
                document.getElementById('result-container').classList.add('d-none');

                // Add event listener untuk reading
                ndef.addEventListener('reading', onReading);

                // Add event listener untuk error
                ndef.addEventListener('error', onError);

                Swal.fire({
                    icon: 'success',
                    title: 'NFC Aktif!',
                    text: 'Dekatkan kartu NFC untuk memulai scan.',
                    timer: 2000,
                    showConfirmButton: false
                });

            } catch (error) {
                console.error('NFC Error:', error);
                updateStatus('error', 'Gagal mengaktifkan NFC: ' + error.message);

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal mengaktifkan NFC: ' + error.message,
                    confirmButtonText: 'Coba Lagi'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="mdi mdi-nfc"></i> Aktifkan Scanner NFC';
            }
        });

        function onReading({ serialNumber, message }) {
        console.log('NFC terbaca - Serial:', serialNumber);

        // Langsung update status supaya ada feedback
        updateStatus('info', 'Kartu terdeteksi, memvalidasi...');

        // Decode records secara opsional (tidak wajib untuk absensi)
        let isi = '';
        try {
            if (message && message.records && message.records.length > 0) {
                for (const record of message.records) {
                    try {
                        isi += new TextDecoder().decode(record.data);
                    } catch (e) {
                        console.warn('Skip record tidak bisa di-decode:', e);
                    }
                }
            }
        } catch (e) {
            console.warn('Error decode message, lanjut dengan serial saja:', e);
        }

        // Serial number sudah cukup untuk absensi
        kirimKeBackend(serialNumber, isi);
    }

        function onError(error) {
            console.error('NFC Error:', error);
            updateStatus('error', 'Error: ' + error.message);

            Swal.fire({
                icon: 'error',
                title: 'Error NFC',
                text: 'Terjadi error saat scan NFC: ' + error.message
            });
        }

        function kirimKeBackend(serialNumber, isi) {
            updateStatus('info', 'Memvalidasi kartu...');

            fetch('/api/nfc/absen', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({
                    serial_number: serialNumber
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    displayHasil(data.data);
                    updateStatus('success', 'Absensi berhasil!');
                } else if (data.status === 'warning') {
                    displayHasilBasic(data.data);
                    updateStatus('warning', data.message);
                } else {
                    updateStatus('error', data.message);
                    displayHasilBasic({
                        serial_number: serialNumber
                    }, 'Kartu Tidak Ditemukan');
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                updateStatus('error', 'Gagal menghubungi server: ' + error.message);
            });
        }

        function displayHasil(data) {
            const resultDiv = document.getElementById('result-container');
            resultDiv.classList.remove('d-none');

            document.getElementById('result-serial').textContent = data.kartu.serial_number;
            document.getElementById('result-nama').textContent = data.kartu.nama_kartu;
            document.getElementById('result-jenis').textContent = data.kartu.jenis;
            document.getElementById('result-active').className = 'badge badge-' + (data.kartu.is_active ? 'success' : 'danger');
            document.getElementById('result-active').textContent = data.kartu.is_active ? 'Aktif' : 'Tidak Aktif';

            if (data.peserta) {
                document.getElementById('result-nim').textContent = data.peserta.nim;
                document.getElementById('result-nama-peserta').textContent = data.peserta.nama;
                document.getElementById('result-kelas').textContent = data.peserta.kelas || '-';
            } else {
                document.getElementById('result-nim').textContent = '-';
                document.getElementById('result-nama-peserta').textContent = '-';
                document.getElementById('result-kelas').textContent = '-';
            }

            const statusSpan = document.getElementById('result-status');
            statusSpan.textContent = data.status === 'masuk' ? 'MASUK ✓' : 'KELUAR ○';
            statusSpan.className = 'display-4 ' + (data.status === 'masuk' ? 'text-success' : 'text-danger');

            document.getElementById('result-waktu').textContent = data.waktu_scan;

            // Show alert
            const alertType = data.status === 'masuk' ? 'success' : 'warning';
            const alertMessage = data.status === 'masuk'
                ? `Absensi berhasil dicatat untuk ${data.peserta?.nama || data.kartu.nama_kartu}`
                : `Absensi keluar dicatat untuk ${data.peserta?.nama || data.kartu.nama_kartu}`;

            Swal.fire({
                icon: alertType,
                title: 'Scan Berhasil!',
                text: alertMessage,
                timer: 2000,
                showConfirmButton: false
            });
        }

        function displayHasilBasic(data, customMessage) {
            const resultDiv = document.getElementById('result-container');
            resultDiv.classList.remove('d-none');

            document.getElementById('result-serial').textContent = data.serial_number || '-';
            document.getElementById('result-nama').textContent = data.nama_kartu || '-';
            document.getElementById('result-jenis').textContent = data.jenis || '-';
            document.getElementById('result-active').className = 'badge badge-' + (data.is_active ? 'success' : 'danger');
            document.getElementById('result-active').textContent = data.is_active ? 'Aktif' : 'Tidak Aktif';

            document.getElementById('result-nim').textContent = '-';
            document.getElementById('result-nama-peserta').textContent = '-';
            document.getElementById('result-kelas').textContent = '-';
            document.getElementById('result-status').textContent = customMessage || '-';
            document.getElementById('result-status').className = 'display-4 text-warning';
            document.getElementById('result-waktu').textContent = '-';
        }

        function updateStatus(type, message) {
            const statusDiv = document.getElementById('nfc-status');
            statusDiv.textContent = message;

            if (type === 'success') {
                statusDiv.className = 'mb-4 p-3 alert alert-success';
            } else if (type === 'error') {
                statusDiv.className = 'mb-4 p-3 alert alert-danger';
            } else if (type === 'warning') {
                statusDiv.className = 'mb-4 p-3 alert alert-warning';
            } else {
                statusDiv.className = 'mb-4 p-3 alert alert-info';
            }
        }
    </script>
@endpush