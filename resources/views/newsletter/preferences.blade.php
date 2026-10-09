@extends('layouts.app')

@section('title', 'Newsletter Preference Center — AQ NEWSWIRE')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white border border-stone-200 rounded p-8 shadow-xs">
        <span class="text-xs uppercase font-bold tracking-widest text-red-600">Executive Briefings</span>
        <h1 class="font-headline text-2xl font-bold text-stone-900 mt-1 mb-2">Manage Your Email Subscriptions</h1>
        <p class="text-xs text-stone-500 mb-6">Customize the market reports, intelligence memos, and sector analyses delivered to your inbox.</p>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded text-xs">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('newsletter.preferences.update') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Your Email Address</label>
                <input type="email" name="email" value="{{ old('email', $subscriber->email ?? $email) }}" required 
                       class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Editorial Topics of Interest</label>
                <div class="space-y-2 text-xs">
                    @php
                        $currentTopics = $subscriber ? explode(', ', $subscriber->topics_interest ?? '') : [];
                    @endphp
                    @foreach(['Artificial Intelligence & Deeptech', 'Global Macroeconomics & Central Banks', 'Venture Capital & Private Equity', 'Billionaires & Dynastic Wealth', 'Clean Energy Transition & Fusion', 'Executive Leadership Strategy'] as $topic)
                        <label class="flex items-center space-x-2 text-stone-800">
                            <input type="checkbox" name="topics[]" value="{{ $topic }}" 
                                   {{ in_array($topic, $currentTopics) ? 'checked' : '' }}
                                   class="rounded text-red-600 focus:ring-0">
                            <span>{{ $topic }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                <label class="flex items-center space-x-2 text-xs text-stone-700">
                    <input type="checkbox" name="is_active" value="1" {{ ($subscriber->is_active ?? true) ? 'checked' : '' }} class="rounded text-red-600">
                    <span>Keep subscription active</span>
                </label>
                <button type="submit" class="bg-stone-900 hover:bg-stone-800 text-white px-6 py-2.5 rounded text-xs font-bold uppercase tracking-wider transition">
                    Save Preferences
                </button>
            </div>
        </form>

        <div class="mt-8 pt-4 border-t border-stone-100 text-center">
            <form action="{{ route('newsletter.unsubscribe') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $subscriber->email ?? $email }}">
                <button type="submit" class="text-xs text-stone-400 hover:text-rose-600 underline transition">
                    Unsubscribe from all AQ NEWSWIRE newsletters
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
