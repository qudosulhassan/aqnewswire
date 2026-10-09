<?php

namespace App\Http\Controllers\Contributor;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ContributorApplication;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function show()
    {
        $existingApplication = null;
        $isExistingContributor = false;

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'contributor' || $user->isWriter() || $user->isEditor() || $user->isAdmin()) {
                $isExistingContributor = true;
            }

            $existingApplication = ContributorApplication::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->latest()
                ->first();
        }

        return view('contributor.apply', compact('existingApplication', 'isExistingContributor'));
    }

    public function store(Request $request)
    {
        if (Auth::check()) {
            $request->merge([
                'name' => $request->name ?? Auth::user()->name,
                'email' => $request->email ?? Auth::user()->email,
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'bio' => 'required|string|max:2000',
            'expertise' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'social_links' => 'nullable|string|max:255',
            'portfolio' => 'nullable|string|max:2000',
            'message' => 'nullable|string|max:2000',
            'pitch' => 'nullable|string|max:2000',
        ]);

        // Duplicate application check
        $email = strtolower(trim($validated['email']));
        $userId = Auth::id();

        $activePending = ContributorApplication::where(function ($q) use ($userId, $email) {
            if ($userId) {
                $q->where('user_id', $userId);
            }
            $q->orWhere('email', $email);
        })
        ->whereIn('status', [ContributorApplication::STATUS_PENDING, ContributorApplication::STATUS_UNDER_REVIEW])
        ->first();

        if ($activePending) {
            return back()->withInput()->with('error', 'You already have an active contributor application under editorial review. Our board will reach out to you directly.');
        }

        if (Auth::check() && (Auth::user()->role === 'contributor' || Auth::user()->isWriter())) {
            return redirect()->route('contributor.dashboard')->with('info', 'You already have active writing privileges on AQ NEWSWIRE.');
        }

        $validated['email'] = $email;
        $validated['user_id'] = $userId;
        $validated['status'] = ContributorApplication::STATUS_PENDING;
        if (empty($validated['message']) && !empty($request->input('pitch'))) {
            $validated['message'] = $request->input('pitch');
        }
        unset($validated['pitch']);

        $application = ContributorApplication::create($validated);

        if ($userId) {
            UserNotification::create([
                'user_id' => $userId,
                'type' => 'contributor_application_submitted',
                'title' => 'Contributor Application Received',
                'message' => 'Thank you for applying to join the AQ NEWSWIRE editorial network. Your application is queued for editorial review.',
                'action_url' => route('contributor.apply'),
            ]);
        }

        AuditLog::record('contributor_application_submitted', 'ContributorApplication', $application->id, "Application submitted by {$application->email} ({$application->name})");

        return redirect()->route('contributor.apply')->with('success', 'Your contributor application has been received. Our editorial board reviews applications within 3-5 business days.');
    }
}
