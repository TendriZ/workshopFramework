@extends('layouts.app')

@section('title', 'Payment Gateway - Customer - as Admin')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Payment Gateway (Customer as Admin)</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pesan Menu Kantin</h4>
                <p class="card-description">Customer tanpa login. User guest dibuat otomatis saat pesanan dikonfirmasi.</p>

                <div class="form-group">
                    <label for="idvendor">Vendor</label>
                    <select id="idvendor" class="form-control">
                        <option value="">Pilih vendor</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="idmenu">Menu</label>
                    <select id="idmenu" class="form-control" disabled>
                        <option value="">Pilih menu</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="harga_menu">Harga</label>
                    <input type="text" id="harga_menu" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" id="jumlah" class="form-control" min="1" value="1">
                </div>

                <div class="form-group">
                    <label for="catatan">Catatan</label>
                    <input type="text" id="catatan" class="form-control" maxlength="255" placeholder="Opsional">
                </div>

                <button class="btn btn-info" type="button" id="btnTambahItem" disabled>
                    <i class="mdi mdi-plus"></i> Tambah Item
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Keranjang Pesanan</h4>
                <div class="table-responsive">
                    <table class="table" id="tableCart">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th id="totalCart">Rp 0</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-success" id="btnCreateOrder" disabled>
                        Buat Pesanan
                    </button>
                </div>

                <hr>

                <div id="paymentSection" class="d-none">
                    <h5>Pembayaran</h5>
                    <p class="mb-1">ID Pesanan: <strong id="orderIdText">-</strong></p>
                    <p class="mb-1">Order ID Midtrans: <strong id="midtransOrderIdText">-</strong></p>
                    <p class="mb-3">Customer: <strong id="guestNameText">-</strong></p>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" id="btnPayOrder">
                            Bayar dengan Midtrans
                        </button>
                        <button type="button" class="btn btn-outline-info" id="btnCheckStatus">
                            Cek Status Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(config('midtrans.is_production'))
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif
<script>
let menuMap = {};
let cart = [];
let currentOrderId = null;
let currentMidtransOrderId = null;
let currentSnapToken = null;

function rupiah(nominal) {
    return 'Rp ' + Number(nominal).toLocaleString('id-ID');
}

function refreshCartTable() {
    const tbody = document.querySelector('#tableCart tbody');
    tbody.innerHTML = '';

    let total = 0;
    cart.forEach((item, idx) => {
        const subtotal = item.harga * item.jumlah;
        total += subtotal;

        tbody.innerHTML += `
            <tr>
                <td>${item.nama_menu}</td>
                <td>${rupiah(item.harga)}</td>
                <td>${item.jumlah}</td>
                <td>${rupiah(subtotal)}</td>
                <td>${item.catatan ?? ''}</td>
                <td><button class="btn btn-sm btn-danger" type="button" onclick="removeItem(${idx})"><i class="mdi mdi-delete"></i></button></td>
            </tr>
        `;
    });

    document.getElementById('totalCart').innerText = rupiah(total);
    document.getElementById('btnCreateOrder').disabled = cart.length === 0;
}

function removeItem(index) {
    cart.splice(index, 1);
    refreshCartTable();
}

async function loadVendors() {
    const res = await axios.get("{{ route('pg.api.vendors') }}");
    const sel = document.getElementById('idvendor');
    sel.innerHTML = '<option value="">Pilih vendor</option>';
    (res.data.data || []).forEach(v => {
        sel.innerHTML += `<option value="${v.idvendor}">${v.nama_vendor}</option>`;
    });
}

async function loadMenus(idvendor) {
    const res = await axios.get("{{ url('/payment-gateway/api/vendors') }}/" + idvendor + "/menus");
    const sel = document.getElementById('idmenu');
    sel.innerHTML = '<option value="">Pilih menu</option>';
    menuMap = {};

    (res.data.data || []).forEach(m => {
        menuMap[m.idmenu] = m;
        sel.innerHTML += `<option value="${m.idmenu}">${m.nama_menu}</option>`;
    });

    sel.disabled = false;
}

document.getElementById('idvendor').addEventListener('change', async function () {
    const idvendor = this.value;
    const menuSel = document.getElementById('idmenu');
    menuSel.disabled = true;
    menuSel.innerHTML = '<option value="">Pilih menu</option>';
    document.getElementById('harga_menu').value = '';
    document.getElementById('btnTambahItem').disabled = true;

    if (!idvendor) return;
    await loadMenus(idvendor);
});

