<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih — RSH Satu Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen">

    {{-- Full-screen background --}}
    <div class="min-h-screen relative flex flex-col items-center justify-center px-5 py-12"
         style="background: url('{{ asset('assets/env/building.jpg') }}') center center / cover no-repeat;">

        {{-- Green overlay --}}
        <div class="absolute inset-0" style="background: rgba(13, 43, 30, 0.78);"></div>

        {{-- Content --}}
        <div class="relative z-10 max-w-sm w-full text-center">

            {{-- Logo --}}
            <img src="{{ asset('assets/logo/logo-rec-white.png') }}"
                 alt="RSH Satu Bumi"
                 class="h-10 w-auto object-contain mx-auto mb-10 opacity-90">

            {{-- Icon --}}
            <div class="text-5xl mb-6">🙏</div>

            {{-- Message --}}
            <h1 class="text-2xl font-bold text-white mb-3">
                Terima kasih, {{ $registration->full_name }}!
            </h1>
            <p class="text-green-200 text-sm leading-relaxed mb-2">
                Energy Level Hari ke-<strong class="text-white">{{ $token->day_number }}</strong>
                berhasil disimpan.
            </p>
            <p class="text-green-300/70 text-sm leading-relaxed mb-10">
                Semoga hari-hari Anda di program ini membawa kesehatan dan kedamaian. 🌿
            </p>

            {{-- Divider --}}
            <div class="border-t border-white/10 mb-10"></div>

            {{-- Back to Home --}}
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-[#2d6a4f] font-semibold rounded-full text-sm hover:bg-green-50 transition-colors shadow-lg">
                ← Kembali ke Beranda
            </a>
        </div>

        {{-- Bottom tagline --}}
        <p class="relative z-10 mt-12 text-white/30 text-xs">
            Rumah Sehat Holistik Satu Bumi &copy; {{ date('Y') }}
        </p>

    </div>

</body>
</html>
