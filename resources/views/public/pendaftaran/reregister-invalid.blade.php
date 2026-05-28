<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Tidak Valid — RSH Satu Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
<div class="max-w-sm w-full bg-white rounded-2xl border shadow-sm p-8 text-center">
    <div class="text-4xl mb-3">🔒</div>
    <h1 class="text-base font-bold text-gray-900 mb-2">Link Tidak Valid</h1>
    @if ($reason === 'used')
    <p class="text-sm text-gray-500 mb-5">Link ini sudah digunakan untuk mendaftar sebelumnya.</p>
    @else
    <p class="text-sm text-gray-500 mb-5">Link ini sudah kedaluwarsa. Minta link baru dari admin RSH Satu Bumi.</p>
    @endif
    <a href="{{ route('home') }}" class="inline-block px-5 py-2.5 bg-[#2d6a4f] text-white rounded-xl text-sm font-medium hover:bg-[#1a5a3f] transition-colors">
        Kembali ke Beranda
    </a>
</div>
</body>
</html>
