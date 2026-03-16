@extends('layouts.app')

@section('title', 'Point of Sales')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Point of Sales</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    {{-- Card 1: Form Input --}}
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Barang</h4>
                <p class="card-description">Masukkan kode barang, tekan <code>Enter</code></p>

                <div class="form-group">
                    <label>Kode barang :</label>
                    <input type="text" id="kodeBarang" class="form-control" placeholder="Contoh: BRG00001" autofocus>
                </div>
                <div class="form-group">
                    <label>Nama barang :</label>
                    <input type="text" id="namaBarang" class="form-control bg-light" readonly>
                </div>
                <div class="form-group">
                    <label>Harga barang :</label>
                    <input type="text" id="hargaBarang" class="form-control bg-light" readonly>
                </div>
                <div class="form-group">
                    <label>Jumlah :</label>
                    <input type="number" id="jumlahBarang" class="form-control" min="1" value="1" disabled>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="switchMode">
                        <label class="form-check-label" for="switchMode" id="modeLabel">Mode: jQuery Ajax</label>
                    </div>
                    <button type="button" id="btnTambahkan" class="btn btn-info" disabled onclick="tambahKeTable()">
                        <i class="mdi mdi-plus"></i> Tambahkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Tabel Belanja --}}
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Belanja</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tabelBelanja">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total</strong></td>
                                <td colspan="2"><strong id="totalBelanja">Rp 0</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button type="button" id="btnBayar" class="btn btn-success btn-lg" disabled onclick="prosesBayar(this)">
                        <i class="mdi mdi-cash-register"></i> Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Axios CDN --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// State
var barangDitemukan = null; // data barang yang ditemukan via ajax
var useAxios = false;       // toggle mode

// Toggle mode switch
$('#switchMode').on('change', function() {
    useAxios = this.checked;
    $('#modeLabel').text(useAxios ? 'Mode: Axios' : 'Mode: jQuery Ajax');
});

// Format rupiah
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// ============================================================
//  CARI BARANG — Enter pada input kode
// ============================================================
$('#kodeBarang').on('keydown', function(e) {
    if (e.key !== 'Enter') return;
    e.preventDefault();

    var kode = $(this).val().trim();
    if (!kode) return;

    // Reset
    $('#namaBarang').val('');
    $('#hargaBarang').val('');
    $('#jumlahBarang').val(1).prop('disabled', true);
    $('#btnTambahkan').prop('disabled', true);
    barangDitemukan = null;

    if (useAxios) {
        // --- Axios ---
        axios.get("{{ url('/ajax/pos/cari') }}", { params: { kode: kode } })
        .then(function(response) {
            handleCariSuccess(response.data);
        })
        .catch(function(error) {
            handleCariError(error.response ? error.response.data : null);
        });
    } else {
        // --- jQuery Ajax ---
        $.ajax({
            url: "{{ url('/ajax/pos/cari') }}",
            type: "GET",
            data: { kode: kode },
            success: function(response) {
                handleCariSuccess(response);
            },
            error: function(xhr) {
                handleCariError(xhr.responseJSON);
            }
        });
    }
});

function handleCariSuccess(response) {
    if (response.status === 'success') {
        barangDitemukan = response.data;
        $('#namaBarang').val(barangDitemukan.nama);
        $('#hargaBarang').val(formatRupiah(barangDitemukan.harga));
        $('#jumlahBarang').prop('disabled', false).val(1).focus();
        cekTombolTambah();
    }
}

function handleCariError(data) {
    barangDitemukan = null;
    $('#namaBarang').val('');
    $('#hargaBarang').val('');
    $('#jumlahBarang').val(1).prop('disabled', true);
    $('#btnTambahkan').prop('disabled', true);
    Swal.fire('Tidak Ditemukan', data ? data.message : 'Barang tidak ditemukan', 'warning');
}

// Cek tombol tambah: aktif hanya bila barang ditemukan & jumlah > 0
$('#jumlahBarang').on('input', function() { cekTombolTambah(); });

function cekTombolTambah() {
    var jumlah = parseInt($('#jumlahBarang').val()) || 0;
    $('#btnTambahkan').prop('disabled', !(barangDitemukan && jumlah > 0));
}

