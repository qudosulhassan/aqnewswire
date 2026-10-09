@extends('layouts.app')

@section('title', 'Sign In — AQ NEWSWIRE')

@section('content')
<div class="max-w-md mx-auto my-14 px-4">
    <div class="bg-white border border-stone-200 rounded p-8 shadow-xs">
        <div class="text-center mb-6">
            <span class="text-xs uppercase font-extrabold tracking-[0.25em] text-red-600 block mb-1">Executive Access</span>
            <h1 class="font-headline text-3xl font-black text-stone-950">Sign In to AQ NEWSWIRE</h1>
            <p class="text-xs text-stone-500 mt-1">Access your saved reading list, following channels, and personalized alerts.</p>
        </div>

        @if(session('info'))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 p-3 rounded text-xs mb-4">
                {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3 rounded text-xs mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900 transition">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-bold uppercase tracking-wider text-stone-700">Password</label>
                </div>
                <input type="password" name="password" required 
                       class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900 transition">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-stone-900 border-stone-300 rounded">
                <label for="remember" class="ml-2 text-xs text-stone-600">Keep me signed in</label>
            </div>

            <button type="submit" class="w-full bg-stone-900 hover:bg-black text-white font-bold text-xs uppercase tracking-widest py-3 rounded transition">
                Sign In
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-stone-100 text-xs text-stone-500">
            Don't have an account yet? 
            <a href="{{ route('register') }}" class="font-bold text-stone-900 hover:underline">Register Free &rarr;</a>
        </div>
    </div>
</div>
@endsection
