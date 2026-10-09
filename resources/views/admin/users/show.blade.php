@extends('layouts.admin')

@section('title', 'User Profile — ' . $user->name)

@section('content')
<div class="max-w-[1400px] mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.users.index') }}" class="hover:text-slate-600">Users & Roles</a>
                <span>&rarr;</span>
                <span class="text-slate-700 font-semibold">{{ $user->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>{{ $user->name }}</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold uppercase border {{ $user->role_badge_class }}">
                    {{ $user->role_name }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                    <span>{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ $user->title ?? 'Account Member' }} &bull; {{ $user->email }}</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                Edit User &rarr;
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                &larr; Back to Users
            </a>
        </div>
    </div>

    <!-- 4 Key Engagement Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Authored Articles -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Articles</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($articlesCount) }}</span>
                <span class="text-xs font-bold text-slate-500">Stories</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Authored editorial content</span>
        </div>

        <!-- Published Articles -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Published</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-emerald-600 tracking-tight">{{ number_format($publishedCount) }}</span>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Live</span>
            </div>
            <span class="text-[10px] text-emerald-600/80 mt-1 block">Live on newsroom site</span>
        </div>

        <!-- Drafts & Submitted -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Pipeline</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-amber-600 tracking-tight">{{ number_format($draftCount + $submittedCount) }}</span>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">{{ $draftCount }} Draft &bull; {{ $submittedCount }} Review</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Pending publication</span>
        </div>

        <!-- Comments & Engagement -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Reader Engagement</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-blue-600 tracking-tight">{{ number_format($commentsCount) }}</span>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ $bookmarksCount }} Saved</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Comments posted & saved stories</span>
        </div>
    </div>

    <!-- Main Profile & Content Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left 4 Cols: Profile Card & Metadata -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                <div class="text-center space-y-3">
                    <img src="{{ $user->avatar_url }}" 
                         alt="{{ $user->name }}" 
                         class="w-20 h-20 rounded-full object-cover mx-auto ring-2 ring-slate-200 bg-slate-100 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 leading-tight">{{ $user->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $user->title ?? 'Staff Member' }}</p>
                    </div>
                </div>

                @if($user->bio)
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 leading-relaxed">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Biography</span>
                        {{ $user->bio }}
                    </div>
                @endif

                <div class="divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-500">Email Address:</span>
                        <span class="font-semibold text-slate-800">{{ $user->email }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-500">Role:</span>
                        <span class="font-semibold text-slate-800">{{ $user->role_name }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-500">Account Status:</span>
                        <span class="font-bold {{ $user->is_active ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-500">Verified Author:</span>
                        <span class="font-semibold text-slate-800">{{ $user->is_verified ? 'Yes ✓' : 'No' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-500">Registered:</span>
                        <span class="font-semibold text-slate-800">{{ $user->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-500">Last Login:</span>
                        <span class="font-semibold text-slate-800">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </span>
                    </div>
                </div>

                @if($user->website || $user->twitter || $user->linkedin)
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-center gap-3 text-xs text-slate-500">
                        @if($user->website)
                            <a href="{{ $user->website }}" target="_blank" class="hover:text-red-600 font-medium">Website &nearr;</a>
                        @endif
                        @if($user->twitter)
                            <span class="text-slate-300">&bull;</span>
                            <span class="font-medium text-slate-700">{{ $user->twitter }}</span>
                        @endif
                        @if($user->linkedin)
                            <span class="text-slate-300">&bull;</span>
                            <span class="font-medium text-slate-700">in/{{ $user->linkedin }}</span>
                        @endif
                    </div>
                @endif
            </div>

        </div>

        <!-- Right 8 Cols: Authored Content & Audit Trail -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Authored Articles Section -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
                <div class="p-4 border-b border-slate-200/90 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Recent Authored Articles</h3>
                        <p class="text-xs text-slate-500">Latest editorial submissions from this journalist/author.</p>
                    </div>
                    @if($articlesCount > 0)
                        <a href="{{ route('admin.articles.index', ['author_id' => $user->id]) }}" class="text-xs font-semibold text-red-600 hover:text-red-700">
                            View all ({{ $articlesCount }}) &rarr;
                        </a>
                    @endif
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentArticles as $art)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition gap-4">
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-red-600 bg-red-50 px-2 py-0.5 rounded">
                                        {{ $art->category?->name ?? 'General' }}
                                    </span>
                                    <span class="text-[11px] font-bold uppercase {{ $art->status === 'published' ? 'text-emerald-600' : 'text-slate-400' }}">
                                        &bull; {{ $art->status }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 hidden sm:inline">
                                        &bull; {{ $art->published_at ? $art->published_at->format('M j, Y') : $art->created_at->format('M j, Y') }}
                                    </span>
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                    <a href="{{ route('admin.articles.edit', $art) }}" class="hover:text-red-600 transition">
                                        {{ $art->title }}
                                    </a>
                                </h4>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs font-semibold text-slate-500">
                                    {{ number_format($art->view_count) }} views
                                </span>
                                <a href="{{ route('admin.articles.edit', $art) }}" class="p-1 text-slate-400 hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-400">
                            No articles authored by this account yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Security & Audit Logs Section -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
                <div class="p-4 border-b border-slate-200/90 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Security & Audit History</h3>
                        <p class="text-xs text-slate-500">Recent actions performed by or targeting this account.</p>
                    </div>
                    <a href="{{ route('admin.audit.index') }}" class="text-xs font-semibold text-red-600 hover:text-red-700">
                        All audit logs &rarr;
                    </a>
                </div>

                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($auditLogs as $log)
                        <div class="p-3.5 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-2 h-2 rounded-full bg-slate-400 shrink-0"></span>
                                <div class="min-w-0">
                                    <span class="font-semibold text-slate-800 block truncate">{{ $log->details ?? $log->action }}</span>
                                    <span class="text-[10px] text-slate-400 block">Action: {{ $log->action }} &bull; IP: {{ $log->ip_address ?? 'Local' }}</span>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400 whitespace-nowrap shrink-0">
                                {{ $log->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-400">
                            No logged audit activity found for this account.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
