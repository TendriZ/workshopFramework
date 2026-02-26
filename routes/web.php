<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Auth\GoogleAuthController;


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



Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);

    // Barang / Tag Harga UMKM
    Route::resource('barang', BarangController::class);
    Route::post('/barang/cetak-tag', [BarangController::class, 'cetakTagHarga'])->name('barang.cetak-tag');

    // PDF
    Route::get('/pdf', [PDFController::class, 'index'])->name('pdf.index');
    Route::get('/pdf/sertifikat', [PDFController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::post('/pdf/sertifikat', [PDFController::class, 'generateSertifikat'])->name('pdf.generate.sertifikat');
    Route::get('/pdf/undangan', [PDFController::class, 'undangan'])->name('pdf.undangan');
    Route::post('/pdf/undangan', [PDFController::class, 'generateUndangan'])->name('pdf.generate.undangan');
});

