@extends('layouts.admin')

@section('page_title', 'Channels & Categories')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- Categories List (8 Cols) -->
    <div class="lg:col-span-8 bg-white border border-stone-200 rounded shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-stone-200">
            <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900">Configured Media Channels</h3>
        </div>

        <table class="w-full text-left text-xs">
            <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3">Channel Name</th>
                    <th class="px-6 py-3">Slug</th>
                    <th class="px-6 py-3">Stories</th>
                    <th class="px-6 py-3">Nav Visible</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @foreach($categories as $cat)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="px-6 py-4 flex items-center space-x-3">
                            <span class="w-3.5 h-3.5 rounded-full shrink-0" style="background-color: {{ $cat->color }}"></span>
                            <span class="font-bold text-stone-900">{{ $cat->name }}</span>
                        </td>
                        <td class="px-6 py-4 font-mono text-stone-500">{{ $cat->slug }}</td>
                        <td class="px-6 py-4 font-semibold text-stone-700">{{ $cat->articles_count }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $cat->is_nav_visible ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                {{ $cat->is_nav_visible ? 'Yes' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('categories.show', $cat->slug) }}" target="_blank" class="text-stone-500 hover:text-stone-900 font-semibold mr-3">View</a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('Delete this channel?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Quick Add Category (4 Cols) -->
    <div class="lg:col-span-4 bg-white border border-stone-200 rounded shadow-xs p-6">
        <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900 border-b border-stone-200 pb-3 mb-4">
            + Add New Channel
        </h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Name *</label>
                <input type="text" name="name" required placeholder="e.g. Energy Transition"
                       class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Slug (Optional)</label>
                <input type="text" name="slug" placeholder="e.g. energy-transition"
                       class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Brand Highlight Color</label>
                <input type="color" name="color" value="#0284c7" class="w-16 h-8 rounded border border-stone-300 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Brief summary for category hub..."
                          class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900"></textarea>
            </div>

            <div class="space-y-2 pt-2">
                <label class="flex items-center space-x-2 text-xs text-stone-700">
                    <input type="checkbox" name="is_nav_visible" value="1" checked class="rounded text-red-600">
                    <span>Show in Header Navigation Bar</span>
                </label>
                <label class="flex items-center space-x-2 text-xs text-stone-700">
                    <input type="checkbox" name="is_featured" value="1" class="rounded text-red-600">
                    <span>Feature on Homepage Blocks</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-stone-900 hover:bg-stone-800 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded transition">
                Create Channel
            </button>
        </form>
    </div>

</div>
@endsection
