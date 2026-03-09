@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Tag Harga UMKM</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Barang Baru</h4>
                <p class="card-description">Masukkan detail barang</p>
                
                <form action="{{ route('barang.store') }}" method="POST" class="forms-sample">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nama">Nama Barang</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" 
                               placeholder="Masukkan nama barang" 
                               value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                               id="harga" name="harga" 
                               placeholder="Masukkan harga barang" 
                               value="{{ old('harga') }}" min="0" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="button" class="btn btn-primary me-2" id="btnSubmit" onclick="submitWithSpinner(this)">Simpan</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
