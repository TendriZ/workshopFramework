<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function barcode()
    {
        return view('scan.barcode');
    }

    public function qr()
    {
        return view('scan.qr');
    }
}
