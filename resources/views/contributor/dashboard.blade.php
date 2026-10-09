@extends('layouts.app')

@section('title', 'Contributor Studio — AQ NEWSWIRE')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-stone-200 mb-8 gap-4">
        <div>
            <span class="text-xs uppercase font-extrabold tracking-widest text-red-600 block mb-1">Author Studio</span>
            <h1 class="font-headline text-3xl font-bold text-stone-950">Contributor Workspace</h1>
            <p class="text-xs text-stone-500 mt-0.5">Manage your editorial columns, draft stories, and review editorial feedback.</p>
        </div>

        <a href="{{ route('contributor.articles.create') }}" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider rounded transition">
            + Write New Article
        </a>
    </div>

    <!-- Contributor KPI row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-stone-200 rounded p-5 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400 block mb-1">Published Stories</span>
            <span class="text-2xl font-black text-stone-900">{{ $stats['published'] }}</span>
        </div>
        <div class="bg-white border border-stone-200 rounded p-5 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400 block mb-1">In Review</span>
            <span class="text-2xl font-black text-amber-600">{{ $stats['submitted'] }}</span>
        </div>
        <div class="bg-white border border-stone-200 rounded p-5 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400 block mb-1">Private Drafts</span>
            <span class="text-2xl font-black text-stone-600">{{ $stats['drafts'] }}</span>
        </div>
        <div class="bg-white border border-stone-200 rounded p-5 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400 block mb-1">Total Readers</span>
            <span class="text-2xl font-black text-emerald-600">{{ number_format($stats['views']) }}</span>
        </div>
    </div>

    <!-- Stories Table -->
    <div class="bg-white border border-stone-200 rounded shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-stone-200">
            <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900">Your Editorial Submissions</h3>
        </div>

        <table class="w-full text-left text-xs">
            <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3">Headline</th>
                    <th class="px-6 py-3">Channel</th>
                    <th class="px-6 py-3">Editorial Status</th>
                    <th class="px-6 py-3">Feedback Notes</th>
                    <th class="px-6 py-3">Views</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse($articles as $art)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="px-6 py-4 max-w-sm">
                            <a href="{{ route('contributor.articles.edit', $art) }}" class="font-bold text-stone-900 hover:text-red-600 line-clamp-1">
                                {{ $art->title }}
                            </a>
                            <span class="text-[10px] text-stone-400">{{ $art->created_at->format('M j, Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-stone-700">{{ $art->category->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $art->status === 'published' ? 'bg-emerald-100 text-emerald-800' : ($art->status === 'submitted' ? 'bg-amber-100 text-amber-800' : 'bg-stone-100 text-stone-600') }}">
                                {{ $art->status === 'submitted' ? 'In Review' : $art->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($art->editorialNotes->isNotEmpty())
                                <span class="bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded text-[10px]">
                                    {{ $art->editorialNotes->count() }} Editorial Note(s)
                                </span>
                            @else
                                <span class="text-stone-400 text-[10px]">None</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono font-semibold text-stone-700">
                            {{ number_format($art->view_count) }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if($art->status === 'published')
                                <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="text-stone-500 hover:text-stone-900 font-semibold">View Live</a>
                            @endif
                            <a href="{{ route('contributor.articles.edit', $art) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Edit / Revisions</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-stone-500">No stories submitted yet. Click "+ Write New Article" to draft your first piece.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>

</div>
@endsection
