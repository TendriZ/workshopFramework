@extends('layouts.app')

@section('title', 'Form Cetak Surat Undangan')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pdf.index') }}">Cetak PDF</a></li>
        <li class="breadcrumb-item active" aria-current="page">Form Surat Undangan</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-email-outline text-info"></i> Form Cetak Surat Undangan
                </h4>
                <p class="card-description text-muted mb-4">
                    Isi data berikut untuk mencetak surat undangan resmi dalam format PDF (Portrait A4).
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif 

                <form action="{{ route('pdf.generate.undangan') }}" method="POST" target="_blank">
                    @csrf

                    <p class="text-muted"><small><i class="mdi mdi-file-document-outline"></i> Identitas Surat</small></p>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Nomor Surat <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="nomor"
                                   class="form-control @error('nomor') is-invalid @enderror"
                                   placeholder="Contoh: 012/UN3.14.10/TU.00.03/2025"
                                   value="{{ old('nomor') }}">
                            @error('nomor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Lampiran <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="lampiran"
                                   class="form-control @error('lampiran') is-invalid @enderror"
                                   placeholder="Contoh: 1 (satu) lembar"
                                   value="{{ old('lampiran') }}">
                            @error('lampiran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Perihal <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="perihal"
                                   class="form-control @error('perihal') is-invalid @enderror"
                                   placeholder="Perihal / judul surat"
                                   value="{{ old('perihal') }}">
                            @error('perihal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Kepada Yth. <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="kepada"
                                   class="form-control @error('kepada') is-invalid @enderror"
                                   placeholder="Nama / jabatan penerima surat"
                                   value="{{ old('kepada') }}">
                            @error('kepada')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted"><small><i class="mdi mdi-calendar"></i> Detail Acara</small></p>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Nama Acara <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="acara"
                                   class="form-control @error('acara') is-invalid @enderror"
                                   placeholder="Nama acara / kegiatan"
                                   value="{{ old('acara') }}">
                            @error('acara')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Hari / Tanggal <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" name="tanggal"
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal') }}">
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Waktu <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="waktu"
                                   class="form-control @error('waktu') is-invalid @enderror"
                                   placeholder="Contoh: 08.00 – 12.00 WIB"
                                   value="{{ old('waktu') }}">
                            @error('waktu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Tempat <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="tempat"
                                   class="form-control @error('tempat') is-invalid @enderror"
                                   placeholder="Lokasi / ruangan acara"
                                   value="{{ old('tempat') }}">
                            @error('tempat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted"><small><i class="mdi mdi-pen"></i> Data Penandatangan</small></p>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Tanggal Surat <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" name="tanggal_surat"
                                   class="form-control @error('tanggal_surat') is-invalid @enderror"
                                   value="{{ old('tanggal_surat') }}">
                            @error('tanggal_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Nama Dekan <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="dekan"
                                   class="form-control @error('dekan') is-invalid @enderror"
                                   placeholder="Nama lengkap Dekan"
                                   value="{{ old('dekan') }}">
                            @error('dekan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">NIP Dekan <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="nip_dekan"
                                   class="form-control @error('nip_dekan') is-invalid @enderror"
                                   placeholder="Nomor Induk Pegawai Dekan"
                                   value="{{ old('nip_dekan') }}">
                            @error('nip_dekan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('pdf.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-gradient-info">
                            <i class="mdi mdi-printer"></i> Cetak Undangan PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
