@extends('layouts.app')

@section('title', 'Payment Gateway - Vendor Menu')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Vendor - Master Menu</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Master Menu</h4>
                <form action="{{ route('pg.vendor.menu.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Vendor</label>
                        <select name="idvendor" class="form-control" required>
                            <option value="">Pilih vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->idvendor }}">{{ $vendor->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Path Gambar</label>
                        <input type="text" name="path_gambar" class="form-control" placeholder="opsional">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Menu</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Menu Vendor</h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vendor</th>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $menu)
                                <tr>
                                    <td>{{ $menu->idmenu }}</td>
                                    <td>{{ $menu->vendor?->nama_vendor }}</td>
                                    <td>{{ $menu->nama_menu }}</td>
                                    <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="collapse" data-bs-target="#edit-{{ $menu->idmenu }}">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <form action="{{ route('pg.vendor.menu.destroy', $menu->idmenu) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus menu?')">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <tr class="collapse" id="edit-{{ $menu->idmenu }}">
                                    <td colspan="5" class="bg-light">
                                        <form action="{{ route('pg.vendor.menu.update', $menu->idmenu) }}" method="POST" class="row g-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="col-md-3">
                                                <select name="idvendor" class="form-control" required>
                                                    @foreach($vendors as $vendor)
                                                        <option value="{{ $vendor->idvendor }}" {{ $menu->idvendor == $vendor->idvendor ? 'selected' : '' }}>
                                                            {{ $vendor->nama_vendor }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="nama_menu" value="{{ $menu->nama_menu }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" name="harga" value="{{ $menu->harga }}" class="form-control" min="1" required>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="path_gambar" value="{{ $menu->path_gambar }}" class="form-control">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="submit" class="btn btn-sm btn-primary w-100">OK</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">Belum ada menu</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
