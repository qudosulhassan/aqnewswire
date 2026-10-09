# APEX Media v2.4 — Article Editor 2.0 Architectural Specification & Audit

## 1. Overview
The Article Editor 2.0 transforms the APEX Media editorial suite from a basic CRUD interface into a professional, production-ready newsroom publishing CMS. Built with native Laravel 11 and Alpine.js, it operates with zero external heavy CDN dependencies, protects unsaved drafts with live autosave and dirty-state tracking, offers true responsive article previews, tracks content revision history, manages private editorial directives, and integrates directly with the platform's media library.

---

## 2. Core Modules & Functionality

### 2.1 Sticky Top Control Bar
- **Navigation:** Seamless return to `/admin/articles` with status filter state preserved.
- **Workflow State Pill:** Color-coded status badge (`published`, `scheduled`, `submitted`, `draft`, `archived`).
- **Autosave Engine:**
  - Debounced 3-second automatic background sync to `POST /admin/articles/{article}/autosave`.
  - Live status indicators: `Idle (All changes saved)`, `Unsaved changes (Dirty)`, `Autosaving...`, `Saved [Timestamp]`.
  - Manual draft save via `Ctrl+S` or `Cmd+S` keyboard shortcuts.
  - `beforeunload` browser safeguard preventing accidental tab closes with unsaved changes.
- **Role-Aware Actions:**
  - Contributors: "Save Draft" & "Submit for Review".
  - Editors & Admins: "Save Draft", "Live Preview", "Publish Story", "Schedule Release", and "Update Live Article".

### 2.2 Editorial Content Canvas
- **Headline (H1):** Live character counter with optimal length guide (50–75 characters) and color-coded status indicator.
- **Slug Management:** Auto-generated clean slug with one-click regeneration and custom editing.
- **Subtitle / Dek:** Journalist dek with live counter (80–160 characters).
- **Lead Summary / Excerpt:** Teaser textarea with live counter (120–250 characters) utilized by homepage lead grids, RSS feeds, and social previews.
- **WYSIWYG Rich Text Editor:**
  - Formatting toolbar: Bold, Italic, Underline, Strikethrough, Headings (H2, H3, Paragraph), Blockquotes with APEX red bar styling, Bullet Lists, Numbered Lists, Horizontal Divider, Links, Image insertion, and Data Tables.
  - **HTML Source Mode (`<>` Toggle):** Two-way synchronized raw HTML editing for power editors and developers.
  - Dynamic Word Count and Reading Time calculator driven by `Setting::get('default_reading_words_per_minute', 220)`.

### 2.3 Inspector Right Sidebar
- **Publishing Controls:**
  - Workflow status selector.
  - Byline Author selector (privileged editors/admins can assign any active staff writer, editor, or contributor).
  - Primary Channel / Category selector.
  - Topics & Tags multi-select with inline dynamic tag creation.
  - Scheduled publication date/time picker with server timezone badge (`Setting::get('site_timezone', 'UTC')`).
  - Diagnostic metrics: Total Views, Reading Time, Word Count, and Last Updated relative timestamp.
- **Featured Cover Media:**
  - Cover image URL input.
  - Integrated **Media Library Modal** with live search, file upload, and one-click selection.
  - Live aspect-ratio image thumbnail preview.
  - Photo credit and caption fields.
  - Accessible and SEO-friendly Alt Text field.
- **Editorial Visibility Flags:**
  - Featured Cover Story (hero banner and main carousel).
  - Breaking News Alert (top red breaking bar).
  - Trending Leaderboard (sidebar and top 10 index).
  - Editors' Pick Spotlight (curated editorial block).
  - Premium / Subscriber Only (paywall gate indicator).
- **SEO & Social Optimization:**
  - Custom Meta Title with Google SERP counter.
  - Meta Description with Google SERP counter.
  - Canonical URL override.
  - Robots directives (`index, follow`, `noindex, follow`, `noindex, nofollow`).
  - Live Google Search result snippet preview card.
- **Monetization & Sponsorship:**
  - Brand sponsor name.
  - Sponsor destination URL.
  - FTC/Editorial affiliate disclosure text.

### 2.4 Bottom Workspaces
- **Internal Editorial Feedback & Directives:**
  - Private communication channel between editors, copy editors, and writers.
  - Four note types: `editorial`, `revision_request`, `fact_check`, and `seo`.
  - Author notifications dispatched upon `revision_request`.
  - 1-click `Mark Resolved` / `Reopen` toggle.
  - 1-click note deletion.
- **Revision History:**
  - Automated versioned snapshots (`article_revisions` table) created on significant content updates and autosaves.
  - Stores author, version number, title, subtitle, excerpt, content, and change summary.
  - 1-click "Restore Revision" with current state auto-snapshotted before restoration.
  - Automated pruning maintaining the latest 30 revisions per article.

---

## 3. Dedicated Preview System
- **Route:** `GET /admin/articles/{article}/preview`
- **Security:** Authenticated access restricted to authors, editors, and administrators.
- **Features:**
  - Sticky top bar with return to editor link, status pill, and metadata inspection drawer.
  - Responsive Viewport Switchers:
    - **Desktop (100% max-w-5xl)**
    - **Tablet (768px frame)**
    - **Mobile (390px simulated smartphone frame with status bar)**
  - Full APEX Media typography and layout rendering.

---

## 4. Routes Reference
| Method | URI | Name | Description |
|---|---|---|---|
| `GET` | `/admin/articles` | `admin.articles.index` | Master article list with filters & stats |
| `GET` | `/admin/articles/create` | `admin.articles.create` | Article authoring workspace |
| `POST` | `/admin/articles` | `admin.articles.store` | Persist new article & revision v1 |
| `GET` | `/admin/articles/{article}/edit` | `admin.articles.edit` | Full Article Editor 2.0 workspace |
| `PUT` | `/admin/articles/{article}` | `admin.articles.update` | Update article & snapshot revision |
| `DELETE` | `/admin/articles/{article}` | `admin.articles.destroy` | Delete article & audit log |
| `POST` | `/admin/articles/{article}/autosave` | `admin.articles.autosave` | Background draft autosave |
| `GET` | `/admin/articles/{article}/preview` | `admin.articles.preview` | Multi-device responsive preview |
| `POST` | `/admin/articles/{article}/revisions/{rev}/restore` | `admin.articles.revisions.restore` | Restore prior content version |
| `POST` | `/admin/articles/{article}/notes` | `admin.articles.notes.store` | Post private editorial directive |
| `POST` | `/admin/articles/{article}/notes/{note}/resolve` | `admin.articles.notes.resolve` | Toggle note resolution status |
| `DELETE` | `/admin/articles/{article}/notes/{note}` | `admin.articles.notes.destroy` | Remove editorial note |
| `GET` | `/admin/media/picker` | `admin.media.picker` | Paginated JSON API for media modal |

---

## 5. Verification & Test Suite
- Comprehensive automated test suite: `tests/Feature/AdminArticleEditorTest.php` (12 tests, 63 assertions, 100% passing).
- Complete platform test suite: `php artisan test` (107 tests, 469 assertions, 100% passing).
- Asset compilation: `npm run build` passing in 1.68s.
- Local server: Verified active on `http://127.0.0.1:8000`.
