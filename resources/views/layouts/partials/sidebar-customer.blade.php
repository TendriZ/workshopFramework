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
    </ul>
</nav>
