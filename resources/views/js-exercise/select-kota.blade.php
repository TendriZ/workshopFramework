@extends('layouts.app')

@section('title', 'Select Kota')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Select Kota</li>
@endsection

@push('styles')
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    {{-- ===================== CARD 1: Select Biasa ===================== --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select</h4>
                <p class="card-description">Menggunakan element select biasa</p>

                {{-- Input Kota --}}
                <div class="form-group">
                    <label for="inputKota1">Kota:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputKota1" placeholder="Masukkan nama kota">
                        <button class="btn btn-success" type="button" id="btnTambah1" onclick="tambahKota1()">
                            Tambahkan
                        </button>
                    </div>
                </div>

                {{-- Select Kota --}}
                <div class="form-group">
                    <label for="selectKota1"><strong>Select Kota:</strong></label>
                    <select class="form-control" id="selectKota1" onchange="pilihKota1()">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                {{-- Kota Terpilih --}}
                <div class="form-group">
                    <label><strong>Kota Terpilih:</strong></label>
                    <p id="kotaTerpilih1" class="text-primary font-weight-bold">-</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== CARD 2: Select2 ===================== --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select 2</h4>
                <p class="card-description">Menggunakan element select2</p>

                {{-- Input Kota --}}
                <div class="form-group">
                    <label for="inputKota2">Kota:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputKota2" placeholder="Masukkan nama kota">
                        <button class="btn btn-success" type="button" id="btnTambah2" onclick="tambahKota2()">
                            Tambahkan
                        </button>
                    </div>
                </div>

                {{-- Select2 Kota --}}
                <div class="form-group">
                    <label for="selectKota2"><strong>Select Kota:</strong></label>
                    <select class="form-control" id="selectKota2" style="width:100%;">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                {{-- Kota Terpilih --}}
                <div class="form-group">
                    <label><strong>Kota Terpilih:</strong></label>
                    <p id="kotaTerpilih2" class="text-primary font-weight-bold">-</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // ===================== CARD 1: Select Biasa =====================

    // Tambahkan opsi ke select biasa
    function tambahKota1() {
        let input = document.getElementById('inputKota1');
        let kota = input.value.trim();
        if (!kota) {
            alert('Nama kota tidak boleh kosong!');
            return;
        }

        let select = document.getElementById('selectKota1');
        let option = document.createElement('option');
        option.value = kota;
        option.text = kota;
        select.appendChild(option);

        input.value = '';
    }

    // Tampilkan kota terpilih
    function pilihKota1() {
        let select = document.getElementById('selectKota1');
        let hasil = document.getElementById('kotaTerpilih1');
        hasil.innerText = select.value || '-';
    }

    // ===================== CARD 2: Select2 =====================

    // Inisialisasi Select2
    $(document).ready(function() {
        $('#selectKota2').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Kota --',
            allowClear: true
        });

        // Event change pada Select2
        $('#selectKota2').on('change', function() {
            let kota = $(this).val();
            $('#kotaTerpilih2').text(kota || '-');
        });
    });

    // Tambahkan opsi ke Select2
    function tambahKota2() {
        let input = document.getElementById('inputKota2');
        let kota = input.value.trim();
        if (!kota) {
            alert('Nama kota tidak boleh kosong!');
            return;
        }

        // Tambah option baru ke select, lalu refresh Select2
        let newOption = new Option(kota, kota, false, false);
        $('#selectKota2').append(newOption).trigger('change');

        input.value = '';
    }
</script>
@endpush
