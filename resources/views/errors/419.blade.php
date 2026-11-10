<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="2; url={{ route('login') }}">
    <title>Sesi Berakhir - Suratku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: #F4F6F9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #fff;
            border: 2px solid #002244;
            border-radius: 20px;
            box-shadow: 5px 5px 0 #002244;
            padding: 36px 32px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .icon {
            font-size: 40px;
            margin-bottom: 16px;
        }
        h1 {
            font-size: 20px;
            font-weight: 800;
            color: #002244;
            margin-bottom: 8px;
        }
        p {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 24px;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            background: #003366;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            padding: 12px 24px;
            border-radius: 10px;
            border: 2px solid #002244;
            box-shadow: 3px 3px 0 #002244;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">⏱️</div>
        <h1>Sesi Berakhir</h1>
        <p>Sesi login Anda telah berakhir demi keamanan. Mengalihkan ke halaman login...</p>
        <a href="{{ route('login') }}" class="btn">Ke Halaman Login</a>
    </div>
</body>
</html>