document.getElementById('idmenu').addEventListener('change', function () {
    const idmenu = this.value;
    if (!idmenu || !menuMap[idmenu]) {
        document.getElementById('harga_menu').value = '';
        document.getElementById('btnTambahItem').disabled = true;
        return;
    }

    document.getElementById('harga_menu').value = rupiah(menuMap[idmenu].harga);
    document.getElementById('btnTambahItem').disabled = false;
});

document.getElementById('btnTambahItem').addEventListener('click', function () {
    const idmenu = document.getElementById('idmenu').value;
    const jumlah = parseInt(document.getElementById('jumlah').value || '0');
    const catatan = document.getElementById('catatan').value;

    if (!idmenu || !menuMap[idmenu] || jumlah < 1) {
        Swal.fire('Validasi', 'Pilih menu dan isi jumlah minimal 1.', 'warning');
        return;
    }

    const existing = cart.find(x => String(x.idmenu) === String(idmenu) && (x.catatan || '') === (catatan || ''));
    if (existing) {
        existing.jumlah += jumlah;
    } else {
        cart.push({
            idmenu: menuMap[idmenu].idmenu,
            nama_menu: menuMap[idmenu].nama_menu,
            harga: menuMap[idmenu].harga,
            jumlah,
            catatan
        });
    }

    document.getElementById('jumlah').value = 1;
    document.getElementById('catatan').value = '';
    refreshCartTable();
});

document.getElementById('btnCreateOrder').addEventListener('click', async function () {
    const idvendor = document.getElementById('idvendor').value;
    if (!idvendor || cart.length === 0) {
        Swal.fire('Validasi', 'Vendor dan item pesanan wajib diisi.', 'warning');
        return;
    }

    const payload = {
        idvendor: idvendor,
        items: cart.map(i => ({ idmenu: i.idmenu, jumlah: i.jumlah, catatan: i.catatan }))
    };

    try {
        const res = await axios.post("{{ route('pg.order') }}", payload, {
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
        });

        currentOrderId = res.data.data.idpesanan;
        currentMidtransOrderId = res.data.data.order_id_midtrans;
        currentSnapToken = res.data.data.snap_token;
        document.getElementById('paymentSection').classList.remove('d-none');
        document.getElementById('orderIdText').innerText = currentOrderId;
        document.getElementById('midtransOrderIdText').innerText = currentMidtransOrderId;
        document.getElementById('guestNameText').innerText = res.data.data.nama_customer;

        Swal.fire('Berhasil', 'Pesanan dibuat. Silakan lanjutkan pembayaran.', 'success');
        openMidtransPopup();
    } catch (e) {
        Swal.fire('Error', e.response?.data?.message || 'Gagal membuat pesanan', 'error');
    }
});

function openMidtransPopup() {
    if (!currentSnapToken) {
        Swal.fire('Info', 'Snap token belum tersedia.', 'info');
        return;
    }

    if (typeof window.snap === 'undefined') {
        Swal.fire('Error', 'Snap.js Midtrans belum termuat. Cek MIDTRANS_CLIENT_KEY.', 'error');
        return;
    }

    window.snap.pay(currentSnapToken, {
        onSuccess: async function () {
            await verifyOrderStatus();
        },
        onPending: function () {
            Swal.fire('Pending', 'Transaksi masih pending. Silakan cek status pembayaran.', 'info');
        },
        onError: function () {
            Swal.fire('Error', 'Pembayaran gagal diproses oleh Midtrans.', 'error');
        },
        onClose: function () {
            Swal.fire('Info', 'Popup pembayaran ditutup. Kamu bisa bayar lagi dari tombol Midtrans.', 'info');
        }
    });
}

async function verifyOrderStatus() {
    if (!currentOrderId) {
        Swal.fire('Info', 'Buat pesanan terlebih dahulu.', 'info');
        return;
    }

    try {
        const res = await axios.post("{{ url('/asAdmin/kantin/pay') }}/" + currentOrderId, {}, {
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
        });

        if (res.data.data.status_bayar === 1 || res.data.data.status_bayar === 'settlement' || res.data.data.status_bayar === 'capture') {
            Swal.fire({
                title: 'Lunas',
                text: 'Pembayaran berhasil dikonfirmasi. Mengalihkan ke Struk QR...',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "{{ url('/kantin/receipt') }}/" + currentOrderId;
            });
            return;
        }

        Swal.fire('Pending', 'Pembayaran belum lunas. Silakan tunggu atau cek ulang beberapa saat lagi.', 'info');
    } catch (e) {
        Swal.fire('Error', e.response?.data?.message || 'Gagal memverifikasi pembayaran', 'error');
    }
}

document.getElementById('btnPayOrder').addEventListener('click', openMidtransPopup);
document.getElementById('btnCheckStatus').addEventListener('click', verifyOrderStatus);

loadVendors();
</script>
@endpush
