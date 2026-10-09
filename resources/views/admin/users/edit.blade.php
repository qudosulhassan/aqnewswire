@extends('layouts.admin')

@section('title', 'Edit User — ' . $user->name)

@section('content')
<div class="max-w-[1200px] mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.users.index') }}" class="hover:text-slate-600">Users & Roles</a>
                <span>&rarr;</span>
                <span class="text-slate-700 font-semibold">{{ $user->name }}</span>
                <span>&rarr;</span>
                <span class="text-slate-400">Edit</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span>Edit User Account</span>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold uppercase border {{ $user->role_badge_class }}">
                    {{ $user->role_name }}
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Modify profile attributes, reassign roles, or update account operational statuses.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.show', $user) }}" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                View Profile
            </a>
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

    <!-- Edit User Form -->
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left 7 Cols: Primary Identity -->
            <div class="lg:col-span-7 space-y-5">
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                        Account Details
                    </h3>

                    <!-- Full Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name *</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address *</label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                    </div>

                    <!-- Password Info Note -->
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Password Security</span>
                            <span class="text-[11px] text-slate-500 block">Passphrases are securely encrypted. Do you need to update it?</span>
                        </div>
                        <button type="button" 
                                onclick="openEditResetPasswordModal()"
                                class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition shadow-2xs">
                            Reset Password
                        </button>
                    </div>
                </div>

                <!-- Editorial Profile Fields -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                        Editorial Profile & Byline
                    </h3>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Professional Title</label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title', $user->title) }}" 
                               placeholder="e.g. Senior Technology Editor"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Author Biography</label>
                        <textarea name="bio" 
                                  rows="3" 
                                  class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Avatar Image URL</label>
                        <input type="text" 
                               name="avatar" 
                               value="{{ old('avatar', $user->avatar) }}" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-red-500 focus:bg-white transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Website</label>
                            <input type="text" name="website" value="{{ old('website', $user->website) }}" placeholder="https://..." class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Twitter / X</label>
                            <input type="text" name="twitter" value="{{ old('twitter', $user->twitter) }}" placeholder="@handle" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">LinkedIn</label>
                            <input type="text" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}" placeholder="username" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 5 Cols: Role & Access Controls -->
            <div class="lg:col-span-5 space-y-5">
                
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                        Role & Security Configuration
                    </h3>

                    <!-- Role Dropdown -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">User Role *</label>
                        <select name="role" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-red-500">
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role', $user->role) === $key ? 'selected' : '' }}>
                                    {{ $label }} ({{ $key }})
                                </option>
                            @endforeach
                        </select>
                        @if($user->id === Auth::id())
                            <span class="text-[11px] text-amber-600 mt-1 block">
                                ⚠ You are editing your currently active administrative account.
                            </span>
                        @endif
                    </div>

                    <!-- Account Active Status -->
                    <div class="pt-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-red-600 focus:ring-red-500">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Account Active</span>
                                <span class="text-[11px] text-slate-500 block">Disable to prevent sign-ins without deleting editorial records.</span>
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
                                   {{ old('is_verified', $user->is_verified) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Verified Staff Journalist</span>
                                <span class="text-[11px] text-slate-500 block">Displays verified checkmark on published stories.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Timestamps Audit Info -->
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5 text-[11px] text-slate-500">
                        <div class="flex justify-between">
                            <span>Created:</span>
                            <span class="font-semibold text-slate-700">{{ $user->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Last Updated:</span>
                            <span class="font-semibold text-slate-700">{{ $user->updated_at->format('M j, Y g:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Last Login:</span>
                            <span class="font-semibold text-slate-700">{{ $user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never' }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-xs transition transform hover:-translate-y-0.5">
                            Save Changes &rarr;
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </form>

    <!-- Modal: Reset Password (Vanilla JS / Hidden by default) -->
    <div id="editResetPasswordModal" 
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
                <button type="button" onclick="closeEditResetPasswordModal()" class="text-slate-400 hover:text-slate-700 text-sm font-bold">&times;</button>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">
                Set a secure new password for <strong class="text-slate-900">{{ $user->name }}</strong>.
            </p>

            <form action="{{ route('admin.users.resetPassword', $user) }}" method="POST" class="space-y-4">
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
                    <button type="button" onclick="closeEditResetPasswordModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Vanilla JS Modal Logic -->
<script>
    function openEditResetPasswordModal() {
        document.getElementById('editResetPasswordModal').classList.remove('hidden');
    }

    function closeEditResetPasswordModal() {
        document.getElementById('editResetPasswordModal').classList.add('hidden');
    }

    document.getElementById('editResetPasswordModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeEditResetPasswordModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEditResetPasswordModal();
    });
</script>
@endsection
