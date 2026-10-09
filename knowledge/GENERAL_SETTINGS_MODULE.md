# APEX Media v2.4 — General Settings 2.0 Architecture & Administration Guide

## 1. Executive Summary

The **General Settings 2.0 Administration Center** provides complete, production-grade, database-backed administrative control over global configuration, branding, public communication, localization, reader features, and editorial metrics for **APEX Media v2.4 (International Business & Leadership)**.

General Settings is decoupled from the **Technical SEO Control Center** (`/admin/seo`), features real-time search, sticky scrollspy navigation, live branding and date/time previews, unsaved changes detection, section status indicators, safe JSON import/export, and authentic consumers across all public and editorial workflows.

---

## 2. Complete Inventory of Settings & Application Consumers

Every single setting rendered in the General Settings interface is connected to authentic application behavior:

| Setting Key | Group | Data Type | Validation Rules | Application Consumer | Public / Workflow Effect | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :---: |
| `site_name` | `general` | string | `required\|string\|max:255` | `layouts/app.blade.php`, Open Graph, `contact.blade.php` | Renders in `<title>`, `og:title`, and footer branding | **FUNCTIONAL** |
| `site_description` | `general` | text | `nullable\|string\|max:1000` | `layouts/app.blade.php`, meta tags | Fallback for `<meta name="description">` & footer mission | **FUNCTIONAL** |
| `site_url` | `general` | string | `required\|url\|max:255` | `canonical`, Schema.org, notifications | Authoritative host for absolute URLs; paired with `APP_URL` | **FUNCTIONAL** |
| `copyright_text` | `general` | string | `nullable\|string\|max:255` | `layouts/app.blade.php` footer | Displays legal copyright text at the base of every page | **FUNCTIONAL** |
| `short_brand_name` | `branding` | string | `required\|string\|max:50` | Public header, `og:site_name`, contact page | Renders header brand text and badges | **FUNCTIONAL** |
| `logo_url` | `branding` | string | `nullable\|string\|max:255` | Public header logo tag | Renders SVG/PNG brand logo image if present | **FUNCTIONAL** |
| `favicon_url` | `branding` | string | `nullable\|string\|max:255` | `<link rel="icon">` in `layouts/app.blade.php` | Browser tab icon for all public pages | **FUNCTIONAL** |
| `primary_email` | `contact` | string | `nullable\|email\|max:255` | `/contact` page & corporate inquiries | Executive corporate inquiry address | **FUNCTIONAL** |
| `editorial_email` | `contact` | string | `nullable\|email\|max:255` | `/contact` page editorial desk | Whistleblower leaks & editorial submissions address | **FUNCTIONAL** |
| `support_email` | `contact` | string | `nullable\|email\|max:255` | `/contact` page subscriber support | Subscription billing & membership assistance address | **FUNCTIONAL** |
| `contact_phone` | `contact` | string | `nullable\|string\|max:50` | `/contact` page switchboard | Direct newsroom telephone line | **FUNCTIONAL** |
| `office_address` | `contact` | text | `nullable\|string\|max:500` | `/contact` page & email compliance footers | Physical corporate headquarters address | **FUNCTIONAL** |
| `contact_page_url` | `contact` | string | `nullable\|string\|max:255` | Footer "Company" section link | Navigates readers to the inquiries form | **FUNCTIONAL** |
| `timezone` | `localization` | string | `required\|string\|in:DateTimeZone::listIdentifiers()` | Application dates, timestamps, live clock | Formats article publish timestamps and financial clock | **FUNCTIONAL** |
| `default_locale` | `localization` | string | `required\|string\|in:en,es,fr,de,ar,zh,ja,ur` | `html[lang]` attribute in layout | Sets primary language attribute for accessibility/SEO | **FUNCTIONAL** |
| `date_format` | `localization` | string | `required\|string\|max:30` | Article dates, live preview widget | Formats publication dates across all views | **FUNCTIONAL** |
| `time_format` | `localization` | string | `required\|string\|max:30` | Article times, live clock widget | Formats time strings across all views | **FUNCTIONAL** |
| `social_linkedin` | `social` | string | `nullable\|url\|starts_with:https://` | Footer social icons & `/contact` page | Links directly to verified LinkedIn organization profile | **FUNCTIONAL** |
| `social_twitter` | `social` | string | `nullable\|url\|starts_with:https://` | Footer social icons & `/contact` page | Links directly to verified X / Twitter newsfeed | **FUNCTIONAL** |
| `social_youtube` | `social` | string | `nullable\|url\|starts_with:https://` | Footer social icons & `/contact` page | Links directly to verified YouTube broadcast channel | **FUNCTIONAL** |
| `social_facebook` | `social` | string | `nullable\|url\|starts_with:https://` | Footer social icons & `/contact` page | Links directly to verified Facebook media page | **FUNCTIONAL** |
| `social_instagram` | `social` | string | `nullable\|url\|starts_with:https://` | Footer social icons & `/contact` page | Links directly to verified Instagram visual news feed | **FUNCTIONAL** |
| `enable_comments` | `reader` | boolean | `nullable\|boolean` | `CommentController::store()`, `articles/show.blade.php` | Disables new comments while preserving existing discussions | **FUNCTIONAL** |
| `enable_newsletter` | `reader` | boolean | `nullable\|boolean` | `NewsletterController::subscribe()`, `layouts/app.blade.php` | Hides newsletter form & pauses new subscriptions | **FUNCTIONAL** |
| `enable_registration` | `reader` | boolean | `nullable\|boolean` | `AuthController::showRegister()`, `register()` | Closes public signup & redirects to login | **FUNCTIONAL** |
| `enable_bookmarks` | `reader` | boolean | `nullable\|boolean` | `BookmarkController::toggle()`, `articles/show.blade.php` | Disables bookmark buttons while keeping saved lists intact | **FUNCTIONAL** |
| `default_reading_words_per_minute` | `editorial` | integer | `required\|integer\|min:50\|max:500` | `Article::saving` hook | Dynamically calculates read time minutes on save | **FUNCTIONAL** |
| `default_article_status` | `editorial` | string | `required\|in:draft,published` | `admin/articles/create.blade.php` | Initial selected state in CMS story composer | **FUNCTIONAL** |
| `enable_reading_history` | `editorial` | boolean | `nullable\|boolean` | `ArticleController::show()` | Skips tracking history when disabled without erasing past data | **FUNCTIONAL** |

