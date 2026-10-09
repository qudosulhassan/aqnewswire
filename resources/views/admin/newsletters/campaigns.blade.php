@extends('layouts.admin')

@section('title', 'Newsletter Campaigns — CMS')
@section('page_title', 'Email Campaigns & Editorial Briefings')

@section('content')
<div class="space-y-6">

    <!-- Sub-Nav -->
    <div class="flex items-center space-x-3 border-b border-stone-200 pb-3 text-xs font-semibold">
        <a href="{{ route('admin.newsletters.index') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Subscribers</a>
        <a href="{{ route('admin.newsletters.campaigns') }}" class="px-3 py-1.5 rounded bg-stone-900 text-white font-bold">Campaigns</a>
        <a href="{{ route('admin.newsletters.segments') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Segments</a>
        <a href="{{ route('admin.newsletters.templates') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Templates</a>
        <a href="{{ route('admin.newsletters.analytics') }}" class="px-3 py-1.5 rounded hover:bg-stone-200 text-stone-600 transition">Analytics</a>
    </div>

    <!-- Create Campaign Form -->
    <div class="bg-white border border-stone-200 rounded p-6 shadow-xs">
        <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900 mb-4">Create New Campaign</h3>
        <form action="{{ route('admin.newsletters.campaigns.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Campaign Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Weekly Tech & AI Dispatch #42" class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Email Subject Line *</label>
                    <input type="text" name="subject" required placeholder="e.g. Inside the $4T Shift in Corporate AI Architecture" class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Audience Segment</label>
                    <select name="segment_id" class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                        <option value="">All Active Subscribers</option>
                        @foreach($segments as $seg)
                            <option value="{{ $seg->id }}">{{ $seg->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Template Style</label>
                    <select name="template_id" class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                        <option value="">Standard AQ NEWSWIRE Clean Layout</option>
                        @foreach($templates as $tmpl)
                            <option value="{{ $tmpl->id }}">{{ $tmpl->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Dispatch Pipeline Status *</label>
                    <select name="status" required class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                        <option value="draft">Draft (Save for review)</option>
                        <option value="scheduled">Scheduled (Queue worker)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Dispatch Content / HTML Body *</label>
                <textarea name="content" rows="4" required placeholder="Enter editorial brief body or markdown..." class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900"></textarea>
            </div>

            <div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider px-6 py-2.5 rounded transition">
                    Save Campaign
                </button>
            </div>
        </form>
    </div>

    <!-- Campaigns Table -->
    <div class="bg-white border border-stone-200 rounded shadow-xs overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-stone-50 border-b border-stone-200 uppercase tracking-wider text-stone-600 font-bold">
                    <th class="p-4">Campaign</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Audience</th>
                    <th class="p-4">Recipients</th>
                    <th class="p-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($campaigns as $camp)
                    <tr class="hover:bg-stone-50 transition">
                        <td class="p-4">
                            <span class="font-bold text-stone-900 block">{{ $camp->name }}</span>
                            <span class="text-stone-500 text-[11px]">{{ $camp->subject }}</span>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $camp->status === 'sent' ? 'bg-emerald-100 text-emerald-800' : ($camp->status === 'scheduled' ? 'bg-amber-100 text-amber-800' : 'bg-stone-100 text-stone-700') }}">
                                {{ $camp->status }}
                            </span>
                        </td>
                        <td class="p-4 text-stone-600">
                            {{ $camp->segment->name ?? 'All Active Subscribers' }}
                        </td>
                        <td class="p-4 font-mono">
                            {{ number_format($camp->total_recipients) }}
                        </td>
                        <td class="p-4 text-stone-500">
                            {{ $camp->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-stone-400">No campaigns created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $campaigns->links() }}
    </div>

</div>
@endsection
