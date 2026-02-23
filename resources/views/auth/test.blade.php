<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Gagal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background-color: #fee2e2;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 40px;
            font-weight: bold;
            margin: 0 auto 20px;
        }

        h1 {
            color: #1f2937;
            margin-bottom: 10px;
            font-size: 24px;
        }

        p {
            color: #6b7280;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            background-color: #ef4444;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            transition: background 0.3s ease;
            font-weight: 600;
        }

        .btn:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="icon-box">
            &times;
        </div>
        <h1>Ups! Gagal</h1>
        <p>Maaf, permintaan Anda tidak dapat diproses saat ini. Silakan periksa kembali data Anda atau coba beberapa saat lagi.</p>
        <a href="#" class="btn">Coba Lagi</a>
    </div>

</body>
</html>