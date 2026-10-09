<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        if (!\App\Models\Setting::get('enable_newsletter', true)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Newsletter subscriptions are currently paused by site administration.',
                ], 403);
            }
            return back()->with('error', 'Newsletter subscriptions are currently paused.');
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'topics' => 'nullable|string|max:255',
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $validated['email']],
            [
                'topics_interest' => $validated['topics'] ?? 'General Business & Tech Briefing',
                'is_active' => true,
                'subscribed_at' => now(),
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing to AQ NEWSWIRE Executive Briefings.'
            ]);
        }

        return back()->with('success', 'Thank you for subscribing to AQ NEWSWIRE Executive Briefings.');
    }

    public function preferences(Request $request)
    {
        $email = $request->query('email');
        $subscriber = $email ? NewsletterSubscriber::where('email', $email)->first() : null;

        return view('newsletter.preferences', compact('subscriber', 'email'));
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'topics' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $topicsStr = isset($validated['topics']) ? implode(', ', $validated['topics']) : 'General Briefing';

        $subscriber = NewsletterSubscriber::updateOrCreate(
            ['email' => $validated['email']],
            [
                'topics_interest' => $topicsStr,
                'is_active' => $request->boolean('is_active', true),
                'subscribed_at' => now(),
            ]
        );

        return back()->with('success', 'Your briefing delivery preferences have been updated.');
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->input('email');
        if ($email) {
            NewsletterSubscriber::where('email', $email)->update(['is_active' => false]);
        }

        return view('newsletter.unsubscribed', ['email' => $email]);
    }
}
