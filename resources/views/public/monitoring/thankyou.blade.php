<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih — RSH Satu Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen">

    <div class="min-h-screen relative flex flex-col items-center justify-center px-6 py-12"
         style="background: url('{{ asset('assets/env/building.jpg') }}') center center / cover no-repeat;">

        <div class="absolute inset-0" style="background: rgba(13, 43, 30, 0.82);"></div>

        <div class="relative z-10 w-full max-w-sm sm:max-w-lg lg:max-w-2xl text-center">

            <img src="{{ asset('assets/logo/logo-rec-white.png') }}"
                 alt="RSH Satu Bumi"
                 class="h-12 w-auto object-contain mx-auto mb-10 opacity-90">

            <div class="text-6xl mb-6">🙏</div>

            <h1 class="text-3xl font-bold text-white mb-5 leading-tight">
                Terima kasih,<br>{{ $registration->full_name }}!
            </h1>

            {{-- Day number highlighted --}}
            <div class="inline-flex flex-col items-center bg-white/10 border border-white/20 rounded-2xl px-10 sm:px-20 lg:px-32 py-6 lg:py-10 mb-8 w-full">
                <p class="text-green-300 text-lg lg:text-2xl font-medium mb-2">Energy Level</p>
                <div class="flex items-baseline gap-3">
                    <span class="text-green-300 text-2xl lg:text-3xl font-semibold">Hari</span>
                    <span class="text-8xl lg:text-9xl font-extrabold text-white leading-none">{{ $token->day_number }}</span>
                    <span class="text-2xl lg:text-3xl text-green-300 font-medium">dari {{ config('monitoring.days', 7) }}</span>
                </div>
                <p class="text-green-200 text-lg lg:text-xl mt-2">berhasil disimpan ✓</p>
            </div>

            <p class="text-white/80 text-xl lg:text-2xl font-medium mb-10">
                Rahayu, Salam Sehat 🌿
            </p>

            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 px-12 py-4 lg:py-5 bg-white text-[#2d6a4f] font-bold rounded-full text-lg lg:text-xl hover:bg-green-50 transition-colors shadow-lg">
                ← Kembali ke Beranda
            </a>
        </div>

        <p class="relative z-10 mt-12 text-white/30 text-sm">
            Rumah Sehat Holistik Satu Bumi &copy; {{ date('Y') }}
        </p>

    </div>

</body>
</html>
