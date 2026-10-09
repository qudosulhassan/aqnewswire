@extends('layouts.admin')

@section('page_title', 'Create Rankings List')

@section('content')
<div class="max-w-3xl bg-white border border-stone-200 rounded p-8 shadow-xs">
    <form action="{{ route('admin.rankings.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">List Title *</label>
            <input type="text" name="title" required placeholder="e.g. The World's Billionaires, 30 Under 30 Finance"
                   class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Edition / Year *</label>
                <input type="number" name="year" value="{{ date('Y') }}" required
                       class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Feature on Homepage</label>
                <div class="pt-2.5">
                    <label class="flex items-center space-x-2 text-xs text-stone-700">
                        <input type="checkbox" name="is_featured" value="1" checked class="rounded text-red-600">
                        <span>Show in Homepage Spotlight</span>
                    </label>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Subtitle</label>
            <input type="text" name="subtitle" placeholder="A one-line description of the index methodology..."
                   class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Detailed Editorial Methodology & Description</label>
            <textarea name="description" rows="4" placeholder="How were the candidates evaluated, net worth assessed, etc..."
                      class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900"></textarea>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Cover Image URL</label>
            <input type="url" name="cover_image" placeholder="https://images.unsplash.com/..."
                   class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
        </div>

        <div class="pt-4 border-t border-stone-100 flex justify-end space-x-3">
            <a href="{{ route('admin.rankings.index') }}" class="px-4 py-2 border border-stone-300 rounded text-xs font-bold uppercase tracking-wider text-stone-600 hover:bg-stone-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded transition">
                Create & Add Leaders
            </button>
        </div>
    </form>
</div>
@endsection
