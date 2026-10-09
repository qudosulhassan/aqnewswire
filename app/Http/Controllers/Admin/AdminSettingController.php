<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminSettingController extends Controller
{
    /**
     * Default canonical settings definition matrix.
     */
    public const SETTINGS_DEFINITIONS = [
        'site_name' => ['string', 'general'],
        'site_description' => ['text', 'general'],
        'site_url' => ['string', 'general'],
        'copyright_text' => ['string', 'general'],

        'short_brand_name' => ['string', 'branding'],
        'logo_url' => ['string', 'branding'],
        'favicon_url' => ['string', 'branding'],

        'primary_email' => ['string', 'contact'],
        'editorial_email' => ['string', 'contact'],
        'support_email' => ['string', 'contact'],
        'contact_phone' => ['string', 'contact'],
        'office_address' => ['text', 'contact'],
        'contact_page_url' => ['string', 'contact'],

        'timezone' => ['string', 'localization'],
        'default_locale' => ['string', 'localization'],
        'date_format' => ['string', 'localization'],
        'time_format' => ['string', 'localization'],

        'social_linkedin' => ['string', 'social'],
        'social_twitter' => ['string', 'social'],
        'social_facebook' => ['string', 'social'],
        'social_instagram' => ['string', 'social'],
        'social_youtube' => ['string', 'social'],

        'enable_comments' => ['boolean', 'reader'],
        'enable_newsletter' => ['boolean', 'reader'],
        'enable_registration' => ['boolean', 'reader'],
        'enable_bookmarks' => ['boolean', 'reader'],

        'default_reading_words_per_minute' => ['integer', 'editorial'],
        'default_article_status' => ['string', 'editorial'],
        'enable_reading_history' => ['boolean', 'editorial'],
    ];

    public const DEFAULT_VALUES = [
        'general' => [
            'site_name' => 'AQ NEWSWIRE International Business & Leadership',
            'site_description' => 'Global business journalism, executive analysis, and market intelligence.',
            'site_url' => 'http://127.0.0.1:8000',
            'copyright_text' => '© 2026 AQ NEWSWIRE Group Inc. All rights reserved.',
        ],
        'branding' => [
            'short_brand_name' => 'AQ NEWSWIRE',
            'logo_url' => '/images/aqnewswire-logo.svg',
            'favicon_url' => '/favicon.ico',
        ],
        'contact' => [
            'primary_email' => 'contact@aqnewswire.com',
            'editorial_email' => 'editorial@aqnewswire.com',
            'support_email' => 'support@aqnewswire.com',
            'contact_phone' => '+1 (555) 019-2834',
            'office_address' => '100 Financial District, Suite 4200, New York, NY 10005',
            'contact_page_url' => '/contact',
        ],
        'localization' => [
            'timezone' => 'UTC',
            'default_locale' => 'en',
            'date_format' => 'F j, Y',
            'time_format' => 'g:i A',
        ],
        'social' => [
            'social_linkedin' => 'https://linkedin.com/company/aqnewswire',
            'social_twitter' => 'https://twitter.com/aqnewswire',
            'social_facebook' => 'https://facebook.com/aqnewswire',
            'social_instagram' => 'https://instagram.com/aqnewswire',
            'social_youtube' => 'https://youtube.com/@aqnewswire',
        ],
        'reader' => [
            'enable_comments' => true,
            'enable_newsletter' => true,
            'enable_registration' => true,
            'enable_bookmarks' => true,
        ],
        'editorial' => [
            'default_reading_words_per_minute' => 220,
            'default_article_status' => 'draft',
            'enable_reading_history' => true,
        ],
    ];

    /**
     * Display the General Settings administrative dashboard.
     */
    public function index()
    {
        $settings = Setting::getAllGrouped();
        $timezones = DateTimeZone::listIdentifiers();

        // Query database health
        $dbConnected = false;
        try {
            DB::connection()->getPdo();
            $dbConnected = true;
        } catch (\Throwable $e) {
            $dbConnected = false;
        }

        // Query cache health
        $cacheWorking = false;
        try {
            Cache::put('health_check_probe', 1, 10);
            $cacheWorking = Cache::get('health_check_probe') === 1;
        } catch (\Throwable $e) {
            $cacheWorking = false;
        }

        // Query storage health
        $storageWritable = is_writable(storage_path('framework/views')) && is_writable(storage_path('logs'));

        // Production security check
        $isProductionWithDebug = config('app.env') === 'production' && config('app.debug') === true;

        // System & Operations read-only diagnostics (NO SENSITIVE SECRETS EXPOSED)
        $systemDiagnostics = [
            'app_env' => config('app.env', 'production'),
            'app_debug' => config('app.debug') ? 'Enabled (Warning in Production)' : 'Disabled (Secure)',
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'database_driver' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'filesystem_driver' => config('filesystems.default'),
            'session_driver' => config('session.driver'),
            'db_connected' => $dbConnected,
            'cache_working' => $cacheWorking,
            'storage_writable' => $storageWritable,
            'is_prod_debug_danger' => $isProductionWithDebug,
            'app_url_configured' => config('app.url'),
        ];

        // Retrieve last saved timestamp from real database record
        $latestSetting = Setting::orderBy('updated_at', 'desc')->first();
        $lastSavedFormatted = $latestSetting && $latestSetting->updated_at 
            ? Carbon::parse($latestSetting->updated_at)->timezone(Setting::get('timezone', 'UTC'))->format('M j, Y g:i A')
            : 'Initial Configuration';

        return view('admin.settings.index', compact(
            'settings', 
            'timezones', 
            'systemDiagnostics', 
            'lastSavedFormatted'
        ));
    }

    /**
     * Update application settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // General
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'site_url' => 'required|url|max:255',
            'copyright_text' => 'nullable|string|max:255',

            // Branding
            'short_brand_name' => 'required|string|max:50',
            'logo_url' => 'nullable|string|max:255',
            'favicon_url' => 'nullable|string|max:255',

            // Contact
            'primary_email' => 'nullable|email|max:255',
            'editorial_email' => 'nullable|email|max:255',
            'support_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'office_address' => 'nullable|string|max:500',
            'contact_page_url' => 'nullable|string|max:255',

            // Localization
            'timezone' => ['required', 'string', Rule::in(DateTimeZone::listIdentifiers())],
            'default_locale' => ['required', 'string', 'in:en,es,fr,de,ar,zh,ja,ur'],
            'date_format' => 'required|string|max:30',
            'time_format' => 'required|string|max:30',

            // Social (Must use HTTPS)
            'social_linkedin' => 'nullable|url|max:255|starts_with:https://',
            'social_twitter' => 'nullable|url|max:255|starts_with:https://',
            'social_facebook' => 'nullable|url|max:255|starts_with:https://',
            'social_instagram' => 'nullable|url|max:255|starts_with:https://',
            'social_youtube' => 'nullable|url|max:255|starts_with:https://',

            // Reader Experience
            'enable_comments' => 'nullable|boolean',
            'enable_newsletter' => 'nullable|boolean',
            'enable_registration' => 'nullable|boolean',
            'enable_bookmarks' => 'nullable|boolean',

            // Editorial Defaults
            'default_reading_words_per_minute' => 'required|integer|min:50|max:500',
            'default_article_status' => 'required|in:draft,published',
            'enable_reading_history' => 'nullable|boolean',
        ]);

        $changedKeys = [];
        foreach (self::SETTINGS_DEFINITIONS as $key => [$type, $group]) {
            if ($type === 'boolean') {
                $val = $request->boolean($key);
            } else {
                $val = $validated[$key] ?? null;
            }

            $current = Setting::get($key);
            if ($current !== $val) {
                $changedKeys[] = $key;
            }

            Setting::set($key, $val, $type, $group);
        }

        // Clear settings cache
        Setting::clearCache();

        // Audit Logging (Do not record sensitive secrets!)
        AuditLog::record(
            'general_settings_updated',
            'Setting',
            null,
            'Updated global settings: ' . (count($changedKeys) > 0 ? implode(', ', $changedKeys) : 'all settings verified')
        );

        return redirect()->route('admin.settings')->with('success', 'General Settings updated successfully.');
    }

    /**
     * Export safe settings as a JSON file (EXCLUDES SECRETS).
     */
    public function export()
    {
        $all = Setting::allCached();
        $safeExport = [];

        foreach (self::SETTINGS_DEFINITIONS as $key => [$type, $group]) {
            if (array_key_exists($key, $all)) {
                $safeExport[$key] = $all[$key];
            }
        }

        AuditLog::record('settings_exported', 'Setting', null, 'Admin exported safe system settings JSON configuration');

        $filename = 'aqnewswire-settings-' . now()->format('Y-m-d-His') . '.json';

        return response()->streamDownload(function () use ($safeExport) {
            echo json_encode($safeExport, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Import safe settings from an uploaded JSON file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json,txt|max:512',
        ]);

        $content = file_get_contents($request->file('settings_file')->getRealPath());
        $data = json_decode($content, true);

        if (!is_array($data)) {
            return redirect()->route('admin.settings')->with('error', 'Invalid JSON file provided.');
        }

        $importedCount = 0;
        foreach (self::SETTINGS_DEFINITIONS as $key => [$type, $group]) {
            if (array_key_exists($key, $data)) {
                $val = $data[$key];

                // Validate individual values
                if ($key === 'timezone' && !in_array($val, DateTimeZone::listIdentifiers(), true)) {
                    continue;
                }
                if (str_starts_with($key, 'social_') && $val && !str_starts_with($val, 'https://')) {
                    continue;
                }

                Setting::set($key, $val, $type, $group);
                $importedCount++;
            }
        }

        Setting::clearCache();

        AuditLog::record('settings_imported', 'Setting', null, "Admin imported {$importedCount} settings from JSON");

        return redirect()->route('admin.settings')->with('success', "Successfully imported {$importedCount} settings.");
    }

    /**
     * Reset a configuration group to defaults.
     */
    public function resetGroup(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required|string|in:general,branding,contact,localization,social,reader,editorial',
        ]);

        $group = $validated['group'];

        if (isset(self::DEFAULT_VALUES[$group])) {
            foreach (self::DEFAULT_VALUES[$group] as $key => $defaultVal) {
                if (isset(self::SETTINGS_DEFINITIONS[$key])) {
                    [$type, $grp] = self::SETTINGS_DEFINITIONS[$key];
                    Setting::set($key, $defaultVal, $type, $grp);
                }
            }

            Setting::clearCache();

            AuditLog::record('settings_reset', 'Setting', null, "Reset configuration group [{$group}] to defaults");

            return redirect()->route('admin.settings')->with('success', "Section [{$group}] has been restored to default settings.");
        }

        return redirect()->route('admin.settings')->with('error', 'Invalid configuration group requested.');
    }
}
