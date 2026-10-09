# System Architecture & Technical Specifications

## 1. High-Level Architecture Overview

APEX Media v2.4 is architected as an enterprise-grade digital news and business intelligence platform built on Laravel 12 and Tailwind CSS v4. The platform employs a clean separation of concerns:
- **Presentation Layer**: Blade component hierarchy styled with modern Tailwind CSS v4 utility classes and lightweight Vanilla/Alpine micro-interactions.
- **Application & Controller Layer**: RESTful and resource controllers split into Public, Reader Account, Contributor Studio, and Staff Editorial Admin domains.
- **Service Layer**: Dedicated domain services encapsulating complex business logic (Trending gravity calculations, multi-signal content recommendations, Web Push dispatching, TTS audio processing, and analytics event logging).
- **Domain Model & Persistence Layer**: Eloquent ORM models with query scopes, casting, foreign key relationships, mass-assignment protections, and audit logging.
- **Middleware & Security Pipeline**: Role-based access control (RBAC), CSRF validation, session encryption, and SEO redirect interception.

```
┌────────────────────────────────────────────────────────────────────────┐
│                        HTTP Request / Client                           │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
                        ┌───────────▼───────────┐
                        │   Middleware Pipeline │
                        │  - CSRF Protection    │
                        │  - Session Handling   │
                        │  - HandleSeoRedirects │
                        │  - Role Authorization │
                        └───────────┬───────────┘
                                    │
                        ┌───────────▼───────────┐
                        │  Route Dispatcher     │
                        │  (routes/web.php)     │
                        └───────────┬───────────┘
                                    │
          ┌─────────────────────────┼─────────────────────────┐
          │                         │                         │
┌─────────▼─────────┐     ┌─────────▼─────────┐     ┌─────────▼─────────┐
│ Public Domain     │     │ Reader / Account  │     │ Staff Admin Domain│
│ HomeController    │     │ AccountController │     │ DashboardControl. │
│ ArticleController │     │ BookmarkControl.  │     │ AdminArticleCont. │
│ CategoryControl.  │     │ FollowController  │     │ AdminAnalyticsCt. │
│ SearchController  │     │ ReaderAuthControl.│     │ AdminMediaControl.│
└─────────┬─────────┘     └─────────┬─────────┘     └─────────┬─────────┘
          │                         │                         │
          └─────────────────────────┼─────────────────────────┘
                                    │
                        ┌───────────▼───────────┐
                        │     Service Layer     │
                        │ - TrendingService     │
                        │ - RecommendationServ. │
                        │ - PushNotificationSvc │
                        │ - TtsService          │
                        │ - AnalyticsService    │
                        └───────────┬───────────┘
                                    │
                        ┌───────────▼───────────┐
                        │   Eloquent ORM Model  │
                        │ (Article, User, etc.) │
                        └───────────┬───────────┘
                                    │
                        ┌───────────▼───────────┐
                        │ Database (SQLite/SQL) │
                        └───────────────────────┘
```

---

## 2. Directory Structure

```text
C:\NewsBlog\
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Account/          # Reader profile, saved articles, registration
│   │   │   ├── Admin/            # Editorial CMS, analytics, media, audit, SEO
│   │   │   ├── Contributor/      # Contributor studio & pitch submissions
│   │   │   ├── HomeController.php
│   │   │   ├── ArticleController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── SearchController.php
│   │   │   └── ...
│   │   └── Middleware/
│   │       └── HandleSeoRedirects.php
│   ├── Models/                   # 20 Eloquent domain models
│   ├── Policies/
│   │   └── ArticlePolicy.php     # Comprehensive multi-role RBAC authorization
│   └── Services/                 # Domain service layer
│       ├── AnalyticsService.php
│       ├── PushNotificationService.php
│       ├── RecommendationService.php
│       ├── TrendingService.php
│       └── TtsService.php
├── bootstrap/
├── config/
├── database/
│   ├── migrations/               # 27 database schema migrations
│   └── seeders/
├── knowledge/                    # Platform documentation & knowledge hub
├── public/                       # Web server document root (index.php, storage symlink)
├── resources/
│   ├── css/app.css               # Tailwind CSS v4 styling & keyframe animations
│   ├── js/app.js                 # Frontend scripts
│   └── views/                    # Blade templates
│       ├── layouts/              # app.blade.php (Public), admin.blade.php (CMS)
│       ├── admin/                # Dashboard, articles, analytics, media, SEO
│       ├── articles/             # Public story display & reading experience
│       ├── categories/           # Channel listing pages
│       ├── rankings/             # APEX Lists directories
│       └── home.blade.php        # Stripe-inspired modular business homepage
├── routes/
│   ├── web.php                   # 106 application HTTP routes
│   └── console.php               # Artisan commands & scheduled background jobs
└── tests/
    └── Feature/                  # 35 automated integration & feature tests
```

