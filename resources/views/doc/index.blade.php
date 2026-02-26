@extends('layouts.app')

@section('title', 'Cetak PDF')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cetak PDF</li>
    </ol>
</nav>
@endsection
aaaaa
@section('content')
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="mdi mdi-certificate mdi-48px text-warning"></i>
                </div>
                <h4 class="card-title">Sertifikat</h4>
                <p class="card-description">
                    Cetak sertifikat seminar dalam format PDF.<br>
                    <small class="text-muted">Landscape A4</small>
                </p>
                <a href="{{ route('pdf.sertifikat') }}" class="btn btn-gradient-primary btn-lg">
                    <i class="mdi mdi-file-document-edit-outline"></i> Isi Form &amp; Cetak Sertifikat
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="mdi mdi-email-outline mdi-48px text-info"></i>
                </div>
                <h4 class="card-title">Surat Undangan</h4>
                <p class="card-description">
                    Cetak surat undangan resmi dalam format PDF.<br>
                    <small class="text-muted">Portrait A4 dengan Kop Surat</small>
                </p>
                <a href="{{ route('pdf.undangan') }}" class="btn btn-gradient-info btn-lg">
                    <i class="mdi mdi-file-document-edit-outline"></i> Isi Form &amp; Cetak Undangan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
