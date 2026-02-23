<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        html, body {
            margin: 0;
            padding: 0;
            background: #fff;
            font-family: 'Times New Roman', serif;
        }

        /* ===== FRAME DEKORATIF ===== */
        .border-outer {
            position: fixed;
            top: 12px; left: 12px; right: 12px; bottom: 12px;
            border: 3px solid #764ba2;
        }
        .border-inner {
            position: fixed;
            top: 19px; left: 19px; right: 19px; bottom: 19px;
            border: 1px solid #b388d9;
        }
        .corner { position: fixed; width: 50px; height: 50px; }
        .corner-tl { top: 24px; left: 24px;
            border-top: 4px solid #764ba2; border-left: 4px solid #764ba2; }
        .corner-tr { top: 24px; right: 24px;
            border-top: 4px solid #764ba2; border-right: 4px solid #764ba2; }
        .corner-bl { bottom: 24px; left: 24px;
            border-bottom: 4px solid #764ba2; border-left: 4px solid #764ba2; }
        .corner-br { bottom: 24px; right: 24px;
            border-bottom: 4px solid #764ba2; border-right: 4px solid #764ba2; }

        /* Medal pojok kanan atas */
        .medal { position: fixed; top: 28px; right: 48px; text-align: center; }
        .medal-circle {
            width: 62px; height: 62px;
            background: linear-gradient(135deg, #f0c040, #d4a520);
            border-radius: 50%; border: 3px solid #c4960e; margin: 0 auto;
        }
        .ribbon {
            width: 0; height: 0;
            border-left: 15px solid #cc3333; border-right: 15px solid #cc3333;
            border-bottom: 20px solid transparent; margin: -4px auto 0;
        }

        /* ===== KONTEN UTAMA ===== */
        .certificate {
            text-align: center;
            padding: 50px 90px 0 90px;
        }

        h1.title {
            font-size: 52px;
            font-weight: bold;
            color: #764ba2;
            letter-spacing: 14px;
            margin: 0 0 2px 0;
            text-transform: uppercase;
        }
        .nomor {
            font-size: 14px;
            color: #777;
            margin: 0 0 18px 0;
        }
        .label-text {
            font-size: 18px;
            color: #555;
            margin: 0 0 8px 0;
        }
        h2.nama {
            font-size: 38px;
            font-weight: bold;
            color: #2c2c2c;
            margin: 0 0 6px 0;
            display: inline-block;
            border-bottom: 3px solid #764ba2;
            padding: 0 20px 6px 20px;
        }
        .partisipasi {
            font-size: 17px;
            color: #555;
            margin: 16px 0 6px 0;
        }
        h3.jabatan {
            font-size: 28px;
            font-weight: bold;
            color: #2c2c2c;
            margin: 0 0 16px 0;
        }
        .deskripsi {
            font-size: 20px;
            color: #555;
            line-height: 1.6;
            max-width: 850px;
            margin: 0 auto;
        }
        .deskripsi strong { color: #333; }

        /* ===== TANDA TANGAN — fixed di bagian bawah halaman ===== */
        .ttd-container {
            position: fixed;
            bottom: 40px;
            left: 60px;
            right: 60px;
        }
        .ttd-table { width: 100%; border-collapse: collapse; }
        .ttd-cell {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        .ttd-title {
            font-size: 15px;
            font-weight: bold;
            color: #444;
            margin-bottom: 50px;
        }
        .ttd-line {
            border-top: 1px solid #555;
            width: 170px;
            margin: 0 auto;
            padding-top: 5px;
        }
        .ttd-name {
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Frame dekoratif -->
    <div class="border-outer"></div>
    <div class="border-inner"></div>
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>
    <div class="medal">
        <div class="medal-circle"></div>
        <div class="ribbon"></div>
    </div>

    <!-- Konten utama -->
    <div class="certificate">
        <h1 class="title">Sertifikat</h1>
        <p class="nomor">No: {{ $nomor }}</p>

        <p class="label-text">Diberikan kepada:</p>
        <h2 class="nama">{{ $nama }}</h2>

        <p class="partisipasi">Atas partisipasinya sebagai:</p>
        <h3 class="jabatan">{{ $jabatan }}</h3>

        <p class="deskripsi">
            Dalam acara Seminar Nasional dengan tema
            "<strong>Membalik Tren Global: Menjawab Epidemi Penyakit Tidak Menular
            Melalui Revolusi Gaya Hidup dan Kekuatan Kesehatan Masyarakat</strong>"
            yang diselenggarakan oleh Program Studi Kesehatan Masyarakat FIKKIA
            Universitas Airlangga pada Sabtu, 18 Oktober 2025.
        </p>
    </div>

    <!-- Tanda tangan (fixed di bawah halaman) -->
    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td class="ttd-cell">
                    <div class="ttd-title">Dekan FIKKIA UNAIR</div>
                    <div class="ttd-line">
                        <div class="ttd-name">{{ $dekan }}</div>
                    </div>
                </td>
                <td class="ttd-cell">
                    <div class="ttd-title">Koordinator Program Studi<br>Kesehatan Masyarakat FIKKIA UNAIR</div>
                    <div class="ttd-line">
                        <div class="ttd-name">{{ $koordinator }}</div>
                    </div>
                </td>
                <td class="ttd-cell">
                    <div class="ttd-title">Ketua Pelaksana</div>
                    <div class="ttd-line">
                        <div class="ttd-name">{{ $ketua }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
