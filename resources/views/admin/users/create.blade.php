@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="max-w-[1200px] mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.users.index') }}" class="hover:text-slate-600">Users & Roles</a>
                <span>&rarr;</span>
                <span class="text-slate-700 font-semibold">Create</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Add New User Account</h1>
            <p class="text-xs text-slate-500 mt-0.5">Provision a new administrator, editor, author, or reader account with secure credentials.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                &larr; Back to Users
            </a>
        </div>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1 shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Please correct the errors below before submitting:</span>
            </div>
            @foreach($errors->all() as $err)
                <p class="text-rose-700 ml-6">&bull; {{ $err }}</p>
            @endforeach
        </div>
    @endif

    <!-- Create User Form -->
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left 7 Cols: Primary Identity & Credentials -->
            <div class="lg:col-span-7 space-y-5">
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                        Account Identity & Credentials
                    </h3>

                    <!-- Full Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name *</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="e.g. John Doe"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address *</label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="e.g. john@aqnewswire.com"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">
                    </div>

                    <!-- Password Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password *</label>
                            <input type="password" 
                                   name="password" 
                                   required 
                                   minlength="8" 
                                   placeholder="Min 8 characters"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Confirm Password *</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   required 
                                   minlength="8" 
                                   placeholder="Repeat password"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">
                        </div>
                    </div>
                </div>

                <!-- Editorial Profile Fields -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                        Editorial Byline & Bio (Optional)
                    </h3>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Professional Title</label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title') }}" 
                               placeholder="e.g. Senior Markets Correspondent, Executive Tech Editor"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Author Biography</label>
                        <textarea name="bio" 
                                  rows="3" 
                                  placeholder="Brief career highlights, beats covered, previous journalism experience..."
                                  class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">{{ old('bio') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Avatar Image URL</label>
                        <input type="text" 
                               name="avatar" 
                               value="{{ old('avatar') }}" 
                               placeholder="https://... or leave blank for automatic monogram avatar"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <!-- Right 5 Cols: Role Assignment & Controls -->
            <div class="lg:col-span-5 space-y-5">
                
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                        Role & Access Permissions
                    </h3>

                    <!-- Role Dropdown -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Assign Role *</label>
                        <select name="role" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-red-500">
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role', 'contributor') === $key ? 'selected' : '' }}>
                                    {{ $label }} ({{ $key }})
                                </option>
                            @endforeach
                        </select>
                        <span class="text-[11px] text-slate-400 mt-1 block">
                            Roles determine staff dashboard access and publishing capabilities.
                        </span>
                    </div>

                    <!-- Account Active Status -->
                    <div class="pt-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-red-600 focus:ring-red-500">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Account Active</span>
                                <span class="text-[11px] text-slate-500 block">Active users can authenticate and access the system.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Verified Badge -->
                    <div class="pt-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_verified" value="0">
                            <input type="checkbox" 
                                   name="is_verified" 
                                   value="1" 
                                   {{ old('is_verified') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Verified Staff Journalist</span>
                                <span class="text-[11px] text-slate-500 block">Displays verified checkmark on published stories.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-xs transition transform hover:-translate-y-0.5">
                            Create User Account &rarr;
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </form>

</div>
@endsection