---

## 3. Forensic Routing & Sidebar Separation

### 3.1 Route Endpoints
```text
GET|HEAD  admin/settings ....................................... admin.settings › Admin\AdminSettingController@index
PUT       admin/settings ............................... admin.settings.update › Admin\AdminSettingController@update
GET|HEAD  admin/settings/export ........................ admin.settings.export › Admin\AdminSettingController@export
POST      admin/settings/import ........................ admin.settings.import › Admin\AdminSettingController@import
POST      admin/settings/reset ...................... admin.settings.reset › Admin\AdminSettingController@resetGroup
```

### 3.2 Authorization & Middleware
All settings routes reside within the protected `admin.` group:
- `auth`: Unauthenticated guests are redirected to `/admin/login`.
- `admin.role` (`EnsureAdminRole`): Users with role `writer`, `contributor`, `editor`, or `reader` receive an HTTP `403 Forbidden` response.

### 3.3 Active State Isolation
- **General Settings:** Highlighted strictly when `request()->routeIs('admin.settings*')`.
- **SEO & Redirects:** Highlighted strictly when `request()->routeIs('admin.seo.*')`.
- Cross-highlighting is completely eliminated.

---

## 4. User Experience & Production Safeguards

1. **Client-Side Real-Time Filter**:
   The `settingsSearchInput` filters all 8 configuration cards instantly by matching section titles, field labels, and descriptions without requiring a page reload.
2. **Sticky ScrollSpy Navigation**:
   Smooth scrolling to `#sec-general`, `#sec-branding`, etc., with real-time active section highlighting.
3. **Dirty State & Unsaved Changes Warning**:
   - Modifying any input activates the sticky `dirtyNoticeBanner` and changes section badges from `Saved` to `Modified`.
   - Browser `beforeunload` listener prompts a confirmation alert if an administrator attempts to navigate away before saving.
4. **Save Experience & Double-Submission Prevention**:
   - Both Top and Bottom Save buttons submit the single authoritative form.
   - Form submission automatically disarms the `beforeunload` prompt and disables both save buttons with a `Saving...` spinner.
