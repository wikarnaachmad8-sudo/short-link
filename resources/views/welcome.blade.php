<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShortLink - Sistem Pemendek URL. Buat short link dengan mudah dan cepat.">
    <title>ShortLink - Sistem Pemendek URL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6rem 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }
        .hero p.lead {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        .btn-hero {
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            margin: 0.25rem;
            transition: all 0.3s ease;
        }
        .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .btn-hero-primary {
            background: white;
            color: #667eea;
            border: 2px solid white;
        }
        .btn-hero-primary:hover {
            background: #f0f0f0;
            color: #5a6fd6;
        }
        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid rgba(255,255,255,0.7);
        }
        .btn-hero-outline:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            border-color: white;
        }
        .feature-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            margin-top: 3rem;
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .feature-card h5 {
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        .feature-card p {
            opacity: 0.85;
            margin: 0;
        }
    </style>
</head>
<body>
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1><i class="bi bi-link-45deg"></i> ShortLink</h1>
                    <p class="lead">
                        Pemendek URL yang simpel, cepat, dan mudah digunakan.
                        Buat short link, pantau jumlah klik, dan kelola semua link Anda di satu tempat.
                    </p>
                    <div>
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-hero btn-hero-primary">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-hero btn-hero-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Masuk / Login
                            </a>
                        @endauth
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="feature-card text-center">
                                <div class="feature-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                                <h5>Cepat & Mudah</h5>
                                <p>Buat short link dalam hitungan detik. Tidak perlu pengaturan rumit.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-card text-center">
                                <div class="feature-icon"><i class="bi bi-bar-chart-fill"></i></div>
                                <h5>Statistik Klik</h5>
                                <p>Pantau berapa kali link Anda diklik secara real-time.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-card text-center">
                                <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                                <h5>Aman & Terpercaya</h5>
                                <p>Link Anda aman dengan validasi URL dan proteksi keamanan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
