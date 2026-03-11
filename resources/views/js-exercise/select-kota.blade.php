@extends('layouts.app')

@section('title', 'Select Kota')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Select Kota</li>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select</h4>
                <p class="card-description">Menggunakan element select biasa</p>

                <div class="form-group">
                    <label for="inputKota1">Kota:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputKota1" placeholder="Masukkan nama kota">
                        <button class="btn btn-success" type="button" id="btnTambah1" onclick="tambahKotaBasic()">
                            Tambahkan
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="selectKota1"><strong>Select Kota:</strong></label>
                    <select class="form-control" id="selectKota1" onchange="pilihKotaBasic()">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><strong>Kota Terpilih:</strong></label>
                    <p id="kotaTerpilih1" class="text-primary font-weight-bold">-</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select 2</h4>
                <p class="card-description">Menggunakan element select2</p>

                <div class="form-group">
                    <label for="inputKota2">Kota:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputKota2" placeholder="Masukkan nama kota">
                        <button class="btn btn-success" type="button" id="btnTambah2" onclick="tambahKotaJquery()">
                            Tambahkan
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="selectKota2"><strong>Select Kota:</strong></label>
                    <select class="form-control" id="selectKota2" style="width:100%;">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    function tambahKotaBasic() {
        let input = document.getElementById('inputKota1');
        let kota = input.value.trim();
        if (kota === '') {
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

    function pilihKotaBasic() {
        let select = document.getElementById('selectKota1');
        let hasil = document.getElementById('kotaTerpilih1');
        hasil.innerText = select.value || '-';
    }


    $(document).ready(function() {
        $('#selectKota2').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Kota --',
            allowClear: true
        });

        $('#selectKota2').on('change', function() {
            let kota = $(this).val();
            $('#kotaTerpilih2').text(kota || '-');
        });
    });

    function tambahKotaJquery() {
        let input = $('#inputKota2');
        let kota = input.val().trim();
        if (kota === '') {
            alert('Nama kota tidak boleh kosong!');
            return;
        }

        let newOption = new Option(kota, kota, false, false);
        $('#selectKota2').append(newOption).trigger('change');

        input.val('');
    }
</script>
@endpush
