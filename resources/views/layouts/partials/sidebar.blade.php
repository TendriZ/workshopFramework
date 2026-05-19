<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    @if(auth()->check() && auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="profile" referrerpolicy="no-referrer">
                    @else
                        <img src="{{ asset('template/images/faces/face1.jpg') }}" alt="profile">
                    @endif
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ auth()->check() ? auth()->user()->name : 'Guest User' }}</span>
                    <span class="text-secondary text-small">{{ auth()->check() ? 'Administrator' : 'Public Customer' }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        
        <li class="nav-item {{ Request::is('home') || Request::is('/') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('home') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        
        <li class="nav-item {{ Request::is('kategori*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-tag-multiple menu-icon"></i>
            </a>
        </li>
        
        <li class="nav-item {{ Request::is('buku*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book-multiple menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ Request::is('barang*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Tag Harga UMKM</span>
                <i class="mdi mdi-label menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ Request::is('pdf*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pdf.index') }}">
                <span class="menu-title">Cetak PDF</span>
                <i class="mdi mdi-file-pdf menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ Request::is('scan/*') ? 'active' : '' }}">
            <a class="nav-link {{ Request::is('scan/*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#scanMenu" aria-expanded="{{ Request::is('scan/*') ? 'true' : 'false' }}">
                <span class="menu-title">Scanner Kamera</span>
                <i class="mdi mdi-qrcode-scan menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('scan/*') ? 'show' : '' }}" id="scanMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('scan/barcode') ? 'active' : '' }}" href="{{ route('scan.barcode') }}">
                            Barcode (Praktikum 1)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('scan/qr') ? 'active' : '' }}" href="{{ route('scan.qr') }}">
                            QR Code (Praktikum 2)
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::is('js/*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#jsMenu" aria-expanded="{{ Request::is('js/*') ? 'true' : 'false' }}">
                <span class="menu-title">JS Exercise</span>
                <i class="mdi mdi-language-javascript menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('js/*') ? 'show' : '' }}" id="jsMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('js/barang-html') ? 'active' : '' }}" href="{{ route('js.barang-html') }}">
                            Barang (HTML Table)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('js/barang-datatable') ? 'active' : '' }}" href="{{ route('js.barang-datatable') }}">
                            Barang (DataTables)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('js/select-kota') ? 'active' : '' }}" href="{{ route('js.select-kota') }}">
                            Select Kota
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::is('ajax/*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#ajaxMenu" aria-expanded="{{ Request::is('ajax/*') ? 'true' : 'false' }}">
                <span class="menu-title">Ajax Exercise</span>
                <i class="mdi mdi-swap-horizontal menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('ajax/*') ? 'show' : '' }}" id="ajaxMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('ajax/wilayah') ? 'active' : '' }}" href="{{ route('ajax.wilayah') }}">
                            Select Wilayah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('ajax/pos') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                            Point of Sales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('kunjungan*') ? 'active' : '' }}" href="{{ route('kunjungan.index') }}">
                            Geolocation Toko
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::is('payment-gateway/*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#paymentGatewayMenu" aria-expanded="{{ Request::is('payment-gateway/*') ? 'true' : 'false' }}">
                <span class="menu-title">Pemesanan Menu</span>
                <i class="mdi mdi-credit-card menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('payment-gateway/*') ? 'show' : '' }}" id="paymentGatewayMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('payment-gateway/customer') ? 'active' : '' }}" href="{{ route('pg.customer.asAdmin') }}">
                            Customer - Pesan & Bayar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('payment-gateway/vendor/menu') ? 'active' : '' }}" href="{{ route('pg.vendor.menu') }}">
                            Vendor - Master Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('payment-gateway/vendor/pesanan-lunas') ? 'active' : '' }}" href="{{ route('pg.vendor.paid-orders') }}">
                            Vendor - Pesanan Lunas
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::is('customer/*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#customerMenu" aria-expanded="{{ Request::is('customer/*') ? 'true' : 'false' }}">
                <span class="menu-title">Customer</span>
                <i class="mdi mdi-account-group menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('customer/*') ? 'show' : '' }}" id="customerMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/index') ? 'active' : '' }}" href="{{ route('customer.index') }}">
                            Data Customer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/create1') ? 'active' : '' }}" href="{{ route('customer.create1') }}">
                            Tambah Customer 1
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/create2') ? 'active' : '' }}" href="{{ route('customer.create2') }}">
                            Tambah Customer 2
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item {{ Request::is('admin/antrian*') ? 'active' : '' }}">
            <a class="nav-link {{ Request::is('admin/antrian*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#antrianMenu" aria-expanded="{{ Request::is('admin/antrian*') ? 'true' : 'false' }}">
                <span class="menu-title">Sistem Antrian</span>
                <i class="mdi mdi-human-queue menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('admin/antrian*') ? 'show' : '' }}" id="antrianMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/antrian') ? 'active' : '' }}" href="{{ route('antrian.admin') }}">
                            Dashboard Admin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/guest" target="_blank">
                            Form Pendaftaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/papan" target="_blank">
                            Papan Antrian
                        </a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</nav>
