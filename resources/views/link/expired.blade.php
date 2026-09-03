<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Expired - ShortLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .expired-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
        }
        .expired-icon {
            font-size: 5rem;
            color: #f5576c;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="expired-card">
        <div class="expired-icon">
            <i class="bi bi-clock-history"></i>
        </div>
        <h2 class="fw-bold mb-3">Link Expired</h2>
        <p class="text-muted mb-4">
            Maaf, short link ini sudah melewati batas waktu dan tidak dapat diakses lagi.
        </p>
        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-house"></i> Kembali ke Beranda
        </a>
    </div>
</body>
</html>
