@extends('layouts.admin')

@section('title', 'Articles & Editorial Stories')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Articles & Editorial Stories</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage, review, publish, and schedule stories across all news channels.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.articles.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Write New Story</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.articles.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Quick Status Filter Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 text-xs font-medium text-slate-600">
                <a href="{{ route('admin.articles.index') }}" 
                   class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 {{ !request('status') ? 'bg-slate-900 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    <span>All Stories</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ !request('status') ? 'bg-slate-700 text-slate-200' : 'bg-slate-200 text-slate-600' }}">{{ $statusCounts['all'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.articles.index', ['status' => 'published']) }}" 
                   class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 {{ request('status') === 'published' ? 'bg-emerald-600 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    <span>Published</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('status') === 'published' ? 'bg-emerald-700 text-emerald-100' : 'bg-slate-200 text-slate-600' }}">{{ $statusCounts['published'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.articles.index', ['status' => 'draft']) }}" 
                   class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 {{ request('status') === 'draft' ? 'bg-blue-600 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    <span>Drafts</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('status') === 'draft' ? 'bg-blue-700 text-blue-100' : 'bg-slate-200 text-slate-600' }}">{{ $statusCounts['draft'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.articles.index', ['status' => 'scheduled']) }}" 
                   class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 {{ request('status') === 'scheduled' ? 'bg-purple-600 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    <span>Scheduled</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('status') === 'scheduled' ? 'bg-purple-700 text-purple-100' : 'bg-slate-200 text-slate-600' }}">{{ $statusCounts['scheduled'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.articles.index', ['status' => 'submitted']) }}" 
                   class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5 {{ request('status') === 'submitted' ? 'bg-orange-600 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    <span>In Review</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('status') === 'submitted' ? 'bg-orange-700 text-orange-100' : 'bg-slate-200 text-slate-600' }}">{{ $statusCounts['submitted'] ?? 0 }}</span>
                </a>
            </div>

            <!-- Search & Filters Select -->
            <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                <div class="relative flex-1 md:w-52">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search headlines..." 
                           class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <select name="category_id" 
                        class="bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-700 focus:outline-none focus:border-slate-400">
                    <option value="">All Channels</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="flag" 
                        class="bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-700 focus:outline-none focus:border-slate-400">
                    <option value="">All Flags</option>
                    <option value="featured" {{ request('flag') === 'featured' ? 'selected' : '' }}>Featured Cover</option>
                    <option value="breaking" {{ request('flag') === 'breaking' ? 'selected' : '' }}>Breaking News</option>
                    <option value="trending" {{ request('flag') === 'trending' ? 'selected' : '' }}>Trending</option>
                    <option value="editors_pick" {{ request('flag') === 'editors_pick' ? 'selected' : '' }}>Editors' Pick</option>
                </select>

                <button type="submit" 
                        class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
                    Filter
                </button>
            </div>

        </form>
    </div>

    <!-- Articles Master Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="py-3 px-4 w-14">Media</th>
                        <th class="py-3 px-4">Headline & Flags</th>
                        <th class="py-3 px-4">Channel</th>
                        <th class="py-3 px-4">Author</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Views</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($articles as $art)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- Thumbnail -->
                            <td class="py-3 px-4">
                                <img src="{{ $art->featured_image ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=150&q=80' }}" 
                                     alt="Thumb" 
                                     class="w-11 h-11 rounded-xl object-cover ring-1 ring-slate-100 shadow-2xs">
                            </td>

                            <!-- Title & Flags -->
                            <td class="py-3 px-4 max-w-sm">
                                <a href="{{ route('admin.articles.edit', $art) }}" 
                                   class="font-semibold text-slate-800 group-hover:text-red-600 transition line-clamp-1 block leading-snug">
                                    {{ $art->title }}
                                </a>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <form action="{{ route('admin.articles.toggleBreaking', $art) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="Click to toggle Breaking News flag" class="text-[9px] font-bold px-1.5 py-0.5 rounded cursor-pointer transition {{ $art->is_breaking ? 'bg-red-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-400 hover:text-slate-700' }}">
                                            ⚡ Breaking
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.articles.toggleFeatured', $art) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="Click to toggle Featured Editorial flag" class="text-[9px] font-bold px-1.5 py-0.5 rounded cursor-pointer transition {{ $art->is_featured ? 'bg-amber-500 text-white shadow-2xs' : 'bg-slate-100 text-slate-400 hover:text-slate-700' }}">
                                            ★ Featured
                                        </button>
                                    </form>

                                    @if($art->is_trending)
                                        <span class="text-[9px] bg-blue-100 text-blue-700 font-bold px-1.5 py-0.5 rounded">Trending</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Channel Badge -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" 
                                      style="background-color: {{ $art->category->color ?? '#6366F1' }}15; color: {{ $art->category->color ?? '#6366F1' }}">
                                    {{ $art->category->name ?? 'General' }}
                                </span>
                            </td>

                            <!-- Author -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="text-slate-700 font-medium">{{ $art->author->name ?? 'Editorial Staff' }}</span>
                            </td>

                            <!-- Status Pill (1-Click Toggle) -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <form action="{{ route('admin.articles.toggleStatus', $art) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            title="Click to toggle between published and draft"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition cursor-pointer hover:shadow-xs {{ $art->status === 'published' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-300 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 ring-1 ring-slate-200 hover:bg-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $art->status === 'published' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $art->status }}</span>
                                        <span class="text-[9px] text-slate-400">⇄</span>
                                    </button>
                                </form>
                            </td>


                            <!-- Views -->
                            <td class="py-3 px-4 font-mono font-medium text-slate-700 whitespace-nowrap">
                                {{ number_format($art->view_count) }}
                            </td>

                            <!-- Date -->
                            <td class="py-3 px-4 text-slate-400 whitespace-nowrap">
                                {{ $art->published_at ? $art->published_at->format('M j, Y') : 'Draft' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right whitespace-nowrap space-x-2">
                                <a href="{{ route('admin.articles.preview', $art) }}" class="text-slate-600 hover:text-slate-900 font-medium">Preview</a>
                                <a href="{{ route('admin.articles.edit', $art) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Edit</a>
                                <form action="{{ route('admin.articles.destroy', $art) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">No stories match your filter criteria.</p>
                                <a href="{{ route('admin.articles.create') }}" class="text-xs text-red-600 hover:underline font-semibold mt-1 inline-block">Create your first article &rarr;</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $articles->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
