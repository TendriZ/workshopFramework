<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .otp-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Kode OTP Login</h2>
        <p>Halo <strong>{{ $name }}</strong>,</p>
        <p>Gunakan kode OTP berikut untuk melanjutkan login:</p>

        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
        </div>

        <p>Kode ini berlaku untuk <strong>1 kali penggunaan</strong>.</p>
        <p>Jangan bagikan kode ini kepada siapapun!</p>

        <div class="footer">
            <p>Email ini dikirim otomatis, jangan balas email ini.</p>
            <p>&copy; {{ date('Y') }} Koleksi Buku. All rights reserved.</p>
        </div>
    </div>
</body>
</html>