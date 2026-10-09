# ANTIGRAVITY — PHASE 2 PRODUCTION HARDENING & DEPLOYMENT PROMPT

## IMPORTANT

The existing APEX International Business & Leadership media platform has already completed the main Phase 2 implementation.

Current reported result:

- 23 tests passed
- 81 assertions passed
- Vite production assets built successfully
- Public accounts implemented
- Bookmarks implemented
- Reading history implemented
- Following implemented
- Contributor workflow implemented
- Comments implemented
- Video implemented
- Podcasts implemented
- SEO sitemap implemented
- Google News sitemap implemented
- robots.txt implemented
- SEO redirects implemented
- Media library implemented
- Editorial notes implemented
- Newsletter subscriber management implemented
- Audit logs implemented

DO NOT rebuild these systems.

DO NOT delete existing data.

DO NOT replace working functionality.

Your task now is:

1. Audit the completed Phase 2 implementation.
2. Close remaining functional gaps.
3. Harden the application for production.
4. Run complete regression testing.
5. Prepare the project for safe deployment.

Project:

`C:\NewsBlog`

---

# 1. START WITH A REAL AUDIT

Before changing anything:

Run:

```bash
cd C:\NewsBlog
php artisan test
php artisan route:list
php artisan migrate:status
npm run build
```

Also inspect:

- `.env`
- `.env.example`
- `config/`
- `routes/`
- `app/Models`
- `app/Http/Controllers`
- `app/Http/Middleware`
- `app/Policies`
- `app/Services`
- `database/migrations`
- `database/seeders`
- `resources/views`
- `resources/js`
- `resources/css`
- `public/build`

Do not assume the completion report is 100% accurate.

Verify the actual implementation.

Create an internal audit:

### VERIFIED COMPLETE

### PARTIALLY COMPLETE

### MISSING

### BUGS

### SECURITY RISKS

### PRODUCTION RISKS

---

# 2. CURRENT BASELINE MUST REMAIN GREEN

Before making changes, record the current test result.

The existing reported baseline is:

`23 passed, 81 assertions`

After all work:

- All existing tests must still pass.
- New tests must pass.
- No regression is acceptable.

If a test fails because of an intentional behavior change, update the test only after verifying that the new behavior is correct and backwards compatible.

---

# 3. IMPORTANT GAP ANALYSIS

The Phase 2 completion report shows several areas that need verification or expansion.

Audit these specifically.

## A. TOPIC FOLLOWING

The original Phase 2 specification required users to follow:

- Authors
- Categories
- Topics

The completion report explicitly mentions authors and categories but does not clearly confirm topic following.

Verify and implement topic following if missing.

Requirements:

- Topic model/relationship
- Follow/unfollow
- Duplicate prevention
- Account following page
- Personalized recommendations
- Notification integration

---

# 4. RECOMMENDATION ENGINE

The original specification required a recommendation engine.

Verify whether recommendations are genuinely algorithmic or simply manually selected.

Implement a reusable:

`RecommendationService`

Support:

- Article page recommendations
- Homepage recommendations
- User dashboard recommendations
- Category recommendations

Signals may include:

- Category
- Tags
- Topic
- Author
- User follows
- Reading history
- Trending
- Popularity
- Recency

Do not create fake personalization.

If insufficient data exists, gracefully fall back to:

- Related content
- Trending
- Latest content

---

# 5. TRENDING ENGINE

Verify whether trending is actually calculated or manually flagged.

Implement a real service if necessary:

`TrendingService`

Use:

- Recent views
- Total views
- Shares
- Saves
- Comments
- Reading engagement
- Recency/time decay

Provide scheduled recalculation.

Admin should be able to:

- View scores
- Feature
- Exclude
- Override

Do not fabricate metrics.

---

# 6. ADVANCED SEARCH

Verify current search implementation.

It must support:

- Articles
- Authors
- Categories
- Tags
- Topics
- Videos
- Podcasts
- Rankings

Filters:

- Category
- Author
- Content type
- Date
- Topic
- Popularity

Add:

- Autocomplete
- Suggestions
- Pagination
- Search state preservation

Prevent N+1 queries.

Add appropriate database indexes.

---

# 7. NEWSLETTER SYSTEM — EXPAND BEYOND SUBSCRIBERS

The current implementation has newsletter audience management.

The original Phase 2 specification also required:

- Segments
- Campaigns
- Templates
- Scheduling
- Analytics
- Preference center
- Unsubscribe flow

Verify each one.

