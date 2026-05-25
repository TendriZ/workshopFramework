@extends('layouts.app')

@section('title', 'Daftar Kartu NFC')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home"></i> Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('nfc.index') }}">Dashboard NFC</a></li>
    <li class="breadcrumb-item active" aria-current="page">Daftar Kartu</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">
                    <i class="mdi mdi-nfc-variant"></i> Daftar Kartu NFC
                </h4>
                <p class="mb-0 text-white">Kelola kartu NFC untuk sistem absensi</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card bg-light mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="mdi mdi-plus"></i> Tambah Kartu Baru
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('nfc.daftar.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Kartu <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_kartu" class="form-control" required placeholder="Contoh: Kartu Peserta 1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis Kartu <span class="text-danger">*</span></label>
                                        <select name="jenis" class="form-control" required id="jenis-select">
                                            <option value="">Pilih Jenis</option>
                                            <option value="peserta">Peserta</option>
                                            <option value="dosen">Dosen</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3" id="peserta-fields" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="mdi mdi-information"></i> Informasi Peserta
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>NIM <span class="text-danger">*</span></label>
                                            <input type="text" name="nim" class="form-control" placeholder="Contoh: 12345678">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nama Peserta <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kelas</label>
                                            <input type="text" name="kelas" class="form-control" placeholder="Contoh: 12 IPA 1">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" id="btn-scan-serial" class="btn btn-secondary">
                                    <i class="mdi mdi-nfc-search"></i> Scan Serial Number
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Simpan Data
                                </button>
                                <a href="{{ route('nfc.index') }}" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="mdi mdi-format-list-bulleted"></i> Daftar Kartu Terdaftar
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Serial Number</th>
                                        <th>Nama Kartu</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Terhubung</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="kartu-daftar-list">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            Memuat data kartu...
                                        </td>
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tampilkan/sembunyikan field peserta berdasarkan jenis
        document.getElementById('jenis-select').addEventListener('change', function() {
            const pesertaFields = document.getElementById('peserta-fields');
            const nimInput = document.querySelector('input[name="nim"]');
            const namaInput = document.querySelector('input[name="nama"]');
            const kelasInput = document.querySelector('input[name="kelas"]');

            if (this.value === 'peserta') {
                pesertaFields.style.display = 'block';
                nimInput.required = true;
                namaInput.required = true;
                kelasInput.required = false;
            } else {
                pesertaFields.style.display = 'none';
                nimInput.required = false;
                namaInput.required = false;
                kelasInput.required = false;
                nimInput.value = '';
                namaInput.value = '';
                kelasInput.value = '';
            }
        });

        // Load daftar kartu saat halaman dimuat
        function loadDaftarKartu() {
            fetch('/api/nfc/kartu')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('kartu-daftar-list');
                    if (data.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Belum ada kartu terdaftar</td></tr>';
                    } else {
                        tbody.innerHTML = data.data.map(k => `
                            <tr>
                                <td><code>${k.serial_number}</code></td>
                                <td>${k.nama_kartu}</td>
                                <td><span class="badge badge-${k.jenis === 'peserta' ? 'primary' : (k.jenis === 'dosen' ? 'warning' : 'secondary')}">${k.jenis}</span></td>
                                <td>
                                    <span class="badge badge-${k.is_active ? 'success' : 'danger'}">
                                        ${k.is_active ? 'Aktif' : 'Non-Aktif'}
                                    </span>
                                </td>
                                <td>${k.peserta ? '<span class="text-success">' + k.peserta.nama + '</span>' : '<span class="text-muted">-</span>'}</td>
                                <td>
                                    <button onclick="editKartu(${k.id})" class="btn btn-sm btn-warning">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    @if($k->is_active)
                                        <button onclick="toggleKartu(${k.id})" class="btn btn-sm btn-secondary">
                                            <i class="mdi mdi-block-helper"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('kartu-daftar-list').innerHTML = `
                        <tr><td colspan="6" class="text-center text-danger">Gagal memuat data kartu</td></tr>
                    `;
                });
        }

        // Scan serial number dari kartu NFC
        document.getElementById('btn-scan-serial').addEventListener('click', async function() {
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
                const ndef = new NDEFReader();
                await ndef.scan();

                Swal.fire({
                    icon: 'info',
                    title: 'Siap Scan',
                    text: 'Dekatkan kartu NFC yang kosong untuk mengambil serial number...',
                    timer: 2000,
                    showConfirmButton: false
                });

                ndef.addEventListener('reading', ({ serialNumber }) => {
                    const serialInput = document.querySelector('input[name="serial_number"]');
                    serialInput.value = serialNumber;
                    serialInput.readOnly = true;

                    Swal.fire({
                        icon: 'success',
                        title: 'Serial Terbaca!',
                        text: 'Serial Number: ' + serialNumber,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    ndef.close();
                });

                ndef.addEventListener('error', (error) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal scan: ' + error.message
                    });
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal mengaktifkan NFC: ' + error.message
                });
            }
        });

        function loadDaftarKartu();
    </script>
@endpush