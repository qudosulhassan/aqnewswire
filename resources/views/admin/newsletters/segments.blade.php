@extends('layouts.admin')

@section('title', 'Newsletter Segments — CMS')
@section('page_title', 'Audience Segments & Targeted Cohorts')

@section('content')
<div class="space-y-6">

    <!-- Sub-Nav -->
    <div class="flex items-center space-x-3 border-b border-stone-200 pb-3 text-xs font-semibold">
        <a href="{{ route('admin.newsletters.index') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Subscribers</a>
        <a href="{{ route('admin.newsletters.campaigns') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Campaigns</a>
        <a href="{{ route('admin.newsletters.segments') }}" class="px-3 py-1.5 rounded bg-stone-900 text-white font-bold">Segments</a>
        <a href="{{ route('admin.newsletters.templates') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Templates</a>
        <a href="{{ route('admin.newsletters.analytics') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Analytics</a>
    </div>

    <!-- Create Segment Form -->
    <div class="bg-white border border-stone-200 rounded p-6 shadow-xs">
        <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900 mb-4">Create Audience Segment</h3>
        <form action="{{ route('admin.newsletters.segments.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Segment Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Venture Capital & Founders Cohort" class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Targeting Criteria *</label>
                    <select name="criteria_type" required class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                        <option value="active_only">Active Subscribers Only</option>
                        <option value="specific_topics">Topic Match (Interests & Tags)</option>
                        <option value="all">Complete Database</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Description / Audience Purpose</label>
                <input type="text" name="description" placeholder="Brief note explaining the subscriber cohort..." class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
            </div>
            <div>
                <button type="submit" class="bg-stone-900 hover:bg-black text-white font-bold text-xs uppercase tracking-wider px-6 py-2.5 rounded transition">
                    Create Segment
                </button>
            </div>
        </form>
    </div>

    <!-- Segments Table -->
    <div class="bg-white border border-stone-200 rounded shadow-xs overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-stone-50 border-b border-stone-200 uppercase tracking-wider text-stone-600 font-bold">
                    <th class="p-4">Segment</th>
                    <th class="p-4">Criteria</th>
                    <th class="p-4">Campaigns Dispatched</th>
                    <th class="p-4">Created Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($segments as $seg)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="p-4">
                            <span class="font-bold text-stone-900 block">{{ $seg->name }}</span>
                            <span class="text-stone-500 text-[11px]">{{ $seg->description ?? 'No description' }}</span>
                        </td>
                        <td class="p-4">
                            <span class="font-mono text-stone-600 uppercase text-[11px]">{{ $seg->criteria_type }}</span>
                        </td>
                        <td class="p-4 font-mono">
                            {{ $seg->campaigns_count }}
                        </td>
                        <td class="p-4 text-stone-500">
                            {{ $seg->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-stone-400">No custom segments created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $segments->links() }}
    </div>

</div>
@endsection
