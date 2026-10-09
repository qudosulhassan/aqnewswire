@extends('layouts.admin')

@section('title', 'Audit Logs — CMS')
@section('page_title', 'System Audit Trail & Security Logs')

@section('content')
<div class="space-y-6">

    <!-- Filters & Header -->
    <div class="bg-white border border-stone-200 p-6 rounded shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-stone-900">Platform Activity Records</h3>
            <p class="text-xs text-stone-500 mt-1">Immutable log of editorial actions, approvals, moderation events, and configuration updates.</p>
        </div>
        <form method="GET" action="{{ route('admin.audit.index') }}" class="flex">
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Filter by action keyword..." 
                   class="px-3 py-2 border border-stone-300 rounded-l text-xs focus:outline-none focus:border-stone-900">
            <button type="submit" class="bg-stone-900 text-white px-4 py-2 text-xs font-bold rounded-r uppercase">Filter</button>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white border border-stone-200 rounded shadow-xs overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-stone-50 border-b border-stone-200 uppercase tracking-wider text-stone-600 font-bold">
                    <th class="p-4">Timestamp</th>
                    <th class="p-4">Actor</th>
                    <th class="p-4">Action</th>
                    <th class="p-4">Entity</th>
                    <th class="p-4">Details</th>
                    <th class="p-4">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100 font-sans">
                @forelse($logs as $log)
                    <tr class="hover:bg-stone-50/70 transition">
                        <td class="p-4 text-stone-500 whitespace-nowrap">
                            {{ $log->created_at->format('M d, Y H:i:s') }}
                        </td>
                        <td class="p-4 font-semibold text-stone-900">
                            {{ $log->user ? $log->user->name : 'System / Guest' }}
                        </td>
                        <td class="p-4">
                            <span class="inline-block px-2 py-0.5 bg-stone-100 text-stone-800 rounded font-mono text-[11px] font-semibold">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="p-4 text-stone-600">
                            {{ $log->entity_type }} #{{ $log->entity_id }}
                        </td>
                        <td class="p-4 text-stone-700 max-w-xs truncate" title="{{ $log->details }}">
                            {{ $log->details ?? '—' }}
                        </td>
                        <td class="p-4 font-mono text-stone-400 text-[11px]">
                            {{ $log->ip_address ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-stone-500">
                            No audit log entries recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>

</div>
@endsection
