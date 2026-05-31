<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih — RSH Satu Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-start justify-center px-4 py-10">
<div class="max-w-md w-full">

    {{-- Success header --}}
    <div class="bg-[#2d6a4f] rounded-2xl p-6 text-white text-center mb-5">
        <div class="text-4xl mb-2">🙏</div>
        <h1 class="text-lg font-bold">Terima kasih, {{ $registration->full_name }}!</h1>
        <p class="text-green-200 text-sm mt-1">
            Energy Level Hari ke-{{ $token->day_number }} berhasil disimpan.
        </p>
    </div>

    {{-- Score cards --}}
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
            <p class="text-2xl font-bold">{{ $emosiScore }}<span class="text-sm font-normal">/{{ $maxScore }}</span></p>
            <p class="text-xs font-semibold mt-1">{{ $emosiLevel['label'] }}</p>
        </div>
        <div class="rounded-xl border p-4 text-center {{ $fisikColor }}">
            <p class="text-xs font-medium opacity-70 mb-1">Kondisi Fisik</p>
            <p class="text-2xl font-bold">{{ $fisikScore }}<span class="text-sm font-normal">/{{ $maxScore }}</span></p>
            <p class="text-xs font-semibold mt-1">{{ $fisikLevel['label'] }}</p>
        </div>
    </div>

    {{-- Message based on worst level --}}
    @php
        $worst = ($emosiLevel['color'] === 'red' || $fisikLevel['color'] === 'red') ? 'red'
            : (($emosiLevel['color'] === 'yellow' || $fisikLevel['color'] === 'yellow') ? 'yellow' : 'green');
    @endphp
    <div class="bg-white rounded-xl border border-gray-200 p-4 text-sm text-gray-700 mb-5">
        @if($worst === 'green')
        <p>✨ Kondisi Anda sangat baik hari ini! Teruskan semangat dan jagalah kebiasaan positif selama program berlangsung.</p>
        @elseif($worst === 'yellow')
        <p>💛 Perlu sedikit perhatian. Luangkan waktu untuk latihan <strong>Nafas Perut</strong> atau sesi <strong>Reiki</strong> untuk menyeimbangkan kondisi Anda.</p>
        @else
        <p>❤️ Tim fasilitator akan menghubungi Anda untuk <strong>sesi pendampingan khusus</strong>. Anda tidak sendirian — kami ada untuk mendukung Anda. 🙏</p>
        @endif
    </div>

    {{-- Program info --}}
    @if($registration->programPeriod)
    <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 text-xs text-gray-500 text-center">
        {{ $registration->programPeriod->name }} &middot;
        {{ $registration->programPeriod->start_date->format('d M') }} –
        {{ $registration->programPeriod->end_date->format('d M Y') }}
    </div>
    @endif

</div>
</body>
</html>
