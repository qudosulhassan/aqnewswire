# Database Schema & Entity Relationships

## 1. Overview & Migrations Status

The APEX Media database is defined across **27 migrations** organized into 4 batches. All migrations have executed cleanly without schema errors.

| Batch | Migration Name | Target Table(s) / Purpose |
| :--- | :--- | :--- |
| **1** | `0001_01_01_000000_create_users_table` | `users`, `password_reset_tokens`, `sessions` |
| **1** | `0001_01_01_000001_create_cache_table` | `cache`, `cache_locks` |
| **1** | `0001_01_01_000002_create_jobs_table` | `jobs`, `job_batches`, `failed_jobs` |
| **2** | `2026_10_01_080923_add_profile_fields_to_users_table` | Adds `role`, `avatar`, `bio`, `title`, `slug`, `social_links` |
| **2** | `2026_10_01_080924_create_categories_table` | `categories` |
| **2** | `2026_10_01_080925_create_tags_table` | `tags` |
| **2** | `2026_10_01_080926_create_article_tag_table` | Pivot table: `article_tag` |
| **2** | `2026_10_01_080926_create_articles_table` | Core editorial: `articles` |
| **2** | `2026_10_01_080927_create_rankings_lists_and_items_table` | `rankings_lists`, `ranking_items` |
| **2** | `2026_10_01_080928_create_advertisements_table` | `advertisements` (billboard, sidebar) |
| **2** | `2026_10_01_080928_create_newsletter_subscribers_table` | `newsletter_subscribers` |
| **3** | `2026_10_01_090623_create_bookmarks_table` | `bookmarks` (user saved articles) |
| **3** | `2026_10_01_090623_create_reading_histories_table` | `reading_histories` (progress tracking) |
| **3** | `2026_10_01_090624_create_follows_table` | Polymorphic `follows` (authors, categories, tags) |
| **3** | `2026_10_01_090625_create_contributor_applications_table` | `contributor_applications` |
| **3** | `2026_10_01_090625_create_editorial_notes_table` | `editorial_notes` (newsroom peer review) |
| **3** | `2026_10_01_090626_create_comments_table` | `comments` (moderation & nested replies) |
| **3** | `2026_10_01_090627_create_media_assets_table` | `media_assets` (central media library) |
| **3** | `2026_10_01_090627_create_videos_table` | `videos` (multimedia broadcasts) |
| **3** | `2026_10_01_090628_create_podcasts_and_episodes_tables` | `podcasts`, `podcast_episodes` |
| **3** | `2026_10_01_090629_create_seo_redirects_table` | `seo_redirects` (301/302 redirect engine) |
| **3** | `2026_10_01_090630_create_audit_logs_table` | `audit_logs` (forensic newsroom activity) |
| **3** | `2026_10_01_090630_create_user_notifications_table` | `user_notifications` |
| **4** | `2026_10_01_091500_add_hardening_fields_to_articles_table` | Adds `trending_score`, `audio_url`, `audio_duration` |
| **4** | `2026_10_01_091501_create_push_subscriptions_table` | `push_subscriptions` (Web Push endpoints) |
| **4** | `2026_10_01_091502_create_newsletter_campaigns_and_segments_tables`| `newsletter_campaigns`, `newsletter_segments`, `newsletter_templates` |
| **4** | `2026_10_01_091503_create_analytics_events_table` | `analytics_events` (internal telemetry) |

---

## 2. Core Tables & Column Specifications

### `articles`
The primary publishing unit for all newsroom stories.
- `id` (bigint, unsigned, primary key)
- `user_id` (foreignId $\rightarrow$ `users.id`, indexed)
- `category_id` (foreignId $\rightarrow$ `categories.id`, indexed)
- `title` (string, 255)
- `slug` (string, 255, unique, indexed)
- `subtitle` (string, 255, nullable)
- `excerpt` (text)
- `content` (longText)
- `featured_image` (string, 500, nullable)
- `featured_image_caption` (string, 255, nullable)
- `status` (string, enum: `'draft'`, `'submitted'`, `'review'`, `'published'`, `'archived'`, default: `'draft'`, indexed)
- `published_at` (timestamp, nullable, indexed)
- `scheduled_at` (timestamp, nullable)
- `reading_time_minutes` (integer, default: 5)
- `view_count` (bigint, unsigned, default: 0)
- `trending_score` (decimal, 12, 4, default: 0.0, indexed)
- `is_breaking` (boolean, default: false)
- `is_featured` (boolean, default: false)
- `is_trending` (boolean, default: false)
- `is_editors_pick` (boolean, default: false)
- `is_premium` (boolean, default: false)
- `canonical_url` (string, 500, nullable)
- `audio_url` (string, 500, nullable)
- `audio_duration` (string, 50, nullable)
- `audio_generated_at` (timestamp, nullable)
- `timestamps` (`created_at`, `updated_at`)

