# Features & Module Specifications

## 1. Public Digital Newsroom & Stripe-Inspired Homepage

The public homepage has been redesigned from a traditional newspaper layout into an animated, digital business-news platform inspired by Stripe design principles.

### Key Homepage Sections
1. **Financial Market Benchmark Ticker**:
   - Fixed at the very top of the layout.
   - Displays real-time indices: S&P 500, NASDAQ, DOW, FTSE 100, Nikkei 225, Gold, Oil (Brent), Bitcoin.
   - Includes delta indicators (`▲ +0.84%`, `▼ -0.42%`) and quick navigation to APEX Lists, Video, and Podcasts.
2. **Sticky Header with Global Command Palette (`Ctrl + K`)**:
   - Compact blurred navigation (`bg-[#0B0F19]/90 backdrop-blur-md`).
   - APEX brand masthead with tagline "International Business & Leadership".
   - Direct channel links, user session avatar menu, and a vibrant red `Subscribe →` button.
   - Zero-dependency Vanilla JS search modal triggered via `Ctrl + K` or search icon with `Escape` handler.
3. **Cinematic Editorial Hero ("THE BIG STORY")**:
   - Deep space background (`#070A12`) with animated ambient radial glows.
   - Red badge: `THE BIG STORY`.
   - Massive headline with electric gradient text: `The New Rules of Global Business`.
   - Primary `Read the full story →` and secondary `▶ Watch analysis` CTAs.
   - Metadata byline: author avatar, name, role, reading time, and real view count.
   - Right visual: High-resolution editorial photography with orbital sheen and a floating annotation callout: *"A more connected global economy is emerging."*
4. **Breaking News Marquee Ticker**:
   - Horizontal news stream (`animate-marquee`) that pauses smoothly on `:hover`.
   - Supports reduced-motion overrides (`@media (prefers-reduced-motion: reduce)`).
5. **Latest Insights Rail**:
   - 4-card horizontal rail with category tags, timestamps, headlines, views, and reading times.
6. **"What's Moving the World" + "Live Markets"**:
   - Left (8 cols): Primary feature story card with thumbnail, category badge, and 3 stacked compact stories.
   - Right (4 cols): Dark financial panel with S&P 500, NASDAQ, DOW, FTSE, Nikkei, Gold, Oil, Bitcoin, value changes, and mini SVG sparklines.
7. **APEX Intelligence Module**:
   - Dark data section with 3 major economic indicators:
     - `$200B` Global AI Investment in 2026 (`+32% YoY`)
     - `3.8%` Global GDP Growth Forecast (`+0.6%`)
     - `72%` CEOs Prioritize AI in Strategy (`+18%`)
   - Interactive SVG dual-spline chart with timeframe selector pills (`1M`, `3M`, `1Y`, `5Y`) and tooltip card.
8. **Category Ecosystem**:
   - Balanced 4-column layout covering Top Stories, AI & Innovation, Leadership, and Markets & Finance.
9. **APEX Lists (Rankings Rail)**:
   - Dark luxury ranking cards (`#01` to `#05`) featuring avatars, titles, companies, and net worth metrics.
10. **High-Impact Newsletter CTA**:
    - Radiant radial gradient container with trust badges (`Daily Briefing`, `Exclusive Analysis`, `Expert Interviews`, `Special Reports`).
11. **Comprehensive Dark Footer**:
    - 5-column directory with legal policies, social links, and copyright notices.

---

## 2. Editorial CMS & Operations Dashboard

Accessible to `admin` and `editor` roles via `/admin`.

### Dashboard Operations Center (`/admin/dashboard`)
- **KPI Stat Cards**: Total Articles, Published, Pageviews, Newsletter Subscribers, Active Contributors.
  - Eliminated hardcoded trends (`+12%`, `+18%`); displays authentic period comparisons over a 30-day window (`All-time`, `6 published`, `Live count`).
