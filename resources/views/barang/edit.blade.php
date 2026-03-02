@extends('layouts.app')

@section('title', 'Edit Barang')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Tag Harga UMKM</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Barang</h4>
                <p class="card-description">Ubah detail barang</p>
                
                <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" class="forms-sample">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="id_barang">ID Barang</label>
                        <input type="text" class="form-control" id="id_barang" 
                               value="{{ $barang->id_barang }}" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama">Nama Barang</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" 
                               value="{{ old('nama', $barang->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                               id="harga" name="harga" 
                               value="{{ old('harga', $barang->harga) }}" min="0" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary me-2">Update</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
