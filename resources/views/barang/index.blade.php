@extends('layouts.app')

@section('title', 'Tag Harga UMKM')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Tag Harga UMKM</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    table.dataTable thead th { padding: 10px 18px; }
    .dt-buttons { margin-bottom: 10px; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Daftar Barang</h4>
                    <div>
                        <button type="button" class="btn btn-success btn-sm" id="btnCetakTag" disabled>
                            <i class="mdi mdi-printer"></i> Cetak Tag Harga
                        </button>
                        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus"></i> Tambah Barang
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover" id="barangTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangs as $barang)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="check-item" value="{{ $barang->id_barang }}">
                                    </td>
                                    <td><label class="badge badge-primary">{{ $barang->id_barang }}</label></td>
                                    <td>{{ $barang->nama }}</td>
                                    <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('barang.edit', $barang->id_barang) }}" class="btn btn-sm btn-warning">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteWithSpinner(this)">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data barang</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Posisi Awal Label -->
<div class="modal fade" id="modalPosisi" tabindex="-1" aria-labelledby="modalPosisiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formCetakTag" action="{{ route('barang.cetak-tag') }}" method="POST" target="_blank">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPosisiLabel">
                        <i class="mdi mdi-printer"></i> Posisi Awal Label
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Kertas label TnJ No. 108 memiliki <strong>5 kolom × 8 baris</strong> (40 label per lembar).
                        Tentukan posisi awal cetak jika menggunakan lembar yang sudah terpakai sebagian.
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="x_start">Kolom Awal (X)</label>
                                <select class="form-control" id="x_start" name="x_start" required>
                                    <option value="1" selected>Kolom 1</option>
                                    <option value="2">Kolom 2</option>
                                    <option value="3">Kolom 3</option>
                                    <option value="4">Kolom 4</option>
                                    <option value="5">Kolom 5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="y_start">Baris Awal (Y)</label>
                                <select class="form-control" id="y_start" name="y_start" required>
                                    <option value="1" selected>Baris 1</option>
                                    <option value="2">Baris 2</option>
                                    <option value="3">Baris 3</option>
                                    <option value="4">Baris 4</option>
                                    <option value="5">Baris 5</option>
                                    <option value="6">Baris 6</option>
                                    <option value="7">Baris 7</option>
                                    <option value="8">Baris 8</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Visual grid preview -->
                    <div class="mt-3">
                        <label class="mb-2">Preview Posisi Awal:</label>
                        <div class="border rounded p-2">
                            <table class="table table-bordered table-sm mb-0 text-center" id="gridPreview">
                                <tbody>
                                    @for($row = 1; $row <= 8; $row++)
                                        <tr>
                                            @for($col = 1; $col <= 5; $col++)
                                                <td class="grid-cell" data-row="{{ $row }}" data-col="{{ $col }}" style="width:20%; padding:4px; font-size:10px; color:#999;">
                                                    {{ $col }},{{ $row }}
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Hidden inputs for selected IDs -->
                    <div id="selectedIdsContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-printer"></i> Cetak PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#barangTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Data tidak ditemukan",
            paginate: { first: "Pertama", last: "Terakhir", next: "›", previous: "‹" }
        },
        columnDefs: [
            { orderable: false, targets: [0, 4] }
        ],
        order: [[1, 'asc']]
    });

    // Check all
    $('#checkAll').on('change', function() {
        var checked = this.checked;
        // Only check visible (filtered) rows
        table.rows({ search: 'applied' }).nodes().to$().find('.check-item').prop('checked', checked);
        updateCetakButton();
    });

    // Individual checkbox
    $('#barangTable tbody').on('change', '.check-item', function() {
        updateCetakButton();
        // Uncheck "check all" if any item is unchecked
        if (!this.checked) {
            $('#checkAll').prop('checked', false);
        }
    });

    function updateCetakButton() {
        var count = $('.check-item:checked').length;
        $('#btnCetakTag').prop('disabled', count === 0);
        if (count > 0) {
            $('#btnCetakTag').html('<i class="mdi mdi-printer"></i> Cetak Tag Harga (' + count + ')');
        } else {
            $('#btnCetakTag').html('<i class="mdi mdi-printer"></i> Cetak Tag Harga');
        }
    }

    // Open modal
    $('#btnCetakTag').on('click', function() {
        var container = $('#selectedIdsContainer');
        container.empty();
        $('.check-item:checked').each(function() {
            container.append('<input type="hidden" name="ids[]" value="' + $(this).val() + '">');
        });
        updateGridPreview();
        $('#modalPosisi').modal('show');
    });

    // Grid preview update
    $('#x_start, #y_start').on('change', function() {
        updateGridPreview();
    });

    function updateGridPreview() {
        var sx = parseInt($('#x_start').val());
        var sy = parseInt($('#y_start').val());
        var count = $('.check-item:checked').length;

        $('.grid-cell').css({ 'background-color': '#fff', 'color': '#999' });

        // Mark skipped cells as grey
        var pos = 0;
        for (var row = 1; row <= 8; row++) {
            for (var col = 1; col <= 5; col++) {
                var cell = $('.grid-cell[data-row="' + row + '"][data-col="' + col + '"]');
                if (row < sy || (row === sy && col < sx)) {
                    cell.css({ 'background-color': '#e9ecef', 'color': '#aaa' });
                }
            }
        }

        // Mark label positions as green
        var filled = 0;
        var started = false;
        for (var row = 1; row <= 8 && filled < count; row++) {
            for (var col = 1; col <= 5 && filled < count; col++) {
                if (!started) {
                    if (row === sy && col === sx) started = true;
                    else continue;
                }
                var cell = $('.grid-cell[data-row="' + row + '"][data-col="' + col + '"]');
                cell.css({ 'background-color': '#d4edda', 'color': '#155724' });
                filled++;
            }
        }
    }
});
</script>
@endpush