- **Live Runtime System Health Probes**:
  - **Database**: Real PDO connection verification (`DB::connection()->getPdo()`).
  - **Cache**: Real write/read roundtrip probe (`Cache::put('health_probe_ts', ...)`).
  - **Queue Worker**: Scopes pending table jobs count.
  - **Storage / Media CDN**: Tests filesystem writability on `storage/app/public`.
- **Content Pipeline**: Real-time counter of Drafts, Submitted, In Review, Scheduled, and Published articles.
- **Recent Articles & Top Stories**: Eagerly loaded tables with status badges.
- **Editorial Activity Feed**: Live audit log entries with fallback to recent newsroom actions.

### Article Publishing Studio (`/admin/articles`)
- **Two-Column Workspace**:
  - Left column: Title, subtitle, slug generator, excerpt, rich text body content, cover image upload with caption.
  - Right column: Publishing controls (Status dropdown, category selector, author assignment, tag selection, scheduled publishing date, reading time calculation, breaking/featured/trending toggles).
- **Revision & Review**: Staff editors can leave internal review feedback via `editorial_notes`.

### Media Library Security (`/admin/media`)
- **Disallowed SVG Uploads**: SVG uploads are rejected to prevent Stored XSS attacks via embedded scripts.
- **Filename Sanitization**: Uploaded files are stripped of path traversal characters (`..`, `/`, `\`) via `basename()` and regex cleaning.
- **Asset Metadata**: Automatically records MIME type, file dimensions, and byte size.

### SEO & Redirect Engine (`/admin/seo`)
- Real-time audit of missing meta titles, descriptions, and featured images.
- 301/302 Redirect Manager with:
  - Self-redirect prevention: Blocks rules where source equals destination.
  - Circular redirect loop prevention: Detects reciprocal rules.

---

## 3. Reader Account Platform

Accessible to registered subscribers via `/account`:
- **Authentication**: Registration, Login, Password Reset, and secure Session Termination.
- **Saved Articles (Bookmarks)**: Readers can bookmark stories from article pages and view them in `/account/bookmarks`.
- **Reading Progress & History**: Automatically tracks article progress percentage and records last read timestamps.
- **Following Channels & Authors**: Polymorphic follow engine allows readers to follow topics, categories, and specific journalists.
- **Contributor Application Workflow**: Readers can submit contributor applications (`/contributor/apply`); staff administrators can review and approve them in the CMS.

---

## 4. Multimedia Broadcasting

- **Video Hub (`/videos`)**: High-definition video broadcasts with category filtering, video duration badges, and responsive player embeds.
- **Podcasts Directory (`/podcasts`)**: Audio show directories with episode listings, guest names, host profiles, and playback integration.
- **Audio & TTS**: Differentiates local audio files from external TTS cloud synthesis.

---

## 5. Search Engine & Discovery

- **Global Modal (`Ctrl + K`)**: Instant search dialog with hotkey listeners.
- **Scoped Search (`/search`)**:
  - Full-text querying across `title`, `subtitle`, `excerpt`, and `content`.
  - Content-type filtering (`all`, `articles`, `videos`, `podcasts`, `rankings`).
  - Category, author, and date-range filters (`24h`, `7d`, `30d`, `1y`).
  - Sorting: Latest, Most Viewed, Highest Trending Score.
- **AJAX Autocomplete (`/search/autocomplete`)**: Fast debounced endpoint returning live title suggestions.

---

## 6. Newsletter & Push Notifications

- **Double Opt-In Newsletters**: Validates email format, verifies uniqueness, generates secure opt-in tokens, and provides 1-click unsubscribe (`/newsletter/unsubscribe`).
- **Preference Center (`/newsletter/preferences`)**: Readers can select specific topic frequencies (Daily Executive Briefing, Tech Radar, Markets Dispatch).
- **Web Push**: Service worker registration endpoint (`/push/subscribe`) storing browser subscription endpoints with encryption public keys.
