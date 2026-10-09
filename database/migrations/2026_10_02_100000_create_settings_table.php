<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('type', 30)->default('string'); // string, text, boolean, integer, json
            $table->string('group', 50)->default('general')->index(); // general, branding, contact, localization, social, reader, editorial
            $table->timestamps();
        });

        // Seed default settings immediately
        $defaults = [
            // General Group
            ['key' => 'site_name', 'value' => 'AQ NEWSWIRE International Business & Leadership', 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Definitive international journalism covering business leadership, disruptive technology, financial markets, and global wealth rankings.', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_url', 'value' => config('app.url', 'http://127.0.0.1:8000'), 'type' => 'string', 'group' => 'general'],
            ['key' => 'copyright_text', 'value' => '© ' . date('Y') . ' AQ NEWSWIRE Group Inc. All rights reserved.', 'type' => 'string', 'group' => 'general'],

            // Branding Group
            ['key' => 'short_brand_name', 'value' => 'AQ NEWSWIRE', 'type' => 'string', 'group' => 'branding'],
            ['key' => 'logo_url', 'value' => '/images/logo.png', 'type' => 'string', 'group' => 'branding'],
            ['key' => 'favicon_url', 'value' => '/images/favicon.png', 'type' => 'string', 'group' => 'branding'],

            // Contact Group
            ['key' => 'primary_email', 'value' => 'contact@aqnewswire.test', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'editorial_email', 'value' => 'editorial@aqnewswire.test', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'support_email', 'value' => 'support@aqnewswire.test', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+1 (212) 555-0199', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'office_address', 'value' => 'AQ NEWSWIRE Tower, 767 Fifth Avenue, New York, NY 10153', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_page_url', 'value' => '/contact', 'type' => 'string', 'group' => 'contact'],

            // Localization Group
            ['key' => 'timezone', 'value' => 'UTC', 'type' => 'string', 'group' => 'localization'],
            ['key' => 'default_locale', 'value' => 'en', 'type' => 'string', 'group' => 'localization'],
            ['key' => 'date_format', 'value' => 'M d, Y', 'type' => 'string', 'group' => 'localization'],
            ['key' => 'time_format', 'value' => 'g:i A', 'type' => 'string', 'group' => 'localization'],

            // Social Profiles Group
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/aq-newswire', 'type' => 'string', 'group' => 'social'],
            ['key' => 'social_twitter', 'value' => 'https://x.com/aqnewswire', 'type' => 'string', 'group' => 'social'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/aqnewswire', 'type' => 'string', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/aqnewswire', 'type' => 'string', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@aqnewswire', 'type' => 'string', 'group' => 'social'],

            // Reader Experience Group
            ['key' => 'enable_comments', 'value' => '1', 'type' => 'boolean', 'group' => 'reader'],
            ['key' => 'enable_newsletter', 'value' => '1', 'type' => 'boolean', 'group' => 'reader'],
            ['key' => 'enable_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'reader'],
            ['key' => 'enable_bookmarks', 'value' => '1', 'type' => 'boolean', 'group' => 'reader'],

            // Editorial Defaults Group
            ['key' => 'default_reading_words_per_minute', 'value' => '200', 'type' => 'integer', 'group' => 'editorial'],
            ['key' => 'default_article_status', 'value' => 'draft', 'type' => 'string', 'group' => 'editorial'],
            ['key' => 'enable_reading_history', 'value' => '1', 'type' => 'boolean', 'group' => 'editorial'],
        ];

        $now = now();
        foreach ($defaults as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        DB::table('settings')->insert($defaults);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
