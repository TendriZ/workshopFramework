# MODUL 9: Sistem Pelacakan Geolocation Kunjungan Toko

## 1. Studi Kasus & Kebutuhan

### 1.1 Problem Statement
Seorang Client (pemilik usaha distributor) memerlukan aplikasi untuk memastikan bahwa sales-salesnya benar-benar telah mengunjungi toko-toko yang masuk dalam area kerja masing-masing sales.

**Masalah Utama:** Accuracy geolocation tidak selalu konsisten, sehingga perlu mekanisme validasi yang akurat.

### 1.2 Solusi Sistem
Menggunakan aturan verifikasi lokasi berbasis **Haversine Formula** dengan pendekatan threshold efektif:

**Workflow Utama:**
1. **Penentuan Titik Awal Toko** - Admin/Sales mendaftarkan toko dengan koordinat + accuracy
2. **Penentuan Titik Kunjungan** - Sales scan barcode, sistem validasi jarak actual vs threshold efektif

## 2. Konsep Teknis

### 2.1 Penentuan Awal Titik Toko
**Cara 1:** Mengambil dari Google Maps API
**Cara 2:** Client langsung ke lokasi toko dan mengambil coordinates via browser Geolocation API

### 2.2 Penentuan Titik Kunjungan Toko
1. Sales scan barcode/QR code toko
2. Sistem mengambil data toko (nama, alamat, lat, long, accuracy)
3. Sales mengambil posisi saat ini via browser Geolocation API
4. Sistem hitung jarak menggunakan **Formula Haversine**
5. Validasi dengan **Threshold Efektif**

### 2.3 Konsep Threshold Efektif
**Threshold Efektif** = Threshold Jarak + Accuracy Toko + Accuracy Sales

**Contoh Penerimaan:**
```
[TOKO]───────────────────────[SALES]
  ↑                                ↑
acc: 30m                        acc: 20m
jarak_aktual = 290m (pusat ke pusat)
threshold = 300m
threshold_efektif = 300 + 30 + 20 = 350m
290m ≤ 350m → DITERIMA ✓
```

**Contoh Penolakan:**
```
jarak_aktual = 450m
threshold_efektif = 350m
450m > 350m → DITOLAK ✗
```

## 3. Algoritma & Formula

### 3.1 Formula Haversine (JavaScript)
```javascript
function haversine(lat1, lng1, lat2, lng2) {
    const R = 6371000; // Radius bumi dalam meter
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2) ** 2 +
              Math.cos(lat1 * Math.PI / 180) *
              Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLng/2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}
```

### 3.2 Fungsi Geolocation Akurat (Lampiran 1)
```javascript
function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
  return new Promise((resolve, reject) => {
    let bestResult = null;
    const startTime = Date.now();

    const watchId = navigator.geolocation.watchPosition(
      (position) => {
        const acc = position.coords.accuracy;

        // Simpan hasil terbaik sejauh ini
        if (!bestResult || acc < bestResult.coords.accuracy) {
          bestResult = position;
        }

        // Kalau sudah cukup akurat, berhenti
        if (acc <= targetAccuracy) {
          navigator.geolocation.clearWatch(watchId);
          resolve(bestResult);
        }

        // Kalau timeout, pakai hasil terbaik yang ada
        if (Date.now() - startTime >= maxWait) {
          navigator.geolocation.clearWatch(watchId);
          if (bestResult) resolve(bestResult);
          else reject(new Error("Timeout, tidak dapat posisi"));
        }
      },
      (error) => reject(error),
      { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
    );
  });
}

// Penggunaan:
const pos = await getAccuratePosition(50); // target: accuracy ≤ 50 meter
console.log(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy);
```

**Cara Kerja Fungsi:**
1. Menggunakan `watchPosition` untuk terus mendapatkan posisi
2. Menyimpan hasil dengan accuracy terbaik
3. Berhenti saat accuracy ≤ target atau timeout
4. Mengembalikan posisi terbaik yang didapat

## 4. Struktur Database

### 4.1 Table `lokasi_toko`
```sql
CREATE TABLE lokasi_toko (
    barcode VARCHAR(8) NOT NULL PRIMARY KEY,
    nama_toko VARCHAR(50) NOT NULL,
    alamat TEXT,
    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    accuracy DOUBLE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 4.2 Model `LokasiToko.php`
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiToko extends Model
{
    protected $table = 'lokasi_toko';
    protected $primaryKey = 'barcode';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'barcode',
        'nama_toko',
        'alamat',
        'latitude',
        'longitude',
        'accuracy'
    ];
}
```

## 5. Rencana Eksekusi (Task Detail)

### Task 1: Database & Model Setup
- [x] Buat migration tabel `lokasi_toko`
- [x] Struktur tabel sesuai spesifikasi (barcode PK, nama_toko, lat, long, accuracy)
- [x] Buat model `LokasiToko.php` dengan fillable dan primary key string
- [x] Jalankan `php artisan migrate`

### Task 2: Kontroler & Routing
**File: `app/Http/Controllers/KunjunganTokoController.php`**