If missing, implement:

`/admin/newsletters/subscribers`

`/admin/newsletters/segments`

`/admin/newsletters/campaigns`

`/admin/newsletters/templates`

`/admin/newsletters/analytics`

Campaign lifecycle:

`Draft → Test → Scheduled → Sending → Sent`

Use queues.

Never send a large newsletter synchronously.

Implement unsubscribe and preference management correctly.

---

# 8. NOTIFICATIONS

The current report says notifications exist.

Verify:

- Database storage
- Read/unread state
- Notification listing
- Mark read
- Mark all read
- User authorization
- Notification creation
- Follow-based notifications
- Contributor/editor notifications

Do not create duplicate notifications.

Add indexes.

---

# 9. BROWSER PUSH

The original Phase 2 prompt requested browser push architecture.

Verify whether this is implemented.

If missing:

- Create subscription storage.
- Add permission UI.
- Add unsubscribe.
- Add expiration handling.
- Add notification service abstraction.

Do not force browser permission automatically.

Do not add external credentials.

---

# 10. AUDIO ARTICLE SYSTEM

The completion report says the audio player interface exists.

Verify whether actual audio is generated and served.

The implementation must distinguish between:

### REAL AUDIO

Actual audio file/source exists.

### PLAYER UI ONLY

Player exists but no real audio source.

If only UI exists, do not claim audio is complete.

Implement provider abstraction for TTS.

Use queue jobs for generation.

Cache generated audio.

Provide a proper disabled state if no TTS provider is configured.

---

# 11. MEDIA LIBRARY HARDENING

Verify:

- Upload
- MIME validation
- File size limits
- Image dimension detection
- Thumbnail generation
- Metadata
- Search
- Filtering
- Usage tracking
- Safe deletion

Prevent:

- Executable uploads
- Malicious file extensions
- Path traversal
- Unauthorized deletion

Verify public storage links do not expose sensitive files.

---

# 12. VIDEO SYSTEM HARDENING

Verify:

- YouTube
- Vimeo
- MP4
- Thumbnail
- Duration
- Transcript
- Captions
- SEO
- VideoObject schema
- Admin CRUD
- Authorization

Validate external URLs.

Do not allow arbitrary unsafe embeds.

---

# 13. PODCAST HARDENING

Verify:

- Podcast CRUD
- Episode CRUD
- Audio upload
- Transcript
- Cover image
- Episode metadata
- Audio player
- Download handling
- SEO
- Structured data
- Authorization

Validate audio file types and size.

---

# 14. CONTRIBUTOR SYSTEM HARDENING

Verify complete workflow:

Contributor applies

↓

Admin reviews

↓

Approve

↓

Role becomes contributor

↓

Contributor creates draft

↓

Contributor submits

↓

Editor reviews

↓

Revision requested OR approved

↓

Scheduled

↓

Published

Ensure contributors cannot:

- Publish without permission
- Change another contributor's article
- Access admin-only data
- Modify editorial notes they should not see
- Change SEO settings unless permitted

Add authorization tests.

---

# 15. COMMENTS SECURITY

Verify:

- Sanitization
- CSRF
- Rate limiting
- Spam prevention
- Authorization
- Report system
- Moderation
- Reply permissions

Test malicious HTML.

Test unauthorized deletion/editing.

Test excessive submissions.

Do not trust client-side validation.

---

# 16. SEO HARDENING

Verify:

### Article

- Title
- Description
- Canonical
- Robots
- Open Graph
- X/Twitter
- Article schema
- Author schema
- Publisher schema
- Breadcrumbs

### Category

- Canonical
- Meta
- Schema
- Indexability

### Author

- Canonical
- Person schema

### Ranking

- Canonical
- Structured data where appropriate

### Video

- VideoObject where appropriate

### Podcast

- Appropriate structured data

Do not generate schema for information that is not actually visible on the page.

---

# 17. SITEMAP HARDENING

Verify:

`/sitemap.xml`

`/sitemap-news.xml`

Requirements:

- Valid XML
- Correct content type
- Only public/indexable content
- Correct canonical URLs
- Correct lastmod
- No drafts
- No deleted content
- No private content
- No duplicates

News sitemap:

- Only eligible recent news content
- Correct publication date
- Correct publication name
- Correct title

---

# 18. ROBOTS.TXT

Verify:

`/robots.txt`

Must:

- Allow public pages
- Block admin
- Avoid blocking CSS/JS required for rendering
- Reference sitemap
- Reference news sitemap where appropriate

