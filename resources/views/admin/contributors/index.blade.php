@extends('layouts.admin')

@section('title', 'Contributors & Writers Network')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto pb-12">

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Header & Action Toolbar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                <span>Editorial Operations</span>
                <span>/</span>
                <span class="text-red-600">Contributors & Network</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Executive Contributor Management</h1>
            <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                Review credentialed applications, journalistic portfolios, and manage accredited guest writers publishing across AQ NEWSWIRE channels.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.contributors.export', ['status' => $status]) }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200/80 text-slate-700 text-xs font-semibold transition border border-slate-200/80">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export CSV</span>
            </a>
            <a href="{{ route('contributor.apply') }}" target="_blank"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>Public Application Form</span>
            </a>
        </div>
    </div>

    <!-- Real Database KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5">
        <!-- Total Applications -->
        <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'all']) }}" 
           class="bg-white p-4 rounded-xl border {{ $activeTab === 'applications' && $status === 'all' ? 'border-slate-800 ring-1 ring-slate-800' : 'border-slate-200/90' }} hover:border-slate-300 transition shadow-2xs block group">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Applications</span>
                <span class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-slate-200 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($kpis['total_applications']) }}</div>
            <p class="text-[10px] text-slate-400 mt-1">All-time candidate submissions</p>
        </a>

        <!-- Pending Review -->
        <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'pending']) }}" 
           class="bg-white p-4 rounded-xl border {{ $activeTab === 'applications' && $status === 'pending' ? 'border-amber-500 ring-1 ring-amber-500' : 'border-slate-200/90' }} hover:border-amber-300 transition shadow-2xs block group">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Pending Review</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-amber-600 tracking-tight">{{ number_format($kpis['pending']) }}</div>
            <p class="text-[10px] text-amber-600/80 mt-1">Awaiting initial triage</p>
        </a>

        <!-- Under Review -->
        <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'under_review']) }}" 
           class="bg-white p-4 rounded-xl border {{ $activeTab === 'applications' && $status === 'under_review' ? 'border-blue-500 ring-1 ring-blue-500' : 'border-slate-200/90' }} hover:border-blue-300 transition shadow-2xs block group">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">Under Review</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-blue-600 tracking-tight">{{ number_format($kpis['under_review']) }}</div>
            <p class="text-[10px] text-blue-600/80 mt-1">In editorial deliberation</p>
        </a>

        <!-- Approved -->
        <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'approved']) }}" 
           class="bg-white p-4 rounded-xl border {{ $activeTab === 'applications' && $status === 'approved' ? 'border-emerald-500 ring-1 ring-emerald-500' : 'border-slate-200/90' }} hover:border-emerald-300 transition shadow-2xs block group">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Approved</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-emerald-600 tracking-tight">{{ number_format($kpis['approved']) }}</div>
            <p class="text-[10px] text-emerald-600/80 mt-1">Accepted contributor dossiers</p>
        </a>

        <!-- Active Accredited Contributors -->
        <a href="{{ route('admin.contributors.index', ['tab' => 'contributors']) }}" 
           class="bg-white p-4 rounded-xl border {{ $activeTab === 'contributors' ? 'border-indigo-600 ring-1 ring-indigo-600' : 'border-slate-200/90' }} hover:border-indigo-300 transition shadow-2xs block group col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">Active Contributors</span>
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-indigo-600 tracking-tight">{{ number_format($kpis['active_contributors']) }}</div>
            <p class="text-[10px] text-indigo-600/80 mt-1">Accredited writers network</p>
        </a>
    </div>

    <!-- Navigation Tabs & Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-4">
        
        <!-- Primary Tab Toggle -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'all']) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab !== 'contributors' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>Candidate Applications</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $activeTab !== 'contributors' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200 text-slate-700' }}">
                        {{ $kpis['total_applications'] }}
                    </span>
                </a>
                <a href="{{ route('admin.contributors.index', ['tab' => 'contributors']) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab === 'contributors' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>Accredited Writers Network</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $activeTab === 'contributors' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200 text-slate-700' }}">
                        {{ $kpis['active_contributors'] }}
                    </span>
                </a>
            </div>

            @if($activeTab !== 'contributors')
                <!-- Status Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-medium py-1">
                    <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'all', 'search' => $search, 'expertise' => $expertise, 'sort' => $sort]) }}"
                       class="px-2.5 py-1 rounded-lg transition {{ $status === 'all' ? 'bg-slate-200 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-100' }}">
                        All ({{ $kpis['total_applications'] }})
                    </a>
                    <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'pending', 'search' => $search, 'expertise' => $expertise, 'sort' => $sort]) }}"
                       class="px-2.5 py-1 rounded-lg transition {{ $status === 'pending' ? 'bg-amber-100 text-amber-900 font-bold' : 'text-slate-500 hover:bg-slate-100' }}">
                        Pending ({{ $kpis['pending'] }})
                    </a>
                    <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'under_review', 'search' => $search, 'expertise' => $expertise, 'sort' => $sort]) }}"
                       class="px-2.5 py-1 rounded-lg transition {{ $status === 'under_review' ? 'bg-blue-100 text-blue-900 font-bold' : 'text-slate-500 hover:bg-slate-100' }}">
                        Under Review ({{ $kpis['under_review'] }})
                    </a>
                    <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'changes_requested', 'search' => $search, 'expertise' => $expertise, 'sort' => $sort]) }}"
                       class="px-2.5 py-1 rounded-lg transition {{ $status === 'changes_requested' ? 'bg-purple-100 text-purple-900 font-bold' : 'text-slate-500 hover:bg-slate-100' }}">
                        Changes Requested ({{ $kpis['changes_requested'] }})
                    </a>
                    <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'approved', 'search' => $search, 'expertise' => $expertise, 'sort' => $sort]) }}"
                       class="px-2.5 py-1 rounded-lg transition {{ $status === 'approved' ? 'bg-emerald-100 text-emerald-900 font-bold' : 'text-slate-500 hover:bg-slate-100' }}">
                        Approved ({{ $kpis['approved'] }})
                    </a>
                    <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'rejected', 'search' => $search, 'expertise' => $expertise, 'sort' => $sort]) }}"
                       class="px-2.5 py-1 rounded-lg transition {{ $status === 'rejected' ? 'bg-rose-100 text-rose-900 font-bold' : 'text-slate-500 hover:bg-slate-100' }}">
                        Rejected ({{ $kpis['rejected'] }})
                    </a>
                </div>
            @endif
        </div>

        <!-- Search, Filter & Sort Form -->
        <form method="GET" action="{{ route('admin.contributors.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            @if($activeTab !== 'contributors')
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            <!-- Search Field -->
            <div class="lg:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="{{ $activeTab === 'contributors' ? 'Search active contributors by name, email, title...' : 'Search applications by name, email, pitch, domain...' }}"
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
            </div>

            @if($activeTab !== 'contributors')
                <!-- Expertise / Domain Filter -->
                <div class="lg:col-span-3">
                    <select name="expertise" 
                            class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                        <option value="all">All Domains / Expertise</option>
                        @foreach($expertiseList as $exp)
                            <option value="{{ $exp }}" {{ $expertise === $exp ? 'selected' : '' }}>{{ $exp }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Sort By -->
            <div class="{{ $activeTab === 'contributors' ? 'lg:col-span-5' : 'lg:col-span-2' }}">
                <select name="sort" 
                        class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                </select>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" 
                        class="flex-1 py-2 px-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition text-center shadow-xs">
                    Filter
                </button>
                @if(!empty($search) || $expertise !== 'all' || $sort !== 'newest')
                    <a href="{{ route('admin.contributors.index', ['tab' => $activeTab, 'status' => $status]) }}" 
                       class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition" title="Clear Filters">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Active Tab Content -->
    @if($activeTab !== 'contributors')
        <!-- APPLICATIONS TABLE -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="py-3.5 px-4">Applicant</th>
                            <th class="py-3.5 px-4">Expertise / Domain</th>
                            <th class="py-3.5 px-4">Pitch & Background</th>
                            <th class="py-3.5 px-4">Portfolio & Links</th>
                            <th class="py-3.5 px-4">Review Status</th>
                            <th class="py-3.5 px-4">Applied</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($applications as $app)
                            <tr class="hover:bg-slate-50/70 transition group">
                                <!-- Applicant Info -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-bold flex items-center justify-center shrink-0 shadow-2xs text-xs">
                                            {{ strtoupper(substr($app->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <button type="button" 
                                                        onclick="openDossierModal({{ $app->id }})" 
                                                        class="font-bold text-slate-900 hover:text-red-600 transition text-left leading-tight">
                                                    {{ $app->name }}
                                                </button>
                                                @if($app->user_id)
                                                    <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-bold bg-slate-100 text-slate-600" title="Registered User ID #{{ $app->user_id }}">
                                                        User #{{ $app->user_id }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] text-slate-500 mt-0.5 block font-mono">{{ $app->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Expertise -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-800 border border-slate-200/60">
                                        {{ $app->expertise ?? 'General Columnist' }}
                                    </span>
                                </td>

                                <!-- Pitch / Bio Preview -->
                                <td class="py-3.5 px-4 max-w-xs">
                                    <div class="cursor-pointer group-hover:text-slate-900 transition" onclick="openDossierModal({{ $app->id }})">
                                        @if(!empty($app->message))
                                            <p class="text-slate-700 font-medium line-clamp-1 text-[11px]">{{ $app->message }}</p>
                                        @endif
                                        <p class="text-slate-500 line-clamp-1 text-[11px] mt-0.5">{{ $app->bio }}</p>
                                    </div>
                                </td>

                                <!-- Portfolio Links -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        @if($app->website)
                                            <a href="{{ $app->website }}" target="_blank" rel="noopener noreferrer" 
                                               class="p-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Website: {{ $app->website }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                            </a>
                                        @endif
                                        @if($app->social_links)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 truncate max-w-[120px]" title="{{ $app->social_links }}">
                                                {{ Str::limit($app->social_links, 18) }}
                                            </span>
                                        @endif
                                        @if($app->portfolio)
                                            <button type="button" onclick="openDossierModal({{ $app->id }})" 
                                                    class="text-[10px] text-blue-600 hover:underline font-semibold" title="View Portfolio Clips">
                                                Clips Attached
                                            </button>
                                        @endif
                                        @if(!$app->website && !$app->social_links && !$app->portfolio)
                                            <span class="text-slate-400 text-[10px] italic">None provided</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($app->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Approved
                                        </span>
                                    @elseif($app->status === 'under_review')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 ring-1 ring-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            Under Review
                                        </span>
                                    @elseif($app->status === 'changes_requested')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-purple-700 ring-1 ring-purple-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            Changes Requested
                                        </span>
                                    @elseif($app->status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 ring-1 ring-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 ring-1 ring-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <!-- Applied Date -->
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                    <span class="font-medium text-slate-800 block">{{ $app->created_at->format('M j, Y') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $app->created_at->diffForHumans() }}</span>
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Quick Review Drawer Trigger -->
                                        <button type="button" 
                                                onclick="openDossierModal({{ $app->id }})"
                                                class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-[11px] transition inline-flex items-center gap-1"
                                                title="View Candidate Dossier">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Review</span>
                                        </button>

                                        @if($app->status !== 'approved')
                                            <!-- 1-Click Approve Form -->
                                            <form action="{{ route('admin.contributors.approve', $app) }}" method="POST" class="inline" onsubmit="return confirm('Approve {{ addslashes($app->name) }} as an accredited AQ NEWSWIRE Contributor?');">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-[11px] transition shadow-2xs"
                                                        title="Approve Application">
                                                    Approve
                                                </button>
                                            </form>
                                        @endif

                                        @if($app->status === 'pending')
                                            <!-- 1-Click Move to Review -->
                                            <form action="{{ route('admin.contributors.review', $app) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-2 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-lg text-[11px] transition"
                                                        title="Mark Under Review">
                                                    Reviewing
                                                </button>
                                            </form>
                                        @endif

                                        @if($app->status !== 'rejected')
                                            <!-- 1-Click Reject Form -->
                                            <form action="{{ route('admin.contributors.reject', $app) }}" method="POST" class="inline" onsubmit="return confirm('Decline this contributor application?');">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-2 py-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg text-[11px] font-medium transition"
                                                        title="Reject Application">
                                                    Reject
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-16 px-4">
                                    <div class="max-w-md mx-auto text-center">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3.5 shadow-2xs">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-800">
                                            @if(!empty($search) || $status !== 'all' || $expertise !== 'all')
                                                No Applications Match Current Filters
                                            @else
                                                No Contributor Applications Pending
                                            @endif
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                                            @if(!empty($search) || $status !== 'all' || $expertise !== 'all')
                                                Try clearing your search query or switching filter tabs to locate candidate records.
                                            @else
                                                New guest writer submissions and portfolio applications submitted via the public site will appear here for editorial review.
                                            @endif
                                        </p>
                                        @if(!empty($search) || $status !== 'all' || $expertise !== 'all')
                                            <div class="mt-4">
                                                <a href="{{ route('admin.contributors.index', ['tab' => 'applications', 'status' => 'all']) }}" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                                    <span>Clear All Filters</span>
                                                </a>
                                            </div>
                                        @else
                                            <div class="mt-4 flex items-center justify-center gap-2">
                                                <a href="{{ route('contributor.apply') }}" target="_blank" 
                                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold transition shadow-xs">
                                                    <span>Open Application Form</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($applications->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>

    @else
        <!-- ACCREDITED CONTRIBUTORS NETWORK TABLE -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Accredited AQ NEWSWIRE Contributors</h2>
                    <p class="text-[11px] text-slate-500">Active users holding the Contributor credential with story drafting and submission privileges.</p>
                </div>
                <div class="text-xs font-semibold text-slate-600 bg-white px-3 py-1.5 rounded-lg border border-slate-200/80 shadow-2xs">
                    {{ number_format($kpis['active_contributors']) }} Active Contributors
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="py-3.5 px-4">Contributor</th>
                            <th class="py-3.5 px-4">Channel / Byline</th>
                            <th class="py-3.5 px-4 text-center">Published Stories</th>
                            <th class="py-3.5 px-4 text-center">Drafts</th>
                            <th class="py-3.5 px-4 text-center">Total Readership</th>
                            <th class="py-3.5 px-4">Member Since</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($activeContributors as $contrib)
                            <tr class="hover:bg-slate-50/70 transition">
                                <!-- Contributor Profile -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl overflow-hidden bg-slate-900 text-white font-bold flex items-center justify-center shrink-0 shadow-2xs">
                                            @if($contrib->avatar)
                                                <img src="{{ $contrib->avatar_url }}" alt="{{ $contrib->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xs">{{ strtoupper(substr($contrib->name, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-slate-900 leading-tight">{{ $contrib->name }}</span>
                                                @if($contrib->is_verified)
                                                    <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Verified Contributor">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                                @if(!$contrib->is_active)
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-rose-100 text-rose-700">Deactivated</span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] text-slate-500 font-mono mt-0.5 block">{{ $contrib->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Title / Byline -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-medium text-slate-700 block">{{ $contrib->title ?? 'AQ NEWSWIRE Contributor' }}</span>
                                    @if($contrib->website)
                                        <a href="{{ $contrib->website }}" target="_blank" rel="noopener noreferrer" class="text-[10px] text-blue-600 hover:underline">
                                            {{ Str::limit($contrib->website, 24) }}
                                        </a>
                                    @endif
                                </td>

                                <!-- Published Stories -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200">
                                        {{ number_format($contrib->published_articles_count ?? 0) }}
                                    </span>
                                </td>

                                <!-- Drafts -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 ring-1 ring-amber-200">
                                        {{ number_format($contrib->draft_articles_count ?? 0) }}
                                    </span>
                                </td>

                                <!-- Total Views -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="font-bold text-slate-900 text-xs">
                                        {{ number_format($contrib->total_views ?? 0) }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block">reads</span>
                                </td>

                                <!-- Member Since -->
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                    <span class="font-medium text-slate-800 block">{{ $contrib->created_at->format('M j, Y') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $contrib->created_at->diffForHumans() }}</span>
                                </td>

                                <!-- Actions -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if(!empty($contrib->slug))
                                            <a href="{{ route('authors.show', $contrib->slug) }}" target="_blank"
                                               class="p-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition" title="View Public Author Byline">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.articles.index', ['author' => $contrib->id]) }}"
                                           class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-[11px] transition" title="View Articles in CMS">
                                            Stories
                                        </a>
                                        <a href="{{ route('admin.users.edit', $contrib) }}"
                                           class="px-2.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg text-[11px] transition shadow-2xs" title="Manage User Account">
                                            Edit Role
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-16 px-4">
                                    <div class="max-w-md mx-auto text-center">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3.5">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        </div>
                                        <h3 class="text-base font-bold text-slate-800">No Accredited Contributors Found</h3>
                                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                                            When applications are approved, candidates receive the Contributor role and appear in this roster.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($activeContributors->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $activeContributors->links() }}
                </div>
            @endif
        </div>
    @endif

</div>

<!-- Vanilla JS Contributor Application Dossier & Editorial Decision Drawer / Modal -->
<div id="contributorDossierModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Dark Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeDossierModal()"></div>

    <!-- Modal Dialog Container -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-2xl border border-slate-200">
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div id="dossierAvatar" class="w-10 h-10 rounded-xl bg-slate-900 text-white font-bold flex items-center justify-center shrink-0 shadow-2xs text-sm">
                        ?
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 id="dossierName" class="text-lg font-bold text-slate-900 leading-tight">Applicant Dossier</h3>
                            <span id="dossierStatusBadge" class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                Pending
                            </span>
                        </div>
                        <p id="dossierEmail" class="text-xs text-slate-500 font-mono mt-0.5">email@example.com</p>
                    </div>
                </div>
                <button type="button" onclick="closeDossierModal()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5 text-xs max-h-[70vh] overflow-y-auto">
                
                <!-- Quick Metadata Row -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Domain / Field</span>
                        <span id="dossierExpertise" class="font-semibold text-slate-800 mt-0.5 block">—</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Applied Date</span>
                        <span id="dossierAppliedAt" class="font-medium text-slate-700 mt-0.5 block">—</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Account Linkage</span>
                        <span id="dossierUserStatus" class="font-medium text-slate-700 mt-0.5 block">—</span>
                    </div>
                </div>

                <!-- Professional Bio -->
                <div>
                    <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Candidate Professional Bio & Credentials</h4>
                    <div id="dossierBio" class="p-3.5 bg-white border border-slate-200/80 rounded-xl text-slate-700 leading-relaxed whitespace-pre-line text-xs">
                        —
                    </div>
                </div>

                <!-- Editorial Pitch & Message -->
                <div>
                    <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Editorial Pitch & Proposed Coverage Focus</h4>
                    <div id="dossierMessage" class="p-3.5 bg-white border border-slate-200/80 rounded-xl text-slate-700 leading-relaxed whitespace-pre-line text-xs">
                        —
                    </div>
                </div>

                <!-- Portfolio Clips & Online Presence -->
                <div class="space-y-3">
                    <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Portfolio Clips & Verification Links</h4>
                    
                    <div id="dossierPortfolioBox" class="p-3.5 bg-white border border-slate-200/80 rounded-xl space-y-2">
                        <div class="text-slate-600 whitespace-pre-line text-xs" id="dossierPortfolio">None specified</div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-2">
                            <span class="text-slate-400 font-bold text-[10px] uppercase">Website:</span>
                            <span id="dossierWebsite" class="truncate font-medium text-blue-600">—</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-2">
                            <span class="text-slate-400 font-bold text-[10px] uppercase">Social:</span>
                            <span id="dossierSocial" class="truncate font-medium text-slate-700">—</span>
                        </div>
                    </div>
                </div>

                <!-- Reviewer History (if reviewed) -->
                <div id="dossierReviewHistory" class="hidden p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Editorial Review History</span>
                    <p id="dossierReviewText" class="text-xs text-slate-700 mt-1">—</p>
                </div>

                <!-- Editorial Review Notes & Decision Form -->
                <div class="border-t border-slate-100 pt-4">
                    <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-2">Editorial Review Decision & Notes</h4>
                    <form id="dossierActionForm" method="POST" action="">
                        @csrf
                        <textarea name="admin_notes" id="dossierNotesInput" rows="3" 
                                  placeholder="Enter editorial feedback, internal evaluation notes, or reason for rejection/clarification..."
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition"></textarea>

                        <div class="mt-4 flex flex-wrap items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="submitDossierAction('approve')" 
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-xs">
                                    Approve Contributor
                                </button>
                                <button type="button" onclick="submitDossierAction('review')" 
                                        class="px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-xl text-xs transition">
                                    Place Under Review
                                </button>
                                <button type="button" onclick="submitDossierAction('request-changes')" 
                                        class="px-3 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 font-semibold rounded-xl text-xs transition">
                                    Request Changes
                                </button>
                            </div>
                            <div>
                                <button type="button" onclick="submitDossierAction('reject')" 
                                        class="px-3 py-2 text-rose-600 hover:bg-rose-50 font-semibold rounded-xl text-xs transition">
                                    Decline / Reject
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    let currentApplicationId = null;

    function openDossierModal(appId) {
        currentApplicationId = appId;
        const modal = document.getElementById('contributorDossierModal');
        modal.classList.remove('hidden');

        // Fetch application details via JSON
        fetch("{{ url('admin/contributors') }}/" + appId, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.application) {
                const app = data.application;
                document.getElementById('dossierName').textContent = app.name;
                document.getElementById('dossierEmail').textContent = app.email;
                document.getElementById('dossierAvatar').textContent = app.name.charAt(0).toUpperCase();
                
                // Status badge
                const statusBadge = document.getElementById('dossierStatusBadge');
                statusBadge.textContent = app.status_label;
                statusBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ' + getStatusBadgeClass(app.status);

                document.getElementById('dossierExpertise').textContent = app.expertise || 'General Columnist';
                document.getElementById('dossierAppliedAt').textContent = app.applied_at;
                document.getElementById('dossierUserStatus').textContent = app.has_user_account ? ('Registered User (#' + app.user.id + ')') : 'Guest / No Account Yet';
                document.getElementById('dossierBio').textContent = app.bio || 'No bio provided.';
                document.getElementById('dossierMessage').textContent = app.message || 'No pitch statement provided.';
                
                // Portfolio links
                const portfolioEl = document.getElementById('dossierPortfolio');
                if (app.portfolio) {
                    portfolioEl.innerHTML = formatUrls(app.portfolio);
                } else {
                    portfolioEl.textContent = 'No portfolio clips attached.';
                }

                // Website
                const websiteEl = document.getElementById('dossierWebsite');
                if (app.website) {
                    websiteEl.innerHTML = '<a href="' + app.website + '" target="_blank" class="hover:underline">' + app.website + '</a>';
                } else {
                    websiteEl.textContent = 'None';
                }

                // Social
                document.getElementById('dossierSocial').textContent = app.social_links || 'None';

                // Review history
                const reviewBox = document.getElementById('dossierReviewHistory');
                if (app.reviewed_at) {
                    reviewBox.classList.remove('hidden');
                    let reviewText = 'Reviewed on ' + app.reviewed_at;
                    if (app.reviewer_name) {
                        reviewText += ' by ' + app.reviewer_name;
                    }
                    if (app.admin_notes) {
                        reviewText += ' — Notes: ' + app.admin_notes;
                    }
                    document.getElementById('dossierReviewText').textContent = reviewText;
                } else {
                    reviewBox.classList.add('hidden');
                }

                // Prepopulate notes if any
                document.getElementById('dossierNotesInput').value = app.admin_notes || '';
            }
        })
        .catch(err => {
            console.error('Error fetching dossier:', err);
        });
    }

    function closeDossierModal() {
        const modal = document.getElementById('contributorDossierModal');
        modal.classList.add('hidden');
        currentApplicationId = null;
    }

    function getStatusBadgeClass(status) {
        switch(status) {
            case 'approved':
                return 'bg-emerald-100 text-emerald-800';
            case 'under_review':
                return 'bg-blue-100 text-blue-800';
            case 'changes_requested':
                return 'bg-purple-100 text-purple-800';
            case 'rejected':
                return 'bg-rose-100 text-rose-800';
            default:
                return 'bg-amber-100 text-amber-800';
        }
    }

    function formatUrls(text) {
        if (!text) return '';
        const urlPattern = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
        return text.replace(urlPattern, '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-all">$1</a>');
    }

    function submitDossierAction(action) {
        if (!currentApplicationId) return;

        const form = document.getElementById('dossierActionForm');
        const notes = document.getElementById('dossierNotesInput').value.trim();

        if (action === 'request-changes' && !notes) {
            alert('Please provide notes explaining what changes or clips are requested.');
            document.getElementById('dossierNotesInput').focus();
            return;
        }

        if (action === 'approve') {
            if (!confirm('Approve this candidate as an AQ NEWSWIRE Contributor?')) return;
            form.action = "{{ url('admin/contributors') }}/" + currentApplicationId + "/approve";
        } else if (action === 'review') {
            form.action = "{{ url('admin/contributors') }}/" + currentApplicationId + "/review";
        } else if (action === 'request-changes') {
            form.action = "{{ url('admin/contributors') }}/" + currentApplicationId + "/request-changes";
        } else if (action === 'reject') {
            if (!confirm('Reject this contributor application?')) return;
            form.action = "{{ url('admin/contributors') }}/" + currentApplicationId + "/reject";
        }

        form.submit();
    }

    // Escape key listener to close modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDossierModal();
        }
    });
</script>
@endsection
