<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editorial Sign In — AQ NEWSWIRE CMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-950 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-lg shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-[#0B0F17] text-white p-8 text-center border-b-4 border-[#635BFF] flex flex-col items-center">
            <div class="bg-white px-5 py-2.5 rounded-xl shadow-lg border border-slate-700/50 mb-3 inline-flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="AQ NEWSWIRE" class="h-9 sm:h-10 w-auto max-w-[220px] object-contain">
            </div>
            <h1 class="sr-only">AQ NEWSWIRE</h1>
            <p class="text-[11px] uppercase font-bold tracking-[0.25em] text-slate-300">Editorial & Publishing CMS</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="p-8 space-y-5">
            @csrf

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3 rounded text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Editorial Email</label>
                <input type="email" name="email" value="{{ old('email', 'admin@aqnewswire.com') }}" required 
                       class="w-full px-3 py-2.5 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900 transition">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-bold uppercase tracking-wider text-stone-700">Password</label>
                </div>
                <input type="password" name="password" value="password123" required 
                       class="w-full px-3 py-2.5 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900 transition">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-red-600 border-stone-300 rounded">
                <label for="remember" class="ml-2 text-xs text-stone-600">Remember credentials on this workstation</label>
            </div>

            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-widest py-3 rounded transition">
                Sign In To CMS
            </button>

            <div class="bg-stone-50 border border-stone-200 p-3 rounded text-[11px] text-stone-600">
                <p class="font-bold text-stone-800 mb-1">Default Pre-Configured Credentials:</p>
                <p>Email: <span class="font-mono font-semibold text-stone-900">admin@aqnewswire.com</span></p>
                <p>Password: <span class="font-mono font-semibold text-stone-900">password123</span></p>
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('home') }}" class="text-xs text-stone-500 hover:text-stone-900 transition">
                    &larr; Return to public publication
                </a>
            </div>
        </form>
    </div>

</body>
</html>
