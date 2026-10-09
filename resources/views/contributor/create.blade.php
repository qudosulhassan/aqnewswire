@extends('layouts.app')

@section('title', 'Draft New Column — Contributor Studio | AQ NEWSWIRE')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <a href="{{ route('contributor.dashboard') }}" class="text-xs text-stone-500 hover:text-stone-900 font-semibold">&larr; Back to Contributor Studio</a>
        <h1 class="font-headline text-3xl font-bold text-stone-950 mt-1">Draft New Editorial Story</h1>
    </div>

    <form action="{{ route('contributor.articles.store') }}" method="POST" class="bg-white border border-stone-200 rounded p-8 shadow-xs space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Headline *</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Enter compelling headline..."
                   class="w-full px-3 py-2 border border-stone-300 rounded text-base font-semibold focus:outline-none focus:border-stone-900">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Channel / Category *</label>
                <select name="category_id" required class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Submission Target</label>
                <select name="status" class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                    <option value="draft">Save as Private Draft</option>
                    <option value="submitted">Submit for Editorial Review</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Subtitle / Dek</label>
            <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="Secondary headline providing context..."
                   class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Lead Excerpt</label>
            <textarea name="excerpt" rows="2" placeholder="Brief summary of the analysis..."
                      class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">{{ old('excerpt') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Article Body (HTML Supported) *</label>
            <textarea name="content" rows="14" required placeholder="Write your full story..."
                      class="w-full px-4 py-3 border border-stone-300 rounded text-sm font-serif leading-relaxed focus:outline-none focus:border-stone-900">{{ old('content') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Cover Image URL</label>
            <input type="url" name="featured_image" placeholder="https://images.unsplash.com/..."
                   class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
        </div>

        <div class="pt-4 border-t border-stone-100 flex justify-end space-x-3">
            <a href="{{ route('contributor.dashboard') }}" class="px-4 py-2 border border-stone-300 text-stone-600 rounded text-xs font-bold uppercase tracking-wider">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded transition">
                Save & Proceed
            </button>
        </div>
    </form>

</div>
@endsection
