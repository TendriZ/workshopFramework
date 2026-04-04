<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Koleksi Buku - @yield('title', 'Dashboard')</title>
    
    @include('layouts.partials.styles')
    @stack('styles')
</head>
<body>
    <div class="container-scroller">
        
        @include('layouts.partials.navbar')
        
        <div class="container-fluid page-body-wrapper">
            
            @include('layouts.partials.sidebar-customer')
            
            <div class="main-panel">
                <div class="content-wrapper">
                    {{-- Breadcrumb --}}
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('home') }}"><i class="mdi mdi-home"></i> Home</a>
                                    </li>
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        </div>
                    </div>
                    @yield('content')
                </div>
                
                @include('layouts.partials.footer')
            </div>
        </div>
    </div>

    @include('layouts.partials.scripts')
    
    @stack('scripts')
</body>
</html>