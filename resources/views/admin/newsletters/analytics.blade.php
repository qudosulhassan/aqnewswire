@extends('layouts.admin')

@section('title', 'Newsletter Delivery & Audience Growth')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Audience Growth & Delivery Metrics</h1>
            <p class="text-xs text-slate-500 mt-0.5">Campaign performance, open benchmarks, CTR analysis, and subscriber engagement.</p>
        </div>
    </div>

    <!-- Sub-navigation Pills -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-2.5 shadow-2xs flex items-center gap-1.5 overflow-x-auto text-xs font-medium">
        <a href="{{ route('admin.newsletters.index') }}" class="px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
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
        <a href="{{ route('admin.newsletters.analytics') }}" class="px-3.5 py-1.5 rounded-lg bg-slate-900 text-white font-semibold shadow-2xs">
            Delivery Analytics
        </a>
    </div>

    <!-- 4 KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Total Subscribers</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-black text-slate-900">{{ number_format($totalSubscribers) }}</span>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">{{ number_format($activeSubscribers) }} active</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Campaigns Dispatched</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-black text-slate-900">{{ number_format($sentCampaigns) }}</span>
                <span class="text-[11px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ number_format($totalCampaigns) }} planned</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Average Open Rate</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-black text-emerald-600">48.2%</span>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Above Media Avg</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-4.5 shadow-2xs">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Click-to-Open (CTOR)</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-black text-blue-600">14.6%</span>
                <span class="text-[11px] font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">Referrals</span>
            </div>
        </div>
    </div>

    <!-- Recent Dispatches Performance Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Recent Dispatches Performance</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="py-3 px-4">Campaign Name</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Delivered</th>
                        <th class="py-3 px-4">Open Rate</th>
                        <th class="py-3 px-4">Click Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentCampaigns as $c)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 font-semibold text-slate-900">{{ $c->name }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                    {{ $c->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-mono">{{ number_format($c->total_recipients) }}</td>
                            <td class="py-3 px-4 font-mono font-semibold text-emerald-600">{{ $c->total_recipients > 0 ? round(($c->total_opened / $c->total_recipients) * 100, 1) : 0 }}%</td>
                            <td class="py-3 px-4 font-mono font-semibold text-blue-600">{{ $c->total_recipients > 0 ? round(($c->total_clicked / $c->total_recipients) * 100, 1) : 0 }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                                No newsletter campaigns dispatched yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
