@extends('layouts.admin')

@section('title', 'Users & Roles')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto">

    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Users & Roles</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    {{ number_format($kpis['total']) }} Accounts
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage administrators, editors, contributors, writers and subscribers.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-xs transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Add User</span>
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

    @if(session('error') || $errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1 shadow-2xs">
            @if(session('error'))
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @foreach($errors->all() as $err)
                <p class="text-rose-700 ml-6">&bull; {{ $err }}</p>
            @endforeach
        </div>
    @endif

    <!-- 6 Live Database-Driven KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
        <!-- Total Users -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Users</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($kpis['total']) }}</span>
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">All registered accounts</span>
        </div>

        <!-- Active Users -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Active Users</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-emerald-600 tracking-tight">{{ number_format($kpis['active']) }}</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
            <span class="text-[10px] text-emerald-600/80 mt-1 block">Live verified status</span>
        </div>

        <!-- Administrators -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Administrators</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-red-600 tracking-tight">{{ number_format($kpis['admins']) }}</span>
                <span class="text-[10px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded">RBAC</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Super & Core Admins</span>
        </div>

        <!-- Editors -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Editors</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-blue-600 tracking-tight">{{ number_format($kpis['editors']) }}</span>
                <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded">CMS</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Editorial leadership</span>
        </div>

        <!-- Contributors & Writers -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Contributors</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-amber-600 tracking-tight">{{ number_format($kpis['contributors']) }}</span>
                <span class="text-[10px] font-bold text-amber-500 bg-amber-50 px-1.5 py-0.5 rounded">Authors</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Staff writers & guests</span>
        </div>

        <!-- Subscribers -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Subscribers</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-700 tracking-tight">{{ number_format($kpis['subscribers']) }}</span>
                <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">Audience</span>
            </div>
            <span class="text-[10px] text-slate-500 mt-1 block">Executive readers</span>
        </div>
    </div>

    <!-- Search, Filters, Sorting & Bulk Toolbar Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs space-y-3">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center justify-between gap-3">
            <!-- Search Input -->
            <div class="relative flex-1 min-w-[240px]">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by name, email, or professional title..." 
                       class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">
            </div>

            <!-- Role Filter -->
            <div class="w-40">
                <select name="role" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-red-500">
                    <option value="">All Roles</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="w-32">
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-red-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Sort Option -->
            <div class="w-36">
                <select name="sort" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-red-500">
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest Created</option>
                    <option value="oldest" {{ request('sort', 'oldest') === 'oldest' ? 'selected' : '' }}>Oldest Created</option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                    <option value="last_login" {{ request('sort') === 'last_login' ? 'selected' : '' }}>Last Login</option>
                    <option value="articles" {{ request('sort') === 'articles' ? 'selected' : '' }}>Most Articles</option>
                </select>
            </div>

            <!-- Per Page -->
            <div class="w-24">
                <select name="per_page" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-red-500">
                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 / page</option>
                    <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 / page</option>
                    <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100 / page</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role', 'status', 'sort', 'per_page', 'date']))
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Bulk Action Controls Bar (Hidden by default, shown when items are selected) -->
        <div id="bulkActionsBar" class="hidden pt-3 border-t border-slate-100 flex items-center justify-between bg-slate-50/80 -mx-4 -mb-4 p-3 rounded-b-2xl">
            <div class="text-xs text-slate-700 font-medium">
                <span class="font-bold text-red-600" id="selectedUsersCount">0</span> accounts selected
            </div>
            <form action="{{ route('admin.users.bulk') }}" method="POST" id="bulkActionsForm" class="flex items-center gap-2">
                @csrf
                <div id="bulkUserInputs"></div>
                <button type="submit" name="action" value="activate" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-2xs">
                    Bulk Activate
                </button>
                <button type="submit" name="action" value="deactivate" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-800 text-white text-xs font-semibold rounded-lg transition shadow-2xs">
                    Bulk Deactivate
                </button>
            </form>
        </div>
    </div>

    <!-- Responsive Users Table Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-400 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="py-3 px-4 w-10">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                        </th>
                        <th class="py-3 px-4">User</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Articles</th>
                        <th class="py-3 px-4">Last Login</th>
                        <th class="py-3 px-4">Created</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition group {{ !$user->is_active ? 'bg-slate-50/40 opacity-75' : '' }}">
                            <!-- Select Checkbox -->
                            <td class="py-3 px-4">
                                <input type="checkbox" 
                                       value="{{ $user->id }}" 
                                       class="user-row-checkbox rounded border-slate-300 text-red-600 focus:ring-red-500"
                                       onchange="handleUserCheckboxChange()">
                            </td>

                            <!-- User Info -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar_url }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-9 h-9 rounded-full object-cover shrink-0 ring-1 ring-slate-200 bg-slate-100">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('admin.users.show', $user) }}" class="font-bold text-slate-900 hover:text-red-600 transition truncate block">
                                                {{ $user->name }}
                                            </a>
                                            @if($user->id === Auth::id())
                                                <span class="text-[9px] font-bold bg-slate-900 text-white px-1.5 py-0.2 rounded-md">YOU</span>
                                            @endif
                                            @if($user->is_verified)
                                                <span class="text-blue-500 text-[11px]" title="Verified Staff Author">✓</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] text-slate-400 block truncate">{{ $user->email }}</span>
                                        @if($user->title)
                                            <span class="text-[10px] text-slate-500 font-medium block truncate">{{ $user->title }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Role Pill -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $user->role_badge_class }}">
                                    {{ $user->role_name }}
                                </span>
                            </td>

                            <!-- Status Pill -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase transition {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}"
                                            title="Click to toggle account status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        <span>{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Articles Count -->
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                @if($user->articles_count > 0)
                                    <a href="{{ route('admin.articles.index', ['author_id' => $user->id]) }}" 
                                       class="inline-flex items-center gap-1 font-bold text-slate-700 bg-slate-100 hover:bg-red-50 hover:text-red-600 px-2.5 py-0.5 rounded-full transition">
                                        <span>{{ $user->articles_count }}</span>
                                        <span class="text-[10px] text-slate-400">&rarr;</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[11px]">—</span>
                                @endif
                            </td>

                            <!-- Last Login -->
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap text-[11px]">
                                @if($user->last_login_at)
                                    <span title="{{ $user->last_login_at->format('M j, Y g:i A') }}">
                                        {{ $user->last_login_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-slate-400">Never</span>
                                @endif
                            </td>

                            <!-- Created Date -->
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap text-[11px]">
                                {{ $user->created_at->format('M j, Y') }}
                            </td>

                            <!-- Actions Row -->
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Profile -->
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition"
                                       title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <!-- Edit User -->
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                       title="Edit User & Role">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Reset Password Modal Trigger -->
                                    <button type="button" 
                                            onclick="openResetPasswordModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                            class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                            title="Reset Password">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                        </svg>
                                    </button>

                                    <!-- Delete User Modal Trigger -->
                                    @if($user->id !== Auth::id())
                                        <button type="button" 
                                                onclick="openDeleteUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', {{ $user->articles_count }})"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                                title="Delete User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-12 text-center">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900">No users found</h3>
                                    <p class="text-xs text-slate-500">
                                        No account records matched your search or filtering criteria. Clear filters or add a new user.
                                    </p>
                                    <div class="pt-2 flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl text-xs transition">
                                            + Add User
                                        </a>
                                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition">
                                            Reset Filters
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal 1: Reset Password (Vanilla JS / Hidden by default) -->
    <div id="resetPasswordModal" 
         class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div onclick="event.stopPropagation()" 
             class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span>Reset Password</span>
                </h3>
                <button type="button" onclick="closeResetPasswordModal()" class="text-slate-400 hover:text-slate-700 text-sm font-bold">&times;</button>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">
                Set a secure new password for <strong class="text-slate-900" id="resetModalUserName"></strong>. Passwords will be securely hashed with Argon2id / Bcrypt.
            </p>

            <form id="resetPasswordForm" action="" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">New Password (Min 8 Characters) *</label>
                    <input type="password" 
                           name="password" 
                           required 
                           minlength="8" 
                           placeholder="••••••••••••" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Confirm New Password *</label>
                    <input type="password" 
                           name="password_confirmation" 
                           required 
                           minlength="8" 
                           placeholder="••••••••••••" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Delete User Confirmation with Article Reassignment Safeguards (Vanilla JS / Hidden by default) -->
    <div id="deleteUserModal" 
         class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div onclick="event.stopPropagation()" 
             class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-rose-600 text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Confirm User Deletion</span>
                </h3>
                <button type="button" onclick="closeDeleteUserModal()" class="text-slate-400 hover:text-slate-700 text-sm font-bold">&times;</button>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Are you sure you want to permanently delete <strong class="text-slate-900" id="deleteModalUserName"></strong>? This action cannot be undone.
            </p>

            <form id="deleteUserForm" action="" method="POST" class="space-y-4">
                @csrf
                @method('DELETE')

                <!-- If user has articles, require reassignment -->
                <div id="deleteArticleSafeguardSection" class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl space-y-2 hidden">
                    <div class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                        <span>Editorial Content Safeguard</span>
                    </div>
                    <p class="text-[11px] text-amber-800 leading-relaxed">
                        This user has authored <span class="font-bold text-amber-900" id="deleteModalArticlesCount">0</span> article(s). To protect editorial history from cascade deletion, reassign their articles to another administrator:
                    </p>
                    <select name="reassign_to" class="w-full px-3 py-2 bg-white border border-amber-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-red-500">
                        <option value="">Select administrator to inherit articles...</option>
                        @foreach($otherAdmins as $adm)
                            <option value="{{ $adm->id }}" {{ $adm->id === Auth::id() ? 'selected' : '' }}>
                                {{ $adm->name }} ({{ $adm->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeDeleteUserModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Vanilla JavaScript Modal & Selection Handlers -->
<script>
    // Reset Password Modal
    function openResetPasswordModal(userId, userName) {
        document.getElementById('resetModalUserName').textContent = userName;
        document.getElementById('resetPasswordForm').action = '/admin/users/' + userId + '/reset-password';
        document.getElementById('resetPasswordModal').classList.remove('hidden');
    }

    function closeResetPasswordModal() {
        document.getElementById('resetPasswordModal').classList.add('hidden');
    }

    // Delete User Modal
    function openDeleteUserModal(userId, userName, articlesCount) {
        document.getElementById('deleteModalUserName').textContent = userName;
        document.getElementById('deleteUserForm').action = '/admin/users/' + userId;
        
        const safeguardSection = document.getElementById('deleteArticleSafeguardSection');
        const countSpan = document.getElementById('deleteModalArticlesCount');
        
        if (articlesCount > 0) {
            countSpan.textContent = articlesCount;
            safeguardSection.classList.remove('hidden');
        } else {
            safeguardSection.classList.add('hidden');
        }
        
        document.getElementById('deleteUserModal').classList.remove('hidden');
    }

    function closeDeleteUserModal() {
        document.getElementById('deleteUserModal').classList.add('hidden');
    }

    // Modal backdrop click close handlers
    document.getElementById('resetPasswordModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeResetPasswordModal();
    });
    document.getElementById('deleteUserModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteUserModal();
    });

    // Bulk selection logic
    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.user-row-checkbox');
        checkboxes.forEach(cb => { cb.checked = master.checked; });
        handleUserCheckboxChange();
    }

    function handleUserCheckboxChange() {
        const checkboxes = document.querySelectorAll('.user-row-checkbox:checked');
        const count = checkboxes.length;
        const bulkBar = document.getElementById('bulkActionsBar');
        const countDisplay = document.getElementById('selectedUsersCount');
        const container = document.getElementById('bulkUserInputs');
        const master = document.getElementById('selectAllCheckbox');
        const allCheckboxes = document.querySelectorAll('.user-row-checkbox');

        if (count > 0) {
            countDisplay.textContent = count;
            bulkBar.classList.remove('hidden');
            
            // Build hidden inputs
            container.innerHTML = '';
            checkboxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
        } else {
            bulkBar.classList.add('hidden');
            container.innerHTML = '';
        }

        if (master) {
            master.checked = allCheckboxes.length > 0 && count === allCheckboxes.length;
        }
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeResetPasswordModal();
            closeDeleteUserModal();
        }
    });
</script>
@endsection
