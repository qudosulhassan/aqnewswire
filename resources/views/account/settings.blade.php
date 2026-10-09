@extends('layouts.app')

@section('title', 'Account Settings — AQ NEWSWIRE')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="pb-6 border-b border-stone-200 mb-8">
        <a href="{{ route('account.dashboard') }}" class="text-xs text-stone-500 hover:text-stone-900 font-semibold">&larr; Back to Dashboard</a>
        <h1 class="font-headline text-3xl font-bold text-stone-950 mt-1">Account & Profile Settings</h1>
    </div>

    <div class="bg-white border border-stone-200 rounded p-8 shadow-xs">
        <form action="{{ route('account.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Display Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                       class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Email Address</label>
                <input type="email" value="{{ $user->email }}" disabled 
                       class="w-full px-3 py-2 border border-stone-200 bg-stone-50 rounded text-sm text-stone-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Bio / Professional Title</label>
                <textarea name="bio" rows="3" placeholder="A brief description of your background..."
                          class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Personal / Company Website</label>
                    <input type="url" name="website" value="{{ old('website', $user->website) }}" placeholder="https://..."
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">𝕏 / Twitter Handle</label>
                    <input type="text" name="twitter" value="{{ old('twitter', $user->twitter) }}" placeholder="@handle"
                           class="w-full px-3 py-2 border border-stone-300 rounded text-xs focus:outline-none focus:border-stone-900">
                </div>
            </div>

            <div class="pt-4 border-t border-stone-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-stone-900 mb-3">Change Password (Leave blank to keep current)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">New Password</label>
                        <input type="password" name="password" class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-stone-300 rounded text-sm focus:outline-none focus:border-stone-900">
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="bg-stone-900 hover:bg-black text-white font-bold text-xs uppercase tracking-widest px-6 py-3 rounded transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
