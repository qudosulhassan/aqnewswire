<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') . ' — ' . \App\Models\Setting::get('site_description', 'Global Business, Finance, Innovation & Leadership'))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('site_description', 'Definitive international journalism covering business leadership, disruptive technology, financial markets, and global wealth rankings.'))">
    <link rel="icon" href="{{ \App\Models\Setting::get('favicon_url', '/favicon.ico') }}">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large')">

    <!-- Open Graph / Facebook / LinkedIn -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ \App\Models\Setting::get('short_brand_name', 'AQ NEWSWIRE') }}">
    <meta property="og:title" content="@yield('og_title', View::getSection('title', 'AQ NEWSWIRE — Global Business, Finance, Innovation & Leadership'))">
    <meta property="og:description" content="@yield('og_description', View::getSection('meta_description', 'Definitive international journalism covering business leadership, disruptive technology, financial markets, and global wealth rankings.'))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    @hasSection('og_image')
    <meta property="og:image" content="@yield('og_image')">
    @endif

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', View::getSection('og_title', View::getSection('title', 'AQ NEWSWIRE')))">
    <meta name="twitter:description" content="@yield('twitter_description', View::getSection('og_description', View::getSection('meta_description', 'Definitive international journalism.')))">
    @hasSection('twitter_image')
    <meta name="twitter:image" content="@yield('twitter_image')">
    @elseif(View::hasSection('og_image'))
    <meta name="twitter:image" content="@yield('og_image')">
    @endif

    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ \App\Models\Setting::get('favicon_url') ?: asset('images/favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;800;900&family=Inter:wght@300;400;500;600;700;800;900&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,600;0,6..72,700;1,6..72,400&display=swap" rel="stylesheet">

    <!-- Tailwind / Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-serif: Georgia, 'Newsreader', Cambria, 'Times New Roman', Times, serif;
            --font-headline: Georgia, 'Newsreader', Cambria, 'Times New Roman', Times, serif;
            --font-sans: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        body {
            font-family: Georgia, 'Newsreader', Cambria, 'Times New Roman', Times, serif;
        }
        .font-serif {
            font-family: Georgia, 'Newsreader', Cambria, 'Times New Roman', Times, serif !important;
        }
        .font-headline {
            font-family: Georgia, 'Newsreader', Cambria, 'Times New Roman', Times, serif;
        }
        .font-brand {
            font-family: 'Cinzel', serif;
            letter-spacing: 0.18em;
        }
        .font-sans,
        header#main-header,
        nav,
        .market-ticker,
        button,
        input,
        select,
        textarea {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
    @stack('head')
</head>
<body class="bg-white text-slate-800 antialiased font-serif selection:bg-[#635BFF] selection:text-white">

    <!-- 1. Top Market Ticker Bar (Global Financial Pulse - Stripe Clean Style) -->
    <div class="bg-[#F8FAFC] border-b border-slate-200/80 text-[11px] text-slate-500 py-1.5 px-4 overflow-hidden hidden sm:block">
        <div class="max-w-[1600px] mx-auto flex items-center justify-between gap-6">
            <div class="flex items-center gap-6 overflow-x-auto no-scrollbar font-medium">
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="text-slate-400 font-semibold">S&P 500</span>
                    <span class="text-[#0A2540] font-bold">5,487.21</span>
                    <span class="text-emerald-600 font-semibold flex items-center">▲ +0.84%</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="text-slate-400 font-semibold">NASDAQ</span>
                    <span class="text-[#0A2540] font-bold">17,371.08</span>
                    <span class="text-emerald-600 font-semibold flex items-center">▲ +1.12%</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="text-slate-400 font-semibold">DOW</span>
                    <span class="text-[#0A2540] font-bold">39,721.36</span>
                    <span class="text-emerald-600 font-semibold flex items-center">▲ +0.62%</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="text-slate-400 font-semibold">FTSE 100</span>
                    <span class="text-[#0A2540] font-bold">7,682.11</span>
                    <span class="text-emerald-600 font-semibold flex items-center">▲ +0.48%</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="text-slate-400 font-semibold">Nikkei 225</span>
                    <span class="text-[#0A2540] font-bold">38,254.17</span>
                    <span class="text-emerald-600 font-semibold flex items-center">▲ +1.21%</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="text-slate-400 font-semibold">Gold</span>
                    <span class="text-[#0A2540] font-bold">2,401.50</span>
                    <span class="text-emerald-600 font-semibold flex items-center">▲ +0.35%</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="text-slate-400 font-semibold">Oil (Brent)</span>
                    <span class="text-[#0A2540] font-bold">82.19</span>
                    <span class="text-rose-600 font-semibold flex items-center">▼ -0.42%</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="text-slate-400 font-semibold">Bitcoin</span>
                    <span class="text-[#0A2540] font-bold">64,317.20</span>
                    <span class="text-emerald-600 font-semibold flex items-center">▲ +2.14%</span>
                </div>
            </div>

            <div class="hidden lg:flex items-center gap-3 shrink-0 text-slate-500 text-[11px]">
                <a href="{{ route('rankings.index') }}" class="text-[#635BFF] hover:text-indigo-700 font-bold uppercase tracking-wider flex items-center gap-1">
                    <span>★</span> The AQ NEWSWIRE Lists
                </a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('videos.index') }}" class="hover:text-slate-900 transition">Video</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('podcasts.index') }}" class="hover:text-slate-900 transition">Podcasts</a>
            </div>
        </div>
    </div>

    <!-- 2. Sticky Modern Navigation Header (Stripe Glassmorphism White) -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-xl border-b border-slate-200/80 transition-all duration-300 shadow-xs" id="main-header">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                
                <!-- Left: AQ NEWSWIRE Logo (Shifted right for clean breathing room away from border) -->
                <div class="flex items-center shrink-0 pl-3 sm:pl-6 lg:pl-10 xl:pl-12 mr-4 lg:mr-8">
                    <a href="{{ route('home') }}" class="flex items-center group py-1" title="{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}">
                        <img src="{{ \App\Models\Setting::get('logo_url') ?: asset('images/logo.png') }}" 
                             alt="{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}" 
                             class="h-11 sm:h-13 lg:h-14 w-auto max-w-[240px] sm:max-w-[290px] object-contain transition-transform duration-200 group-hover:scale-[1.02]">
                        <span class="sr-only">{{ \App\Models\Setting::get('short_brand_name', 'AQ NEWSWIRE') }}</span>
                    </a>
                </div>

                <!-- Center: Stripe-Style Mega-Menu Category Tabs -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-2 text-xs font-semibold text-slate-700">
                    
                    <!-- 1. Business -->
                    <div class="relative group py-6">
                        <a href="{{ route('categories.show', 'business') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-[#0A2540] hover:bg-slate-50 transition group-hover:text-[#635BFF] group-hover:bg-slate-50">
                            <span>Business</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#635BFF] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 absolute left-0 top-full -mt-2 w-[620px] bg-white rounded-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] border border-slate-200/90 p-5 z-50 pointer-events-none group-hover:pointer-events-auto">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[#635BFF]"></span>
                                    <span class="text-[11px] font-black uppercase tracking-wider text-[#0A2540]">Business & Global Commerce</span>
                                </div>
                                <a href="{{ route('categories.show', 'business') }}" class="text-[11px] font-bold text-[#635BFF] hover:text-indigo-700 flex items-center gap-1">
                                    <span>All Business Desk</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-xs">
                                <a href="{{ route('categories.show', 'economy') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-indigo-50 text-[#635BFF] font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">EC</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Economy</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Macro indicators & policy</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'finance') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">FN</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Finance</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Commercial credit & banking</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'real-estate') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">RE</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Real Estate</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Commercial & prime property</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'energy') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-teal-50 text-teal-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">EN</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Energy</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Grid transition & oil logistics</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'climate') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-green-50 text-green-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">CL</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Climate</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Carbon markets & ESG</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'transportation') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-sky-50 text-sky-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">TR</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Transportation</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Aviation & global shipping</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'investigations') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-rose-50 text-rose-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">IN</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Investigations</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Forensic deep investigations</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'industrials') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">ID</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Industrials</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Heavy industry & robotics</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'retail') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-purple-50 text-purple-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">RT</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Retail</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Consumer trends & luxury</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'wealth') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-amber-50 text-amber-700 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">WL</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Wealth</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Private family offices & PE</div>
                                    </div>
                                </a>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 bg-slate-50/70 -mx-5 -mb-5 px-5 py-3 rounded-b-2xl">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('categories.show', 'media') }}" class="font-semibold text-slate-700 hover:text-[#635BFF]">Media Desk</a>
                                    <span class="text-slate-300">&bull;</span>
                                    <a href="{{ route('categories.show', 'sports') }}" class="font-semibold text-slate-700 hover:text-[#635BFF]">Sports</a>
                                    <span class="text-slate-300">&bull;</span>
                                    <a href="{{ route('categories.show', 'small-business') }}" class="font-semibold text-slate-700 hover:text-[#635BFF]">Small Business</a>
                                </div>
                                <a href="{{ route('rankings.index') }}" class="font-bold text-[#0A2540] hover:text-[#635BFF]">★ AQ NEWSWIRE Billionaires &rarr;</a>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Markets -->
                    <div class="relative group py-6">
                        <a href="{{ route('categories.show', 'markets') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-[#0A2540] hover:bg-slate-50 transition group-hover:text-[#635BFF] group-hover:bg-slate-50">
                            <span>Markets</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#635BFF] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 absolute left-0 top-full -mt-2 w-[580px] bg-white rounded-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] border border-slate-200/90 p-5 z-50 pointer-events-none group-hover:pointer-events-auto">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-[11px] font-black uppercase tracking-wider text-[#0A2540]">Global Capital Markets</span>
                                </div>
                                <a href="{{ route('categories.show', 'markets') }}" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                                    <span>All Markets Beat</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-xs">
                                <a href="{{ route('categories.show', 'us-markets') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">US</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-emerald-600 transition">U.S. Markets</div>
                                        <div class="text-[11px] text-slate-500 font-normal">S&P 500, Dow & Nasdaq</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'europe-markets') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">EU</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-blue-600 transition">Europe Markets</div>
                                        <div class="text-[11px] text-slate-500 font-normal">FTSE, DAX & Euro Stoxx</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'asia-markets') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-indigo-50 text-[#635BFF] font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">AS</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Asia & China Markets</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Nikkei, Hang Seng & regional</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'bonds') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">BD</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-amber-600 transition">Bonds & Sovereign Debt</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Treasury yields & credit curves</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'currencies') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-teal-50 text-teal-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">FX</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-teal-600 transition">Currencies</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Dollar index & global FX</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'cryptocurrency') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-purple-50 text-purple-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">CR</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-purple-600 transition">Cryptocurrency</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Bitcoin, Ethereum & custody</div>
                                    </div>
                                </a>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 bg-slate-50/70 -mx-5 -mb-5 px-5 py-3 rounded-b-2xl">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('categories.show', 'pre-markets') }}" class="font-semibold text-slate-700 hover:text-emerald-600">Pre-Markets</a>
                                    <span class="text-slate-300">&bull;</span>
                                    <a href="{{ route('categories.show', 'prediction-markets') }}" class="font-semibold text-slate-700 hover:text-emerald-600">Prediction Mkts</a>
                                    <span class="text-slate-300">&bull;</span>
                                    <a href="{{ route('categories.show', 'futures-commodities') }}" class="font-semibold text-slate-700 hover:text-emerald-600">Commodities</a>
                                </div>
                                <span class="font-bold text-emerald-600">Real-Time Data Active</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Investing -->
                    <div class="relative group py-6">
                        <a href="{{ route('categories.show', 'investing') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-[#0A2540] hover:bg-slate-50 transition group-hover:text-[#635BFF] group-hover:bg-slate-50">
                            <span>Investing</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#635BFF] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 absolute left-0 top-full -mt-2 w-[480px] bg-white rounded-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] border border-slate-200/90 p-5 z-50 pointer-events-none group-hover:pointer-events-auto">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    <span class="text-[11px] font-black uppercase tracking-wider text-[#0A2540]">Portfolio & Wealth Strategy</span>
                                </div>
                                <a href="{{ route('categories.show', 'investing') }}" class="text-[11px] font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1">
                                    <span>All Investing</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                                <a href="{{ route('categories.show', 'personal-finance') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-amber-600">Personal Finance</div>
                                    <div class="text-[11px] text-slate-500">Wealth creation & taxes</div>
                                </a>
                                <a href="{{ route('categories.show', 'fintech') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-amber-600">Fintech</div>
                                    <div class="text-[11px] text-slate-500">Neobanking & payment rails</div>
                                </a>
                                <a href="{{ route('categories.show', 'etf-street') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-amber-600">ETF Street</div>
                                    <div class="text-[11px] text-slate-500">Index & thematic funds</div>
                                </a>
                                <a href="{{ route('categories.show', 'options-action') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-amber-600">Options Action</div>
                                    <div class="text-[11px] text-slate-500">Derivatives & volatility</div>
                                </a>
                                <a href="{{ route('categories.show', 'earnings') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-amber-600">Earnings</div>
                                    <div class="text-[11px] text-slate-500">Quarterly financials & guidance</div>
                                </a>
                                <a href="{{ route('categories.show', 'trader-talk') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-amber-600">Trader Talk</div>
                                    <div class="text-[11px] text-slate-500">Quant & institutional desk</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Tech -->
                    <div class="relative group py-6">
                        <a href="{{ route('categories.show', 'tech') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-[#0A2540] hover:bg-slate-50 transition group-hover:text-[#635BFF] group-hover:bg-slate-50">
                            <span>Tech</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#635BFF] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 absolute left-0 top-full -mt-2 w-[540px] bg-white rounded-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] border border-slate-200/90 p-5 z-50 pointer-events-none group-hover:pointer-events-auto">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                    <span class="text-[11px] font-black uppercase tracking-wider text-[#0A2540]">Frontier Computing & AI</span>
                                </div>
                                <a href="{{ route('categories.show', 'tech') }}" class="text-[11px] font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1">
                                    <span>All Tech Beat</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-xs">
                                <a href="{{ route('categories.show', 'ai') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-purple-50 text-purple-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">AI</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-[#635BFF] transition">Artificial Intelligence</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Foundation models & agents</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'enterprise') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-sky-50 text-sky-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">EP</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-sky-600 transition">Enterprise Infrastructure</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Hyperscale data & cloud</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'cybersecurity') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-rose-50 text-rose-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">CS</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-rose-600 transition">Cybersecurity</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Threat intelligence & defence</div>
                                    </div>
                                </a>
                                <a href="{{ route('categories.show', 'mobile') }}" class="group/item flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <span class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">MB</span>
                                    <div>
                                        <div class="font-bold text-[#0A2540] group-hover/item:text-emerald-600 transition">Mobile & Chips</div>
                                        <div class="text-[11px] text-slate-500 font-normal">Semiconductors & hardware</div>
                                    </div>
                                </a>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 bg-slate-50/70 -mx-5 -mb-5 px-5 py-3 rounded-b-2xl">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('categories.show', 'internet') }}" class="font-semibold text-slate-700 hover:text-sky-600">Internet</a>
                                    <span class="text-slate-300">&bull;</span>
                                    <a href="{{ route('categories.show', 'social-media') }}" class="font-semibold text-slate-700 hover:text-sky-600">Social Media</a>
                                    <span class="text-slate-300">&bull;</span>
                                    <a href="{{ route('categories.show', 'tech-guide') }}" class="font-semibold text-slate-700 hover:text-sky-600">Tech Guide</a>
                                </div>
                                <a href="{{ route('videos.index') }}" class="font-bold text-sky-600">Tech Videos &rarr;</a>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Politics -->
                    <div class="relative group py-6">
                        <a href="{{ route('categories.show', 'politics') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-[#0A2540] hover:bg-slate-50 transition group-hover:text-[#635BFF] group-hover:bg-slate-50">
                            <span>Politics</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#635BFF] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 absolute left-0 top-full -mt-2 w-[520px] bg-white rounded-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] border border-slate-200/90 p-5 z-50 pointer-events-none group-hover:pointer-events-auto">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span class="text-[11px] font-black uppercase tracking-wider text-[#0A2540]">Global Policy & Geopolitics</span>
                                </div>
                                <a href="{{ route('categories.show', 'politics') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                                    <span>All Politics</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                                <a href="{{ route('categories.show', 'white-house') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-rose-600">White House</div>
                                    <div class="text-[11px] text-slate-500">Executive directives & cabinet</div>
                                </a>
                                <a href="{{ route('categories.show', 'defense') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-rose-600">Defense & Security</div>
                                    <div class="text-[11px] text-slate-500">Aerospace & intelligence</div>
                                </a>
                                <a href="{{ route('categories.show', 'congress') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-rose-600">Congress</div>
                                    <div class="text-[11px] text-slate-500">Legislation & hearings</div>
                                </a>
                                <a href="{{ route('categories.show', 'world-politics') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-rose-600">World Diplomacy</div>
                                    <div class="text-[11px] text-slate-500">Summits & global treaties</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 6. LifeStyle -->
                    <div class="relative group py-6">
                        <a href="{{ route('categories.show', 'lifestyle') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-[#0A2540] hover:bg-slate-50 transition group-hover:text-[#635BFF] group-hover:bg-slate-50">
                            <span>LifeStyle</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#635BFF] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 absolute left-0 top-full -mt-2 w-[460px] bg-white rounded-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] border border-slate-200/90 p-5 z-50 pointer-events-none group-hover:pointer-events-auto">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-pink-500"></span>
                                    <span class="text-[11px] font-black uppercase tracking-wider text-[#0A2540]">Executive Life & Leadership</span>
                                </div>
                                <a href="{{ route('categories.show', 'lifestyle') }}" class="text-[11px] font-bold text-pink-600 hover:text-pink-700 flex items-center gap-1">
                                    <span>All LifeStyle</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                                <a href="{{ route('categories.show', 'careers') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-pink-600">Careers</div>
                                    <div class="text-[11px] text-slate-500">Executive hiring & roles</div>
                                </a>
                                <a href="{{ route('categories.show', 'workplace') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-pink-600">Workplace</div>
                                    <div class="text-[11px] text-slate-500">Headquarters & culture</div>
                                </a>
                                <a href="{{ route('categories.show', 'travel') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-pink-600">Travel</div>
                                    <div class="text-[11px] text-slate-500">Elite hospitality & jets</div>
                                </a>
                                <a href="{{ route('categories.show', 'cars') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-pink-600">Cars</div>
                                    <div class="text-[11px] text-slate-500">Luxury & EV performance</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 7. Health -->
                    <div class="relative group py-6">
                        <a href="{{ route('categories.show', 'health') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-[#0A2540] hover:bg-slate-50 transition group-hover:text-[#635BFF] group-hover:bg-slate-50">
                            <span>Health</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#635BFF] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 absolute right-0 top-full -mt-2 w-[440px] bg-white rounded-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] border border-slate-200/90 p-5 z-50 pointer-events-none group-hover:pointer-events-auto">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-[11px] font-black uppercase tracking-wider text-[#0A2540]">Longevity & Medicine</span>
                                </div>
                                <a href="{{ route('categories.show', 'health') }}" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                                    <span>All Health</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                                <a href="{{ route('categories.show', 'mental-health') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-emerald-600">Mental Health</div>
                                    <div class="text-[11px] text-slate-500">Neuroscience & focus</div>
                                </a>
                                <a href="{{ route('categories.show', 'supplements') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-emerald-600">Supplements</div>
                                    <div class="text-[11px] text-slate-500">Clinical longevity</div>
                                </a>
                                <a href="{{ route('categories.show', 'medical-devices') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-emerald-600">Medical Devices</div>
                                    <div class="text-[11px] text-slate-500">Biotech & diagnostics</div>
                                </a>
                                <a href="{{ route('categories.show', 'diet') }}" class="p-1.5 rounded-xl hover:bg-slate-50 transition">
                                    <div class="font-bold text-[#0A2540] hover:text-emerald-600">Diet & Nutrition</div>
                                    <div class="text-[11px] text-slate-500">Metabolic research</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Rankings -->
                    <a href="{{ route('rankings.index') }}" class="text-[#635BFF] hover:text-indigo-700 font-bold transition flex items-center gap-1 shrink-0 px-3 py-1.5 rounded-lg hover:bg-slate-50">
                        <span>★</span> Rankings
                    </a>
                </nav>

                <!-- Right: Search, Auth, and Subscribe CTA Split Button (Stripe Style) -->
                <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                    <!-- Global Search Button (Ctrl+K trigger) -->
                    <button type="button" 
                            id="search-modal-trigger"
                            class="px-2.5 py-1.5 rounded-xl text-slate-600 hover:text-[#0A2540] bg-slate-100 hover:bg-slate-200/70 border border-slate-200/80 transition flex items-center gap-2 text-xs font-medium cursor-pointer"
                            title="Search AQ NEWSWIRE (Ctrl + K)">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span class="hidden sm:inline text-slate-500 text-[11px] font-medium">Ctrl K</span>
                    </button>

                    @auth
                        <div class="relative group">
                            <a href="{{ route('account.dashboard') }}" class="text-xs font-semibold text-slate-700 hover:text-[#0A2540] transition flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs text-[#0A2540] font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                                <span class="hidden md:inline">{{ explode(' ', Auth::user()->name)[0] }}</span>
                            </a>
                            <!-- User Mini Dropdown -->
                            <div class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-xl py-2 hidden group-hover:block z-50 text-xs">
                                <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-[#0A2540]">Account Settings</a>
                                <a href="{{ route('account.bookmarks') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-[#0A2540]">Saved Articles</a>
                                @if(Auth::user()->canAccessAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-[#635BFF] hover:bg-slate-50 font-bold">Editorial CMS</a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST" class="border-t border-slate-100 mt-1 pt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-slate-50">Sign Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-700 hover:text-[#0A2540] transition hidden sm:inline">
                            Sign In
                        </a>
                        <a href="#newsletter-cta" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#635BFF] hover:bg-[#5851EA] text-white text-xs font-bold rounded-xl transition shadow-sm hover:shadow-[#635BFF]/30">
                            <span>Subscribe</span>
                            <span class="text-white/80">&rarr;</span>
                        </a>
                    @endauth

                    <!-- Mobile Menu Hamburger Button -->
                    <button type="button" id="mobile-menu-toggle" class="lg:hidden p-2 text-slate-600 hover:text-[#0A2540]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Mobile Navigation Drawer -->
            <div id="mobile-nav" class="hidden lg:hidden pb-6 pt-3 border-t border-slate-200 max-h-[75vh] overflow-y-auto bg-white">
                <div class="grid grid-cols-2 gap-2.5 text-xs font-semibold text-slate-700">
                    <div class="space-y-1 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <a href="{{ route('categories.show', 'business') }}" class="text-[#0A2540] font-bold block pb-1 border-b border-slate-200">Business</a>
                        <div class="pt-1 flex flex-col gap-1 text-[11px] text-slate-500">
                            <a href="{{ route('categories.show', 'economy') }}" class="hover:text-[#635BFF]">Economy</a>
                            <a href="{{ route('categories.show', 'finance') }}" class="hover:text-[#635BFF]">Finance</a>
                            <a href="{{ route('categories.show', 'real-estate') }}" class="hover:text-[#635BFF]">Real Estate</a>
                            <a href="{{ route('categories.show', 'energy') }}" class="hover:text-[#635BFF]">Energy</a>
                        </div>
                    </div>
                    <div class="space-y-1 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <a href="{{ route('categories.show', 'markets') }}" class="text-[#0A2540] font-bold block pb-1 border-b border-slate-200">Markets</a>
                        <div class="pt-1 flex flex-col gap-1 text-[11px] text-slate-500">
                            <a href="{{ route('categories.show', 'us-markets') }}" class="hover:text-[#635BFF]">U.S. Markets</a>
                            <a href="{{ route('categories.show', 'europe-markets') }}" class="hover:text-[#635BFF]">Europe Markets</a>
                            <a href="{{ route('categories.show', 'cryptocurrency') }}" class="hover:text-[#635BFF]">Cryptocurrency</a>
                            <a href="{{ route('categories.show', 'bonds') }}" class="hover:text-[#635BFF]">Bonds</a>
                        </div>
                    </div>
                    <div class="space-y-1 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <a href="{{ route('categories.show', 'investing') }}" class="text-[#0A2540] font-bold block pb-1 border-b border-slate-200">Investing</a>
                        <div class="pt-1 flex flex-col gap-1 text-[11px] text-slate-500">
                            <a href="{{ route('categories.show', 'personal-finance') }}" class="hover:text-[#635BFF]">Personal Finance</a>
                            <a href="{{ route('categories.show', 'fintech') }}" class="hover:text-[#635BFF]">Fintech</a>
                            <a href="{{ route('categories.show', 'etf-street') }}" class="hover:text-[#635BFF]">ETF Street</a>
                        </div>
                    </div>
                    <div class="space-y-1 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <a href="{{ route('categories.show', 'tech') }}" class="text-[#0A2540] font-bold block pb-1 border-b border-slate-200">Tech</a>
                        <div class="pt-1 flex flex-col gap-1 text-[11px] text-slate-500">
                            <a href="{{ route('categories.show', 'ai') }}" class="hover:text-[#635BFF]">AI</a>
                            <a href="{{ route('categories.show', 'enterprise') }}" class="hover:text-[#635BFF]">Enterprise</a>
                            <a href="{{ route('categories.show', 'cybersecurity') }}" class="hover:text-[#635BFF]">Cybersecurity</a>
                        </div>
                    </div>
                    <div class="space-y-1 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <a href="{{ route('categories.show', 'politics') }}" class="text-[#0A2540] font-bold block pb-1 border-b border-slate-200">Politics</a>
                        <div class="pt-1 flex flex-col gap-1 text-[11px] text-slate-500">
                            <a href="{{ route('categories.show', 'white-house') }}" class="hover:text-[#635BFF]">White House</a>
                            <a href="{{ route('categories.show', 'defense') }}" class="hover:text-[#635BFF]">Defense</a>
                            <a href="{{ route('categories.show', 'congress') }}" class="hover:text-[#635BFF]">Congress</a>
                        </div>
                    </div>
                    <div class="space-y-1 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <a href="{{ route('categories.show', 'lifestyle') }}" class="text-[#0A2540] font-bold block pb-1 border-b border-slate-200">LifeStyle</a>
                        <div class="pt-1 flex flex-col gap-1 text-[11px] text-slate-500">
                            <a href="{{ route('categories.show', 'careers') }}" class="hover:text-[#635BFF]">Careers</a>
                            <a href="{{ route('categories.show', 'travel') }}" class="hover:text-[#635BFF]">Travel</a>
                            <a href="{{ route('categories.show', 'workplace') }}" class="hover:text-[#635BFF]">Workplace</a>
                        </div>
                    </div>
                    <div class="col-span-2 flex items-center justify-between pt-2 px-1">
                        <a href="{{ route('categories.show', 'health') }}" class="p-2 hover:bg-slate-50 rounded-lg text-emerald-600 font-bold">Health Channel &rarr;</a>
                        <a href="{{ route('rankings.index') }}" class="p-2 text-[#635BFF] hover:bg-slate-50 rounded-lg font-bold">★ Rankings & Lists</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 3. Global Command Palette / Search Modal (Live Autocomplete, Ctrl + K - Stripe Style) -->
    <div id="search-modal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden flex items-start justify-center pt-16 sm:pt-20 px-4">
        <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            <!-- Search Input Header -->
            <div class="p-4 border-b border-slate-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <form id="global-search-form" action="{{ route('search.index') }}" method="GET" class="flex-1">
                    <input type="text" 
                           id="global-search-input"
                           name="q" 
                           autocomplete="off"
                           placeholder="Search global intelligence, market trends, founders, companies..." 
                           class="w-full bg-transparent text-[#0A2540] placeholder-slate-400 text-sm focus:outline-none font-medium">
                </form>
                <button type="button" id="search-modal-close" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 text-xs font-bold cursor-pointer">
                    ESC
                </button>
            </div>

            <!-- Live Autocomplete Suggestions Area -->
            <div id="search-autocomplete-results" class="hidden max-h-80 overflow-y-auto divide-y divide-slate-100 p-2 text-xs">
                <!-- Injected via JavaScript -->
            </div>

            <!-- Quick Filter Topics Bar -->
            <div class="p-3.5 bg-slate-50 text-xs text-slate-500 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-slate-400 text-[11px] font-medium">Trending:</span>
                    <button type="button" onclick="runQuickSearch('Artificial Intelligence')" class="px-2 py-0.5 rounded-md bg-white border border-slate-200 hover:border-[#635BFF] hover:text-[#635BFF] text-slate-700 text-[11px] font-medium transition cursor-pointer">AI</button>
                    <button type="button" onclick="runQuickSearch('Semiconductors')" class="px-2 py-0.5 rounded-md bg-white border border-slate-200 hover:border-[#635BFF] hover:text-[#635BFF] text-slate-700 text-[11px] font-medium transition cursor-pointer">Semiconductors</button>
                    <button type="button" onclick="runQuickSearch('Sovereign Debt')" class="px-2 py-0.5 rounded-md bg-white border border-slate-200 hover:border-[#635BFF] hover:text-[#635BFF] text-slate-700 text-[11px] font-medium transition cursor-pointer">Sovereign Debt</button>
                    <button type="button" onclick="runQuickSearch('Clean Energy')" class="px-2 py-0.5 rounded-md bg-white border border-slate-200 hover:border-[#635BFF] hover:text-[#635BFF] text-slate-700 text-[11px] font-medium transition cursor-pointer">Clean Energy</button>
                </div>
                <span class="text-slate-400 text-[11px] hidden sm:inline">Press Enter to view all</span>
            </div>
        </div>
    </div>

    <!-- Global Toast Notification Container -->
    <div id="apex-toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 pointer-events-none"></div>

    <!-- 4. Flash Message Alerts -->
    @if(session('success'))
        <div class="max-w-[1600px] mx-auto px-4 mt-4">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs flex items-center justify-between shadow-xs">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold cursor-pointer">&times;</button>
            </div>
        </div>
    @endif

    <!-- 5. Main Yielded Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- 6. Conditional Default Newsletter for Non-Home Pages (Stripe Aesthetic) -->
    @if(\App\Models\Setting::get('enable_newsletter', true) && !request()->is('/') && !request()->routeIs('home'))
        <section class="bg-[#F6F9FC] border-t border-slate-200/80 py-16 text-center">
            <div class="max-w-3xl mx-auto px-4">
                <span class="text-[#635BFF] text-xs font-black uppercase tracking-widest block mb-2">AQ NEWSWIRE Intelligence</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#0A2540] mb-3">Unrivaled Market Intelligence Direct to Your Inbox</h2>
                <p class="text-slate-600 text-xs sm:text-sm mb-6">Join global business leaders, investors and innovators reading AQ NEWSWIRE daily.</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    @csrf
                    <input type="email" name="email" required placeholder="Enter corporate email..." 
                           class="flex-1 px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#635BFF] shadow-xs">
                    <button type="submit" class="px-6 py-2.5 bg-[#635BFF] hover:bg-[#5851EA] text-white font-bold text-xs rounded-xl transition shadow-xs cursor-pointer">
                        Subscribe
                    </button>
                </form>
            </div>
        </section>
    @endif

    <!-- 7. Premium Light Footer (Stripe-Inspired Architecture) -->
    <footer class="bg-[#F8FAFC] text-slate-600 text-xs border-t border-slate-200/80 py-16">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-12 gap-8 lg:gap-12 mb-16">
                
                <!-- Col 1: Brand & Mission (4 cols) -->
                <div class="col-span-2 md:col-span-4 space-y-4">
                    <a href="{{ route('home') }}" class="inline-block group" title="{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}">
                        <img src="{{ \App\Models\Setting::get('logo_url') ?: asset('images/logo.png') }}" 
                             alt="{{ \App\Models\Setting::get('site_name', 'AQ NEWSWIRE') }}" 
                             class="h-11 sm:h-12 w-auto max-w-[240px] object-contain mb-1 transition-transform group-hover:scale-[1.02]">
                    </a>
                    <p class="text-slate-500 leading-relaxed text-xs max-w-sm">
                        {{ \App\Models\Setting::get('site_description', 'Independent international journalism for a more informed, more ambitious world. Covering sovereign finance, technology shifts, and business leadership.') }}
                    </p>
                    <div class="flex items-center gap-2.5 text-slate-400 pt-2 flex-wrap">
                        @if(\App\Models\Setting::get('social_twitter'))
                        <a href="{{ \App\Models\Setting::get('social_twitter') }}" target="_blank" rel="noopener noreferrer" class="hover:text-[#635BFF] transition p-2 rounded-lg bg-white border border-slate-200 shadow-xs" title="X / Twitter">
                            <span class="sr-only">X (Twitter)</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('social_linkedin'))
                        <a href="{{ \App\Models\Setting::get('social_linkedin') }}" target="_blank" rel="noopener noreferrer" class="hover:text-[#635BFF] transition p-2 rounded-lg bg-white border border-slate-200 shadow-xs" title="LinkedIn">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('social_youtube'))
                        <a href="{{ \App\Models\Setting::get('social_youtube') }}" target="_blank" rel="noopener noreferrer" class="hover:text-[#635BFF] transition p-2 rounded-lg bg-white border border-slate-200 shadow-xs" title="YouTube">
                            <span class="sr-only">YouTube</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('social_facebook'))
                        <a href="{{ \App\Models\Setting::get('social_facebook') }}" target="_blank" rel="noopener noreferrer" class="hover:text-[#635BFF] transition p-2 rounded-lg bg-white border border-slate-200 shadow-xs" title="Facebook">
                            <span class="sr-only">Facebook</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8H6v4h3v12h5V12h3.642L18 8h-4V6.333C14 5.374 14.5 5 15.5 5H18V0h-3.808C10.597 0 9 1.583 9 4.615V8z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Col 2: Channels (2 cols) -->
                <div class="col-span-1 md:col-span-2 space-y-3">
                    <h4 class="text-[#0A2540] font-bold uppercase tracking-wider text-[11px]">Sections</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('categories.show', 'business') }}" class="hover:text-[#635BFF] transition">Business</a></li>
                        <li><a href="{{ route('categories.show', 'markets') }}" class="hover:text-[#635BFF] transition">Markets</a></li>
                        <li><a href="{{ route('categories.show', 'investing') }}" class="hover:text-[#635BFF] transition">Investing</a></li>
                        <li><a href="{{ route('categories.show', 'tech') }}" class="hover:text-[#635BFF] transition">Tech</a></li>
                        <li><a href="{{ route('categories.show', 'politics') }}" class="hover:text-[#635BFF] transition">Politics</a></li>
                        <li><a href="{{ route('categories.show', 'lifestyle') }}" class="hover:text-[#635BFF] transition">LifeStyle</a></li>
                        <li><a href="{{ route('categories.show', 'health') }}" class="hover:text-[#635BFF] transition">Health</a></li>
                    </ul>
                </div>

                <!-- Col 3: Lists & Rankings (2 cols) -->
                <div class="col-span-1 md:col-span-2 space-y-3">
                    <h4 class="text-[#0A2540] font-bold uppercase tracking-wider text-[11px]">Lists</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('rankings.index') }}" class="hover:text-[#635BFF] transition">The World's Billionaires</a></li>
                        <li><a href="{{ route('rankings.index') }}" class="hover:text-[#635BFF] transition">Top Tech Innovators</a></li>
                        <li><a href="{{ route('rankings.index') }}" class="hover:text-[#635BFF] transition">30 Under 30</a></li>
                        <li><a href="{{ route('rankings.index') }}" class="hover:text-[#635BFF] transition">Global Companies</a></li>
                        <li><a href="{{ route('rankings.index') }}" class="text-[#635BFF] hover:text-indigo-700 font-semibold transition">All Rankings &rarr;</a></li>
                    </ul>
                </div>

                <!-- Col 4: Company (2 cols) -->
                <div class="col-span-1 md:col-span-2 space-y-3">
                    <h4 class="text-[#0A2540] font-bold uppercase tracking-wider text-[11px]">Company</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('contact') }}" class="hover:text-[#635BFF] transition">Contact & Bureaus</a></li>
                        <li><a href="#" class="hover:text-[#635BFF] transition">About Us</a></li>
                        <li><a href="#" class="hover:text-[#635BFF] transition">Our Team</a></li>
                        <li><a href="#" class="hover:text-[#635BFF] transition">Advertise</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-[#635BFF] transition">Staff Editorial CMS</a></li>
                    </ul>
                </div>

                <!-- Col 5: Legal & Trust (2 cols) -->
                <div class="col-span-1 md:col-span-2 space-y-3">
                    <h4 class="text-[#0A2540] font-bold uppercase tracking-wider text-[11px]">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-[#635BFF] transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-[#635BFF] transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-[#635BFF] transition">Cookie Policy</a></li>
                        <li><a href="#" class="hover:text-[#635BFF] transition">Editorial Standards</a></li>
                        <li><a href="{{ url('/sitemap.xml') }}" class="hover:text-[#635BFF] transition">Sitemap</a></li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Copyright & Compliance -->
            <div class="pt-8 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
                <p>{{ \App\Models\Setting::get('copyright_text', '© ' . date('Y') . ' AQ NEWSWIRE Group Inc. All rights reserved.') }}</p>
                <p>Real-time financial benchmarks are for informative editorial analysis.</p>
            </div>
        </div>
    </footer>

    <!-- Global Client Interactions: Autocomplete, Quick Search, Toasts, Bookmarking -->
    <script>
        // Global Toast Notification Helper
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('apex-toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            const bgClass = type === 'success' 
                ? 'bg-slate-900/95 border-emerald-500/50 text-emerald-300' 
                : (type === 'error' ? 'bg-slate-900/95 border-rose-500/50 text-rose-300' : 'bg-slate-900/95 border-sky-500/50 text-sky-300');
            const iconSvg = type === 'success'
                ? '<svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>'
                : (type === 'error' 
                    ? '<svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>'
                    : '<svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>');

            toast.className = `pointer-events-auto flex items-center gap-2.5 px-4 py-3 rounded-xl border shadow-xl text-xs font-semibold backdrop-blur-md transition-all duration-300 transform translate-y-2 opacity-0 ${bgClass}`;
            toast.innerHTML = `
                ${iconSvg}
                <span class="flex-1">${message}</span>
                <button type="button" class="text-slate-400 hover:text-white ml-2 text-sm leading-none">&times;</button>
            `;

            container.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            });

            const removeToast = () => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            };

            toast.querySelector('button').addEventListener('click', removeToast);
            setTimeout(removeToast, 4000);
        };

        // Quick Search Helper (Used by trending tags in search modal)
        window.runQuickSearch = function(term) {
            const input = document.getElementById('global-search-input');
            const form = document.getElementById('global-search-form');
            if (input && form) {
                input.value = term;
                form.submit();
            }
        };

        // Global Bookmark Toggle Helper (AJAX)
        window.toggleBookmark = async function(articleId, btnEl) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch(`/bookmarks/${articleId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 401) {
                    window.showToast('Please sign in to save articles to your reading list', 'info');
                    return;
                }

                const data = await response.json();
                if (data.success) {
                    window.showToast(data.message, 'success');
                    if (btnEl) {
                        const svg = btnEl.querySelector('svg');
                        if (svg) {
                            if (data.status === 'saved') {
                                svg.classList.add('fill-current', 'text-red-500');
                                svg.classList.remove('text-slate-400', 'text-slate-300');
                            } else {
                                svg.classList.remove('fill-current', 'text-red-500');
                                svg.classList.add('text-slate-400');
                            }
                        }
                    }
                } else {
                    window.showToast(data.message || 'Unable to update bookmark', 'error');
                }
            } catch (err) {
                console.error('Bookmark error:', err);
                window.showToast('Error updating bookmark', 'error');
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('search-modal');
            const trigger = document.getElementById('search-modal-trigger');
            const closeBtn = document.getElementById('search-modal-close');
            const searchInput = document.getElementById('global-search-input');
            const autocompleteContainer = document.getElementById('search-autocomplete-results');
            const mobileToggle = document.getElementById('mobile-menu-toggle');
            const mobileNav = document.getElementById('mobile-nav');

            function openModal() {
                if (modal) {
                    modal.classList.remove('hidden');
                    if (searchInput) {
                        searchInput.focus();
                        if (searchInput.value.trim().length >= 2) {
                            fetchSuggestions(searchInput.value.trim());
                        }
                    }
                }
            }

            function closeModal() {
                if (modal) modal.classList.add('hidden');
            }

            if (trigger) trigger.addEventListener('click', openModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);

            document.addEventListener('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    if (modal && modal.classList.contains('hidden')) {
                        openModal();
                    } else {
                        closeModal();
                    }
                }
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) closeModal();
                });
            }

            if (mobileToggle && mobileNav) {
                mobileToggle.addEventListener('click', function () {
                    mobileNav.classList.toggle('hidden');
                });
            }

            // Real-Time Autocomplete with Debounce
            let debounceTimer = null;
            async function fetchSuggestions(query) {
                if (!autocompleteContainer) return;
                if (query.length < 2) {
                    autocompleteContainer.classList.add('hidden');
                    autocompleteContainer.innerHTML = '';
                    return;
                }

                try {
                    const res = await fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`);
                    const data = await res.json();
                    
                    if (data.suggestions && data.suggestions.length > 0) {
                        autocompleteContainer.innerHTML = data.suggestions.map(item => {
                            const badgeColor = item.type === 'article' 
                                ? 'bg-red-500/10 text-red-400 border border-red-500/20' 
                                : (item.type === 'author' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20');
                            const label = item.type.toUpperCase();

                            return `
                                <a href="${item.url}" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-800/70 transition group">
                                    <div class="flex items-center gap-2.5 min-w-0 pr-3">
                                        <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded ${badgeColor}">${label}</span>
                                        <span class="text-slate-200 group-hover:text-white font-medium truncate">${item.title}</span>
                                        ${item.subtitle ? `<span class="text-slate-500 text-[11px] truncate hidden sm:inline">&middot; ${item.subtitle}</span>` : ''}
                                    </div>
                                    <span class="text-slate-500 group-hover:text-slate-300 text-xs shrink-0">&rarr;</span>
                                </a>
                            `;
                        }).join('');
                        autocompleteContainer.classList.remove('hidden');
                    } else {
                        autocompleteContainer.innerHTML = `
                            <div class="p-4 text-center text-slate-500">
                                No direct matches found for "<span class="text-slate-300">${query}</span>". Press Enter to perform comprehensive search.
                            </div>
                        `;
                        autocompleteContainer.classList.remove('hidden');
                    }
                } catch (e) {
                    console.error('Autocomplete error:', e);
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', function (e) {
                    clearTimeout(debounceTimer);
                    const q = e.target.value.trim();
                    debounceTimer = setTimeout(() => fetchSuggestions(q), 220);
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
