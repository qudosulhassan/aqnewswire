@extends('layouts.app')

@section('title', 'Apply to Become an AQ NEWSWIRE Contributor — AQ NEWSWIRE')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="text-center mb-10">
        <span class="text-xs uppercase font-extrabold tracking-[0.25em] text-red-600 block mb-2">Editorial Network</span>
        <h1 class="font-headline text-4xl sm:text-5xl font-black text-stone-950 mb-3">Become an AQ NEWSWIRE Contributor</h1>
        <p class="text-stone-600 text-sm max-w-xl mx-auto leading-relaxed">
            Join an elite global network of industry executives, venture partners, research scientists, and market economists publishing insights to over 450,000 monthly decision-makers.
        </p>
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl text-xs sm:text-sm flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <strong class="font-bold">Application Received!</strong>
                <p class="mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-xl text-xs sm:text-sm flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <strong class="font-bold">Notice:</strong>
                <p class="mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($isExistingContributor)
        <div class="bg-white border border-stone-200 rounded-2xl p-8 sm:p-10 shadow-xs text-center space-y-4">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-stone-900">You Already Have Contributor Access</h2>
            <p class="text-xs text-stone-600 max-w-md mx-auto">
                Your account is currently accredited with writing privileges on the AQ NEWSWIRE editorial network.
            </p>
            <div class="pt-2">
                <a href="{{ route('contributor.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-stone-900 hover:bg-black text-white text-xs font-bold uppercase tracking-wider rounded-xl transition">
                    <span>Open Contributor Workspace</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    @elseif($existingApplication && in_array($existingApplication->status, ['pending', 'under_review']))
        <div class="bg-white border border-stone-200 rounded-2xl p-8 sm:p-10 shadow-xs space-y-4">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Application Under Review</span>
            </div>
            <h2 class="text-xl font-bold text-stone-900">Your Submission is Queued</h2>
            <p class="text-xs text-stone-600 leading-relaxed">
                Thank you for applying to join the AQ NEWSWIRE Editorial Network. Your application was submitted on <strong>{{ $existingApplication->created_at->format('F j, Y') }}</strong> and is currently being assessed by the senior editorial board.
            </p>
            <div class="p-4 bg-stone-50 rounded-xl border border-stone-200 text-xs space-y-1">
                <div><strong>Domain of Expertise:</strong> {{ $existingApplication->expertise }}</div>
                <div><strong>Status:</strong> <span class="uppercase font-semibold text-amber-600">{{ str_replace('_', ' ', $existingApplication->status) }}</span></div>
            </div>
        </div>
    @else
        <div class="bg-white border border-stone-200 rounded-2xl p-8 sm:p-10 shadow-xs">
            <form action="{{ route('contributor.apply.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required 
                               class="w-full px-3.5 py-2.5 border border-stone-300 rounded-xl text-sm focus:outline-none focus:border-stone-900">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Professional Email *</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required 
                               class="w-full px-3.5 py-2.5 border border-stone-300 rounded-xl text-sm focus:outline-none focus:border-stone-900">
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Domain of Expertise *</label>
                    <input type="text" name="expertise" value="{{ old('expertise') }}" required placeholder="e.g. Artificial Intelligence, Venture Capital, Macroeconomics, Clean Energy"
                           class="w-full px-3.5 py-2.5 border border-stone-300 rounded-xl text-sm focus:outline-none focus:border-stone-900">
                    @error('expertise') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Executive Biography & Credentials *</label>
                    <textarea name="bio" rows="4" required placeholder="Highlight current title, firm/institution, notable achievements, and academic background..."
                              class="w-full px-3.5 py-2.5 border border-stone-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-stone-900">{{ old('bio') }}</textarea>
                    @error('bio') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Website / Substack</label>
                        <input type="url" name="website" value="{{ old('website') }}" placeholder="https://..."
                               class="w-full px-3.5 py-2.5 border border-stone-300 rounded-xl text-xs focus:outline-none focus:border-stone-900">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">LinkedIn or 𝕏 Profile</label>
                        <input type="text" name="social_links" value="{{ old('social_links') }}" placeholder="linkedin.com/in/username"
                               class="w-full px-3.5 py-2.5 border border-stone-300 rounded-xl text-xs focus:outline-none focus:border-stone-900">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Portfolio Links / Prior Publications</label>
                    <textarea name="portfolio" rows="3" placeholder="Paste links to articles, papers, or op-eds you have authored..."
                              class="w-full px-3.5 py-2.5 border border-stone-300 rounded-xl text-xs focus:outline-none focus:border-stone-900">{{ old('portfolio') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Editorial Pitch / Message to Editors</label>
                    <textarea name="message" rows="3" placeholder="What specific topics or columns do you intend to contribute to AQ NEWSWIRE?"
                              class="w-full px-3.5 py-2.5 border border-stone-300 rounded-xl text-xs focus:outline-none focus:border-stone-900">{{ old('message') }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-widest py-3.5 rounded-xl shadow-xs transition">
                        Submit Contributor Application
                    </button>
                </div>
            </form>
        </div>
    @endif

</div>
@endsection
