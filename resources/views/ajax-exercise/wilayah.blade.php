@extends('layouts.app')

@section('title', 'Select Wilayah (AJAX)')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Select Wilayah</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    {{-- Card 1: jQuery Ajax --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select Wilayah — jQuery Ajax</h4>
                <p class="card-description">Cascading select menggunakan <code>$.ajax()</code></p>

                <div class="form-group">
                    <label for="ajaxProvinsi">Provinsi :</label>
                    <select id="ajaxProvinsi" class="form-control">
                        <option value="0">Pilih Provinsi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ajaxKota">Kota :</label>
                    <select id="ajaxKota" class="form-control" disabled>
                        <option value="0">Pilih Kota</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ajaxKecamatan">Kecamatan :</label>
                    <select id="ajaxKecamatan" class="form-control" disabled>
                        <option value="0">Pilih Kecamatan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ajaxKelurahan">Kelurahan :</label>
                    <select id="ajaxKelurahan" class="form-control" disabled>
                        <option value="0">Pilih Kelurahan</option>
                    </select>
                </div>

                <div id="ajaxResult" class="alert alert-info d-none mt-3"></div>
            </div>
        </div>
    </div>

    {{-- Card 2: Axios --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select Wilayah — Axios</h4>
                <p class="card-description">Cascading select menggunakan <code>axios</code></p>

                <div class="form-group">
                    <label for="axiosProvinsi">Provinsi :</label>
                    <select id="axiosProvinsi" class="form-control">
                        <option value="0">Pilih Provinsi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="axiosKota">Kota :</label>
                    <select id="axiosKota" class="form-control" disabled>
                        <option value="0">Pilih Kota</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="axiosKecamatan">Kecamatan :</label>
                    <select id="axiosKecamatan" class="form-control" disabled>
                        <option value="0">Pilih Kecamatan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="axiosKelurahan">Kelurahan :</label>
                    <select id="axiosKelurahan" class="form-control" disabled>
                        <option value="0">Pilih Kelurahan</option>
                    </select>
                </div>

                <div id="axiosResult" class="alert alert-info d-none mt-3"></div>
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
// ============================================================
//  CARD 1 — jQuery Ajax
// ============================================================

// Helper: reset select ke default & disable
function resetSelect(sel, placeholder) {
    sel.html('<option value="0">' + placeholder + '</option>').prop('disabled', true);
}

// Load provinsi on page load
$(function() {
    $.ajax({
        url: "{{ route('wilayah.provinsi') }}",
        type: "GET",
        success: function(data) {
            $.each(data, function(i, item) {
                $('#ajaxProvinsi').append('<option value="'+ item.id_provinsi +'">'+ item.nama +'</option>');
            });
        }
    });

    // Provinsi change → load kota, reset kecamatan & kelurahan
    $('#ajaxProvinsi').on('change', function() {
        var id = $(this).val();
        resetSelect($('#ajaxKota'), 'Pilih Kota');
        resetSelect($('#ajaxKecamatan'), 'Pilih Kecamatan');
        resetSelect($('#ajaxKelurahan'), 'Pilih Kelurahan');
        $('#ajaxResult').addClass('d-none');

        if (id == 0) return;

        $.ajax({
            url: "{{ url('/ajax/wilayah/kota') }}/" + id,
            type: "GET",
            success: function(data) {
                var sel = $('#ajaxKota');
                sel.prop('disabled', false);
                $.each(data, function(i, item) {
                    sel.append('<option value="'+ item.id_kota +'">'+ item.nama +'</option>');
                });
            }
        });
    });

    // Kota change → load kecamatan, reset kelurahan
    $('#ajaxKota').on('change', function() {
        var id = $(this).val();
        resetSelect($('#ajaxKecamatan'), 'Pilih Kecamatan');
        resetSelect($('#ajaxKelurahan'), 'Pilih Kelurahan');
        $('#ajaxResult').addClass('d-none');

        if (id == 0) return;

        $.ajax({
            url: "{{ url('/ajax/wilayah/kecamatan') }}/" + id,
            type: "GET",
            success: function(data) {
                var sel = $('#ajaxKecamatan');
                sel.prop('disabled', false);
                $.each(data, function(i, item) {
                    sel.append('<option value="'+ item.id_kecamatan +'">'+ item.nama +'</option>');
                });
            }
        });
    });

    // Kecamatan change → load kelurahan
    $('#ajaxKecamatan').on('change', function() {
        var id = $(this).val();
        resetSelect($('#ajaxKelurahan'), 'Pilih Kelurahan');
        $('#ajaxResult').addClass('d-none');

        if (id == 0) return;

        $.ajax({
            url: "{{ url('/ajax/wilayah/kelurahan') }}/" + id,
            type: "GET",
            success: function(data) {
                var sel = $('#ajaxKelurahan');
                sel.prop('disabled', false);
                $.each(data, function(i, item) {
                    sel.append('<option value="'+ item.id_kelurahan +'">'+ item.nama +'</option>');
                });
            }
        });
    });

    // Kelurahan change → tampilkan hasil
    $('#ajaxKelurahan').on('change', function() {
        if ($(this).val() == 0) { $('#ajaxResult').addClass('d-none'); return; }
        var prov = $('#ajaxProvinsi option:selected').text();
        var kota = $('#ajaxKota option:selected').text();
        var kec  = $('#ajaxKecamatan option:selected').text();
        var kel  = $(this).find('option:selected').text();
        $('#ajaxResult').removeClass('d-none').html(
            '<strong>Alamat terpilih (jQuery Ajax):</strong><br>' +
            kel + ', ' + kec + ', ' + kota + ', ' + prov
        );
    });
});

// ============================================================
//  CARD 2 — Axios
// ============================================================

// Helper: reset select & disable
function axiosReset(el, placeholder) {
    el.innerHTML = '<option value="0">' + placeholder + '</option>';
    el.disabled = true;
}

document.addEventListener('DOMContentLoaded', function() {
    var selProv = document.getElementById('axiosProvinsi');
    var selKota = document.getElementById('axiosKota');
    var selKec  = document.getElementById('axiosKecamatan');
    var selKel  = document.getElementById('axiosKelurahan');
    var resultDiv = document.getElementById('axiosResult');

    // Load provinsi
    axios.get("{{ route('wilayah.provinsi') }}")
    .then(function(response) {
        response.data.forEach(function(item) {
            var opt = new Option(item.nama, item.id_provinsi);
            selProv.appendChild(opt);
        });
    })
    .catch(function(error) {
        console.log(error);
    });

    // Provinsi change
    selProv.addEventListener('change', function() {
        var id = this.value;
        axiosReset(selKota, 'Pilih Kota');
        axiosReset(selKec, 'Pilih Kecamatan');
        axiosReset(selKel, 'Pilih Kelurahan');
        resultDiv.classList.add('d-none');

        if (id == 0) return;

        axios.get("{{ url('/ajax/wilayah/kota') }}/" + id)
        .then(function(response) {
            selKota.disabled = false;
            response.data.forEach(function(item) {
                selKota.appendChild(new Option(item.nama, item.id_kota));
            });
        })
        .catch(function(error) { console.log(error); });
    });

    // Kota change
    selKota.addEventListener('change', function() {
        var id = this.value;
        axiosReset(selKec, 'Pilih Kecamatan');
        axiosReset(selKel, 'Pilih Kelurahan');
        resultDiv.classList.add('d-none');

        if (id == 0) return;

        axios.get("{{ url('/ajax/wilayah/kecamatan') }}/" + id)
        .then(function(response) {
            selKec.disabled = false;
            response.data.forEach(function(item) {
                selKec.appendChild(new Option(item.nama, item.id_kecamatan));
            });
        })
        .catch(function(error) { console.log(error); });
    });

    // Kecamatan change
    selKec.addEventListener('change', function() {
        var id = this.value;
        axiosReset(selKel, 'Pilih Kelurahan');
        resultDiv.classList.add('d-none');

        if (id == 0) return;

        axios.get("{{ url('/ajax/wilayah/kelurahan') }}/" + id)
        .then(function(response) {
            selKel.disabled = false;
            response.data.forEach(function(item) {
                selKel.appendChild(new Option(item.nama, item.id_kelurahan));
            });
        })
        .catch(function(error) { console.log(error); });
    });

    // Kelurahan change → show result
    selKel.addEventListener('change', function() {
        if (this.value == 0) { resultDiv.classList.add('d-none'); return; }
        var prov = selProv.options[selProv.selectedIndex].text;
        var kota = selKota.options[selKota.selectedIndex].text;
        var kec  = selKec.options[selKec.selectedIndex].text;
        var kel  = this.options[this.selectedIndex].text;
        resultDiv.classList.remove('d-none');
        resultDiv.innerHTML =
            '<strong>Alamat terpilih (Axios):</strong><br>' +
            kel + ', ' + kec + ', ' + kota + ', ' + prov;
    });
});
</script>
@endpush
