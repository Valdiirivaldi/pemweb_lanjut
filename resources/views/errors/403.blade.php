<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Forbidden - Eduria</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #4e73df 100%);
            padding: 20px;
        }
        .error-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 480px;
            width: 100%;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 800;
            color: #e53e3e;
            line-height: 1;
        }
        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e3c72;
            margin-top: 8px;
        }
        .error-message {
            color: #718096;
            font-size: 0.95rem;
            margin-top: 12px;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            padding: 12px 28px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-home:hover { box-shadow: 0 8px 30px rgba(78, 115, 223, 0.4); color: #fff; }
        .brand { font-size: 1.2rem; font-weight: 800; color: #1e3c72; margin-bottom: 24px; }
        .brand i { margin-right: 8px; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="brand"><i class="fas fa-graduation-cap"></i>Eduria</div>
        <div class="error-code">403</div>
        <div class="error-title">Akses Ditolak</div>
        <div class="error-message">Anda tidak memiliki izin untuk mengakses halaman ini.</div>
        <a href="{{ route('home') }}" class="btn-home"><i class="fas fa-home"></i>Kembali ke Beranda</a>
    </div>
</body>
</html>