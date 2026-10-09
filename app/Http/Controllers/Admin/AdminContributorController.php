<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ContributorApplication;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminContributorController extends Controller
{
    /**
     * Display a listing of contributor applications and active contributors.
     */
    public function index(Request $request)
    {
        $this->authorizeAccess();

        $activeTab = $request->query('tab', 'applications');
        $status = $request->query('status', 'all');
        $search = trim($request->query('search', ''));
        $expertise = $request->query('expertise', 'all');
        $sort = $request->query('sort', 'newest');

        // Real Database KPIs (Zero Fake Metrics)
        $kpis = [
            'total_applications' => ContributorApplication::count(),
            'pending' => ContributorApplication::pending()->count(),
            'under_review' => ContributorApplication::underReview()->count(),
            'changes_requested' => ContributorApplication::changesRequested()->count(),
            'approved' => ContributorApplication::approved()->count(),
            'rejected' => ContributorApplication::rejected()->count(),
            'active_contributors' => User::where('role', 'contributor')->count(),
        ];

        // Query Contributor Applications
        $applicationsQuery = ContributorApplication::with(['user', 'reviewer']);

        if ($status !== 'all' && in_array($status, [
            ContributorApplication::STATUS_PENDING,
            ContributorApplication::STATUS_UNDER_REVIEW,
            ContributorApplication::STATUS_CHANGES_REQUESTED,
            ContributorApplication::STATUS_APPROVED,
            ContributorApplication::STATUS_REJECTED,
        ])) {
            $applicationsQuery->where('status', $status);
        }

        if ($expertise !== 'all' && !empty($expertise)) {
            $applicationsQuery->where('expertise', $expertise);
        }

        if (!empty($search)) {
            $applicationsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('expertise', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%")
                  ->orWhere('portfolio', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        switch ($sort) {
            case 'oldest':
                $applicationsQuery->oldest();
                break;
            case 'name_asc':
                $applicationsQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $applicationsQuery->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $applicationsQuery->latest();
                break;
        }

        $applications = $applicationsQuery->paginate(15)->withQueryString();

        // Query Active Contributors (Accredited Users with role = 'contributor')
        $contributorsQuery = User::where('role', 'contributor')
            ->withCount([
                'articles as total_articles_count',
                'articles as published_articles_count' => function ($q) {
                    $q->where('status', 'published');
                },
                'articles as draft_articles_count' => function ($q) {
                    $q->where('status', 'draft');
                },
            ])
            ->withSum('articles as total_views', 'view_count');

        if (!empty($search) && $activeTab === 'contributors') {
            $contributorsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        $activeContributors = $contributorsQuery->latest()->paginate(15, ['*'], 'contrib_page')->withQueryString();

        // Unique expertise categories for the filter dropdown
        $expertiseList = ContributorApplication::select('expertise')
            ->whereNotNull('expertise')
            ->where('expertise', '!=', '')
            ->distinct()
            ->pluck('expertise');

        return view('admin.contributors.index', compact(
            'applications',
            'activeContributors',
            'kpis',
            'activeTab',
            'status',
            'search',
            'expertise',
            'sort',
            'expertiseList'
        ));
    }

    /**
     * Show application details (returns JSON for modal or view).
     */
    public function show(Request $request, ContributorApplication $application)
    {
        $this->authorizeAccess();

        $application->load(['user', 'reviewer']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'application' => [
                    'id' => $application->id,
                    'name' => $application->name,
                    'email' => $application->email,
                    'bio' => $application->bio,
                    'expertise' => $application->expertise,
                    'website' => $application->website,
                    'social_links' => $application->social_links,
                    'portfolio' => $application->portfolio,
                    'message' => $application->message,
                    'status' => $application->status,
                    'status_label' => ContributorApplication::STATUSES[$application->status] ?? ucfirst($application->status),
                    'admin_notes' => $application->admin_notes,
                    'applied_at' => $application->created_at->format('M j, Y \a\t g:i A'),
                    'reviewed_at' => $application->reviewed_at ? $application->reviewed_at->format('M j, Y \a\t g:i A') : null,
                    'reviewer_name' => $application->reviewer ? $application->reviewer->name : null,
                    'has_user_account' => (bool) $application->user_id,
                    'user' => $application->user ? [
                        'id' => $application->user->id,
                        'name' => $application->user->name,
                        'email' => $application->user->email,
                        'role' => $application->user->role,
                        'is_active' => $application->user->is_active,
                    ] : null,
                ],
            ]);
        }

        return redirect()->route('admin.contributors.index', ['status' => $application->status]);
    }

    /**
     * Approve a contributor application.
     */
    public function approve(Request $request, ContributorApplication $application)
    {
        $this->authorizeAccess();

        $notes = $request->input('admin_notes');

        $application->update([
            'status' => ContributorApplication::STATUS_APPROVED,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $notes ?: $application->admin_notes,
        ]);

        // Resolve or create user account
        $user = null;
        if ($application->user_id) {
            $user = User::find($application->user_id);
        }

        if (!$user) {
            $user = User::where('email', strtolower($application->email))->first();
        }

        if ($user) {
            // Promote to contributor
            $user->update([
                'role' => 'contributor',
                'title' => $user->title ?: ($application->expertise . ' Contributor'),
                'bio' => $user->bio ?: $application->bio,
                'website' => $user->website ?: $application->website,
                'is_verified' => true,
                'is_active' => true,
            ]);

            if (!$application->user_id) {
                $application->update(['user_id' => $user->id]);
            }

            // Send notification to user
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'contributor_approved',
                'title' => 'Contributor Application Approved!',
                'message' => 'Congratulations! Your application to become an accredited AQ NEWSWIRE contributor has been approved. You now have full access to the Contributor Portal.',
                'action_url' => route('contributor.dashboard'),
            ]);
        } else {
            // Create user account with contributor role
            $tempPassword = Str::random(16);
            $user = User::create([
                'name' => $application->name,
                'email' => strtolower($application->email),
                'password' => Hash::make($tempPassword),
                'role' => 'contributor',
                'title' => $application->expertise . ' Contributor',
                'bio' => $application->bio,
                'website' => $application->website,
                'is_verified' => true,
                'is_active' => true,
                'slug' => Str::slug($application->name) . '-' . Str::random(4),
            ]);

            $application->update(['user_id' => $user->id]);

            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'contributor_approved',
                'title' => 'Welcome to AQ NEWSWIRE Contributors',
                'message' => 'Your contributor application has been approved. Your contributor account is active.',
                'action_url' => route('contributor.dashboard'),
            ]);
        }

        AuditLog::record(
            'contributor_approved',
            'ContributorApplication',
            $application->id,
            "Approved application for {$application->email} ({$application->name}). Contributor privileges activated."
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Contributor application approved for {$application->name}. Role elevated to Contributor.",
                'status' => ContributorApplication::STATUS_APPROVED,
            ]);
        }

        return back()->with('success', "Contributor application approved for {$application->name}. Contributor credentials activated.");
    }

    /**
     * Mark an application as under editorial review.
     */
    public function moveToReview(Request $request, ContributorApplication $application)
    {
        $this->authorizeAccess();

        $notes = $request->input('admin_notes');

        $application->update([
            'status' => ContributorApplication::STATUS_UNDER_REVIEW,
            'reviewed_by' => Auth::id(),
            'admin_notes' => $notes ?: $application->admin_notes,
        ]);

        if ($application->user_id) {
            UserNotification::create([
                'user_id' => $application->user_id,
                'type' => 'contributor_under_review',
                'title' => 'Application Under Review',
                'message' => 'Your contributor application is currently under formal review by our senior editorial board.',
                'action_url' => route('contributor.apply'),
            ]);
        }

        AuditLog::record(
            'contributor_under_review',
            'ContributorApplication',
            $application->id,
            "Application placed Under Review for {$application->email}"
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Application for {$application->name} moved to Under Review.",
                'status' => ContributorApplication::STATUS_UNDER_REVIEW,
            ]);
        }

        return back()->with('success', "Application for {$application->name} moved to Under Review.");
    }

    /**
     * Request additional information or changes from the applicant.
     */
    public function requestChanges(Request $request, ContributorApplication $application)
    {
        $this->authorizeAccess();

        $request->validate([
            'admin_notes' => 'required|string|max:2000',
        ]);

        $notes = $request->input('admin_notes');

        $application->update([
            'status' => ContributorApplication::STATUS_CHANGES_REQUESTED,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $notes,
        ]);

        if ($application->user_id) {
            UserNotification::create([
                'user_id' => $application->user_id,
                'type' => 'contributor_changes_requested',
                'title' => 'Editorial Feedback on Contributor Application',
                'message' => 'Our editorial board requested additional details: ' . Str::limit($notes, 150),
                'action_url' => route('contributor.apply'),
            ]);
        }

        AuditLog::record(
            'contributor_changes_requested',
            'ContributorApplication',
            $application->id,
            "Changes requested for {$application->email}: {$notes}"
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Editorial feedback sent to {$application->name}.",
                'status' => ContributorApplication::STATUS_CHANGES_REQUESTED,
            ]);
        }

        return back()->with('success', "Editorial feedback recorded for {$application->name}.");
    }

    /**
     * Reject a contributor application.
     */
    public function reject(Request $request, ContributorApplication $application)
    {
        $this->authorizeAccess();

        $notes = $request->input('admin_notes');

        $application->update([
            'status' => ContributorApplication::STATUS_REJECTED,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $notes ?: $application->admin_notes,
        ]);

        if ($application->user_id) {
            UserNotification::create([
                'user_id' => $application->user_id,
                'type' => 'contributor_rejected',
                'title' => 'Update on Your Contributor Application',
                'message' => 'Thank you for your interest in AQ NEWSWIRE. At this time, our editorial board is unable to accept your contributor application.',
                'action_url' => route('contributor.apply'),
            ]);
        }

        AuditLog::record(
            'contributor_rejected',
            'ContributorApplication',
            $application->id,
            "Rejected application for {$application->email}"
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Application for {$application->name} marked as rejected.",
                'status' => ContributorApplication::STATUS_REJECTED,
            ]);
        }

        return back()->with('success', "Contributor application rejected for {$application->name}.");
    }

    /**
     * Export contributor applications to CSV format.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $this->authorizeAccess();

        $status = $request->query('status', 'all');

        $query = ContributorApplication::with(['reviewer']);
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $applications = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="aqnewswire-contributor-applications-' . now()->format('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($applications) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID',
                'Name',
                'Email',
                'Expertise',
                'Website',
                'Social Profile',
                'Portfolio Clips',
                'Editorial Pitch',
                'Status',
                'Applied At',
                'Reviewed At',
                'Reviewer',
                'Review Notes',
            ]);

            foreach ($applications as $app) {
                fputcsv($handle, [
                    $app->id,
                    $app->name,
                    $app->email,
                    $app->expertise,
                    $app->website,
                    $app->social_links,
                    $app->portfolio,
                    $app->message,
                    $app->status,
                    $app->created_at->format('Y-m-d H:i:s'),
                    $app->reviewed_at ? $app->reviewed_at->format('Y-m-d H:i:s') : '',
                    $app->reviewer ? $app->reviewer->name : '',
                    $app->admin_notes,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Verify user has editorial or administrative privileges.
     */
    protected function authorizeAccess(): void
    {
        $user = Auth::user();
        if (!$user || (!$user->isEditor() && !$user->isAdmin())) {
            abort(403, 'Unauthorized. Editorial or administrative privileges are required to manage contributors.');
        }
    }
}
