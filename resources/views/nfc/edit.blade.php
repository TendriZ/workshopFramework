@extends('layouts.app')

@section('title', 'Edit Kartu NFC')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home"></i> Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('nfc.index') }}">Dashboard NFC</a></li>
    <li class="breadcrumb-item"><a href="{{ route('nfc.daftar') }}">Daftar Kartu</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Kartu</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">
                    <i class="mdi mdi-pencil"></i> Edit Data Kartu NFC
                </h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('nfc.update', $kartu->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_method" value="PUT">

                    <div class="form-group">
                        <label>Serial Number <span class="text-muted">(tidak diubah)</span></label>
                        <input type="text" value="{{ $kartu->serial_number }}" class="form-control" readonly>
                        <small class="form-text text-muted">Serial number bersifat, tidak dapat diubah</small>
                    </div>

                    <div class="form-group">
                        <label>Nama Kartu <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kartu" class="form-control" value="{{ $kartu->nama_kartu }}" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Kartu <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-control" required>
                            <option value="peserta" {{ $kartu->jenis === 'peserta' ? 'selected' : '' }}>Peserta</option>
                            <option value="dosen" {{ $kartu->jenis === 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="staff" {{ $kartu->jenis === 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status Kartu</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $kartu->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$kartu->is_active ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>

                    <div class="alert alert-warning mt-2">
                        <strong>Catatan:</strong> Perubahan jenis kartu tidak akan mempengaruhi data peserta yang sudah terhubung.
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('nfc.daftar') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection