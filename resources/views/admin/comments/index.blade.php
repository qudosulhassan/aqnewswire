@extends('layouts.admin')

@section('title', 'Comments & Moderation')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Community Comments Moderation</h1>
            <p class="text-xs text-slate-500 mt-0.5">Enforce newsroom guidelines, address reader flags, and eliminate spam commentary.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 text-xs font-medium">
            <a href="{{ route('admin.comments.index') }}" 
               class="px-3 py-1.5 rounded-lg transition {{ !request('status') ? 'bg-slate-900 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                All Comments
            </a>
            <a href="{{ route('admin.comments.index', ['status' => 'reported']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('status') === 'reported' ? 'bg-rose-600 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Reported Flags
            </a>
            <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('status') === 'approved' ? 'bg-emerald-600 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Approved
            </a>
            <a href="{{ route('admin.comments.index', ['status' => 'spam']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ request('status') === 'spam' ? 'bg-amber-600 text-white font-semibold' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                Spam
            </a>
        </div>
    </div>

    <!-- Comments Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="py-3 px-4">Reader</th>
                        <th class="py-3 px-4">Commentary Content</th>
                        <th class="py-3 px-4">Referenced Article</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Reports</th>
                        <th class="py-3 px-4 text-right">Moderation Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($comments as $c)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- User -->
                            <td class="py-3 px-4">
                                <span class="font-semibold text-slate-900 block leading-tight">{{ $c->user->name ?? 'Anonymous Reader' }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5 block">{{ $c->user->email ?? 'no-email' }}</span>
                            </td>

                            <!-- Content -->
                            <td class="py-3 px-4 max-w-md">
                                <p class="text-slate-700 leading-relaxed">{{ $c->content }}</p>
                            </td>

                            <!-- Article -->
                            <td class="py-3 px-4 max-w-xs">
                                <a href="{{ route('articles.show', $c->article->slug) }}" target="_blank" class="font-semibold text-slate-800 hover:text-red-600 transition line-clamp-1">
                                    {{ $c->article->title }}
                                </a>
                            </td>

                            <!-- Status -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $c->status === 'approved' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : ($c->status === 'reported' ? 'bg-rose-50 text-rose-700 ring-1 ring-rose-200' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $c->status }}
                                </span>
                            </td>

                            <!-- Reports -->
                            <td class="py-3 px-4 font-mono font-semibold {{ $c->reports_count > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                {{ $c->reports_count }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right whitespace-nowrap space-x-2">
                                @if($c->status !== 'approved')
                                    <form action="{{ route('admin.comments.status', $c) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-[11px] transition">Approve</button>
                                    </form>
                                @endif
                                @if($c->status !== 'spam')
                                    <form action="{{ route('admin.comments.status', $c) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="spam">
                                        <button type="submit" class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg text-[11px] transition">Spam</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.comments.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Delete this comment permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 font-semibold rounded-lg text-[11px] transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <p class="font-semibold text-slate-600 text-sm">No comments require moderation.</p>
                                <p class="text-slate-400 mt-0.5">Reader commentary submitted on articles will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($comments->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $comments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
