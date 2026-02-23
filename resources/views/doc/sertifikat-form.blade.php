@extends('layouts.app')

@section('title', 'Form Cetak Sertifikat')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pdf.index') }}">Cetak PDF</a></li>
        <li class="breadcrumb-item active" aria-current="page">Form Sertifikat</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-certificate text-warning"></i> Form Cetak Sertifikat
                </h4>
                <p class="card-description text-muted mb-4">
                    Isi data berikut untuk mencetak sertifikat dalam format PDF (Landscape A4).
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

                <form action="{{ route('pdf.generate.sertifikat') }}" method="POST" target="_blank">
                    @csrf

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Nomor Sertifikat <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="nomor"
                                   class="form-control @error('nomor') is-invalid @enderror"
                                   placeholder="Contoh: 001/SRT/FV/X/2025"
                                   value="{{ old('nomor') }}">
                            @error('nomor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Nama Penerima <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   placeholder="Nama lengkap penerima sertifikat"
                                   value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Jabatan / Peran <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="jabatan"
                                   class="form-control @error('jabatan') is-invalid @enderror"
                                   placeholder="Contoh: Peserta, Pembicara, Panitia"
                                   value="{{ old('jabatan') }}">
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted"><small><i class="mdi mdi-pen"></i> Data Penandatangan</small></p>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Nama Dekan <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="dekan"
                                   class="form-control @error('dekan') is-invalid @enderror"
                                   placeholder="Nama Dekan"
                                   value="{{ old('dekan') }}">
                            @error('dekan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Koordinator Prodi <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="koordinator"
                                   class="form-control @error('koordinator') is-invalid @enderror"
                                   placeholder="Nama Koordinator Program Studi"
                                   value="{{ old('koordinator') }}">
                            @error('koordinator')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label">Ketua Pelaksana <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="ketua"
                                   class="form-control @error('ketua') is-invalid @enderror"
                                   placeholder="Nama Ketua Pelaksana"
                                   value="{{ old('ketua') }}">
                            @error('ketua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('pdf.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-printer"></i> Cetak Sertifikat PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
