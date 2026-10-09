@extends('layouts.app')

@section('title', 'The AQ NEWSWIRE Lists — Global Rankings & Indexes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Masthead Header -->
    <div class="text-center max-w-3xl mx-auto mb-12">
        <span class="text-xs font-bold uppercase tracking-[0.25em] text-red-600 block mb-2">Exclusive Editorial Indexes</span>
        <h1 class="font-headline text-4xl sm:text-5xl font-black text-stone-950 mb-4">The AQ NEWSWIRE Lists</h1>
        <p class="text-stone-600 text-sm sm:text-base leading-relaxed">
            The world's most trusted proprietary rankings of wealth, technological influence, leadership excellence, and high-growth enterprise ventures.
        </p>
    </div>

    <!-- Lists Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($lists as $list)
            <div class="bg-white border border-stone-200 rounded overflow-hidden shadow-xs hover:shadow-md transition group flex flex-col justify-between">
                <div>
                    <div class="aspect-16/10 bg-stone-900 overflow-hidden relative">
                        <img src="{{ $list->cover_image ?? 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80' }}" 
                             alt="{{ $list->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                        <div class="absolute top-3 left-3">
                            <span class="bg-red-600 text-white text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded">
                                {{ $list->year }} Edition
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="font-headline text-2xl font-bold text-stone-900 group-hover:text-red-700 transition mb-2">
                            <a href="{{ route('rankings.show', $list->slug) }}">
                                {{ $list->title }}
                            </a>
                        </h2>
                        <p class="text-xs text-stone-600 leading-relaxed mb-4">
                            {{ $list->subtitle ?? $list->description }}
                        </p>
                    </div>
                </div>

                <div class="px-6 pb-6 pt-2 border-t border-stone-100 flex items-center justify-between">
                    <span class="text-xs text-stone-500 font-semibold">{{ $list->items_count }} Profiled Leaders</span>
                    <a href="{{ route('rankings.show', $list->slug) }}" class="text-xs font-bold uppercase tracking-wider text-red-600 hover:text-red-800 transition">
                        Explore Ranking &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-10">
        {{ $lists->links() }}
    </div>

</div>
@endsection
