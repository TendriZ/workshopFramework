<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VendorAuth
{
    public function handle(Request $request, Closure $next)
    {
        // ngecek apakah session 'vendor_id' ada, apa enggak
        if (!session('vendor_id')) {
            return redirect()->route('vendor.login')
                ->with('error', 'Silahkan login terlebih dahulu.');
        }

        return $next($request);
    }
}