Provide safe production configuration.

---

# 19. REDIRECT ENGINE

Verify:

- 301
- 302
- Hit counting
- Active/inactive
- Admin CRUD
- Loop prevention
- Self-redirect prevention

Test:

`/old-url → /new-url`

Verify correct HTTP status.

Avoid redirect chains where possible.

---

# 20. GRANULAR PERMISSIONS

The original Phase 2 specification required granular roles.

Audit whether permissions are truly enforced server-side.

Verify roles:

- Super Admin
- Administrator
- Editor-in-Chief
- Managing Editor
- Editor
- Author
- Contributor
- SEO Manager
- Ad Manager
- Video Editor
- Moderator
- Analyst
- User

Test permissions for:

- Publish
- Delete
- Restore
- Manage users
- Manage SEO
- Manage redirects
- Manage media
- Manage comments
- Manage rankings
- Manage newsletters

Never rely only on hiding buttons.

---

# 21. AUDIT LOG HARDENING

Verify logs are created for:

- Login
- Registration
- Article changes
- Publishing
- Deleting
- Restoring
- Role changes
- Permission changes
- SEO changes
- Redirect changes
- Newsletter changes
- Contributor approval
- Comment moderation
- Media changes

Never store:

- Passwords
- API keys
- Tokens
- Payment secrets

Ensure audit logs are protected from unauthorized modification.

---

# 22. ADVANCED ANALYTICS

Verify whether the current analytics are real or placeholders.

Track where implemented:

- Pageviews
- Article views
- Search
- Shares
- Saves
- Comments
- Follows
- Newsletter signup
- Video play
- Podcast play

Admin reports:

- Today
- 7 days
- 30 days
- Custom

Do not fabricate metrics.

If analytics are not implemented, create the data model and service architecture rather than displaying fake numbers.

---

# 23. ARTICLE METRICS

Create/verify:

- Views
- Saves
- Shares
- Comments
- Reading time
- Newsletter conversions

Avoid expensive queries on every page request.

Use aggregation jobs/caching where appropriate.

---

# 24. ADMIN UX

Audit every Phase 2 admin module.

Required:

- Search
- Filtering
- Sorting
- Pagination
- Empty states
- Loading states
- Error states
- Success notifications
- Form validation
- Permission checks
- Mobile/tablet responsiveness

Do not introduce a second unrelated admin design.

---

# 25. PRODUCTION ENVIRONMENT

Inspect `.env.example` and environment configuration.

Ensure production supports:

- `APP_ENV=production`
- Correct `APP_URL`
- HTTPS
- Secure cookies
- Correct session configuration
- Correct mail configuration
- Queue configuration
- Cache configuration
- Storage configuration
- Database configuration

Never expose `.env`.

Never commit secrets.

---

# 26. CACHE AND QUEUE

Verify:

- Cache driver
- Queue driver
- Failed jobs
- Retry configuration
- Scheduler

Heavy tasks should use queues:

- Newsletter
- TTS
- Notifications
- Media processing
- Analytics aggregation
- Sitemap generation where appropriate

Do not run heavy processing inside normal HTTP requests.

---

# 27. DATABASE PERFORMANCE

Inspect slow/high-volume queries.

Check indexes for:

- articles
- users
- bookmarks
- follows
- comments
- notifications
- analytics
- media
- redirects
- newsletter subscribers

Look specifically for N+1 queries.

Use eager loading appropriately.

---

# 28. SECURITY AUDIT

Run a security-oriented review.

Check:

- Authentication
- Authorization
- CSRF
- XSS
- SQL injection
- File uploads
- Path traversal
- Open redirects
- Rate limits
- Session security
- Password hashing
- Email verification
- Admin access
- Sensitive data exposure

Do not introduce security scanners requiring external credentials unless already configured.

---

# 29. FRONTEND BUILD

Run:

```bash
npm run build
```

Verify:

- No build errors
- No console-breaking errors
- CSS loaded
- JS loaded
- Mobile layout
- Desktop layout

Verify the generated assets are referenced correctly.

---

# 30. COMPLETE TEST SUITE

Run:

```bash
php artisan test
```

Then add tests for every missing/hardened feature.

Target:

- 100% pass rate for the current suite
- No regression
- New functionality covered by tests

Do not simply increase test count with meaningless tests.

---

# 31. MANUAL SMOKE TEST

After automated tests, manually verify:

### Public

