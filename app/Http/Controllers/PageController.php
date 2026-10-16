<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    /**
     * About Us — Corporate Mission, Charter & Journalistic Standards
     */
    public function about()
    {
        $publishedCount = Article::published()->count();
        $authorCount = User::whereHas('articles', fn($q) => $q->published())->count();

        return view('pages.about', compact('publishedCount', 'authorCount'));
    }

    /**
     * Our Team — Editorial Leadership, Senior Correspondents & Contributors
     */
    public function team()
    {
        $teamMembers = User::where('is_active', true)
            ->whereIn('role', ['super_admin', 'admin', 'editor', 'managing_editor', 'writer', 'contributor'])
            ->withCount(['articles' => fn($q) => $q->published()])
            ->orderByDesc('articles_count')
            ->take(18)
            ->get();

        return view('pages.team', compact('teamMembers'));
    }

    /**
     * Contact & Bureaus — Global Newsrooms & Whistleblower Protocol
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Handle Contact Form Submission
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:160',
            'department' => 'required|string|in:general,editorial,corrections,investigations,legal,advertising',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:15|max:6000',
        ]);

        Log::info('Contact submission received', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'department' => $validated['department'],
            'subject' => $validated['subject'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your transmission has been securely logged and routed to our ' . ucfirst($validated['department']) . ' Bureau. An editor will review and follow up as appropriate.',
        ]);
    }

    /**
     * Advertise — Commercial Partnerships, Institutional Reach & Media Kit
     */
    public function advertise()
    {
        return view('pages.advertise');
    }

    /**
     * Handle Advertising / Sponsorship Inquiry
     */
    public function submitAdvertise(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'company' => 'required|string|max:160',
            'work_email' => 'required|email|max:160',
            'budget' => 'required|string|max:80',
            'campaign_type' => 'required|string|max:80',
            'message' => 'required|string|min:15|max:6000',
        ]);

        Log::info('Advertising inquiry received', [
            'name' => $validated['name'],
            'company' => $validated['company'],
            'work_email' => $validated['work_email'],
            'budget' => $validated['budget'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for contacting AQ NEWSWIRE Commercial Solutions. A senior partnership director will contact you within 24 business hours.',
        ]);
    }

    /**
     * Privacy Policy — GDPR, CCPA/CPRA, and CalOPPA Compliance
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * Terms of Service — Subscriber Terms & Non-Advice Financial Disclaimers
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Cookie Policy — Technical Tracking, Consent & Disclosures
     */
    public function cookies()
    {
        return view('pages.cookies');
    }

    /**
     * Editorial Standards — Verification, Fact-Checking, Corrections & AI Policy
     */
    public function editorialStandards()
    {
        return view('pages.editorial-standards');
    }
}
