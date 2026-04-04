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

        
    </ul>
</nav>
