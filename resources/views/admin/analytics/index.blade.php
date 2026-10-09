@extends('layouts.admin')

@section('title', 'Platform Audience & Traffic Analytics')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Platform Audience & Traffic Analytics</h1>
            <p class="text-xs text-slate-500 mt-0.5">Real-time readership metrics, channel distribution, and content engagement velocity.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-medium text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-xl shadow-2xs">
                Real-Time Data Engine
            </span>
        </div>
    </div>

    <!-- 4 KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Total Pageviews</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-black text-slate-900">{{ number_format($totalViews) }}</span>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">All-Time Live</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Unique Visitors (Est.)</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-black text-blue-600">{{ number_format($uniqueVisitorsEstimate) }}</span>
                <span class="text-[11px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">~39% Reach</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Indexed Editorial Stories</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-black text-slate-900">{{ number_format($totalArticles) }}</span>
                <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">Active Catalog</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Reader Comments</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-black text-purple-600">{{ number_format($totalComments) }}</span>
                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">Discourse</span>
            </div>
        </div>
    </div>

    <!-- Middle: Channel Distribution & Top Performing Stories -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Channel Traffic Breakdown (5 Cols) -->
        <div class="lg:col-span-5 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100">Readership by Channel</h3>
                <div class="mt-4 space-y-3">
                    @foreach($channelTraffic as $cat)
                        @php
                            $percentage = $totalViews > 0 ? round(($cat['views'] / $totalViews) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-semibold text-slate-800">{{ $cat['name'] }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-slate-600">{{ number_format($cat['views']) }} views</span>
                                    <span class="font-bold text-slate-400 text-[11px]">{{ $percentage }}%</span>
                                </div>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500" 
                                     style="width: {{ max(4, $percentage) }}%; background-color: {{ $cat['color'] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Performing Stories (7 Cols) -->
        <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
            <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100">Top Content by Total Impressions</h3>
            <div class="overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-2.5">Story</th>
                            <th class="py-2.5 px-2">Channel</th>
                            <th class="py-2.5 px-2">Author</th>
                            <th class="py-2.5 text-right">Views</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($topArticles as $art)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 pr-2">
                                    <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="font-semibold text-slate-800 hover:text-red-600 line-clamp-1 block">
                                        {{ $art->title }}
                                    </a>
                                </td>
                                <td class="py-3 px-2 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" style="background-color: {{ $art->category->color ?? '#6366F1' }}15; color: {{ $art->category->color ?? '#6366F1' }}">
                                        {{ $art->category->name ?? 'General' }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-slate-500 whitespace-nowrap">
                                    {{ $art->author->name ?? 'Staff' }}
                                </td>
                                <td class="py-3 text-right font-mono font-bold text-slate-800 whitespace-nowrap">
                                    {{ number_format($art->view_count) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Analytics Events Log -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
        <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100">Recent Tracked Reader Events</h3>
        <div class="overflow-x-auto mt-2">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-2.5 px-3">Event Type</th>
                        <th class="py-2.5 px-3">Referenced Story</th>
                        <th class="py-2.5 px-3">Reader</th>
                        <th class="py-2.5 px-3 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentEvents as $event)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                    {{ $event->event_type }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-slate-800">
                                {{ $event->article ? $event->article->title : ($event->page_url ?? 'N/A') }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-500 whitespace-nowrap">
                                {{ $event->user ? $event->user->name : 'Anonymous Reader' }}
                            </td>
                            <td class="py-2.5 px-3 text-right text-slate-400 whitespace-nowrap">
                                {{ $event->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-xs">
                                No raw analytics events recorded yet. Views increment via story visits.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
