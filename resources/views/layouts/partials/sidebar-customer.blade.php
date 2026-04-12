<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link {{ Request::is('payment-gateway/*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#paymentGatewayMenu" aria-expanded="{{ Request::is('payment-gateway/*') ? 'true' : 'false' }}">
                <span class="menu-title">Pemesanan Menu</span>
                <i class="mdi mdi-credit-card menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('payment-gateway/*') ? 'show' : '' }}" id="paymentGatewayMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('payment-gateway/customer') ? 'active' : '' }}" href="{{ route('pg.customer') }}">
                            Customer - Pesan & Bayar
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
        
    </ul>
</nav>
