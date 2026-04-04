<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PaymentGatewayController;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect'); 
Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::get('/verifikasi-otp', [GoogleAuthController::class, 'showOtpForm'])->name('otp.form');
Route::post('/verifikasi-otp', [GoogleAuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/verifikasi-otp/resend', [GoogleAuthController::class, 'resendOtp'])->name('otp.resend');


Auth::routes();

Route::get('/test', [HomeController::class, 'test'])->name('test');

// Modul 6 - Payment Gateway (Customer / Public)
Route::get('/kantin/customer', [PaymentGatewayController::class, 'customerPage'])->name('pg.customer');
Route::post('/kantin/order', [PaymentGatewayController::class, 'createOrder'])->name('pg.order');
Route::post('/kantin/pay/{idpesanan}', [PaymentGatewayController::class, 'payOrder'])->name('pg.pay');
Route::post('/kantin/midtrans/notification', [PaymentGatewayController::class, 'midtransNotification'])->name('pg.midtrans.notification');



Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);

    // Barang / Tag Harga UMKM
    Route::post('/barang/cetak-tag', [BarangController::class, 'cetakTagHarga'])->name('barang.cetak-tag');
    Route::resource('barang', BarangController::class);

    // PDF
    Route::get('/pdf', [PDFController::class, 'index'])->name('pdf.index');
    Route::get('/pdf/sertifikat', [PDFController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::post('/pdf/sertifikat', [PDFController::class, 'generateSertifikat'])->name('pdf.generate.sertifikat');
    Route::get('/pdf/undangan', [PDFController::class, 'undangan'])->name('pdf.undangan');
    Route::post('/pdf/undangan', [PDFController::class, 'generateUndangan'])->name('pdf.generate.undangan');

    // JS Exercise (JavaScript & jQuery)
    Route::get('/js/barang-html', function () { return view('js-exercise.barang-html'); })->name('js.barang-html');
    Route::get('/js/barang-datatable', function () { return view('js-exercise.barang-datatable'); })->name('js.barang-datatable');
    Route::get('/js/select-kota', function () { return view('js-exercise.select-kota'); })->name('js.select-kota');

    // Ajax Exercise (Modul 5)
    Route::get('/ajax/wilayah', function () { return view('ajax-exercise.wilayah'); })->name('ajax.wilayah');
    Route::get('/ajax/wilayah/provinsi', [WilayahController::class, 'provinsi'])->name('wilayah.provinsi');
    Route::get('/ajax/wilayah/kota/{id_provinsi}', [WilayahController::class, 'kota'])->name('wilayah.kota');
    Route::get('/ajax/wilayah/kecamatan/{id_kota}', [WilayahController::class, 'kecamatan'])->name('wilayah.kecamatan');
    Route::get('/ajax/wilayah/kelurahan/{id_kecamatan}', [WilayahController::class, 'kelurahan'])->name('wilayah.kelurahan');

    Route::get('/ajax/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/ajax/pos/cari', [PosController::class, 'cariBarang'])->name('pos.cari');
    Route::post('/ajax/pos/bayar', [PosController::class, 'bayar'])->name('pos.bayar');

    // Modul 6 - Payment Gateway (Vendor / Auth)
    Route::get('/asAdmin/kantin/customer', [PaymentGatewayController::class, 'customerPageasAdmin'])->name('pg.customer.asAdmin');
    Route::post('/asAdmin/kantin/order', [PaymentGatewayController::class, 'createOrder'])->name('pg.order.asAdmin');
    Route::post('/asAdmin/kantin/pay/{idpesanan}', [PaymentGatewayController::class, 'payOrder'])->name('pg.pay.asAdmin');
    Route::post('/asAdmin/kantin/midtrans/notification', [PaymentGatewayController::class, 'midtransNotification'])->name('pg.midtrans.notification.asAdmin');

    Route::get('/kantin/api/vendors', [PaymentGatewayController::class, 'listVendor'])->name('pg.api.vendors');
    Route::get('/kantin/api/vendors/{idvendor}/menus', [PaymentGatewayController::class, 'listMenuByVendor'])->name('pg.api.vendor-menus');
    Route::get('/kantin/vendor/menu', [PaymentGatewayController::class, 'vendorMenuIndex'])->name('pg.vendor.menu');
    Route::post('/kantin/vendor/menu', [PaymentGatewayController::class, 'vendorMenuStore'])->name('pg.vendor.menu.store');
    Route::put('/kantin/vendor/menu/{idmenu}', [PaymentGatewayController::class, 'vendorMenuUpdate'])->name('pg.vendor.menu.update');
    Route::delete('/kantin/vendor/menu/{idmenu}', [PaymentGatewayController::class, 'vendorMenuDestroy'])->name('pg.vendor.menu.destroy');
    Route::get('/kantin/vendor/pesanan-lunas', [PaymentGatewayController::class, 'paidOrders'])->name('pg.vendor.paid-orders');
});

