@extends('layouts.admin')

@section('page_title', 'Publish Video Broadcast')

@section('content')
<div class="max-w-3xl bg-white border border-stone-200 rounded p-8 shadow-xs">
    <form action="{{ route('admin.videos.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Video Title *</label>
            <input type="text" name="title" required placeholder="e.g. Inside the Quantum Chip Revolution"
                   class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Video Embed / Stream URL *</label>
            <input type="url" name="video_url" required placeholder="https://www.youtube.com/watch?v=... or https://player.vimeo.com/..."
                   class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Custom Thumbnail Image URL</label>
            <input type="url" name="thumbnail_url" placeholder="https://images.unsplash.com/..."
                   class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Associated Channel</label>
                <select name="category_id" class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                    <option value="">General Video</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Feature on Video Hub</label>
                <div class="pt-2">
                    <label class="flex items-center space-x-2 text-xs text-stone-700">
                        <input type="checkbox" name="is_featured" value="1" class="rounded text-red-600">
                        <span>Set as Lead Featured Broadcast</span>
                    </label>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Synopsis / Episode Description</label>
            <textarea name="description" rows="4" placeholder="Brief summary of the interview or report..."
                      class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900"></textarea>
        </div>

        <div class="pt-4 border-t border-stone-100 flex justify-end space-x-3">
            <a href="{{ route('admin.videos.index') }}" class="px-4 py-2 border border-stone-300 text-stone-600 rounded text-xs font-bold uppercase tracking-wider">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded transition">
                Publish Video
            </button>
        </div>
    </form>
</div>
@endsection
