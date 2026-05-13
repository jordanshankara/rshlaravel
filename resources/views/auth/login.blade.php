<!DOCTYPE html>
<html lang="id" class="h-full bg-emerald-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — RSH Satu Bumi Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex items-center justify-center">
<div class="w-full max-w-sm">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-emerald-800">RSH Satu Bumi</h1>
        <p class="text-sm text-gray-500 mt-1">Panel Administrasi</p>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-8">
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300 text-emerald-600">
                <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
            </div>
            <button type="submit"
                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition text-sm">
                Masuk
            </button>
        </form>
    </div>
</div>
</body>
</html>
