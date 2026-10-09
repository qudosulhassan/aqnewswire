<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Editorial Operations') — AQ NEWSWIRE Editorial CMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

    <!-- Tailwind / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC] font-sans text-slate-900 antialiased min-h-screen flex flex-col">

    <!-- Global Shell Wrapper -->
    <div class="flex-1 flex overflow-hidden">

        <!-- Sidebar Backdrop (Mobile) -->
        <div id="sidebarBackdrop" 
             onclick="toggleSidebar()"
             class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden hidden transition-opacity"></div>

        <!-- Left Sidebar Navigation -->
        <aside id="adminSidebar"
               class="fixed lg:static inset-y-0 left-0 z-50 w-[240px] bg-[#0B0F17] text-slate-300 flex flex-col justify-between shrink-0 -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out border-r border-slate-800/80">
            
            <div class="flex flex-col h-full overflow-hidden">
                <!-- Brand Header -->
                <div class="px-5 py-4 border-b border-slate-800/80 flex items-center justify-between shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('images/favicon.png') }}" alt="AQ NEWSWIRE" class="w-8 h-8 rounded-lg object-contain shadow-sm shadow-indigo-600/30">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-base font-extrabold text-white tracking-wider leading-none">AQ NEWSWIRE</span>
                            </div>
                            <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400">EDITORIAL CMS</span>
                        </div>
                    </a>
                    <button type="button" class="text-slate-400 hover:text-white p-1 rounded hover:bg-slate-800/60 transition" title="CMS Options">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Groups -->
                <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-5 text-[13px] font-medium scrollbar-thin scrollbar-thumb-slate-800">
                    
                    <!-- Primary Dashboard -->
                    <div>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white font-semibold shadow-sm shadow-red-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <!-- CONTENT SECTION -->
                    <div>
                        <div class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Content
                        </div>
                        <div class="space-y-0.5">
                            <a href="{{ route('admin.articles.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.articles.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                <span>Articles & Stories</span>
                            </a>

                            <a href="{{ route('admin.categories.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Categories & Channels</span>
                            </a>

                            <a href="{{ route('admin.rankings.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.rankings.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                <span>Lists & Rankings</span>
                            </a>

                            <a href="{{ route('admin.contributors.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.contributors.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>Contributors</span>
                            </a>

                            <a href="{{ route('admin.media.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.media.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Media Library</span>
                            </a>

                            <a href="{{ route('admin.videos.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.videos.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span>Video Network</span>
                            </a>

                            <a href="{{ route('admin.podcasts.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.podcasts.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                                <span>Podcasts CMS</span>
                            </a>
                        </div>
                    </div>

                    <!-- ENGAGEMENT SECTION -->
                    <div>
                        <div class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Engagement
                        </div>
                        <div class="space-y-0.5">
                            <a href="{{ route('admin.comments.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.comments.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>Comments</span>
                            </a>

                            <a href="{{ route('admin.newsletters.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.newsletters.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>Newsletter</span>
                            </a>

                            <a href="{{ route('account.notifications') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('account.notifications') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span>Notifications</span>
                            </a>
                        </div>
                    </div>

                    <!-- ANALYTICS SECTION -->
                    <div>
                        <div class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Analytics
                        </div>
                        <div class="space-y-0.5">
                            <a href="{{ route('admin.analytics.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.analytics.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span>Analytics & Reports</span>
                            </a>

                            <a href="{{ route('admin.seo.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.seo.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span>SEO & Redirects</span>
                            </a>

                            <a href="{{ route('admin.audit.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.audit.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Audit Logs</span>
                            </a>
                        </div>
                    </div>

                    <!-- SETTINGS SECTION -->
                    <div>
                        <div class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Settings
                        </div>
                        <div class="space-y-0.5">
                            @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.users.index') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>Users & Roles</span>
                            </a>
                            @endif

                            @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.settings') }}" 
                               class="flex items-center gap-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.settings*') ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>General Settings</span>
                            </a>
                            @endif
                        </div>
                    </div>

                </nav>

                <!-- Sidebar Footer: User Profile Pill -->
                <div class="p-3 border-t border-slate-800/80 shrink-0">
                    <div class="relative">
                        <button id="userMenuButton"
                                onclick="toggleUserMenu()" 
                                type="button" 
                                class="w-full flex items-center justify-between p-2 rounded-xl bg-slate-900/80 hover:bg-slate-850 border border-slate-800 transition text-left cursor-pointer">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <img src="{{ Auth::user()->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80' }}" 
                                     alt="Avatar" 
                                     class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-slate-700">
                                <div class="overflow-hidden">
                                    <p class="text-xs font-semibold text-white truncate leading-tight">{{ Auth::user()->name ?? 'Alexander Vance' }}</p>
                                    <p class="text-[11px] text-slate-400 truncate leading-tight">{{ Auth::user()->title ?? 'Editor-in-Chief' }}</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- User Popup Menu -->
                        <div id="userMenuDropdown" 
                             class="hidden absolute bottom-full left-0 mb-2 w-full bg-[#0F172A] border border-slate-700 rounded-xl shadow-xl py-1 text-xs z-50">
                            <a href="{{ route('authors.show', Auth::user()->slug ?? 'alexander-vance') }}" target="_blank" class="flex items-center px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-800 transition">
                                Public Author Profile
                            </a>
                            <a href="{{ route('account.notifications') }}" class="flex items-center px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-800 transition">
                                Editorial Notifications
                            </a>
                            <div class="border-t border-slate-800 my-1"></div>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 text-rose-400 hover:text-rose-300 hover:bg-slate-800 transition cursor-pointer">
                                    Sign Out of CMS
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            
            <!-- Global Topbar Header -->
            <header class="bg-white border-b border-slate-200/80 px-6 py-3 flex items-center justify-between sticky top-0 z-30 shrink-0">
                
                <!-- Left: Hamburger & Command Search Trigger -->
                <div class="flex items-center gap-4 flex-1 max-w-xl">
                    <button type="button" 
                            onclick="toggleSidebar()" 
                            class="lg:hidden text-slate-600 hover:text-slate-900 p-1.5 rounded-lg border border-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Global Command Search Input -->
                    <div class="relative w-full">
                        <button type="button" 
                                onclick="openCommandPalette()"
                                class="w-full flex items-center justify-between px-3.5 py-1.5 text-xs text-slate-500 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition text-left group cursor-pointer">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span>Search articles, authors, categories...</span>
                            </div>
                            <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200 rounded shadow-2xs">Ctrl + K</kbd>
                        </button>
                    </div>
                </div>

                <!-- Right: Quick Links & Actions -->
                <div class="flex items-center gap-3">
                    
                    <!-- View Website Link Button -->
                    <a href="{{ route('home') }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg shadow-2xs transition">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>View Website</span>
                    </a>

                    <!-- Notification Bell -->
                    <div class="relative">
                        <a href="{{ route('account.notifications') }}" 
                           class="relative p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <!-- Notification Counter Badge -->
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-600 rounded-full ring-2 ring-white"></span>
                        </a>
                    </div>

                    <!-- Topbar Profile Capsule -->
                    <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
                        <img src="{{ Auth::user()->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80' }}" 
                             alt="Avatar" 
                             class="w-7 h-7 rounded-full object-cover ring-1 ring-slate-200">
                        <div class="hidden md:block text-left">
                            <p class="text-xs font-semibold text-slate-800 leading-none">{{ Auth::user()->name ?? 'Alexander Vance' }}</p>
                            <p class="text-[10px] text-slate-400 leading-none mt-0.5">{{ Auth::user()->title ?? 'Editor-in-Chief' }}</p>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 p-6 lg:p-8">
                
                <!-- Alerts / Flashes -->
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm shadow-2xs">
                        <div class="font-bold mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Please resolve the following:
                        </div>
                        <ul class="list-disc pl-5 space-y-0.5 text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

    <!-- Command Palette Modal (Ctrl + K) - HIDDEN BY DEFAULT -->
    <div id="commandPaletteModal" 
         class="hidden fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20 flex items-start justify-center">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity cursor-pointer" onclick="closeCommandPalette()"></div>

        <!-- Palette Box -->
        <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-xl overflow-hidden z-10" onclick="event.stopPropagation()">
            
            <div class="p-4 border-b border-slate-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" 
                       id="commandPaletteInput"
                       oninput="filterCommands(this.value)"
                       placeholder="Type a command or search articles, channels, authors..." 
                       class="w-full bg-transparent text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
                
                <!-- Explicit Close 'X' Button -->
                <button type="button" 
                        onclick="closeCommandPalette()" 
                        class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition cursor-pointer" 
                        title="Close (ESC)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Suggested Actions -->
            <div id="commandResults" class="p-3 text-xs divide-y divide-slate-100 max-h-80 overflow-y-auto">
                <div class="pb-2">
                    <p class="px-3 py-1 text-[10px] uppercase font-bold text-slate-400 tracking-wider">Quick Actions</p>
                    <a href="{{ route('admin.articles.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700 transition">
                        <span class="w-6 h-6 rounded bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">+</span>
                        <span>Write New Article</span>
                    </a>
                    <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700 transition">
                        <span class="w-6 h-6 rounded bg-blue-100 text-blue-600 flex items-center justify-center text-xs">📁</span>
                        <span>Upload Media Assets</span>
                    </a>
                    <a href="{{ route('admin.newsletters.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700 transition">
                        <span class="w-6 h-6 rounded bg-purple-100 text-purple-600 flex items-center justify-center text-xs">✉️</span>
                        <span>Create Newsletter Campaign</span>
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700 transition">
                        <span class="w-6 h-6 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">📈</span>
                        <span>Open Audience Analytics</span>
                    </a>
                </div>

                <div class="pt-2">
                    <p class="px-3 py-1 text-[10px] uppercase font-bold text-slate-400 tracking-wider">Navigation</p>
                    <a href="{{ route('admin.articles.index') }}" class="block px-3 py-1.5 rounded-lg hover:bg-slate-50 text-slate-600">All Published Articles</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-3 py-1.5 rounded-lg hover:bg-slate-50 text-slate-600">Categories & Channels</a>
                    <a href="{{ route('admin.seo.index') }}" class="block px-3 py-1.5 rounded-lg hover:bg-slate-50 text-slate-600">SEO & Redirect Manager</a>
                    <a href="{{ route('admin.audit.index') }}" class="block px-3 py-1.5 rounded-lg hover:bg-slate-50 text-slate-600">Audit Trail Logs</a>
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-1.5 rounded-lg hover:bg-slate-50 text-slate-600">Users & Roles Management</a>
                    <a href="{{ route('admin.settings') }}" class="block px-3 py-1.5 rounded-lg hover:bg-slate-50 text-slate-600">General Settings & Configuration</a>
                    @endif
                </div>
            </div>

            <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span>Press <kbd class="font-semibold text-slate-600">ESC</kbd> or click outside to dismiss</span>
                <span>Shortcut: <kbd class="font-semibold text-slate-600">Ctrl + K</kbd></span>
            </div>

        </div>
    </div>

    <!-- Vanilla Javascript Interactions (Zero Dependency) -->
    <script>
        function openCommandPalette() {
            const modal = document.getElementById('commandPaletteModal');
            if (modal) {
                modal.classList.remove('hidden');
                const input = document.getElementById('commandPaletteInput');
                if (input) setTimeout(() => input.focus(), 60);
            }
        }

        function closeCommandPalette() {
            const modal = document.getElementById('commandPaletteModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) sidebar.classList.toggle('-translate-x-full');
            if (backdrop) backdrop.classList.toggle('hidden');
        }

        function toggleUserMenu() {
            const menu = document.getElementById('userMenuDropdown');
            if (menu) menu.classList.toggle('hidden');
        }

        function filterCommands(query) {
            const items = document.querySelectorAll('#commandResults a');
            const q = query.toLowerCase().trim();
            items.forEach(item => {
                if (!q || item.textContent.toLowerCase().includes(q)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
                e.preventDefault();
                const modal = document.getElementById('commandPaletteModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeCommandPalette();
                } else {
                    openCommandPalette();
                }
            }
            if (e.key === 'Escape') {
                closeCommandPalette();
                const userMenu = document.getElementById('userMenuDropdown');
                if (userMenu) userMenu.classList.add('hidden');
            }
        });

        // Close user menu on outside click
        document.addEventListener('click', function(e) {
            const userMenu = document.getElementById('userMenuDropdown');
            const userButton = document.getElementById('userMenuButton');
            if (userMenu && !userMenu.contains(e.target) && userButton && !userButton.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>

</body>
</html>
