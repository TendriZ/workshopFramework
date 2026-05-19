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

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('antrianForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const nama = document.getElementById('nama').value;
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

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
                        text: `Nomor antrian ${data.nomor_antrian} telah dibuat untuk ${data.nama}`,
                        confirmButtonText: 'OK'
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
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    </script>
@endsection