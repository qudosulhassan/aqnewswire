@extends('layouts.app')

@section('title', $list->title . ' — The AQ NEWSWIRE Lists')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Banner -->
    <div class="bg-stone-950 text-white rounded p-8 sm:p-12 mb-8 relative overflow-hidden">
        <div class="relative z-10 max-w-3xl">
            <span class="inline-block bg-red-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 mb-4 rounded">
                The {{ $list->year }} Official Index
            </span>
            <h1 class="font-headline text-3xl sm:text-5xl font-black mb-4 leading-tight">
                {{ $list->title }}
            </h1>
            <p class="text-stone-300 text-sm sm:text-base leading-relaxed">
                {{ $list->description }}
            </p>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white border border-stone-200 rounded p-4 mb-8 shadow-xs">
        <form method="GET" action="{{ route('rankings.show', $list->slug) }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Search -->
            <div class="sm:col-span-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, company..."
                       class="w-full bg-stone-50 border border-stone-200 rounded px-3 py-2 text-xs focus:outline-none focus:border-stone-900 transition">
            </div>

            <!-- Industry Filter -->
            <div class="sm:col-span-3">
                <select name="industry" class="w-full bg-stone-50 border border-stone-200 rounded px-3 py-2 text-xs focus:outline-none focus:border-stone-900 transition">
                    <option value="">All Industries</option>
                    @foreach($industries as $ind)
                        <option value="{{ $ind }}" {{ request('industry') === $ind ? 'selected' : '' }}>{{ $ind }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Country Filter -->
            <div class="sm:col-span-2">
                <select name="country" class="w-full bg-stone-50 border border-stone-200 rounded px-3 py-2 text-xs focus:outline-none focus:border-stone-900 transition">
                    <option value="">All Countries</option>
                    @foreach($countries as $cnt)
                        <option value="{{ $cnt }}" {{ request('country') === $cnt ? 'selected' : '' }}>{{ $cnt }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <div class="sm:col-span-2 flex space-x-2">
                <button type="submit" class="flex-1 bg-stone-900 hover:bg-stone-800 text-white font-bold text-xs uppercase tracking-wider py-2 rounded transition">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'industry', 'country']))
                    <a href="{{ route('rankings.show', $list->slug) }}" class="px-3 py-2 border border-stone-300 text-stone-600 hover:bg-stone-100 rounded text-xs">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Leaderboard Table / Cards -->
    <div class="space-y-4">
        @forelse($items as $item)
            <div class="bg-white border border-stone-200 rounded p-5 sm:p-6 shadow-xs hover:border-red-600 transition flex flex-col md:flex-row md:items-center justify-between gap-6 group">
                <!-- Rank & Profile Info -->
                <div class="flex items-start sm:items-center space-x-5 flex-1">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-stone-900 group-hover:bg-red-600 transition text-white font-black text-xl flex items-center justify-center rounded shrink-0">
                        #{{ $item->rank }}
                    </div>

                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full overflow-hidden border-2 border-stone-200 shrink-0">
                        <img src="{{ $item->photo_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80' }}" 
                             alt="{{ $item->name }}" class="w-full h-full object-cover">
                    </div>

                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="font-headline text-lg sm:text-xl font-bold text-stone-900 group-hover:text-red-700 transition">
                                {{ $item->name }}
                            </h3>
                            @if($item->country)
                                <span class="text-xs text-stone-400">({{ $item->country }})</span>
                            @endif
                        </div>
                        <p class="text-xs font-semibold text-stone-600 mt-0.5">
                            {{ $item->title_or_role }} • <span class="text-stone-900 font-bold">{{ $item->company }}</span>
                        </p>
                        @if($item->bio)
                            <p class="text-xs text-stone-500 mt-2 max-w-xl leading-relaxed">
                                {{ $item->bio }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Metric / Net Worth -->
                <div class="flex flex-col sm:items-end justify-center border-t md:border-t-0 pt-3 md:pt-0 border-stone-100">
                    <span class="text-[10px] uppercase font-bold tracking-widest text-stone-400">Net Worth / Metric</span>
                    <span class="font-sans text-xl sm:text-2xl font-black text-emerald-600">
                        {{ $item->net_worth_or_metric ?? 'N/A' }}
                    </span>
                    @if($item->industry)
                        <span class="text-[11px] font-semibold text-stone-500 mt-0.5">
                            {{ $item->industry }}
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white border border-stone-200 rounded">
                <p class="text-stone-500 text-sm">No ranking profiles match your filter criteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $items->links() }}
    </div>

</div>
@endsection
