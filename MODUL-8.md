# MODUL-8: Implementasi Barcode, QR Code & Kamera

Modul ini memuat langkah-langkah implementasi sistem Barcode (Code 128), QR Code 2D, Scanner Web Base, dan sinkronisasi hardware Kamera HTML5. Terdapat revisi alur praktikum di mana mahasiswa diwajibkan untuk merealisasikan **Web Camera Scanner** menggunakan library HTML5 (seperti html5-qrcode).

## Struktur Project / Folder yang Terlibat

```text
workshopFramework/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ScanController.php     (Baru: Handle View)
│   │   │   ... (controller controller sebelumnya)
├── resources/
│   ├── views/
│   │   ├── scan/
│   │   │   ├── barcode.blade.php      (Baru: Praktikum 1 Barcode Reader)
│   │   │   └── qr.blade.php           (Baru: Praktikum 2 Vendor QR Scanner)
├── routes/
│   └── web.php                        (Update: register routes scan)
```

## Breakdown Task Step-by-Step

### Praktikum 1: Halaman Barcode Reader (Kamera)
Membuat halaman yang membaca barcode dari kertas label. Setiap kali sistem berhasil membaca barcode:
a. Dikeluarkan bunyi beep pendek.
b. Scanner berhenti scan.
c. Menampilkan IDbarang, nama barang, dan harga barang.

1. Buat Controller baru ScanController yang memuat logika pemanggilan view.
2. Buat resources/views/scan/barcode.blade.php.
3. Integrasikan library html5-qrcode via CDN yang dapat melakukan instruksi web kamera.
4. Berikan handler scan success untuk memanggil API detail barang, hentikan scan, dan play audio.

### Praktikum 2: Update Aplikasi Kantin (Kamera QR)
Sistem QR yang bisa di-scan oleh Vendor untuk melihat status pesanan.

**A. Customer:**
1. Menyediakan tampilan Success berisi QR code String Data Penjualan (Telah Diimplementasikan).

**B. Vendor:**
1. Buat satu halaman Vendor Scanner Web (contoh: resources/views/scan/qr.blade.php).
2. Vendor melakukan scan melalui kamera pada halaman tersebut untuk mengurai QR JSON milik customer.
3. Setelah QR terbaca:
    - Muncul bunyi beep.
    - Scanner di-clear (berhenti).
    - Tabel menu yang dipesan & status dibentuk di DOM berdasarkan parse JSON pembeli.