---

## 3. Role-Based Access Control (RBAC) Architecture

The platform supports 5 hierarchical and scoped roles:

| Role | Target Persona | Permissions & Capabilities |
| :--- | :--- | :--- |
| **`admin`** | Executive Leadership & Operations | Full unconditional access across articles, categories, users, roles, audit logs, media library, redirects, and system configurations. |
| **`editor`** | Managing & Senior Editors | Can review, edit, approve, schedule, and publish all articles and notes across all channels. Cannot alter system credentials or delete users. |
| **`writer`** | Staff Journalists & Correspondents | Can create, draft, and edit assigned articles. Cannot unilaterally publish without editorial review. |
| **`contributor`** | External Subject-Matter Experts | Can submit article drafts in their own studio. Restricted by `ArticlePolicy` to strictly their own submissions; cannot view or edit other authors' drafts. |
| **`reader`** | Public Subscribers & Audience | Authenticated reader capabilities: bookmarks, reading progress tracking, following channels/authors, posting comments, and setting newsletter preferences. |

### Policy Implementation (`app/Policies/ArticlePolicy.php`)
```php
public function update(User $user, Article $article): bool
{
    if ($user->canManageContent()) {
        return true; // admin & editor
    }
    if ($user->isWriter() && $article->user_id === $user->id) {
        return true;
    }
    if ($user->isContributor() && $article->user_id === $user->id && $article->status === 'draft') {
        return true; // Contributors cannot modify published articles
    }
    return false;
}
```

---

## 4. Service Layer Specifications

### 1. `TrendingService` (`app/Services/TrendingService.php`)
Calculates real engagement gravity scores for articles published within the last 30 days:
$$\text{Score} = \frac{(\text{views} \times 1) + (\text{bookmarks} \times 5) + (\text{comments} \times 3)}{(\text{hours\_old} + 2)^{1.3}}$$
Scores are rounded to 4 decimal places and saved quietly (`updateQuietly(['trending_score' => ...])`) so audit logs and `updated_at` timestamps are not artificially modified.

### 2. `RecommendationService` (`app/Services/RecommendationService.php`)
- **Anonymous / Article Context**: Compares shared category IDs, shared tag IDs, and author IDs. If fewer than the requested count exist, backfills gracefully with latest editor's picks.
- **Registered User Context**: Collects user follows (`authors`, `categories`, `topics`) and cross-references against read history (`ReadingHistory::where('progress_percent', '>=', 80)`) to deliver personalized discovery.

### 3. `PushNotificationService` (`app/Services/PushNotificationService.php`)
Manages browser push endpoints via `PushSubscription`. Supports payload dispatching and logs events safely if cloud VAPID keys are absent.

### 4. `TtsService` (`app/Services/TtsService.php`)
Distinguishes between uploaded audio tracks (`audio_url` stored on `Article`) and cloud-synthesized text-to-speech. Checks `config('services.tts.key')` and falls back gracefully when external keys are not provided.

### 5. `AnalyticsService` (`app/Services/AnalyticsService.php`)
Captures internal engagement telemetry (`pageview`, `scroll_depth`, `bookmark`, `share`) into `analytics_events` table for newsroom reporting without external third-party tracking dependencies.

---

## 5. Middleware Pipeline

1. **`HandleSeoRedirects`**: Intercepts requests, queries `seo_redirects` for matching `source_path`, increments `hit_count`, and issues `301` or `302` redirects. Protected against self-redirects and circular loops.
2. **`Authenticate`**: Restricts account, contributor, and admin routes.
3. **`AdminMiddleware` / Role Guards**: Restricts `/admin/*` routes to users with `admin` or `editor` roles.
