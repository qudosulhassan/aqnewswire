@extends('layouts.admin')

@section('title', 'Media Asset Library')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Header & Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Media Asset Library</h1>
            <p class="text-xs text-slate-500 mt-0.5">Central repository for high-resolution photography, graphics, videos, and documents.</p>
        </div>
    </div>

    <!-- Upload Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" 
              class="flex flex-col md:flex-row items-center justify-between gap-4">
            @csrf
            
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <input type="file" 
                       name="file" 
                       required 
                       class="text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-black transition">
                <input type="text" 
                       name="title" 
                       placeholder="Asset title or caption..." 
                       class="px-3.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400">
            </div>

            <button type="submit" 
                    class="w-full md:w-auto px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs uppercase tracking-wider rounded-xl shadow-xs transition">
                + Upload Asset
            </button>
        </form>
    </div>

    <!-- Type Filter Tabs & Search -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 text-xs font-medium">
            <a href="{{ route('admin.media.index') }}" 
               class="px-3 py-1.5 rounded-lg transition {{ !request('type') ? 'bg-slate-900 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                All Assets
            </a>
            <a href="{{ route('admin.media.index', ['type' => 'image']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('type') === 'image' ? 'bg-blue-600 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Images
            </a>
            <a href="{{ route('admin.media.index', ['type' => 'video']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('type') === 'video' ? 'bg-purple-600 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Videos
            </a>
            <a href="{{ route('admin.media.index', ['type' => 'audio']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('type') === 'audio' ? 'bg-emerald-600 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Audio
            </a>
        </div>

        <form method="GET" action="{{ route('admin.media.index') }}" class="flex items-center gap-2 w-full md:w-auto">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Search filename..." 
                   class="w-full md:w-56 px-3.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400">
            <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                Search
            </button>
        </form>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($assets as $asset)
            <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs hover:shadow-xs transition group flex flex-col justify-between">
                <div class="aspect-square bg-slate-100 flex items-center justify-center overflow-hidden relative">
                    @if($asset->file_type === 'image')
                        <img src="{{ $asset->url }}" alt="{{ $asset->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <span class="text-4xl text-slate-400">📁</span>
                    @endif
                    <span class="absolute top-2 right-2 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-slate-900/70 text-white backdrop-blur-xs">
                        {{ strtoupper($asset->file_type) }}
                    </span>
                </div>
                <div class="p-3">
                    <p class="text-xs font-semibold text-slate-800 truncate" title="{{ $asset->title ?? $asset->filename }}">{{ $asset->title ?? $asset->filename }}</p>
                    <div class="text-[10px] text-slate-400 mt-1 flex justify-between">
                        <span>{{ round($asset->file_size / 1024) }} KB</span>
                        <span>{{ $asset->created_at->format('M j') }}</span>
                    </div>
                    <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                        <button onclick="navigator.clipboard.writeText('{{ $asset->url }}'); alert('Media asset URL copied to clipboard!');" 
                                class="text-blue-600 hover:text-blue-800 font-semibold">
                            Copy Link
                        </button>
                        <form action="{{ route('admin.media.destroy', $asset) }}" method="POST" onsubmit="return confirm('Delete this media asset permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-6 text-center py-16 bg-white border border-slate-200/90 rounded-2xl text-slate-400 text-xs shadow-2xs">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="font-semibold text-slate-600 text-sm">No media assets found.</p>
                <p class="text-slate-400 mt-0.5">Upload high-resolution photography or broadcast audio above.</p>
            </div>
        @endforelse
    </div>

    @if($assets->hasPages())
        <div class="p-4 bg-white border border-slate-200/90 rounded-2xl shadow-2xs">
            {{ $assets->links() }}
        </div>
    @endif

</div>
@endsection
