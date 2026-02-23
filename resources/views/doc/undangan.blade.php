<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #222;
            font-size: 13px;
            line-height: 1.5;
        }
        .page {
            padding: 30px 60px 40px 60px;
        }

        /* === HEADER / KOP SURAT === */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #222;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-logo-cell {
            width: 90px;
            vertical-align: middle;
            text-align: center;
        }
        .kop-logo {
            width: 75px;
            height: 75px;
        }
        .kop-text-cell {
            vertical-align: middle;
            text-align: center;
            padding: 0 10px;
        }
        .kop-univ {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a3c6e;
            letter-spacing: 1px;
        }
        .kop-fakultas {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a3c6e;
            letter-spacing: 2px;
        }
        .kop-alamat {
            font-size: 10px;
            color: #444;
            margin-top: 2px;
        }

        /* === ISI SURAT === */
        .info-surat {
            margin-bottom: 15px;
        }
        .info-table {
            border-collapse: collapse;
        }
        .info-table td {
            padding: 1px 5px;
            font-size: 13px;
            vertical-align: top;
        }
        .info-label {
            width: 80px;
        }
        .info-colon {
            width: 15px;
        }

        .perihal {
            margin-bottom: 20px;
        }

        .kepada {
            margin-bottom: 15px;
            padding-left: 0;
        }
        .kepada p {
            margin: 2px 0;
        }

        .salam {
            margin: 10px 0;
        }

        .isi-surat {
            text-align: justify;
            margin-bottom: 10px;
        }
        .isi-surat p {
            margin: 8px 0;
            text-indent: 40px;
        }

        /* Detail acara */
        .detail-acara {
            margin: 10px 0 10px 40px;
        }
        .detail-table {
            border-collapse: collapse;
        }
        .detail-table td {
            padding: 2px 5px;
            font-size: 13px;
            vertical-align: top;
        }
        .detail-label {
            width: 120px;
        }
        .detail-colon {
            width: 15px;
        }

        .penutup {
            text-align: justify;
            margin: 10px 0;
        }

        /* === TANDA TANGAN === */
        .ttd-section {
            margin-top: 25px;
            width: 100%;
        }
        .ttd-right {
            float: right;
            text-align: center;
            width: 280px;
        }
        .ttd-right p {
            margin: 2px 0;
            font-size: 13px;
        }
        .ttd-space {
            height: 60px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }
        .ttd-nip {
            font-size: 12px;
        }

        /* Tembusan */
        .tembusan {
            clear: both;
            margin-top: 130px;
            font-size: 12px;
        }
        .tembusan p {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- KOP SURAT -->
        <div class="kop-surat">
            <table class="kop-table">
                <tr>
                    <td class="kop-logo-cell">
                        {{-- Logo placeholder (kotak sebagai pengganti logo) --}}
                        <div style="width:70px;height:70px;border:2px solid #1a3c6e;border-radius:50%;margin:0 auto;display:flex;align-items:center;justify-content:center;">
                            <span style="font-size:10px;color:#1a3c6e;font-weight:bold;">UNAIR</span>
                        </div>
                    </td>
                    <td class="kop-text-cell">
                        <div class="kop-univ">Universitas Airlangga</div>
                        <div class="kop-fakultas">Fakultas Vokasi</div>
                        <div class="kop-alamat">
                            Jl. Dharmawangsa Dalam No.28-30, Airlangga, Kec. Gubeng, Surabaya, Jawa Timur 60286<br>
                            Telp: (031) 5033869 | Email: fvokasi@unair.ac.id | Website: https://vokasi.unair.ac.id
                        </div>
                    </td>
                    <td class="kop-logo-cell">
                        <div style="width:70px;height:70px;border:2px solid #1a3c6e;border-radius:50%;margin:0 auto;display:flex;align-items:center;justify-content:center;">
                            <span style="font-size:8px;color:#1a3c6e;font-weight:bold;">F.VOKASI</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- INFO SURAT -->
        <div class="info-surat">
            <table class="info-table">
                <tr>
                    <td class="info-label">Nomor</td>
                    <td class="info-colon">:</td>
                    <td>{{ $nomor }}</td>
                </tr>
                <tr>
                    <td class="info-label">Lampiran</td>
                    <td class="info-colon">:</td>
                    <td>{{ $lampiran }}</td>
                </tr>
                <tr>
                    <td class="info-label">Perihal</td>
                    <td class="info-colon">:</td>
                    <td><strong>{{ $perihal }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- KEPADA -->
        <div class="kepada">
            <p>Kepada Yth.</p>
            <p><strong>{{ $kepada }}</strong></p>
            <p>di Tempat</p>
        </div>

        <!-- ISI SURAT -->
        <p class="salam"><em>Assalamu'alaikum Wr. Wb.</em></p>

        <div class="isi-surat">
            <p>
                Sehubungan dengan pelaksanaan kegiatan akademik di lingkungan Fakultas Vokasi 
                Universitas Airlangga, dengan ini kami mengundang Bapak/Ibu/Sdr/i untuk hadir 
                dalam acara yang akan dilaksanakan pada:
            </p>
        </div>

        <!-- DETAIL ACARA -->
        <div class="detail-acara">
            <table class="detail-table">
                <tr>
                    <td class="detail-label">Acara</td>
                    <td class="detail-colon">:</td>
                    <td><strong>{{ $acara }}</strong></td>
                </tr>
                <tr>
                    <td class="detail-label">Hari/Tanggal</td>
                    <td class="detail-colon">:</td>
                    <td>{{ $tanggal }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Waktu</td>
                    <td class="detail-colon">:</td>
                    <td>{{ $waktu }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Tempat</td>
                    <td class="detail-colon">:</td>
                    <td>{{ $tempat }}</td>
                </tr>
            </table>
        </div>

        <p class="penutup">
            Mengingat pentingnya acara ini, kami mengharapkan kehadiran Bapak/Ibu/Sdr/i tepat pada 
            waktu yang telah ditentukan. Atas perhatian dan kehadirannya, kami ucapkan terima kasih.
        </p>

        <p class="salam"><em>Wassalamu'alaikum Wr. Wb.</em></p>

        <!-- TANDA TANGAN -->
        <div class="ttd-section">
            <div class="ttd-right">
                <p>Surabaya, {{ $tanggal_surat }}</p>
                <p>Dekan Fakultas Vokasi</p>
                <div class="ttd-space"></div>
                <p class="ttd-nama">{{ $dekan }}</p>
                <p class="ttd-nip">NIP. {{ $nip_dekan }}</p>
            </div>
        </div>

        <!-- TEMBUSAN -->
        <div class="tembusan">
            <p><em>Tembusan:</em></p>
            <p>1. Wakil Dekan Fakultas Vokasi</p>
            <p>2. Arsip</p>
        </div>
    </div>
</body>
</html>
