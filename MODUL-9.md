# MODUL 9: Sistem Pelacakan Geolocation Kunjungan Toko

## 1. Analisis Kebutuhan
Modul ini bertujuan untuk memastikan validitas kunjungan sales ke titik lokasi toko yang telah ditentukan menggunakan kombinasi **Scan Barcode** dan verifikasi **Geolocation (Latitude, Longitude, Accuracy)**.

Berdasarkan gambar instruksi, terdapat dua proses utama:
1. **Penentuan Titik Awal Toko:** Admin atau Sales mendaftarkan data toko baru beserta lokasi koordinat (latitude, longitude, dan accuracy) yang didapatkan secara langsung dari API Geolocation browser yang tingkat akurasinya difilter.
2. **Penentuan Titik Kunjungan Toko:** Sales mendatangi toko, melakukan *scan barcode* toko untuk menarik data koordinat target dari database. Sistem lalu melacak posisi sales saat itu, dan menghitung jarak aktual antara sales dengan toko menggunakan formula **Haversine**. Jarak tersebut dievaluasi dengan *Threshold Efektif* (jarak toleransi + akurasi hp sales + akurasi fiktif toko) untuk menentukan apakah kunjungan **DITERIMA** atau **DITOLAK**.

## 2. Struktur Tabel Database
Sesuai dengan lampiran, tabel yang dibutuhkan adalah `lokasi_toko` dengan spesifikasi:
- `barcode`: VARCHAR(8) NOT NULL (Primary Key)
- `nama_toko`: VARCHAR(50) NOT NULL
- `latitude`: DOUBLE NOT NULL
- `longitude`: DOUBLE NOT NULL
- `accuracy`: DOUBLE NOT NULL

## 3. Rencana Eksekusi (Tasks)
Berikut adalah daftar task berurutan untuk menyelesaikan fitur ini secara utuh:

- [ ] **Task 1: Database & Model Setup**
  - Membuat *migration* tabel `lokasi_toko`.
  - Merancang susunan struktur tabel sesuai permintaan.
  - Membuat model `LokasiToko.php` beserta variable fillable dan primary key string.
  - Menjalankan `php artisan migrate`.

- [ ] **Task 2: Kontroler & Routing**
  - Membuat `KunjunganTokoController.php`.
  - Mendaftarkan *routes* pada `web.php` untuk:
    - Menampilkan Daftar Toko (`index`).
    - Halaman form input titik awal toko (`create`) dan aksi simpannya (`store`).
    - Cetak Barcode Toko (`cetak_barcode`).
    - Halaman Scanner kunjungan toko oleh sales (`scan_visit`).
    - API Endpoint pemanggilan data toko berdasarkan barcode (`api_toko`).

- [ ] **Task 3: Desain Tampilan Frontend (Views)**
  - Menyesuaikan _Sidebar_ navigasi, menambahkan menu "Kunjungan Toko".
  - Pembuatan view **Daftar Toko (index)**: Menampilkan tabel list toko dan tombol Cetak Barcode.
  - Pembuatan view **Input Titik Awal (create)**: Form isian barcode, nama toko, latitude, longitude, error/accuracy, dengan script tambahan Lampiran 1 (Fungsi Promise JS penentuan lokasi paling presisi).
  - Pembuatan view **Titik Kunjungan / Scanner (scan)**: Layar integrasi HTML5-QRCode, dilengkapi logika *Haversine Formula* Javascript (Lampiran 2 & 3) untuk proses verifikasi dan laporan validitas kedekatan radius secara aktual.

- [ ] **Task 4: Pengujian**
  - Uji perizinan *Geolocation* di browser.
  - Validasi perhitungan radius penolakan/penerimaan berdasarkan fungsi Threshold.