5. **Live Previews**:
   - **Header Preview**: Live updates the brand name and dot indicator.
   - **Tab Preview**: Live updates simulated browser tab title and URL.
   - **Clock Preview**: Live updates formatted time using the configured timezone and formats.
6. **Operational Health Telemetry**:
   - Live Database Probe (`✓ Connected` or `✕ Disconnected`).
   - Live Cache Probe (`✓ Operational` or `✕ Failed`).
   - Storage Writable Probe (`✓ Writable` or `✕ Read-Only`).
   - Critical Production Warning: Automatically triggers a high-visibility alert if `app.env === 'production'` and `app.debug === true`.
   - Zero Secret Exposure: Sensitive credentials (`APP_KEY`, `DB_PASSWORD`, API secrets) are never read into the view or written to audit logs.
7. **Safe JSON Import & Export**:
   - Export downloads only allowlisted safe settings.
   - Import strictly validates keys and formats before applying to the database.
8. **Section Reset to Defaults**:
   Allows resetting an individual group (e.g. `branding`) back to canonical defaults with user confirmation.

---

## 5. Development vs. Production Configuration Audit

| Occurrence | Location | Classification | Production Recommendation |
| :--- | :--- | :--- | :--- |
| `http://localhost` | `.env:5`, `config/app.php:55` | **DEVELOPMENT DEFAULT** | Set real domain `https://your-domain.com` in production `.env` |
| `127.0.0.1` | `config/database.php`, `config/redis.php` | **DEVELOPMENT FIXTURE** | Connect to production database host |
| `apexmedia.test` | `DatabaseSeeder.php` | **TEST FIXTURE** | Seeded sample author websites; not used in public layout |
| `hello@example.com` | `config/mail.php` | **DEVELOPMENT DEFAULT** | Configure real transactional sender in production `.env` |
| `http://127.0.0.1:8000` | `2026_10_02_100000_create_settings_table.php` | **MIGRATION FALLBACK** | Overridden by database setting or `APP_URL` |

---

## 6. Verification & Automated Test Results

### 6.1 Feature Test Suite: `AdminGeneralSettingsTest`
```
   PASS  Tests\Feature\AdminGeneralSettingsTest
  ✓ guest is redirected to login when accessing settings                      1.19s  
  ✓ non admin user receives forbidden when accessing settings                 0.14s  
  ✓ admin can access general settings page with status 200                    0.13s  
  ✓ general settings does not redirect to seo                                 0.18s  
  ✓ seo and settings sidebar highlight independently                          0.18s  
  ✓ admin can update settings successfully                                    0.26s  
  ✓ validation rejects invalid email                                          0.22s  
  ✓ validation rejects invalid url                                            0.34s  
  ✓ validation rejects invalid timezone                                       0.22s  
  ✓ updating settings clears cache                                            0.29s  
  ✓ audit log is recorded on settings update                                  0.19s  
  ✓ sensitive environment secrets are not rendered                            0.11s  
  ✓ admin can export settings json without secrets                            0.18s  
  ✓ admin can import settings json                                            0.13s  
  ✓ admin can reset settings group to defaults                                0.11s  
  ✓ registration toggle controls reader signup                                0.11s  
  ✓ comments toggle controls comment submission                               0.20s  
  ✓ newsletter toggle controls newsletter subscription                        0.13s  
  ✓ bookmark toggle controls story bookmarking                                0.11s  
  ✓ reading history toggle controls history recording                         0.17s  
  ✓ public layout reflects custom site name and brand                         0.14s  
  ✓ public contact page renders configured bureau information                 0.15s  

  Tests:    22 passed (70 assertions)
  Duration: 5.41s
```

### 6.2 Full Platform Test Suite
```
  Tests:    95 passed (406 assertions)
  Duration: 16.75s
  Result:   100% PASSING (0 failures, 0 errors)
```

### 6.3 Asset Build & Runtime Logs
- **Vite Build (`npm run build`):** Built cleanly in 3.17s (`119.73 kB CSS`, `51.52 kB JS`, 0 errors).
- **Log Verification:** `storage/logs/laravel.log` clean with 0 unhandled exceptions.
- **Local Server:** Responding HTTP 200 on all public, admin, and contact routes.
