@extends('layouts.admin')

@section('page_title', 'Video Broadcasts Management')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-stone-900">AQ NEWSWIRE Video Broadcasts & Documentaries</h2>
            <p class="text-xs text-stone-500">Manage video programming, YouTube/Vimeo embeds, and executive interviews.</p>
        </div>
        <a href="{{ route('admin.videos.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded transition">
            + Publish New Video
        </a>
    </div>

    <div class="bg-white border border-stone-200 rounded shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3">Thumbnail</th>
                    <th class="px-6 py-3">Video Title</th>
                    <th class="px-6 py-3">Channel</th>
                    <th class="px-6 py-3">Views</th>
                    <th class="px-6 py-3">Published</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse($videos as $v)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="px-6 py-3 w-20">
                            <img src="{{ $v->thumbnail_url ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=120&q=80' }}" class="w-16 h-10 object-cover rounded">
                        </td>
                        <td class="px-6 py-3">
                            <span class="font-bold text-stone-900 block">{{ $v->title }}</span>
                            <span class="text-[10px] text-stone-400 font-mono">{{ $v->video_url }}</span>
                        </td>
                        <td class="px-6 py-3">
                            {{ $v->category->name ?? 'Special' }}
                        </td>
                        <td class="px-6 py-3 font-mono font-semibold">
                            {{ number_format($v->view_count) }}
                        </td>
                        <td class="px-6 py-3 text-stone-400">
                            {{ $v->published_at ? $v->published_at->format('M j, Y') : '' }}
                        </td>
                        <td class="px-6 py-3 text-right space-x-2">
                            <a href="{{ route('videos.show', $v->slug) }}" target="_blank" class="text-stone-500 hover:text-stone-900 font-semibold">View</a>
                            <form action="{{ route('admin.videos.destroy', $v) }}" method="POST" class="inline" onsubmit="return confirm('Delete video?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-stone-500">No videos published yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $videos->links() }}
    </div>

</div>
@endsection