### `users`
Accounts across all platform roles.
- `id` (bigint, unsigned, primary key)
- `name` (string, 255)
- `email` (string, 255, unique)
- `password` (string, 255)
- `role` (string, enum: `'admin'`, `'editor'`, `'writer'`, `'contributor'`, `'reader'`, default: `'reader'`, indexed)
- `avatar` (string, 500, nullable)
- `bio` (text, nullable)
- `title` (string, 255, nullable)
- `slug` (string, 255, unique, indexed)
- `social_links` (json, nullable)
- `timestamps`

### `categories`
Channel taxonomies.
- `id` (bigint, unsigned, primary key)
- `name` (string, 255)
- `slug` (string, 255, unique, indexed)
- `description` (text, nullable)
- `color` (string, 50, default: `'#dc2626'`)
- `is_nav_visible` (boolean, default: true)
- `sort_order` (integer, default: 0)
- `timestamps`

### `rankings_lists` & `ranking_items`
Powers "The Apex Lists" directory and homepage ranking cards.
- `rankings_lists`: `id`, `title`, `slug` (unique), `subtitle`, `description`, `cover_image`, `status`, `is_featured`, `timestamps`.
- `ranking_items`: `id`, `rankings_list_id` (foreignId), `rank` (integer), `name`, `title_or_role`, `company`, `net_worth_or_metric` (e.g. `"$248 Billion"`), `industry`, `country`, `bio`, `photo_url`, `timestamps`.

### `seo_redirects`
High-performance 301/302 redirects.
- `id` (bigint, unsigned, primary key)
- `source_path` (string, 255, unique, indexed)
- `destination_url` (string, 500)
- `status_code` (smallint, default: 301)
- `hit_count` (bigint, default: 0)
- `is_active` (boolean, default: true)
- `timestamps`

---

## 3. Seeded Database State

The database currently maintains an authentic baseline dataset:

```json
{
    "users": 4,
    "articles": 6,
    "categories": 8,
    "tags": 8,
    "comments": 0,
    "rankings_lists": 1,
    "rankings_items": 5,
    "media_assets": 0,
    "newsletter_subscribers": 1,
    "notifications": 0,
    "bookmarks": 0,
    "reading_history": 3,
    "follows": 0
}
```

### Seeded Users
1. **Alexander Vance** (`admin@apexmedia.com`): Platform Administrator
2. **Sophia Thorne** (`sophia@apexmedia.com`): Managing Editor
3. **Marcus Sterling** (`marcus@apexmedia.com`): Senior Technology Writer
4. **Dr. Elena Rostova** (`elena@apexmedia.com`): Contributing Fellow

### Seeded Categories
1. Business (`business`)
2. Technology (`technology`)
3. Markets & Finance (`markets`)
4. AI & Innovation (`ai-innovation`)
5. Leadership (`leadership`)
6. Billionaires (`billionaires`)
7. Startups (`startups`)
8. Lifestyle & Wealth (`lifestyle`)

---

## 4. Cross-Driver Portability

All schema definitions use cross-platform column types and avoid database engine specifics:
- SQLite compatibility: Tested locally with 0 SQL syntax or lock errors.
- MySQL 8.0+ compatibility: Schema tables use InnoDB-compliant foreign key constraints and `utf8mb4` character encoding.
- PostgreSQL 14+ compatibility: Strict column types, native boolean casts, and timestamp operations. Zero raw SQL fragments (`DB::raw`) exist in application code.
