<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSegment;
use App\Models\NewsletterSubscriber;
use App\Models\NewsletterTemplate;
use Illuminate\Http\Request;

class AdminNewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $subscribers = $query->latest('subscribed_at')->paginate(25)->withQueryString();
        $totalActive = NewsletterSubscriber::where('is_active', true)->count();

        return view('admin.newsletters.index', compact('subscribers', 'totalActive'));
    }

    public function toggleStatus(NewsletterSubscriber $subscriber)
    {
        $subscriber->update(['is_active' => !$subscriber->is_active]);
        AuditLog::record('newsletter_subscriber_toggled', 'NewsletterSubscriber', $subscriber->id, "Status changed for {$subscriber->email}");

        return back()->with('success', 'Subscriber status updated.');
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        AuditLog::record('newsletter_subscriber_deleted', 'NewsletterSubscriber', $subscriber->id, "Removed {$subscriber->email}");
        $subscriber->delete();

        return back()->with('success', 'Subscriber removed from distribution list.');
    }

    public function campaigns()
    {
        $campaigns = NewsletterCampaign::with(['template', 'segment'])->latest()->paginate(15);
        $templates = NewsletterTemplate::all();
        $segments = NewsletterSegment::where('is_active', true)->get();

        return view('admin.newsletters.campaigns', compact('campaigns', 'templates', 'segments'));
    }

    public function storeCampaign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'template_id' => 'nullable|exists:newsletter_templates,id',
            'segment_id' => 'nullable|exists:newsletter_segments,id',
            'content' => 'required|string',
            'status' => 'required|in:draft,scheduled',
            'scheduled_for' => 'nullable|date',
        ]);

        $campaign = NewsletterCampaign::create($validated);
        AuditLog::record('newsletter_campaign_created', 'NewsletterCampaign', $campaign->id, "Created campaign: {$campaign->name}");

        return back()->with('success', "Campaign '{$campaign->name}' created successfully.");
    }

    public function segments()
    {
        $segments = NewsletterSegment::withCount('campaigns')->latest()->paginate(15);
        return view('admin.newsletters.segments', compact('segments'));
    }

    public function storeSegment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'criteria_type' => 'required|in:all,active_only,specific_topics',
        ]);

        $segment = NewsletterSegment::create($validated);
        AuditLog::record('newsletter_segment_created', 'NewsletterSegment', $segment->id, "Created segment: {$segment->name}");

        return back()->with('success', "Audience segment '{$segment->name}' created.");
    }

    public function templates()
    {
        $templates = NewsletterTemplate::latest()->paginate(15);
        return view('admin.newsletters.templates', compact('templates'));
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'html_content' => 'required|string',
        ]);

        $template = NewsletterTemplate::create($validated);
        AuditLog::record('newsletter_template_created', 'NewsletterTemplate', $template->id, "Created template: {$template->name}");

        return back()->with('success', "Newsletter template '{$template->name}' saved.");
    }

    public function analytics()
    {
        $totalSubscribers = NewsletterSubscriber::count();
        $activeSubscribers = NewsletterSubscriber::where('is_active', true)->count();
        $totalCampaigns = NewsletterCampaign::count();
        $sentCampaigns = NewsletterCampaign::where('status', 'sent')->count();
        $recentCampaigns = NewsletterCampaign::latest()->take(5)->get();

        return view('admin.newsletters.analytics', compact(
            'totalSubscribers',
            'activeSubscribers',
            'totalCampaigns',
            'sentCampaigns',
            'recentCampaigns'
        ));
    }

    public function exportCsv()
    {
        $subscribers = NewsletterSubscriber::all();
        $fileName = 'aqnewswire_subscribers_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Email', 'Active', 'Frequency', 'Subscribed At']);

            foreach ($subscribers as $s) {
                fputcsv($file, [
                    $s->id,
                    $s->email,
                    $s->is_active ? 'Active' : 'Inactive',
                    $s->frequency ?? 'daily',
                    $s->created_at ? $s->created_at->toDateTimeString() : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

