@extends('layouts.app')

@section('title', 'JS Barang - DataTables')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">JS Barang (DataTables)</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="row">
    {{-- Form Input --}}
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Barang</h4>
                <p class="card-description">Data hanya disimpan di browser (tidak ke database) — versi DataTables</p>

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

    {{-- Tabel --}}
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Barang (DataTables)</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tabelBarang">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Row ditambahkan oleh JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit/Hapus (Studi Kasus 3) --}}
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
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    let counter = 0;
    let selectedRowData = null; // Menyimpan data row yang diklik

    // Inisialisasi DataTable
    var table = $('#tabelBarang').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan",
            paginate: { next: "›", previous: "‹" }
        },
        columns: [
            { data: 'id' },
            { data: 'nama' },
            {
                data: 'harga',
                render: function(data) {
                    return 'Rp ' + parseInt(data).toLocaleString('id-ID');
                }
            }
        ],
        order: [[0, 'asc']]
    });

    // ===================== STUDI KASUS 3: Klik row → modal =====================
    $('#tabelBarang tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        if (!data) return;

        selectedRowData = { row: table.row(this), data: data };

        $('#modalId').val(data.id);
        $('#modalNama').val(data.nama);
        $('#modalHarga').val(data.harga);

        $('#modalCRUD').modal('show');
    });

    // Cursor pointer pada row
    $('#tabelBarang tbody').on('mouseenter', 'tr', function() {
        $(this).css('cursor', 'pointer');
    });

    // ===================== STUDI KASUS 1: Spinner =====================
    function tambahBarang() {
        var btn = document.getElementById('btnSubmit');
        var form = document.getElementById('formBarang');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

        setTimeout(function() {
            // ===================== STUDI KASUS 2: Tambah Row =====================
            counter++;
            var nama = $('#nama').val();
            var harga = $('#harga').val();
            var idBarang = 'BRG' + String(counter).padStart(3, '0');

            // Tambah row ke DataTable
            table.row.add({
                id: idBarang,
                nama: nama,
                harga: harga
            }).draw();

            // Reset form
            $('#nama').val('');
            $('#harga').val('');

            // Spinner OFF
            btn.disabled = false;
            btn.innerHTML = '<i class="mdi mdi-plus"></i> Submit';
        }, 500);
    }

    // ===================== STUDI KASUS 3: Hapus & Ubah =====================
    function hapusBarang() {
        if (selectedRowData) {
            selectedRowData.row.remove().draw();
            selectedRowData = null;
            $('#modalCRUD').modal('hide');
        }
    }

    function ubahBarang() {
        var formModal = document.getElementById('formModal');
        var btnUbah = document.getElementById('btnUbah');

        // Studi Kasus 1: checkValidity + reportValidity
        if (!formModal.checkValidity()) {
            formModal.reportValidity();
            return;
        }

        // Spinner ON
        btnUbah.disabled = true;
        btnUbah.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

        setTimeout(function() {
            if (selectedRowData) {
                var nama = $('#modalNama').val().trim();
                var harga = $('#modalHarga').val();

                selectedRowData.row.data({
                    id: selectedRowData.data.id,
                    nama: nama,
                    harga: harga
                }).draw();

                selectedRowData = null;
                $('#modalCRUD').modal('hide');
            }

            // Spinner OFF
            btnUbah.disabled = false;
            btnUbah.innerHTML = '<i class="mdi mdi-pencil"></i> Ubah';
        }, 500);
    }
</script>
@endpush
