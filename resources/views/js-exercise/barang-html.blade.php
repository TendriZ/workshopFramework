@extends('layouts.app')

@section('title', 'JS Barang - HTML Table')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">JS Barang (HTML Table)</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Barang</h4>
                <p class="card-description">Data hanya disimpan di browser (tidak ke database)</p>

                <form id="formBarang" class="forms-sample" onsubmit="return false;">
                    <div class="form-group">
                        <label for="nama">Nama Barang</label>
                        <input type="text" class="form-control" id="nama" placeholder="Masukkan nama barang" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga Barang</label>
                        <input type="number" class="form-control" id="harga" placeholder="Masukkan harga barang" min="0" required>
                    </div>
                    <button type="button" class="btn btn-success" id="btnSubmit" onclick="tambahBarang()">
                        <i class="mdi mdi-plus"></i> Submit
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Barang (HTML Table)</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tabelBarang">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyBarang">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCRUD" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formModal" onsubmit="return false;">
                    <div class="form-group">
                        <label>ID Barang</label>
                        <input type="text" class="form-control" id="modalId" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" id="modalNama" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Barang</label>
                        <input type="number" class="form-control" id="modalHarga" min="0" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" id="btnHapus" onclick="hapusBarang()">
                    <i class="mdi mdi-delete"></i> Hapus
                </button>
                <button type="button" class="btn btn-success" id="btnUbah" onclick="ubahBarang()">
                    <i class="mdi mdi-pencil"></i> Ubah
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let counter = 0;       
    let selectedRow = null; 

    function tambahBarang() {
        let btn = document.getElementById('btnSubmit');
        let form = document.getElementById('formBarang');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

        setTimeout(function() {
            counter++;
            let nama = document.getElementById('nama').value;
            let harga = document.getElementById('harga').value;
            let idBarang = 'BRG' + String(counter).padStart(3, '0');

            let tbody = document.getElementById('tbodyBarang');
            let tr = document.createElement('tr');
            tr.style.cursor = 'pointer';
            tr.setAttribute('data-id', idBarang);
            tr.setAttribute('data-nama', nama);
            tr.setAttribute('data-harga', harga);

            tr.onclick = function() { bukaModal(this); };

            tr.innerHTML =
                '<td>' + idBarang + '</td>' +
                '<td>' + nama + '</td>' +
                '<td>Rp ' + parseInt(harga).toLocaleString('id-ID') + '</td>';

            tbody.appendChild(tr);

            document.getElementById('nama').value = '';
            document.getElementById('harga').value = '';

            // Spinner OFF
            btn.disabled = false;
            btn.innerHTML = '<i class="mdi mdi-plus"></i> Submit';
        }, 500);
    }

    function bukaModal(tr) {
        selectedRow = tr;
        document.getElementById('modalId').value = tr.getAttribute('data-id');
        document.getElementById('modalNama').value = tr.getAttribute('data-nama');
        document.getElementById('modalHarga').value = tr.getAttribute('data-harga');

        var modal = new bootstrap.Modal(document.getElementById('modalCRUD'));
        modal.show();
    }

    function hapusBarang() {
        let formModal = document.getElementById('formModal');
        let btnHapus = document.getElementById('btnHapus');

        if (!formModal.checkValidity()) {
            formModal.reportValidity();
            return;
        }

        btnHapus.disabled = true;
        btnHapus.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghapus...';

        setTimeout(function() {
            if (selectedRow) {
                selectedRow.remove();
                selectedRow = null;
                bootstrap.Modal.getInstance(document.getElementById('modalCRUD')).hide();
            }

            btnHapus.disabled = false;  
            btnHapus.innerHTML = '<i class="mdi mdi-delete"></i> Hapus';
        }, 500);
    }

    function ubahBarang() {
        let formModal = document.getElementById('formModal');
        let btnUbah = document.getElementById('btnUbah');

        if (!formModal.checkValidity()) {
            formModal.reportValidity();
            return;
        }

        btnUbah.disabled = true;
        btnUbah.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

        setTimeout(function() {
            if (selectedRow) {
                let nama = document.getElementById('modalNama').value;
                let harga = document.getElementById('modalHarga').value;

                selectedRow.setAttribute('data-nama', nama);
                selectedRow.setAttribute('data-harga', harga);
                selectedRow.cells[1].innerText = nama;
                selectedRow.cells[2].innerText = 'Rp ' + parseInt(harga).toLocaleString('id-ID');

                selectedRow = null;
                bootstrap.Modal.getInstance(document.getElementById('modalCRUD')).hide();
            }

            btnUbah.disabled = false;
            btnUbah.innerHTML = '<i class="mdi mdi-pencil"></i> Ubah';
        }, 500);
    }
</script>
@endpush
