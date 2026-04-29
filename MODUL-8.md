# MODUL-8: Implementasi Barcode, QR Code & Kamera

Modul ini memuat langkah-langkah implementasi sistem Barcode (Code 128), QR Code 2D, dan sinkronisasi hardware Kamera HTML5 (getUserMedia API) dalam framework Laravel 10.48.

## Struktur Project / Folder yang Terlibat

```text
workshopFramework/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── BarangController.php   (Update: generateBarcode)
│   │   │   ├── PosController.php      (Update: success)
│   │   │   └── CustomerController.php (Baru: CRUD & upload kamera)
│   ├── Models/
│   │   └── Customer.php               (Baru)
├── database/
│   └── migrations/
│       └── xxxx_xx_xx_xxxxxx_create_customers_table.php (Baru)
├── resources/
│   ├── views/
│   │   ├── barang/
│   │   │   └── pdf-label.blade.php    (Update: img barcode)
│   │   ├── pos/
│   │   │   └── success.blade.php      (Baru: render QR)
│   │   └── customer/
│   │       ├── index.blade.php        (Baru)
│   │       ├── create-blob.blade.php  (Baru)
│   │       └── create-file.blade.php  (Baru)
├── routes/
│   └── web.php                        (Update: register routes)
└── composer.json                      (Update: require libraries)
```

## Urutan Implementasi & Fase Dependencies

Proses dibagi menjadi 4 fase agar aman diintegrasikan step-by-step:
1. **Fase 1: Setup Library** (Bisa diparalel)
2. **Fase 2: Tag Harga Barcode** (Bergantung pada Picqer)
3. **Fase 3: POS QR Code** (Bergantung pada Endroid)
4. **Fase 4: Customer Management Kamera** (Independent web API)

---

## Breakdown Task Step-by-Step

### Fase 1: Setup Library & Dependencies
1. [x] Jalankan command instalasi lib barcode: `composer require picqer/php-barcode-generator "^2.4"`
2. [x] Jalankan command instalasi lib QR: `composer require endroid/qr-code "^5.0"`

---

### Fase 2: Studi Kasus 1 (Generate Barcode Tag Harga)
1. [x] Buka `app/Http/Controllers/BarangController.php`.
2. [x] Tambahkan import `use Picqer\Barcode\BarcodeGeneratorPNG;`.
3. [x] Update method `cetakTagHarga()`. Inisialisasi generator: `$generator = new BarcodeGeneratorPNG();`.
4. [x] Di dalam logic looping `$barangs`, generate base64 string dari `$barang->id_barang`.
5. [x] Pass property `$barang->barcode` ke view.
6. [x] Buka `resources/views/barang/pdf-label.blade.php`.
7. [x] Sisipkan tag render base64 img barcode (sekitar dimensi 30x8mm) tepat di atas text `$barang->id_barang` untuk tiap cell.

---

### Fase 3: Studi Kasus 2 (Generate QR Code Payment Success)
1. [x] Buka `app/Http/Controllers/PosController.php`.
2. [x] Tambahkan import `use Endroid\QrCode\Builder\Builder;`.
3. [x] Daftarkan method public baru `success($id_penjualan)`.
4. [x] Di dalam method `success()`, query data master `penjualan` dan relasi `penjualan_detail`.
5. [x] Encode relasi object result ke format JSON.
6. [x] Letakkan string JSON ke QR logic. Render QR code ke output `base64_encode()`.
7. [x] Pass variable `$penjualan` dan `$qr_code_base64` ke render view `pos.success`.
8. [x] Buat file baru: `resources/views/pos/success.blade.php` dan render tag HTML `<img src="data:image/png;base64,{{ $qr_code_base64 }}">`.
9. [x] Daftarkan route GET baru `/pos/success/{id}` ke method `success` di `routes/web.php`.

---

### Fase 4: Studi Kasus 3 (Akses Kamera Customer)

#### 4.1 Persiapan Skema Data & Endpoint
1. [x] Jalankan command: `php artisan make:migration create_customers_table` dan `php artisan make:model Customer -c`.
2. [x] Update migration pelanggan sesuai spec (id, nama, email, foto_blob BYTEA, foto_path VARCHAR, timestamps).
3. [x] Jalankan `php artisan migrate`.
4. [x] Buka `app/Http/Controllers/CustomerController.php` dan daftarkan view methods: `index()`, `createBlob()`, `createFile()`.
5. [x] Buka `routes/web.php` dan register URL route resource.

#### 4.2 Handling Controller Logic
6. [x] Pada `CustomerController`, buat method `storeBlob(Request $request)`. Decode field string JSON base64 img dan simpan ke database kolom `foto_blob`.
7. [x] Pada `CustomerController`, buat method `storeFile(Request $request)`. Handle file input tipe `FormData`, simpan path dengan `$request->file('foto')->store('public/customers')` ke kolom `foto_path`.

#### 4.3 View & Hardware HTML5
8. [x] Sediakan file view induk: `resources/views/customer/index.blade.php` dengan tabel daftar grid. Munculkan gambar dari base64 stream atau relative storage path.
9. [x] Buat `create-blob.blade.php`: render  `<video autoplay playsinline>` & `<canvas>`. Injeksi API `navigator.mediaDevices.getUserMedia()`. Tangkap stream base64 lewat `canvas.toDataURL()` lalu Submit Axios POST API ke `storeBlob`.
10. [x] Buat `create-file.blade.php`: Tangkap frame sebagai `canvas.toBlob()`, injeksi dalam `FormData` POST menuju endpoint `storeFile`.

---

## Testing Checklist
- [x] **Generate Barcode**: Export PDF dilabeli layout 5x8 Tom & Jerry, dan readable.
- [x] **Pos QR Receipt**: Setelah URL hit halaman berhasil, UI receipt muncul lengkap dengan pattern valid JSON didalam QR Code.
- [x] **Hardware Kamera (BLOB)**: Alert _user media access_ memintas akses Webcam. Gambar berhasil tertampung di PostgreSQL Table column.
- [x] **Hardware Kamera (File)**: Gambar dirender kedalam /storage Server Path dan URL File terbaca di index pelanggan.

***

_Document compiled by Agent based on Requirements._