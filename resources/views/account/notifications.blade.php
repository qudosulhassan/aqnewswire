@extends('layouts.app')

@section('title', 'Notifications — AQ NEWSWIRE')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="pb-6 border-b border-stone-200 mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('account.dashboard') }}" class="text-xs text-stone-500 hover:text-stone-900 font-semibold">&larr; Back to Dashboard</a>
            <h1 class="font-headline text-3xl font-bold text-stone-950 mt-1">Notifications & Alerts</h1>
        </div>
        @if($notifications->whereNull('read_at')->count() > 0)
            <form action="{{ route('account.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded transition">
                    Mark All as Read ✓
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded text-xs">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-stone-200 rounded divide-y divide-stone-100 overflow-hidden shadow-xs">
        @forelse($notifications as $n)
            <div class="p-5 flex items-start space-x-4 {{ $n->read_at ? 'opacity-70 bg-white' : 'bg-red-50/30' }}">
                <span class="text-xl">🔔</span>
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-sm font-bold text-stone-900">{{ $n->title }}</h3>
                        @if(!$n->read_at)
                            <span class="inline-block w-2 h-2 rounded-full bg-red-600" title="Unread"></span>
                        @endif
                    </div>
                    <p class="text-xs text-stone-600 mt-0.5 leading-relaxed">{{ $n->message }}</p>
                    <span class="text-[10px] text-stone-400 mt-2 block">{{ $n->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex items-center space-x-3 shrink-0">
                    @if($n->action_url)
                        <a href="{{ $n->action_url }}" class="text-xs font-bold text-red-600 hover:underline">View &rarr;</a>
                    @endif
                    @if(!$n->read_at)
                        <form action="{{ route('account.notifications.read', $n) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-[11px] text-stone-500 hover:text-stone-900 underline">
                                Mark read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-stone-500 text-sm">
                No notifications at this time.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>

</div>
@endsection
