<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Halaman Tidak Ditemukan</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            padding: 48px 40px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,.08), 0 8px 24px rgba(0,0,0,.06);
        }
        .code {
            font-size: 3.5rem;
            font-weight: 800;
            color: #2d6a4f;
            letter-spacing: -1px;
            line-height: 1;
            margin-bottom: 8px;
        }
        h1 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
        }
        .quote {
            border-left: 3px solid #2d6a4f;
            margin: 0 auto 28px;
            padding: 12px 16px;
            text-align: left;
            max-width: 360px;
        }
        .quote p {
            font-size: 0.88rem;
            color: #374151;
            font-style: italic;
            line-height: 1.6;
            margin-bottom: 6px;
        }
        .quote cite {
            font-size: 0.78rem;
            color: #2d6a4f;
            font-style: normal;
            font-weight: 600;
        }
        .countdown {
            font-size: 0.82rem;
            color: #9ca3af;
            margin-bottom: 20px;
        }
        .countdown span {
            font-weight: 700;
            color: #6b7280;
        }
        .btn {
            display: inline-block;
            padding: 10px 24px;
            background: #2d6a4f;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            transition: background 0.15s;
        }
        .btn:hover { background: #1a5a3f; }
    </style>
</head>
<body>
    <div class="card">
        <div class="code">404</div>
        <h1>Halaman Tidak Ditemukan</h1>

        <div class="quote">
            <p>"Serve the Almighty by Serving Humanity and Society"</p>
            <cite>— Anand Krishna</cite>
        </div>

        <p class="countdown">Kembali ke beranda dalam <span id="count">5</span> detik…</p>
        <a href="/" class="btn">Kembali ke Beranda</a>
    </div>

    <script>
        let n = 5;
        const el = document.getElementById('count');
        const t = setInterval(function () {
            el.textContent = --n;
            if (n <= 0) { clearInterval(t); location.href = '/'; }
        }, 1000);
    </script>
</body>
</html>
