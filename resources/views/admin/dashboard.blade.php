@extends('layouts.admin')

@section('title', 'Editorial Operations Dashboard')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Top Greeting & Header Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span>Welcome back, {{ explode(' ', Auth::user()->name ?? 'Alexander Vance')[0] }}</span>
                <span class="inline-block animate-wave text-xl">👋</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Here's what's happening with AQ NEWSWIRE today.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Current Date Display -->
            <div class="hidden sm:flex items-center gap-2 px-3.5 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-200 rounded-xl shadow-2xs">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ now()->format('l, M j, Y') }}</span>
            </div>

            <!-- New Article CTA Split Button -->
            <div class="relative flex items-center shadow-xs">
                <a href="{{ route('admin.articles.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>New Article</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Operations & Maintenance Quick Control Bar -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Live System Operations</span>
            <span class="text-slate-300">|</span>
            <span class="text-xs text-slate-500 font-medium">1-Click Actions & Newsroom Maintenance</span>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Clear Cache -->
            <form action="{{ route('admin.quick.clearCache') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 text-xs font-semibold rounded-xl border border-slate-200 transition shadow-2xs cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Clear Caches</span>
                </button>
            </form>

            <!-- Recalculate Trending Scores -->
            <form action="{{ route('admin.quick.recalculateTrending') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 hover:text-blue-900 text-xs font-semibold rounded-xl border border-blue-200 transition shadow-2xs cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <span>Recalculate Trending</span>
                </button>
            </form>

            <!-- Export Subscribers -->
            <a href="{{ route('admin.newsletters.export') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 hover:text-emerald-900 text-xs font-semibold rounded-xl border border-emerald-200 transition shadow-2xs">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Export Subscribers (CSV)</span>
            </a>

            <!-- Media Library -->
            <a href="{{ route('admin.media.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 text-xs font-semibold rounded-xl border border-slate-200 transition shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Media Assets</span>
            </a>
        </div>
    </div>


    <!-- 5 KPI Stat Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Card 1: Total Articles -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between mb-2.5">
                <div class="w-8 h-8 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                @if(str_contains($stats['total_articles_trend'], '%'))
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        {{ $stats['total_articles_trend'] }}
                    </span>
                @else
                    <span class="inline-flex items-center text-[10px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                        {{ $stats['total_articles_trend'] }}
                    </span>
                @endif
            </div>
            <p class="text-xs font-semibold text-slate-500">Total Articles</p>
            <div class="flex items-baseline justify-between mt-1">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_articles']) }}</h3>
                <!-- Red Sparkline -->
                <svg class="w-16 h-7 text-red-500 overflow-visible" viewBox="0 0 70 28" fill="none">
                    <path d="M 0 24 Q 15 22, 25 18 T 45 12 T 70 4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        <!-- Card 2: Published Articles -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between mb-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if(str_contains($stats['published_articles_trend'], '%'))
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        {{ $stats['published_articles_trend'] }}
                    </span>
                @else
                    <span class="inline-flex items-center text-[10px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                        {{ $stats['published_articles_trend'] }}
                    </span>
                @endif
            </div>
            <p class="text-xs font-semibold text-slate-500">Published</p>
            <div class="flex items-baseline justify-between mt-1">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['published_articles']) }}</h3>
                <!-- Green Sparkline -->
                <svg class="w-16 h-7 text-emerald-500 overflow-visible" viewBox="0 0 70 28" fill="none">
                    <path d="M 0 25 Q 15 24, 25 20 T 45 14 T 70 5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        <!-- Card 3: Pageviews -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between mb-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                @if(str_contains($stats['views_trend'], '%'))
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        {{ $stats['views_trend'] }}
                    </span>
                @else
                    <span class="inline-flex items-center text-[10px] font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">
                        {{ $stats['views_trend'] }}
                    </span>
                @endif
            </div>
            <p class="text-xs font-semibold text-slate-500">Pageviews</p>
            <div class="flex items-baseline justify-between mt-1">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_views']) }}</h3>
                <!-- Blue Sparkline -->
                <svg class="w-16 h-7 text-blue-500 overflow-visible" viewBox="0 0 70 28" fill="none">
                    <path d="M 0 24 Q 15 20, 25 18 T 45 10 T 70 3" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        <!-- Card 4: Newsletter Subscribers -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between mb-2.5">
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                @if(str_contains($stats['subscribers_trend'], '%'))
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        {{ $stats['subscribers_trend'] }}
                    </span>
                @else
                    <span class="inline-flex items-center text-[10px] font-medium text-orange-700 bg-orange-50 px-2 py-0.5 rounded-full">
                        {{ $stats['subscribers_trend'] }}
                    </span>
                @endif
            </div>
            <p class="text-xs font-semibold text-slate-500">Newsletter Subscribers</p>
            <div class="flex items-baseline justify-between mt-1">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_subscribers']) }}</h3>
                <!-- Orange Sparkline -->
                <svg class="w-16 h-7 text-orange-500 overflow-visible" viewBox="0 0 70 28" fill="none">
                    <path d="M 0 26 Q 15 25, 25 21 T 45 13 T 70 4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        <!-- Card 5: Active Contributors -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs hover:shadow-xs transition">
            <div class="flex items-center justify-between mb-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                @if(str_contains($stats['contributors_trend'], '%'))
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        {{ $stats['contributors_trend'] }}
                    </span>
                @else
                    <span class="inline-flex items-center text-[10px] font-medium text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">
                        {{ $stats['contributors_trend'] }}
                    </span>
                @endif
            </div>
            <p class="text-xs font-semibold text-slate-500">Active Contributors</p>
            <div class="flex items-baseline justify-between mt-1">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($stats['active_contributors']) }}</h3>
                <!-- Purple Sparkline -->
                <svg class="w-16 h-7 text-purple-500 overflow-visible" viewBox="0 0 70 28" fill="none">
                    <path d="M 0 25 Q 15 22, 25 20 T 45 15 T 70 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

    </div>

    <!-- Middle Row: Website Traffic, Content Pipeline, Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Website Traffic Large Chart Card (6 or 7 cols) -->
        <div class="lg:col-span-6 xl:col-span-6 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs"
             x-data="{ timeRange: '7d' }">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Website Traffic</h3>
                    <div class="flex items-center gap-4 mt-2">
                        <!-- Pageviews Legend -->
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                            <span class="text-xs text-slate-500">Pageviews:</span>
                            <span class="text-xs font-bold text-slate-800">{{ number_format($traffic['pageviews_total']) }}</span>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded">{{ $traffic['pageviews_trend'] }}</span>
                        </div>
                        <!-- Unique Visitors Legend -->
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                            <span class="text-xs text-slate-500">Unique Visitors:</span>
                            <span class="text-xs font-bold text-slate-800">{{ number_format($traffic['unique_visitors_total']) }}</span>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded">{{ $traffic['unique_visitors_trend'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Time Filter Pills -->
                <div class="flex items-center bg-slate-50 p-0.5 rounded-lg border border-slate-200 text-[11px] font-semibold text-slate-600">
                    <button @click="timeRange = '7d'" :class="timeRange === '7d' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-md transition">7 Days</button>
                    <button @click="timeRange = '30d'" :class="timeRange === '30d' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-md transition">30 Days</button>
                    <button @click="timeRange = '90d'" :class="timeRange === '90d' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-md transition">90 Days</button>
                    <button @click="timeRange = '1y'" :class="timeRange === '1y' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-md transition">1 Year</button>
                </div>
            </div>

            <!-- Dual Line Chart Canvas/SVG -->
            <div class="mt-4 relative h-56 w-full">
                <svg class="w-full h-full overflow-visible" viewBox="0 0 600 200" preserveAspectRatio="none">
                    <!-- Horizontal Grid lines -->
                    <line x1="40" y1="20" x2="590" y2="20" stroke="#F1F5F9" stroke-width="1"/>
                    <line x1="40" y1="65" x2="590" y2="65" stroke="#F1F5F9" stroke-width="1"/>
                    <line x1="40" y1="110" x2="590" y2="110" stroke="#F1F5F9" stroke-width="1"/>
                    <line x1="40" y1="155" x2="590" y2="155" stroke="#F1F5F9" stroke-width="1"/>
                    <line x1="40" y1="190" x2="590" y2="190" stroke="#E2E8F0" stroke-width="1"/>

                    <!-- Y-Axis labels -->
                    <text x="32" y="24" text-anchor="end" font-size="10" fill="#94A3B8" font-family="sans-serif">40K</text>
                    <text x="32" y="69" text-anchor="end" font-size="10" fill="#94A3B8" font-family="sans-serif">30K</text>
                    <text x="32" y="114" text-anchor="end" font-size="10" fill="#94A3B8" font-family="sans-serif">20K</text>
                    <text x="32" y="159" text-anchor="end" font-size="10" fill="#94A3B8" font-family="sans-serif">10K</text>
                    <text x="32" y="193" text-anchor="end" font-size="10" fill="#94A3B8" font-family="sans-serif">0</text>

                    <!-- Blue Unique Visitors Area & Line -->
                    <path d="M 50 160 Q 120 150, 190 140 T 330 130 T 470 120 T 580 125" 
                          fill="none" stroke="#3B82F6" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="50" cy="160" r="3.5" fill="#3B82F6" class="hover:r-5 transition-all"/>
                    <circle cx="190" cy="140" r="3.5" fill="#3B82F6"/>
                    <circle cx="330" cy="130" r="3.5" fill="#3B82F6"/>
                    <circle cx="470" cy="120" r="3.5" fill="#3B82F6"/>
                    <circle cx="580" cy="125" r="3.5" fill="#3B82F6"/>

                    <!-- Red Pageviews Area & Line -->
                    <path d="M 50 145 Q 120 135, 190 125 T 330 100 T 470 70 T 580 65" 
                          fill="none" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="50" cy="145" r="4" fill="#EF4444"/>
                    <circle cx="190" cy="125" r="4" fill="#EF4444"/>
                    <circle cx="330" cy="100" r="4" fill="#EF4444"/>
                    <circle cx="470" cy="70" r="4" fill="#EF4444"/>
                    <circle cx="580" cy="65" r="4" fill="#EF4444"/>
                </svg>

                <!-- X-Axis Labels -->
                <div class="flex justify-between pl-10 pr-2 pt-1 text-[10px] text-slate-400">
                    @foreach($traffic['labels'] as $label)
                        <span>{{ $label }}</span>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Content Pipeline Card (3 cols) -->
        <div class="lg:col-span-3 xl:col-span-3 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Content Pipeline</h3>
                    <a href="{{ route('admin.articles.index') }}" class="text-xs text-red-600 hover:text-red-700 font-semibold inline-flex items-center gap-0.5">
                        <span>View All</span> &rarr;
                    </a>
                </div>

                <div class="mt-3.5 space-y-2.5">
                    <!-- Drafts -->
                    <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">📝</span>
                            <span class="text-xs font-medium text-slate-700">Drafts</span>
                        </div>
                        <span class="text-xs font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded-full">{{ $pipeline['drafts'] }}</span>
                    </div>

                    <!-- Submitted for Review -->
                    <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-xs font-bold">📤</span>
                            <span class="text-xs font-medium text-slate-700">Submitted for Review</span>
                        </div>
                        <span class="text-xs font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded-full">{{ $pipeline['submitted'] }}</span>
                    </div>

                    <!-- In Editorial Review -->
                    <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">🔍</span>
                            <span class="text-xs font-medium text-slate-700">In Editorial Review</span>
                        </div>
                        <span class="text-xs font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded-full">{{ $pipeline['review'] }}</span>
                    </div>

                    <!-- Scheduled -->
                    <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">📅</span>
                            <span class="text-xs font-medium text-slate-700">Scheduled</span>
                        </div>
                        <span class="text-xs font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded-full">{{ $pipeline['scheduled'] }}</span>
                    </div>

                    <!-- Published (Recent) -->
                    <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">✅</span>
                            <span class="text-xs font-medium text-slate-700">Published (Last 7 Days)</span>
                        </div>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">{{ $pipeline['published_recent'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card (3 cols) -->
        <div class="lg:col-span-3 xl:col-span-3 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Quick Actions</h3>
                </div>

                <div class="mt-3.5 grid grid-cols-2 gap-3">
                    <!-- New Article -->
                    <a href="{{ route('admin.articles.create') }}" 
                       class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-red-50/50 hover:border-red-200 text-center transition group">
                        <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-800 group-hover:text-red-600 transition">New Article</span>
                    </a>

                    <!-- Upload Media -->
                    <a href="{{ route('admin.media.index') }}" 
                       class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-blue-50/50 hover:border-blue-200 text-center transition group">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-800 group-hover:text-blue-600 transition">Upload Media</span>
                    </a>

                    <!-- Manage Categories -->
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-amber-50/50 hover:border-amber-200 text-center transition group">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-800 group-hover:text-amber-600 transition">Manage Categories</span>
                    </a>

                    <!-- Create Newsletter -->
                    <a href="{{ route('admin.newsletters.index') }}" 
                       class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-purple-50/50 hover:border-purple-200 text-center transition group">
                        <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-800 group-hover:text-purple-600 transition">Create Newsletter</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Row: Recent Articles, Top Performing Stories, Editorial Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Recent Articles Table Card (6 cols) -->
        <div class="lg:col-span-6 xl:col-span-6 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Recent Articles</h3>
                <a href="{{ route('admin.articles.index') }}" class="text-xs text-red-600 hover:text-red-700 font-semibold inline-flex items-center gap-0.5">
                    <span>View All</span> &rarr;
                </a>
            </div>

            <div class="overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-2.5">Title</th>
                            <th class="py-2.5 px-2">Channel</th>
                            <th class="py-2.5 px-2">Status</th>
                            <th class="py-2.5 px-2">Views</th>
                            <th class="py-2.5 text-right">Updated</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentArticles as $art)
                            <tr class="hover:bg-slate-50/70 transition group">
                                <td class="py-3 pr-2">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $art->featured_image ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=150&q=80' }}" 
                                             alt="Thumbnail" 
                                             class="w-8 h-8 rounded-lg object-cover shrink-0 ring-1 ring-slate-100">
                                        <div class="overflow-hidden">
                                            <a href="{{ route('admin.articles.edit', $art) }}" 
                                               class="font-semibold text-slate-800 group-hover:text-red-600 transition line-clamp-1 block leading-tight">
                                                {{ $art->title }}
                                            </a>
                                            <span class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                                <span>&bull;</span> {{ $art->author->name ?? 'Staff Writer' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-2 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" 
                                          style="background-color: {{ $art->category->color ?? '#6366F1' }}15; color: {{ $art->category->color ?? '#6366F1' }}">
                                        {{ $art->category->name ?? 'General' }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $art->status === 'published' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $art->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 font-mono font-medium text-slate-700 whitespace-nowrap">
                                    {{ number_format($art->view_count) }}
                                </td>
                                <td class="py-3 text-right text-[11px] text-slate-400 whitespace-nowrap">
                                    {{ $art->updated_at->diffForHumans(null, true) }} ago
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Performing Articles Card (3 cols) -->
        <div class="lg:col-span-3 xl:col-span-3 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Top Performing Articles</h3>
                    <span class="text-[11px] font-medium text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200">Last 7 Days</span>
                </div>

                <div class="mt-3 space-y-3.5">
                    @foreach($topArticles as $idx => $tArt)
                        @php
                            $badgeColors = [
                                0 => 'bg-red-500 text-white',
                                1 => 'bg-orange-500 text-white',
                                2 => 'bg-amber-500 text-white',
                                3 => 'bg-slate-500 text-white',
                                4 => 'bg-stone-500 text-white',
                            ];
                        @endphp
                        <div class="flex items-start gap-3 group">
                            <!-- Number Badge -->
                            <span class="w-5 h-5 rounded-full {{ $badgeColors[$idx] ?? 'bg-slate-400 text-white' }} flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                                {{ $idx + 1 }}
                            </span>
                            <!-- Article Thumbnail -->
                            <img src="{{ $tArt->featured_image ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=150&q=80' }}" 
                                 alt="Thumb" 
                                 class="w-10 h-10 rounded-lg object-cover shrink-0 ring-1 ring-slate-100">
                            <!-- Article Details -->
                            <div class="overflow-hidden flex-1">
                                <a href="{{ route('articles.show', $tArt->slug) }}" 
                                   target="_blank" 
                                   class="text-xs font-semibold text-slate-800 group-hover:text-red-600 transition line-clamp-2 leading-tight">
                                    {{ $tArt->title }}
                                </a>
                                <p class="text-[11px] font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                                    <span>&bull;</span> {{ number_format($tArt->view_count) }} views
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Editorial Activity Timeline Feed (3 cols) -->
        <div class="lg:col-span-3 xl:col-span-3 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Editorial Activity</h3>
                    <a href="{{ route('admin.audit.index') }}" class="text-xs text-red-600 hover:text-red-700 font-semibold inline-flex items-center gap-0.5">
                        <span>View All</span> &rarr;
                    </a>
                </div>

                <div class="mt-3.5 space-y-4">
                    @foreach($editorialActivity as $act)
                        <div class="flex items-start gap-3">
                            <img src="{{ $act->user_avatar ?? ($act->user && $act->user->avatar ? $act->user->avatar : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80') }}" 
                                 alt="User" 
                                 class="w-7 h-7 rounded-full object-cover shrink-0 ring-1 ring-slate-200 mt-0.5">
                            <div class="overflow-hidden flex-1 text-xs">
                                <p class="text-slate-700 leading-tight">
                                    <span class="font-bold text-slate-900">{{ $act->user_name ?? ($act->user ? $act->user->name : 'Alexander Vance') }}</span>
                                    <span>{{ $act->action }}</span>
                                </p>
                                <span class="text-[11px] text-slate-400 mt-0.5 block">
                                    {{ $act->created_at instanceof \Carbon\Carbon ? $act->created_at->diffForHumans(null, true) . ' ago' : 'Recently' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- Newsroom System Health Status Strip -->
    <div class="bg-white border border-slate-200/80 rounded-2xl px-5 py-3 shadow-2xs flex flex-wrap items-center justify-between gap-4 text-xs text-slate-600">
        <div class="flex items-center gap-2">
            <span class="font-bold text-slate-900">Newsroom Platform:</span>
            <span class="text-slate-500">AQ NEWSWIRE v2.4 (Production Engine)</span>
        </div>
        <div class="flex flex-wrap items-center gap-6 text-[11px] font-medium">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full {{ str_contains($systemHealth['database'], 'Operational') ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                <span class="text-slate-500">Database:</span>
                <span class="text-slate-800 font-bold">{{ $systemHealth['database'] }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full {{ str_contains($systemHealth['queue'], 'Operational') || str_contains($systemHealth['queue'], 'Idle') ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                <span class="text-slate-500">Queue Worker:</span>
                <span class="text-slate-800 font-bold">{{ $systemHealth['queue'] }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full {{ str_contains($systemHealth['cache'], 'Operational') ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                <span class="text-slate-500">Redis/Cache:</span>
                <span class="text-slate-800 font-bold">{{ $systemHealth['cache'] }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full {{ str_contains($systemHealth['storage'], 'Operational') ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                <span class="text-slate-500">Media CDN:</span>
                <span class="text-slate-800 font-bold">{{ $systemHealth['storage'] }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
