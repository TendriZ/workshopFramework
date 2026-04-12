@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h3>Dashboard Vendor: {{ $vendor->nama_vendor }}</h3>
            <form action="{{ route('vendor.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Logout</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Kelola Menu -->
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Kelola Menu</h5>
                </div>
                <div class="card-body">
                    <!-- Form Tambah Menu -->
                    <form action="{{ route('vendor.menu.store') }}" method="POST" class="mb-4 bg-light p-3 rounded">
                        @csrf
                        <h6>Tambah Menu Baru</h6>
                        <div class="mb-3">
                            <label for="nama_menu" class="form-label">Nama Menu</label>
                            <input type="text" name="nama_menu" id="nama_menu" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga (Rp)</label>
                            <input type="number" name="harga" id="harga" class="form-control" min="0" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100">Simpan Menu</button>
                    </form>

                    <!-- Daftar Menu -->
                    <h6>Daftar Menu Aktif</h6>
                    <ul class="list-group">
                        @forelse($vendor->menus as $m)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $m->nama_menu }}</strong> <br>
                                    <small class="text-muted">Rp {{ number_format($m->harga, 0, ',', '.') }}</small>
                                </div>
                                <form action="{{ route('vendor.menu.destroy', $m->idmenu ?? $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada menu.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Daftar Pesanan -->
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Daftar Pesanan Lunas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID / Snap</th>
                                    <th>Menu</th>
                                    <th>Total</th>
                                    <th>Waktu Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesanan as $p)
                                    <tr>
                                        <td>
                                            <strong>#{{ $p->id_pesanan }}</strong><br>
                                            <small class="text-muted">{{ $p->snap_token ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <ul class="mb-0 ps-3">
                                                @foreach($p->detailPesanans as $pd)
                                                    <li>{{ $pd->menu->nama_menu ?? 'Menu dihapus' }} (x{{ $pd->jumlah }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->timestamp)->format('d M Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada pesanan lunas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection