@extends('layouts.admin')

@section('page_title', 'Podcast Shows & Audio Programming')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- Podcasts Table (7 Cols) -->
    <div class="lg:col-span-7 bg-white border border-stone-200 rounded shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-stone-200">
            <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900">Registered Audio Series</h3>
        </div>

        <table class="w-full text-left text-xs">
            <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3">Show</th>
                    <th class="px-6 py-3">Host</th>
                    <th class="px-6 py-3">Episodes</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse($podcasts as $p)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="px-6 py-4 flex items-center space-x-3">
                            <img src="{{ $p->cover_image ?? 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=100&q=80' }}" class="w-10 h-10 rounded object-cover">
                            <div>
                                <span class="font-bold text-stone-900 block">{{ $p->title }}</span>
                                <span class="text-[10px] text-stone-400 font-mono">{{ $p->slug }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-stone-700">{{ $p->host_name }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600">{{ $p->episodes_count }} Ep.</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('podcasts.show', $p->slug) }}" target="_blank" class="text-stone-500 hover:text-stone-900 font-semibold">View Live</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-stone-500">No podcast shows registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Create Podcast Series Form (5 Cols) -->
    <div class="lg:col-span-5 space-y-6">
        <div class="bg-white border border-stone-200 rounded p-6 shadow-xs">
            <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900 border-b border-stone-200 pb-2 mb-4">
                + Register New Podcast Series
            </h3>

            <form action="{{ route('admin.podcasts.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Series Title *</label>
                    <input type="text" name="title" required placeholder="e.g. AQ NEWSWIRE Global Macro"
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Host Name *</label>
                    <input type="text" name="host_name" required placeholder="e.g. Alexander Vance"
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Cover Artwork URL</label>
                    <input type="url" name="cover_image" placeholder="https://..."
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Series Overview</label>
                    <textarea name="description" rows="3" placeholder="Summary of the show's focus..."
                              class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900"></textarea>
                </div>

                <button type="submit" class="w-full bg-stone-900 hover:bg-black text-white font-bold text-xs uppercase tracking-wider py-2 rounded transition">
                    Register Show
                </button>
            </form>
        </div>

        @if($podcasts->isNotEmpty())
            <!-- Publish Episode to Show -->
            <div class="bg-white border border-stone-200 rounded p-6 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900 border-b border-stone-200 pb-2 mb-4">
                    + Publish Episode
                </h3>
                <form action="{{ route('admin.podcasts.episodes.store', $podcasts->first()) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Episode Title *</label>
                        <input type="text" name="title" required placeholder="Episode headline..." class="w-full px-3 py-1.5 border border-stone-300 rounded text-xs">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Ep. #</label>
                            <input type="number" name="episode_number" value="1" required class="w-full px-3 py-1.5 border border-stone-300 rounded text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Duration</label>
                            <input type="text" name="duration" placeholder="38 mins" class="w-full px-3 py-1.5 border border-stone-300 rounded text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">MP3 Audio Stream URL *</label>
                        <input type="url" name="audio_url" required placeholder="https://..." class="w-full px-3 py-1.5 border border-stone-300 rounded text-xs">
                    </div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider py-2 rounded transition">
                        Publish Episode
                    </button>
                </form>
            </div>
        @endif
    </div>

</div>
@endsection