**Methods yang dibutuhkan:**
```php
class KunjunganTokoController extends Controller {
    public function index()        // Daftar semua toko
    public function create()       // Form input titik awal toko
    public function store()        // Simpan data toko baru
    public function cetakBarcode($barcode) // Cetak barcode toko
    public function scanVisit()    // Halaman scanner kunjungan
    public function apiToko($barcode) // API endpoint untuk ambil data toko
}
```

**Routes:**
```php
// Route Resource (CRUD)
Route::resource('kunjungan', KunjunganTokoController::class);

// Custom Routes
Route::get('kunjungan/cetak-barcode/{barcode}', [KunjunganTokoController::class, 'cetakBarcode']);
Route::get('kunjungan/scan', [KunjunganTokoController::class, 'scanVisit']);
Route::get('/api/toko/{barcode}', [KunjunganTokoController::class, 'apiToko']);
```

### Task 3: Desain Tampilan Frontend (Views)

#### 3.1 Sidebar Navigation
Update `resources/views/layouts/partials/sidebar.blade.php`
- Tambah menu "Kunjungan Toko"

#### 3.2 View: Daftar Toko (`resources/views/kunjungan/index.blade.php`)
- Tampilkan tabel semua toko
- Kolom: Barcode, Nama Toko, Alamat, Koordinat, Accuracy, Tombol Cetak Barcode

#### 3.3 View: Input Titik Awal (`resources/views/kunjungan/create.blade.php`)
- Form input: Barcode, Nama Toko, Alamat
- Display: Latitude, Longitude, Accuracy (auto-fetch via Geolocation)
- Tombol: "Ambil Lokasi" (panggil `getAccuratePosition()`)
- Tombol: "Simpan Data Toko"
- Script: Integrasi `getAccuratePosition()` function

#### 3.4 View: Scanner Kunjungan (`resources/views/kunjungan/scan.blade.php`)
- Integrasi HTML5-QRCode Scanner
- Form: Barcode Input (auto-fill dari scan)
- Display: Data Toko (Nama, Alamat, Koordinat Target)
- Display: Posisi Sales Saat Ini (Lat, Long, Accuracy)
- Button: "Cek Lokasi" (panggil `getAccuratePosition()` + Haversine)
- Display: Hasil Validasi (DITERIMA/DITOLAK + jarak)
- Script:
  - HTML5-QRCode integration
  - `getAccuratePosition()` function
  - `haversine()` function
  - Perhitungan threshold efektif

### Task 4: Pengujian
- [ ] Uji izin Geolocation di browser
- [ ] Validasi fungsi `getAccuratePosition()` mendapat accuracy ≤ 50m
- [ ] Validasi perhitungan Haversine menghasilkan jarak dalam meter
- [ ] Validasi threshold efektif: `threshold_jarak + accuracy_toko + accuracy_sales`
- [ ] Uji case: sales di dalam radius → DITERIMA
- [ ] Uji case: sales di luar radius → DITOLAK
- [ ] Uji barcode scanner (HTML5-QRCode)
- [ ] Uji cetak barcode toko

## 6. Teknologi & Library

### Backend:
- **Framework**: Laravel 10+
- **Database**: PostgreSQL
- **Validation**: FormRequest

### Frontend:
- **Framework**: Purple Admin (Bootstrap 4)
- **Barcode Scanner**: HTML5-QRCode
- **Geolocation**: Browser Native API
- **Notifications**: SweetAlert2

### Libraries yang dibutuhkan:
```bash
# Barcode generation (sudah ada di Modul 8)
composer require picqer/php-barcode-generator

# QR Code (sudah ada di Modul 8)
composer require endroid/qr-code
```

## 7. Konfigurasi Threshold

### Default Threshold:
- **Threshold Jarak**: 300 meter
- **Target Accuracy**: 50 meter
- **Max Wait Time**: 20 detik

### Format Validasi:
```javascript
const threshold = 300; // meter
const threshold_efektif = threshold + toko_accuracy + sales_accuracy;
const jarak_aktual = haversine(toko_lat, toko_lng, sales_lat, sales_lng);

if (jarak_aktual <= threshold_efektif) {
    // DITERIMA
} else {
    // DITOLAK
}
```

## 8. Implementasi Priority

### Priority 1 (Core Functionality):
1. Database & Model
2. Controller Basic CRUD
3. View: Daftar Toko + Cetak Barcode
4. API Endpoint data toko

### Priority 2 (Geolocation Integration):
5. View: Input Titik Awal dengan Geolocation
6. Integrasi `getAccuratePosition()` function

### Priority 3 (Validation System):
7. View: Scanner Kunjungan
8. HTML5-QRCode integration
9. Haversine formula implementation
10. Threshold efektif validation

### Priority 4 (UX & Testing):
11. Error handling
12. User notifications
13. Testing semua flow

---

**Status Spesifikasi**: ✅ Complete & Ready for Implementation
**Files Referensi**: Geolocation (1).docx, MODUL-8-PLAN.md
**Estimasi Waktu Implementasi**: 4-6 jam