- Homepage
- Search
- Article
- Category
- Ranking
- Author
- Register
- Login
- Account
- Saved
- History
- Following
- Notifications
- Contributor application
- Contributor studio
- Videos
- Podcasts
- Comments

### Admin

- Login
- Dashboard
- Articles
- Categories
- Rankings
- Contributors
- Comments
- Media
- Videos
- Podcasts
- Newsletters
- SEO
- Redirects
- Audit logs
- Users
- Analytics

---

# 32. DEPLOYMENT PREPARATION

Before deployment:

1. Backup database.
2. Backup storage.
3. Verify migration status.
4. Verify environment variables.
5. Run tests.
6. Build frontend.
7. Run migrations safely.
8. Clear/rebuild application cache.
9. Verify route cache compatibility.
10. Verify config cache compatibility.
11. Verify view cache.
12. Verify storage link.
13. Verify queue workers.
14. Verify scheduler.
15. Verify sitemap.
16. Verify robots.
17. Verify HTTPS/canonical URLs.
18. Verify logs.
19. Run smoke tests.

Do not run destructive production commands.

---

# 33. ROLLBACK PLAN

Before production deployment, document:

- Database backup
- Storage backup
- Current git commit
- New migration list
- Rollback migration commands
- Cache clearing procedure
- Queue restart procedure

If deployment fails:

1. Put application into maintenance mode if required.
2. Inspect logs.
3. Stop problematic queue workers if necessary.
4. Roll back only the new migration(s) if safe.
5. Restore application version.
6. Clear/rebuild caches.
7. Verify homepage and admin.
8. Verify database integrity.

Never blindly run a full database rollback.

---

# 34. FINAL PRODUCTION CHECKLIST

Do not declare production ready until all are checked:

### Application

- [ ] Tests pass
- [ ] No fatal errors
- [ ] No obvious PHP errors
- [ ] No frontend build errors

### Database

- [ ] Migrations succeed
- [ ] Existing data preserved
- [ ] Indexes present
- [ ] Foreign keys valid

### Security

- [ ] Admin protected
- [ ] Permissions enforced
- [ ] Uploads validated
- [ ] CSRF enabled
- [ ] XSS protections
- [ ] Rate limits
- [ ] Secrets not exposed

### SEO

- [ ] Canonicals
- [ ] Meta
- [ ] Schema
- [ ] Sitemap
- [ ] News sitemap
- [ ] Robots
- [ ] Redirects

### Performance

- [ ] Assets built
- [ ] Images optimized
- [ ] N+1 checked
- [ ] Caching configured
- [ ] Queues configured

### Content

- [ ] Articles preserved
- [ ] Categories preserved
- [ ] Authors preserved
- [ ] Rankings preserved
- [ ] Subscribers preserved

---

# 35. REQUIRED FINAL REPORT

After completing the hardening/deployment pass, report:

## 1. VERIFIED FEATURES

List features that were tested and confirmed working.

## 2. FIXES MADE

For every fix:

- Problem
- Root cause
- Solution
- Files changed

## 3. DATABASE CHANGES

List migrations, tables, indexes and relationships.

## 4. SECURITY FIXES

List security improvements.

## 5. SEO FIXES

List SEO improvements.

## 6. PERFORMANCE FIXES

List caching/query/asset improvements.

## 7. TEST RESULTS

Report exact result:

- Total tests
- Passed
- Failed
- Assertions

## 8. BUILD RESULTS

Report:

- Vite build
- Asset sizes
- Build errors/warnings

## 9. DEPLOYMENT STATUS

Clearly state one of:

`READY FOR PRODUCTION`

or

`NOT READY — BLOCKERS REMAIN`

If not ready, list the blockers precisely.

## 10. REMAINING CONFIGURATION

List only configuration that genuinely requires human action, such as:

- Production database
- SMTP
- Queue worker
- TTS provider
- Push provider
- Analytics provider
- CDN
- Payment gateway

Never invent credentials.

---

# FINAL COMMAND

START WITH:

```bash
cd C:\NewsBlog
php artisan test
php artisan route:list
php artisan migrate:status
npm run build
```

Then audit the actual implementation.

Do NOT rebuild Phase 2.

Do NOT delete existing data.

Do NOT create duplicate systems.

Do NOT fabricate analytics.

Do NOT claim audio/video/push/analytics functionality is complete unless it actually works.

Close the remaining gaps, harden the existing implementation, run the complete regression suite, and prepare the application for safe production deployment.

The final goal is a stable, secure, SEO-friendly, high-performance APEX digital media platform ready for production.
