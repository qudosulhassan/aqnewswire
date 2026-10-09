@extends('layouts.app')

@section('title', 'Following — AQ NEWSWIRE')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="pb-6 border-b border-stone-200 mb-8">
        <a href="{{ route('account.dashboard') }}" class="text-xs text-stone-500 hover:text-stone-900 font-semibold">&larr; Back to Dashboard</a>
        <h1 class="font-headline text-3xl font-bold text-stone-950 mt-1">Authors & Channels You Follow</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($follows as $f)
            @php
                $typeStr = match(class_basename($f->followable_type)) {
                    'User' => 'user',
                    'Category' => 'category',
                    'Tag' => 'topic',
                    default => 'user'
                };
                $subtitle = match(class_basename($f->followable_type)) {
                    'User' => ($f->followable->title ?? 'Author / Contributor'),
                    'Category' => 'Channel / Category',
                    'Tag' => 'Topic / Sector',
                    default => 'Subject'
                };
            @endphp
            <div class="bg-white border border-stone-200 rounded p-6 shadow-xs flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-stone-900 text-sm">
                        {{ $f->followable->name ?? 'Channel / Topic' }}
                    </h3>
                    <p class="text-[11px] text-stone-400">
                        {{ $subtitle }}
                    </p>
                </div>
                <form action="{{ route('follow.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $typeStr }}">
                    <input type="hidden" name="id" value="{{ $f->followable_id }}">
                    <button type="submit" class="px-3 py-1 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded transition">
                        Following ✓
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white border border-stone-200 rounded text-stone-500 text-sm">
                You are not following any authors or channels yet. Follow authors from their articles to receive personalized updates.
            </div>
        @endforelse
    </div>

</div>
@endsection