// ============================================================
//  TAMBAHKAN KE TABLE
// ============================================================
function tambahKeTable() {
    if (!barangDitemukan) return;

    var kode    = barangDitemukan.id_barang;
    var nama    = barangDitemukan.nama;
    var harga   = parseInt(barangDitemukan.harga);
    var jumlah  = parseInt($('#jumlahBarang').val()) || 1;
    var subtotal = harga * jumlah;

    // Cek apakah kode sudah ada di tabel
    var existingRow = $('#tabelBelanja tbody tr[data-kode="' + kode + '"]');

    if (existingRow.length > 0) {
        // Update jumlah & subtotal
        var oldJumlah = parseInt(existingRow.find('.col-jumlah input').val());
        var newJumlah = oldJumlah + jumlah;
        existingRow.find('.col-jumlah input').val(newJumlah);
        existingRow.find('.col-subtotal').text(formatRupiah(harga * newJumlah));
        existingRow.attr('data-harga', harga);
    } else {
        // Tambah baris baru
        var tr = '<tr data-kode="' + kode + '" data-harga="' + harga + '">' +
            '<td>' + kode + '</td>' +
            '<td>' + nama + '</td>' +
            '<td>' + formatRupiah(harga) + '</td>' +
            '<td class="col-jumlah">' +
                '<input type="number" class="form-control form-control-sm" style="width:70px" min="1" value="' + jumlah + '" onchange="ubahJumlah(this)">' +
            '</td>' +
            '<td class="col-subtotal">' + formatRupiah(subtotal) + '</td>' +
            '<td>' +
                '<button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">' +
                    '<i class="mdi mdi-delete"></i>' +
                '</button>' +
            '</td>' +
            '</tr>';
        $('#tabelBelanja tbody').append(tr);
    }

    hitungTotal();
    resetForm();
}

// ============================================================
//  UBAH JUMLAH di tabel
// ============================================================
function ubahJumlah(input) {
    var tr = $(input).closest('tr');
    var harga = parseInt(tr.attr('data-harga'));
    var jumlah = parseInt(input.value) || 0;

    if (jumlah <= 0) {
        hapusBaris(input);
        return;
    }

    tr.find('.col-subtotal').text(formatRupiah(harga * jumlah));
    hitungTotal();
}

// ============================================================
//  HAPUS BARIS
// ============================================================
function hapusBaris(el) {
    $(el).closest('tr').remove();
    hitungTotal();
}

// ============================================================
//  HITUNG TOTAL
// ============================================================
function hitungTotal() {
    var total = 0;
    $('#tabelBelanja tbody tr').each(function() {
        var harga = parseInt($(this).attr('data-harga'));
        var jumlah = parseInt($(this).find('.col-jumlah input').val()) || 0;
        total += harga * jumlah;
    });
    $('#totalBelanja').text(formatRupiah(total));
    $('#btnBayar').prop('disabled', total <= 0);
}

// ============================================================
//  RESET FORM
// ============================================================
function resetForm() {
    barangDitemukan = null;
    $('#kodeBarang').val('').focus();
    $('#namaBarang').val('');
    $('#hargaBarang').val('');
    $('#jumlahBarang').val(1).prop('disabled', true);
    $('#btnTambahkan').prop('disabled', true);
}

// ============================================================
//  BAYAR — simpan ke database
// ============================================================
function prosesBayar(btn) {
    var items = [];
    var total = 0;

    $('#tabelBelanja tbody tr').each(function() {
        var kode = $(this).attr('data-kode');
        var harga = parseInt($(this).attr('data-harga'));
        var jumlah = parseInt($(this).find('.col-jumlah input').val()) || 0;
        var subtotal = harga * jumlah;
        total += subtotal;
        items.push({
            id_barang: kode,
            jumlah: jumlah,
            subtotal: subtotal
        });
    });

    if (items.length === 0) return;

    // Spinner on button
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Memproses...';

    var payload = {
        _token: "{{ csrf_token() }}",
        items: items,
        total: total
    };

    if (useAxios) {
        // --- Axios ---
        axios.post("{{ route('pos.bayar') }}", payload)
        .then(function(response) {
            handleBayarSuccess(response.data, btn);
        })
        .catch(function(error) {
            handleBayarError(error.response ? error.response.data : null, btn);
        });
    } else {
        // --- jQuery Ajax ---
        $.ajax({
            url: "{{ route('pos.bayar') }}",
            type: "POST",
            contentType: "application/json",
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            data: JSON.stringify(payload),
            success: function(response) {
                handleBayarSuccess(response, btn);
            },
            error: function(xhr) {
                handleBayarError(xhr.responseJSON, btn);
            }
        });
    }
}

function handleBayarSuccess(response, btn) {
    btn.disabled = false;
    btn.innerHTML = '<i class="mdi mdi-cash-register"></i> Bayar';

    Swal.fire(
        'Success!',
        'Pembayaran berhasil disimpan. ID Transaksi: ' + response.data.id_penjualan,
        'success'
    );

    // Kosongkan semua
    $('#tabelBelanja tbody').html('');
    hitungTotal();
    resetForm();
}

function handleBayarError(data, btn) {
    btn.disabled = false;
    btn.innerHTML = '<i class="mdi mdi-cash-register"></i> Bayar';

    Swal.fire(
        'Error!',
        data ? data.message : 'Terjadi kesalahan saat menyimpan pembayaran.',
        'error'
    );
}
</script>
@endpush
