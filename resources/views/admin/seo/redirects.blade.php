@extends('layouts.admin')

@section('title', 'URL Redirect Manager (301 & 302)')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Header & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.seo.index') }}" class="hover:text-slate-600">SEO Center</a>
                <span>&rarr;</span>
                <span class="text-slate-700 font-semibold">Redirects</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>URL Redirect Manager (301 & 302)</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    {{ $redirects->total() }} Rules
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage permanent 301 and temporary 302 redirects with automated loop and chain prevention.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.seo.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                &larr; Back to SEO Center
            </a>
        </div>
    </div>

    <!-- Flash Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1 shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Redirect Validation Notice:</span>
            </div>
            @foreach($errors->all() as $err)
                <p class="text-rose-700 ml-6">&bull; {{ $err }}</p>
            @endforeach
        </div>
    @endif

    <!-- Network Health Alerts: Chains and Loops -->
    @if($auditNetwork['has_anomalies'])
        <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-4 shadow-2xs space-y-2">
            <h4 class="text-xs font-bold text-amber-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Redirect Network Anomalies Detected</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                @foreach($auditNetwork['loops'] as $loop)
                    <div class="p-3 bg-white rounded-xl border border-rose-200 text-xs space-y-1">
                        <span class="font-bold text-rose-700 uppercase text-[10px]">Circular Loop</span>
                        <p class="font-mono text-slate-800 text-[11px]">{{ $loop['trace'] }}</p>
                    </div>
                @endforeach
                @foreach($auditNetwork['chains'] as $chain)
                    <div class="p-3 bg-white rounded-xl border border-amber-200 text-xs space-y-1">
                        <span class="font-bold text-amber-700 uppercase text-[10px]">Multi-Hop Chain (A &rarr; B &rarr; C)</span>
                        <p class="font-mono text-slate-800 text-[11px]">{{ $chain['source'] }} &rarr; {{ $chain['intermediate'] }} &rarr; {{ $chain['ultimate'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Test Redirect Simulator Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Live Redirect Diagnostic Simulator</h3>
            <span class="text-[10px] text-slate-400">Tests request path against active rules</span>
        </div>
        <form action="{{ route('admin.seo.redirects.test') }}" method="POST" class="flex flex-wrap items-center gap-2.5">
            @csrf
            <div class="flex-1 min-w-[260px]">
                <input type="text" 
                       name="test_path" 
                       value="{{ session('test_result.source') ?? '' }}" 
                       required 
                       placeholder="/example-source-path" 
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                Simulate Redirect
            </button>
        </form>

        @if(session('test_result'))
            <div class="mt-2 p-3 rounded-xl border {{ session('test_result.matched') ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-700' }} text-xs font-mono">
                @if(session('test_result.matched'))
                    <span class="font-bold">MATCH FOUND:</span> Path <strong>{{ session('test_result.source') }}</strong> redirects via <strong>HTTP {{ session('test_result.status_code') }}</strong> to <strong>{{ session('test_result.destination') }}</strong> (Total Hits: {{ session('test_result.hits') }}).
                @else
                    <span class="font-bold text-slate-500">NO RULE MATCH:</span> Path <strong>{{ session('test_result.source') }}</strong> does not match any active redirect rules.
                @endif
            </div>
        @endif
    </div>

    <!-- Main Grid: Table (8 cols) + Create Form (4 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left 8 Cols: Redirects Table & Filters -->
        <div class="lg:col-span-8 space-y-4">
            
            <!-- Filters & Search Toolbar -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
                <form action="{{ route('admin.seo.redirects') }}" method="GET" class="flex flex-wrap items-center justify-between gap-3">
                    <div class="relative flex-1 min-w-[180px]">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search source or target..." 
                               class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-red-500">
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <div class="w-32">
                        <select name="status_code" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none">
                            <option value="">All Codes</option>
                            <option value="301" {{ request('status_code') == '301' ? 'selected' : '' }}>301 Permanent</option>
                            <option value="302" {{ request('status_code') == '302' ? 'selected' : '' }}>302 Temporary</option>
                        </select>
                    </div>

                    <div class="w-32">
                        <select name="status" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none">
                            <option value="">All States</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="px-3.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl hover:bg-slate-800 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status_code', 'status']))
                        <a href="{{ route('admin.seo.redirects') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                            <tr>
                                <th class="py-3 px-4">Source Path</th>
                                <th class="py-3 px-4">Destination Target</th>
                                <th class="py-3 px-4">Type</th>
                                <th class="py-3 px-4">State</th>
                                <th class="py-3 px-4 text-center">Hits</th>
                                <th class="py-3 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($redirects as $r)
                                <tr class="hover:bg-slate-50/60 transition {{ !$r->is_active ? 'opacity-60 bg-slate-50/40' : '' }}">
                                    <td class="py-3 px-4 font-mono font-bold text-slate-900 max-w-xs truncate">
                                        {{ $r->source_path }}
                                    </td>

                                    <td class="py-3 px-4 font-mono text-slate-600 max-w-xs truncate" title="{{ $r->destination_url }}">
                                        {{ $r->destination_url }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $r->status_code === 301 ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $r->status_code }} {{ $r->status_code === 301 ? 'Permanent' : 'Temporary' }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <form action="{{ route('admin.seo.redirects.toggle', $r) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase transition {{ $r->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $r->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                <span>{{ $r->is_active ? 'Active' : 'Disabled' }}</span>
                                            </button>
                                        </form>
                                    </td>

                                    <td class="py-3 px-4 text-center font-mono font-semibold text-slate-700 whitespace-nowrap">
                                        {{ number_format($r->hit_count) }}
                                    </td>

                                    <td class="py-3 px-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Edit Modal Trigger -->
                                            <button type="button" 
                                                    onclick="openEditRedirectModal({{ $r->id }}, '{{ addslashes($r->source_path) }}', '{{ addslashes($r->destination_url) }}', {{ $r->status_code }}, {{ $r->is_active ? 'true' : 'false' }})"
                                                    class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                                    title="Edit Rule">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <!-- Delete -->
                                            <form action="{{ route('admin.seo.redirects.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this redirect rule?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Rule">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-500">
                                        No URL redirect rules currently configured.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($redirects->hasPages())
                    <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                        <div class="text-xs text-slate-500">
                            Showing {{ $redirects->firstItem() }} to {{ $redirects->lastItem() }} of {{ $redirects->total() }} rules
                        </div>
                        <div>{{ $redirects->links() }}</div>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right 4 Cols: Create New Redirect Form -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                    + Register New Redirect
                </h3>

                <form action="{{ route('admin.seo.redirects.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Source Path *</label>
                        <input type="text" 
                               name="source_path" 
                               value="{{ old('source_path') }}" 
                               required 
                               placeholder="/legacy-article-path"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                        <span class="text-[10px] text-slate-400 mt-1 block">Relative path starting with a forward slash (/).</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Destination Target *</label>
                        <input type="text" 
                               name="destination_url" 
                               value="{{ old('destination_url') }}" 
                               required 
                               placeholder="/article/new-slug or https://..."
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                        <span class="text-[10px] text-slate-400 mt-1 block">Internal relative path or full external HTTPS URL.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Status Code *</label>
                        <select name="status_code" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-red-500">
                            <option value="301" {{ old('status_code', '301') == '301' ? 'selected' : '' }}>
                                301 — Moved Permanently (Passes PageRank)
                            </option>
                            <option value="302" {{ old('status_code') == '302' ? 'selected' : '' }}>
                                302 — Temporary Found (Preserves Original URL)
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-xs transition transform hover:-translate-y-0.5">
                        Create Redirect Rule &rarr;
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- Edit Redirect Modal (Zero-dependency Vanilla JS, Hidden by Default) -->
    <div id="editRedirectModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div onclick="event.stopPropagation()" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-base">Edit Redirect Rule</h3>
                <button type="button" onclick="closeEditRedirectModal()" class="text-slate-400 hover:text-slate-700 text-sm font-bold">&times;</button>
            </div>

            <form id="editRedirectForm" action="" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Source Path *</label>
                    <input type="text" 
                           name="source_path" 
                           id="modalSourcePath" 
                           required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Destination Target *</label>
                    <input type="text" 
                           name="destination_url" 
                           id="modalDestinationUrl" 
                           required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Status Code *</label>
                    <select name="status_code" id="modalStatusCode" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-red-500">
                        <option value="301">301 — Moved Permanently</option>
                        <option value="302">302 — Temporary Found</option>
                    </select>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                        <input type="checkbox" name="is_active" id="modalIsActive" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                        <span>Rule is active and executing</span>
                    </label>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeEditRedirectModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                        Save Redirect
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Vanilla JavaScript Modal Handlers -->
<script>
    function openEditRedirectModal(id, source, destination, statusCode, isActive) {
        document.getElementById('editRedirectForm').action = '/admin/seo/redirects/' + id;
        document.getElementById('modalSourcePath').value = source;
        document.getElementById('modalDestinationUrl').value = destination;
        document.getElementById('modalStatusCode').value = statusCode;
        document.getElementById('modalIsActive').checked = Boolean(isActive);
        document.getElementById('editRedirectModal').classList.remove('hidden');
    }

    function closeEditRedirectModal() {
        document.getElementById('editRedirectModal').classList.add('hidden');
    }

    document.getElementById('editRedirectModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeEditRedirectModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEditRedirectModal();
    });
</script>
@endsection
