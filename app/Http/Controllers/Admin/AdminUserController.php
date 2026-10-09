<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users with live KPIs, search, filters, and pagination.
     */
    public function index(Request $request)
    {
        // 1. Live dynamic KPI counts directly calculated from User database
        $kpis = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
            'editors' => User::whereIn('role', ['editor', 'managing_editor'])->count(),
            'contributors' => User::whereIn('role', ['contributor', 'writer'])->count(),
            'subscribers' => User::whereIn('role', ['subscriber', 'reader'])->count(),
        ];

        // 2. Base Query with Article counts
        $query = User::query()->withCount('articles');

        // 3. Search filter (name, email)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // 4. Role filter
        if ($request->filled('role') && array_key_exists($request->role, User::ROLES)) {
            $query->where('role', $request->role);
        }

        // 5. Status filter (active / inactive)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // 6. Date created filter
        if ($request->filled('date')) {
            match ($request->date) {
                'today' => $query->where('created_at', '>=', now()->startOfDay()),
                '7d' => $query->where('created_at', '>=', now()->subDays(7)),
                '30d' => $query->where('created_at', '>=', now()->subDays(30)),
                'year' => $query->where('created_at', '>=', now()->subYear()),
                default => null,
            };
        }

        // 7. Whitelisted sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest' => $query->orderBy('created_at', 'asc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'last_login' => $query->orderByDesc('last_login_at'),
            'articles' => $query->orderByDesc('articles_count'),
            default => $query->orderByDesc('created_at'),
        };

        // 8. Whitelisted Pagination
        $perPage = (int) $request->input('per_page', 25);
        if (!in_array($perPage, [25, 50, 100], true)) {
            $perPage = 25;
        }

        $users = $query->paginate($perPage)->withQueryString();
        $roles = User::ROLES;
        $otherAdmins = User::whereIn('role', ['admin', 'super_admin'])->get(['id', 'name', 'email']);

        return view('admin.users.index', compact('users', 'kpis', 'roles', 'otherAdmins'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = User::ROLES;
        // Non-super-admins cannot assign super_admin
        if (Auth::user()->role !== 'super_admin') {
            unset($roles['super_admin']);
        }

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $allowedRoles = array_keys(User::ROLES);
        if (Auth::user()->role !== 'super_admin') {
            $allowedRoles = array_diff($allowedRoles, ['super_admin']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in($allowedRoles)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
            'title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'is_verified' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active', true),
            'title' => $validated['title'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'avatar' => $validated['avatar'] ?? null,
            'is_verified' => $request->boolean('is_verified', false),
            'slug' => Str::slug($validated['name']) . '-' . Str::random(4),
        ]);

        AuditLog::record(
            'user_created',
            'User',
            $user->id,
            "Created user {$user->email} with role '{$user->role}' by " . Auth::user()->email
        );

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' was created successfully.");
    }

    /**
     * Display the specified user details with editorial statistics.
     */
    public function show(User $user)
    {
        $articlesCount = $user->articles()->count();
        $publishedCount = $user->articles()->where('status', 'published')->count();
        $draftCount = $user->articles()->where('status', 'draft')->count();
        $submittedCount = $user->articles()->where('status', 'submitted')->count();
        $commentsCount = $user->comments()->count();
        $bookmarksCount = $user->bookmarks()->count();

        $recentArticles = $user->articles()->with('category')->latest()->take(6)->get();
        $auditLogs = AuditLog::where(function ($q) use ($user) {
            $q->where(function ($sub) use ($user) {
                $sub->where('entity_type', 'User')->where('entity_id', $user->id);
            })->orWhere('user_id', $user->id);
        })->latest()->take(8)->get();

        return view('admin.users.show', compact(
            'user',
            'articlesCount',
            'publishedCount',
            'draftCount',
            'submittedCount',
            'commentsCount',
            'bookmarksCount',
            'recentArticles',
            'auditLogs'
        ));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = User::ROLES;
        if (Auth::user()->role !== 'super_admin') {
            unset($roles['super_admin']);
        }

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $allowedRoles = array_keys(User::ROLES);
        if (Auth::user()->role !== 'super_admin') {
            $allowedRoles = array_diff($allowedRoles, ['super_admin']);
        }

        // 1. Self demotion check
        if ($user->id === Auth::id() && $request->role !== $user->role && !in_array($request->role, ['admin', 'super_admin'], true)) {
            return back()->withErrors(['role' => 'You cannot demote your own administrator account to a lower role.']);
        }

        // 2. Final administrator protection
        if ($user->isAdmin() && !in_array($request->role, ['admin', 'super_admin'], true)) {
            $remainingAdmins = User::whereIn('role', ['admin', 'super_admin'])->where('id', '!=', $user->id)->count();
            if ($remainingAdmins === 0) {
                return back()->withErrors(['role' => 'Cannot change role. At least one administrator account must remain in the system.']);
            }
        }

        // 3. Self deactivation check
        if ($user->id === Auth::id() && !$request->boolean('is_active', true)) {
            return back()->withErrors(['is_active' => 'You cannot deactivate your own administrative account.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in($allowedRoles)],
            'is_active' => ['nullable', 'boolean'],
            'title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'is_verified' => ['nullable', 'boolean'],
        ]);

        $oldRole = $user->role;
        $roleChanged = $oldRole !== $validated['role'];

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
            'title' => $validated['title'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'avatar' => $validated['avatar'] ?? null,
            'website' => $validated['website'] ?? null,
            'twitter' => $validated['twitter'] ?? null,
            'linkedin' => $validated['linkedin'] ?? null,
            'is_verified' => $request->boolean('is_verified', false),
        ]);

        if ($roleChanged) {
            AuditLog::record(
                'role_changed',
                'User',
                $user->id,
                "Role changed from '{$oldRole}' to '{$user->role}' by " . Auth::user()->email
            );
        }

        AuditLog::record(
            'user_updated',
            'User',
            $user->id,
            "User {$user->email} profile updated by " . Auth::user()->email
        );

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' was updated successfully.");
    }

    /**
     * Toggle user status (active <-> inactive).
     */
    public function toggleStatus(Request $request, User $user)
    {
        // 1. Self deactivation protection
        if ($user->id === Auth::id()) {
            $msg = 'You cannot deactivate your own administrative account.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        // 2. Final active administrator protection
        if ($user->isAdmin() && $user->is_active) {
            $otherActiveAdmins = User::whereIn('role', ['admin', 'super_admin'])
                ->where('is_active', true)
                ->where('id', '!=', $user->id)
                ->count();

            if ($otherActiveAdmins === 0) {
                $msg = 'Cannot deactivate the final remaining active administrator account in the system.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return back()->with('error', $msg);
            }
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $action = $user->is_active ? 'user_activated' : 'user_deactivated';
        AuditLog::record(
            $action,
            'User',
            $user->id,
            "User {$user->email} " . ($user->is_active ? 'activated' : 'deactivated') . " by " . Auth::user()->email
        );

        $msg = $user->is_active 
            ? "User '{$user->name}' activated successfully." 
            : "User '{$user->name}' deactivated successfully.";

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'is_active' => $user->is_active, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Secure password reset initiated by administrator.
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        AuditLog::record(
            'password_reset',
            'User',
            $user->id,
            "Password reset for {$user->email} by administrator " . Auth::user()->email
        );

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Password updated successfully for '{$user->name}'."]);
        }

        return back()->with('success', "Password updated successfully for '{$user->name}'.");
    }

    /**
     * Remove the specified user with article preservation/reassignment safeguards.
     */
    public function destroy(Request $request, User $user)
    {
        // 1. Self deletion protection
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // 2. Final administrator protection
        if ($user->isAdmin()) {
            $remainingAdmins = User::whereIn('role', ['admin', 'super_admin'])->where('id', '!=', $user->id)->count();
            if ($remainingAdmins === 0) {
                return back()->with('error', 'Cannot delete the final remaining administrator in the system.');
            }
        }

        // 3. Editorial content preservation check
        $articleCount = $user->articles()->count();
        if ($articleCount > 0) {
            $reassignToId = $request->input('reassign_to');
            if ($reassignToId) {
                $target = User::find($reassignToId);
                if (!$target) {
                    return back()->with('error', 'Invalid article reassignment target user.');
                }
                $user->articles()->update(['user_id' => $target->id]);
                AuditLog::record(
                    'articles_reassigned',
                    'User',
                    $user->id,
                    "Reassigned {$articleCount} articles from {$user->email} to {$target->email} prior to deletion"
                );
            } else {
                return back()->with('error', "Cannot delete user. '{$user->name}' has authored {$articleCount} articles. Please reassign their articles before deletion, or deactivate their account instead.");
            }
        }

        $userName = $user->name;
        $userEmail = $user->email;

        // Delete user
        $user->delete();

        AuditLog::record(
            'user_deleted',
            'User',
            null,
            "Deleted user {$userEmail} by administrator " . Auth::user()->email
        );

        return redirect()->route('admin.users.index')->with('success', "User '{$userName}' was deleted successfully.");
    }

    /**
     * Bulk actions for active/inactive user management.
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:activate,deactivate'],
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $currentUserId = Auth::id();
        $processed = 0;

        foreach ($validated['user_ids'] as $id) {
            $user = User::find($id);
            if (!$user) continue;

            // Protection: cannot bulk-deactivate self
            if ($validated['action'] === 'deactivate' && $user->id === $currentUserId) {
                continue;
            }

            // Protection: cannot bulk-deactivate final admin
            if ($validated['action'] === 'deactivate' && $user->isAdmin()) {
                $otherActive = User::whereIn('role', ['admin', 'super_admin'])
                    ->where('is_active', true)
                    ->where('id', '!=', $user->id)
                    ->count();
                if ($otherActive === 0) continue;
            }

            $user->is_active = ($validated['action'] === 'activate');
            $user->save();
            $processed++;

            AuditLog::record(
                $validated['action'] === 'activate' ? 'user_activated' : 'user_deactivated',
                'User',
                $user->id,
                "Bulk {$validated['action']} executed by " . Auth::user()->email
            );
        }

        return back()->with('success', "Bulk operation completed. {$processed} user accounts updated.");
    }
}
