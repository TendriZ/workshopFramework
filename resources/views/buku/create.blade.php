@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Buku</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Buku Baru</h4>
                <p class="card-description">Masukkan detail buku</p>
                
                <form action="{{ route('buku.store') }}" method="POST" class="forms-sample">
                    @csrf
                    
                    <div class="form-group">
                        <label for="kode">Kode Buku</label>
                        <input type="text" class="form-control @error('kode') is-invalid @enderror" 
                               id="kode" name="kode" 
                               placeholder="Contoh: NV-01" 
                               value="{{ old('kode') }}" required>
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="judul">Judul Buku</label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                               id="judul" name="judul" 
                               placeholder="Masukkan judul buku" 
                               value="{{ old('judul') }}" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" class="form-control @error('pengarang') is-invalid @enderror" 
                               id="pengarang" name="pengarang" 
                               placeholder="Masukkan nama pengarang" 
                               value="{{ old('pengarang') }}" required>
                        @error('pengarang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="idkategori">Kategori</label>
                        <select class="form-control @error('idkategori') is-invalid @enderror" 
                                id="idkategori" name="idkategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->idkategori }}" {{ old('idkategori') == $kategori->idkategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('idkategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                    <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
