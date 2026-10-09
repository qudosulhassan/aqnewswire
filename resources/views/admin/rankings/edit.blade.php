@extends('layouts.admin')

@section('page_title', 'Manage List: ' . $list->title)

@section('content')
<div class="space-y-8 max-w-6xl">

    <!-- List Meta Info Bar -->
    <div class="bg-white border border-stone-200 rounded p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <span class="text-xs uppercase font-extrabold tracking-widest text-red-600 block mb-1">Edition {{ $list->year }}</span>
            <h2 class="text-2xl font-bold text-stone-900">{{ $list->title }}</h2>
            <p class="text-xs text-stone-500 mt-1">{{ $list->subtitle }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('rankings.show', $list->slug) }}" target="_blank" class="px-4 py-2 border border-stone-300 rounded text-xs font-bold uppercase tracking-wider text-stone-700 hover:bg-stone-50">
                View Live Leaderboard &rarr;
            </a>
        </div>
    </div>

    <!-- Two Columns: Add New Leader + Existing Leaders Table -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Form to Add Leader to List (4 Cols) -->
        <div class="lg:col-span-4 bg-white border border-stone-200 rounded p-6 shadow-xs">
            <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900 border-b border-stone-200 pb-3 mb-4">
                + Add Leader / Profile
            </h3>

            <form action="{{ route('admin.rankings.items.store', $list) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Rank # *</label>
                    <input type="number" name="rank" value="{{ ($list->items->max('rank') ?? 0) + 1 }}" required 
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Full Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Jensen Huang"
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Title / Role</label>
                    <input type="text" name="title_or_role" placeholder="e.g. Co-Founder & CEO"
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Company / Organization</label>
                    <input type="text" name="company" placeholder="e.g. NVIDIA"
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Net Worth / Valuation / Metric</label>
                    <input type="text" name="net_worth_or_metric" placeholder="e.g. $128.5 Billion"
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Industry</label>
                    <input type="text" name="industry" placeholder="e.g. Semiconductors"
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Country</label>
                    <input type="text" name="country" placeholder="e.g. United States"
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Headshot Photo URL</label>
                    <input type="url" name="photo_url" placeholder="https://..."
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Mini Bio / Citation</label>
                    <textarea name="bio" rows="3" placeholder="Brief rationale and career highlights..."
                              class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900"></textarea>
                </div>

                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded transition">
                    Add to Leaderboard
                </button>
            </form>
        </div>

        <!-- Table of Existing Leaders in List (8 Cols) -->
        <div class="lg:col-span-8 bg-white border border-stone-200 rounded shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900">Ranked Profiles ({{ $list->items->count() }})</h3>
            </div>

            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                    <tr>
                        <th class="px-4 py-3">Rank</th>
                        <th class="px-4 py-3">Profile</th>
                        <th class="px-4 py-3">Company</th>
                        <th class="px-4 py-3">Net Worth</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($list->items as $item)
                        <tr class="hover:bg-stone-50 transition">
                            <td class="px-4 py-3 font-black text-stone-900 text-sm">#{{ $item->rank }}</td>
                            <td class="px-4 py-3 flex items-center space-x-3">
                                <img src="{{ $item->photo_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80' }}" 
                                     class="w-8 h-8 rounded-full object-cover shrink-0">
                                <div>
                                    <span class="font-bold text-stone-900">{{ $item->name }}</span>
                                    <p class="text-[10px] text-stone-400">{{ $item->title_or_role }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-semibold text-stone-700">{{ $item->company }}</td>
                            <td class="px-4 py-3 font-bold text-emerald-600">{{ $item->net_worth_or_metric }}</td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('admin.rankings.items.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Remove leader from list?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-stone-500">No leaders added to this list yet. Use the form on the left.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
