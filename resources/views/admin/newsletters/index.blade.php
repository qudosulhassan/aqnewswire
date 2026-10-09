@extends('layouts.admin')

@section('title', 'Newsletter Audience & Subscribers')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Header & Sub-navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Newsletter & Audience Center</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage subscriber lists, automated campaigns, audience segments, and delivery analytics.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.newsletters.campaigns') }}" class="px-3.5 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-xs transition">
                + Create Campaign
            </a>
        </div>
    </div>

    <!-- Sub-navigation Pills -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-2.5 shadow-2xs flex items-center gap-1.5 overflow-x-auto text-xs font-medium">
        <a href="{{ route('admin.newsletters.index') }}" class="px-3.5 py-1.5 rounded-lg bg-slate-900 text-white font-semibold shadow-2xs">
            Subscribers List
        </a>
        <a href="{{ route('admin.newsletters.campaigns') }}" class="px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
            Campaign Broadcasts
        </a>
        <a href="{{ route('admin.newsletters.segments') }}" class="px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
            Audience Segments
        </a>
        <a href="{{ route('admin.newsletters.templates') }}" class="px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
            Email Templates
        </a>
        <a href="{{ route('admin.newsletters.analytics') }}" class="px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
            Delivery Analytics
        </a>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Active Subscribers</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-black text-slate-900">{{ number_format($totalActive) }}</span>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Opt-in</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Total in Database</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-black text-slate-900">{{ number_format($subscribers->total()) }}</span>
                <span class="text-[11px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">All records</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs flex flex-col justify-center">
            <form method="GET" action="{{ route('admin.newsletters.index') }}" class="flex gap-2">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Filter by email address..." 
                       class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400">
                <button type="submit" class="px-3.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl hover:bg-black transition">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Subscribers Table Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="py-3 px-4">Subscriber Email</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Subscribed Date</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subscribers as $subscriber)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <td class="py-3 px-4 font-semibold text-slate-900">
                                {{ $subscriber->email }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($subscriber->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">Active</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-600">Unsubscribed</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-400 whitespace-nowrap">
                                {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('M j, Y H:i') : 'N/A' }}
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap space-x-2">
                                <form action="{{ route('admin.newsletters.toggle', $subscriber) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-700 transition">
                                        {{ $subscriber->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.newsletters.destroy', $subscriber) }}" method="POST" class="inline" onsubmit="return confirm('Remove subscriber permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold text-rose-600 hover:text-rose-800 transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="font-semibold text-slate-600 text-sm">No subscribers in database yet.</p>
                                <p class="text-slate-400 mt-0.5">Audience members subscribing via the public portal will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
