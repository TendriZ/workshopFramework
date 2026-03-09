<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="profile" referrerpolicy="no-referrer">
                    @else
                        <img src="{{ asset('template/images/faces/face1.jpg') }}" alt="profile">
                    @endif
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ Auth::user()?->name }}</span>
                    <span class="text-secondary text-small">Administrator</span>
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

        
    </ul>
</nav>
