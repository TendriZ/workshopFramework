@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between flex-wrap">
            <div class="d-flex align-items-end flex-wrap">
                <div class="me-md-3 me-xl-5">
                    <h2>Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="mb-md-0">Dashboard Koleksi Buku</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Total Kategori</p>
                <p class="font-weight-500">Jumlah kategori buku yang terdaftar</p>
                <div class="d-flex flex-wrap mb-2 align-items-center">
                    <h1 class="font-weight-bold mb-0">{{ \App\Models\Kategori::count() }}</h1>
                    <div class="ms-auto">
                        <i class="mdi mdi-tag-multiple icon-lg text-primary"></i>
                    </div>
                </div>
                <a href="{{ route('kategori.index') }}" class="text-primary">Lihat Detail</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Total Buku</p>
                <p class="font-weight-500">Jumlah buku yang terdaftar</p>
                <div class="d-flex flex-wrap mb-2 align-items-center">
                    <h1 class="font-weight-bold mb-0">{{ \App\Models\Buku::count() }}</h1>
                    <div class="ms-auto">
                        <i class="mdi mdi-book-multiple icon-lg text-success"></i>
                    </div>
                </div>
                <a href="{{ route('buku.index') }}" class="text-success">Lihat Detail</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Buku Terbaru</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Buku::with('kategori')->latest()->limit(5)->get() as $buku)
                                <tr>
                                    <td class="py-1">
                                        <label class="badge badge-primary">{{ $buku->kode }}</label>
                                    </td>
                                    <td>{{ $buku->judul }}</td>
                                    <td>{{ $buku->pengarang }}</td>
                                    <td>
                                        <label class="badge badge-info">{{ $buku->kategori->nama_kategori }}</label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
