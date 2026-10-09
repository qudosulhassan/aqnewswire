@extends('layouts.app')

@section('title', 'Create Executive Reader Account — AQ NEWSWIRE')

@section('content')
<div class="max-w-md mx-auto my-14 px-4">
    <div class="bg-white border border-stone-200 rounded p-8 shadow-xs">
        <div class="text-center mb-6">
            <span class="text-xs uppercase font-extrabold tracking-[0.25em] text-red-600 block mb-1">AQ NEWSWIRE Membership</span>
            <h1 class="font-headline text-3xl font-black text-stone-950">Create Your Account</h1>
            <p class="text-xs text-stone-500 mt-1">Unlock saved articles, reading history, and personalized market intelligence.</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3 rounded text-xs mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900 transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900 transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Password</label>
                <input type="password" name="password" required 
                       class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900 transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required 
                       class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900 transition">
            </div>

            <button type="submit" class="w-full bg-stone-900 hover:bg-black text-white font-bold text-xs uppercase tracking-widest py-3 rounded transition mt-2">
                Join AQ NEWSWIRE Free
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-stone-100 text-xs text-stone-500">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-stone-900 hover:underline">Sign In &rarr;</a>
        </div>
    </div>
</div>
@endsection
