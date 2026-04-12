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
                
        <div class="container-fluid page-body-wrapper">
            
            @include('layouts.partials.sidebar-customer')
            
            <div class="main-panel">
                <div class="content-wrapper">
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