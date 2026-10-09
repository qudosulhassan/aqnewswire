@extends('layouts.app')

@section('title', 'Unsubscribed — AQ NEWSWIRE')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <div class="bg-white border border-stone-200 rounded p-8 shadow-xs space-y-4">
        <span class="text-4xl block">✉️</span>
        <h1 class="font-headline text-2xl font-bold text-stone-900">You Have Been Unsubscribed</h1>
        <p class="text-xs text-stone-600">
            {{ $email ? "The address {$email} has been removed from all active email dispatches." : 'You have been successfully removed from our distribution lists.' }}
        </p>
        <div class="pt-4">
            <a href="{{ route('home') }}" class="inline-block bg-stone-900 text-white px-6 py-2 rounded text-xs font-bold uppercase tracking-wider hover:bg-stone-800 transition">
                Return to Homepage
            </a>
        </div>
    </div>
</div>
@endsection
