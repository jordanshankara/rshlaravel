<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sudah Diisi — RSH Satu Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-start justify-center px-4 py-10">
<div class="max-w-md w-full">

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 text-center mb-5">
        <div class="text-4xl mb-3">✅</div>
        <h1 class="text-base font-bold text-gray-900 mb-1">Sudah Diisi</h1>
        <p class="text-sm text-gray-500">
            Anda sudah mengisi monitoring hari ke-<strong>{{ $token->day_number }}</strong>
            pada {{ $token->completed_at->translatedFormat('d F Y, H:i') }}.
        </p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-3 mb-5">
        @php
            $emosiColor = $emosiLevel['color'] === 'green' ? 'bg-green-50 border-green-200 text-green-800'
                : ($emosiLevel['color'] === 'yellow' ? 'bg-yellow-50 border-yellow-200 text-yellow-800'
                : 'bg-red-50 border-red-200 text-red-800');
            $fisikColor = $fisikLevel['color'] === 'green' ? 'bg-green-50 border-green-200 text-green-800'
                : ($fisikLevel['color'] === 'yellow' ? 'bg-yellow-50 border-yellow-200 text-yellow-800'
                : 'bg-red-50 border-red-200 text-red-800');
        @endphp
        <div class="rounded-xl border p-4 text-center {{ $emosiColor }}">
            <p class="text-xs font-medium opacity-70 mb-1">Emosi & Pikiran</p>
            <p class="text-2xl font-bold">{{ $emosiScore }}<span class="text-sm font-normal">/16</span></p>
            <p class="text-xs font-semibold mt-1">{{ $emosiLevel['label'] }}</p>
        </div>
        <div class="rounded-xl border p-4 text-center {{ $fisikColor }}">
            <p class="text-xs font-medium opacity-70 mb-1">Kondisi Fisik</p>
            <p class="text-2xl font-bold">{{ $fisikScore }}<span class="text-sm font-normal">/16</span></p>
            <p class="text-xs font-semibold mt-1">{{ $fisikLevel['label'] }}</p>
        </div>
    </div>

    <p class="text-xs text-gray-400 text-center">
        Link ini hanya bisa digunakan sekali. Terima kasih! 🙏
    </p>

</div>
</body>
</html>
