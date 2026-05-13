<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — RSH Satu Bumi Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full min-h-screen flex items-center justify-center bg-gradient-to-br from-green-950 to-green-800">
<div class="w-full max-w-sm px-4">
    <div class="text-center mb-8">
        <img src="{{ asset('assets/logo/logo-rec-white.png') }}" alt="RSH Satu Bumi" class="h-12 w-auto object-contain mx-auto mb-4">
        <p class="text-sm text-green-200/70">Panel Administrasi</p>
    </div>
    <div class="glass-card rounded-2xl p-8">
        @if($errors->any())
            <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                       class="input">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" required class="input">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="remember" name="remember"
                       class="rounded border-gray-300 text-[#2d6a4f] focus:ring-[#2d6a4f]">
                <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
            </div>
            <button type="submit"
                    class="w-full py-3 bg-[#2d6a4f] hover:bg-[#1a5a3f] text-white font-semibold rounded-xl transition text-sm">
                Masuk
            </button>
        </form>
    </div>
    <p class="text-center text-xs text-green-200/40 mt-6">RSH Satu Bumi &copy; {{ date('Y') }}</p>
</div>
</body>
</html>
