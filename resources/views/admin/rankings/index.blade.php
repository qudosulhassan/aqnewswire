@extends('layouts.admin')

@section('page_title', 'Lists & Rankings Management')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-stone-900">Forbes-Style Editorial Lists & Rankings</h2>
            <p class="text-xs text-stone-500">Manage annual indexes, billionaire trackers, 30 Under 30 lists, and directory profiles.</p>
        </div>
        <a href="{{ route('admin.rankings.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded transition">
            + Create New Ranking List
        </a>
    </div>

    <div class="bg-white border border-stone-200 rounded shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <tr>
                    <th class="px-6 py-3">List Title</th>
                    <th class="px-6 py-3">Edition / Year</th>
                    <th class="px-6 py-3">Profiled Entries</th>
                    <th class="px-6 py-3">Featured on Homepage</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse($lists as $list)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.rankings.edit', $list) }}" class="font-bold text-stone-900 hover:text-red-600">
                                {{ $list->title }}
                            </a>
                            <p class="text-[11px] text-stone-400">{{ $list->subtitle }}</p>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-stone-700">{{ $list->year }}</td>
                        <td class="px-6 py-4 font-semibold text-stone-700">{{ $list->items_count }} Leaders</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $list->is_featured ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                {{ $list->is_featured ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('rankings.show', $list->slug) }}" target="_blank" class="text-stone-500 hover:text-stone-900 font-semibold">View Live</a>
                            <a href="{{ route('admin.rankings.edit', $list) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Manage Items</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-stone-500">No rankings lists created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
