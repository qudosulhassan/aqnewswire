@extends('layouts.app')

@section('title', 'Edit Story — ' . $article->title . ' | AQ NEWSWIRE')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('contributor.dashboard') }}" class="text-xs text-stone-500 hover:text-stone-900 font-semibold">&larr; Back to Contributor Studio</a>
            <h1 class="font-headline text-3xl font-bold text-stone-950 mt-1">Edit / Respond to Revisions</h1>
        </div>
        <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $article->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
            Status: {{ $article->status }}
        </span>
    </div>

    <!-- Editorial Feedback Strip if notes exist -->
    @if($article->editorialNotes->isNotEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded p-6 mb-8">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-900 mb-3 flex items-center">
                <span class="mr-2">📝</span> Editorial Board Feedback & Revision Notes
            </h3>
            <div class="space-y-3">
                @foreach($article->editorialNotes as $note)
                    <div class="bg-white p-4 rounded border border-blue-100 text-xs">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-stone-900">{{ $note->user->name }} ({{ ucfirst($note->note_type) }})</span>
                            <span class="text-[10px] text-stone-400">{{ $note->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-stone-700 leading-relaxed">{{ $note->content }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('contributor.articles.update', $article) }}" method="POST" class="bg-white border border-stone-200 rounded p-8 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Headline *</label>
            <input type="text" name="title" value="{{ old('title', $article->title) }}" required 
                   class="w-full px-3 py-2 border border-stone-300 rounded text-base font-semibold focus:outline-none focus:border-stone-900">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Channel / Category *</label>
                <select name="category_id" required class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $article->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Submission Target</label>
                <select name="status" class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                    <option value="draft" {{ $article->status === 'draft' ? 'selected' : '' }}>Private Draft</option>
                    <option value="submitted" {{ $article->status === 'submitted' ? 'selected' : '' }}>Submit for Review</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Subtitle / Dek</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $article->subtitle) }}" 
                   class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Lead Excerpt</label>
            <textarea name="excerpt" rows="2" 
                      class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Article Body (HTML Supported) *</label>
            <textarea name="content" rows="14" required 
                      class="w-full px-4 py-3 border border-stone-300 rounded text-sm font-serif leading-relaxed focus:outline-none focus:border-stone-900">{{ old('content', $article->content) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Cover Image URL</label>
            <input type="url" name="featured_image" value="{{ old('featured_image', $article->featured_image) }}" 
                   class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
        </div>

        <div class="pt-4 border-t border-stone-100 flex justify-end space-x-3">
            <a href="{{ route('contributor.dashboard') }}" class="px-4 py-2 border border-stone-300 text-stone-600 rounded text-xs font-bold uppercase tracking-wider">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded transition">
                Update & Submit Revisions
            </button>
        </div>
    </form>

</div>
@endsection
