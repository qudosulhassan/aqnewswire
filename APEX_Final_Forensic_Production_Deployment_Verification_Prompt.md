# APEX Media v2.4 — FINAL FORENSIC PRODUCTION DEPLOYMENT VERIFICATION
## Antigravity Master Prompt

**Project:** APEX International Business & Leadership / APEX Media v2.4  
**Local Path:** `C:\NewsBlog`  
**Purpose:** Final forensic verification before production deployment  
**Important:** This is NOT a redesign, rewrite, or feature expansion task.

---

# 1. YOUR ROLE

Act as a senior Laravel production engineer, security auditor, DevOps engineer, QA engineer, database engineer, and forensic code reviewer.

You are auditing the **actual existing APEX Media v2.4 codebase**.

Do not assume that previous audit reports are correct.

Do not mark something PASS merely because the architecture appears to support it.

Inspect the actual implementation, execute safe verification commands, inspect database state, inspect routes, inspect controllers/services/models/migrations/views/configuration, and where possible perform real runtime/browser smoke tests.

The goal is to establish the difference between:

1. **CODE VERIFIED**
2. **ENVIRONMENT CONFIGURED**
3. **PRODUCTION RUNTIME VERIFIED**
4. **FULL DEPLOYMENT VERIFIED**

Do not fake, infer, or manufacture successful results.

---

# 2. CRITICAL SAFETY RULES

## DO NOT

- Do not rebuild the application.
- Do not redesign the UI.
- Do not replace working architecture unnecessarily.
- Do not run `migrate:fresh`.
- Do not run `db:wipe`.
- Do not delete production-like data.
- Do not reset the database.
- Do not remove existing articles, users, categories, tags, comments, media, rankings, or settings.
- Do not change public URLs unless absolutely necessary and explicitly documented.
- Do not fabricate test results.
- Do not report a feature as working simply because a route exists.
- Do not report an external service as operational merely because credentials exist.
- Do not expose passwords, API keys, APP_KEY, SMTP secrets, VAPID private keys, or database credentials in the final report.

## SAFE CHANGES

If a genuine blocker is discovered:

1. Identify it.
2. Explain why it is a blocker.
3. Make the smallest safe code-level fix possible.
4. Run targeted tests.
5. Run the complete test suite.
6. Re-verify the affected functionality.
7. Document exactly what changed.

Preserve all existing functionality.

---

# 3. STARTING PROCEDURE

Open PowerShell in:

```powershell
cd C:\NewsBlog
```

First inspect the project before modifying anything.

Run appropriate commands such as:

```powershell
php -v
php artisan --version
php artisan about
php artisan route:list
php artisan migrate:status
php artisan schedule:list
php artisan queue:failed
npm --version
node --version
```

Inspect:

```text
.env
.env.example
composer.json
package.json
vite.config.*
config/
app/
database/
resources/
routes/
public/
storage/
```

Determine the actual application architecture.

---

# 4. DO NOT TRUST THE PREVIOUS AUDIT

The previous report claimed:

- 35 tests
- 145 assertions
- 20 migrations
- 6 articles
- 8 categories
- 8 tags
- 4 users
- production-ready code
- analytics fixed
- dashboard health probes fixed
- SVG upload security fixed
- dedicated analytics controller added
- command palette fixed
- trending engine verified

Treat all of these as **claims requiring verification**.

Confirm every one from the actual code/database/runtime.

If a claim is incorrect, report the actual state.

---

# 5. APPLICATION ENVIRONMENT AUDIT

Inspect:

```env
APP_ENV
APP_DEBUG
APP_KEY
APP_URL
LOG_CHANNEL
LOG_LEVEL
DB_CONNECTION
SESSION_DRIVER
SESSION_SECURE_COOKIE
SESSION_SAME_SITE
CACHE_STORE
QUEUE_CONNECTION
MAIL_MAILER
```

Verify that production configuration can safely use:

```env
APP_ENV=production
APP_DEBUG=false
```

Never expose the actual APP_KEY.

Report only:

```text
APP_KEY: CONFIGURED
```

or:

```text
APP_KEY: MISSING
```

Verify that `.env` is not publicly accessible through the web server.

Check:

```text
/.env
/.git/
storage/
bootstrap/cache/
```

for accidental exposure.

---

# 6. AUTHENTICATION AND SESSION SECURITY

Verify actual implementation of:

- login
- logout
- registration
- password hashing
- password reset
- email verification if implemented
- session regeneration
- CSRF protection
- secure cookies
- SameSite configuration
- authentication middleware
- rate limiting
- authorization checks

Test for:

- unauthenticated admin access
- reader accessing admin pages
- contributor accessing another contributor's article
- writer modifying unauthorized content
- editor/admin privileges

Do not rely only on UI hiding.

Verify server-side authorization.

---

# 7. RBAC FORENSIC AUDIT

Inspect:

```text
ArticlePolicy
User model
role middleware
authorization gates
controllers
admin routes
contributor routes
```

Verify roles:

```text
admin
editor
writer
contributor
reader
```

Confirm:

```text
Contributor → own allowed submissions only
Writer → authorized writing operations
Editor → newsroom governance
Admin → full administration
Reader → public/reader capabilities only
```

Attempt unauthorized operations through direct HTTP requests where safely possible.

---

# 8. DATABASE FORENSIC AUDIT

Run:

```powershell
php artisan migrate:status
```

Inspect all migrations.

Verify:

- foreign keys
- indexes
- unique constraints
- nullable fields
- cascade behavior
- slugs
- status fields
- timestamps
- JSON fields
- soft deletes where applicable

Confirm existing data is intact.

Do NOT use:

```text
migrate:fresh
db:wipe
```

Compare actual counts for:

```text
users
articles
categories
tags
comments
rankings
media
newsletter subscribers
notifications
bookmarks
reading history
follows
```

Report the actual counts.

---

# 9. DATABASE DRIVER COMPATIBILITY

The project claims support for:

```text
SQLite
MySQL
PostgreSQL
```

Do not accept this claim merely because Laravel migrations exist.

Inspect queries/services for database-specific assumptions.

Check:

- raw SQL
- full-text search
- JSON operations
- date functions
- boolean handling
- pagination
- ordering
- indexes
- case sensitivity
- PostgreSQL/MySQL compatibility

Clearly report:

```text
SQLite verified
MySQL verified
PostgreSQL verified
```

Only where actually tested.

Otherwise:

```text
MySQL: CODE COMPATIBLE / NOT RUNTIME VERIFIED
PostgreSQL: CODE COMPATIBLE / NOT RUNTIME VERIFIED
```

---

# 10. TRENDING ENGINE — VERIFY THE ACTUAL FORMULA

Inspect the real implementation:

```text
app/Services/TrendingService.php
```

Do not infer the formula from documentation.

Determine the exact mathematical equation implemented by PHP.

For example, if the implementation is conceptually:

```text
(
    views × 1
    + bookmarks × 5
    + comments × 3
)
/
(
    hours_since_published + 2
) ^ 1.3
```

document that exact implementation.

But DO NOT assume this is the formula until verified in code.

Verify:

- views
- bookmarks
- comments
- publication age
- score calculation
- persistence
- `updateQuietly()` if applicable
- scheduling
- recalculation
- ordering
- published-only filtering

Create a small controlled test if safe.

---

# 11. RECOMMENDATION ENGINE

Inspect:

```text
RecommendationService.php
```

Verify actual signals:

- author
- category
- tags
- reading history
- follows
- editor's picks fallback

Test:

```text
known user
new user
reader with no history
article with no related signals
```

Ensure recommendations never break when data is missing.

Do not claim AI/personalization if the implementation is rule-based.

Describe the actual algorithm accurately.

---

# 12. ANALYTICS INTEGRITY

Inspect:

```text
AdminAnalyticsController.php
dashboard controllers
analytics models
analytics services
analytics views
```

Verify that dashboard statistics are not fabricated.

Search the project for suspicious hardcoded values such as:

```text
61933
+12%
+18%
+24%
fake views
fake subscribers
fake traffic
```

Do not merely remove suspicious numbers.

Trace where each displayed metric actually comes from.

Every KPI must be classified as one of:

```text
REAL DATABASE METRIC
REAL APPLICATION-DERIVED METRIC
REAL EXTERNAL ANALYTICS METRIC
STATIC CONFIGURATION
NOT AVAILABLE
```

Never display database counts as external traffic analytics.

---

# 13. DASHBOARD SYSTEM HEALTH

Verify actual runtime probes for:

```text
Database
Cache
Queue
Storage
Scheduler
Mail
Search
External services where applicable
```

Important:

```text
Queue table exists ≠ queue worker is running
SMTP configured ≠ email delivered
Cache configured ≠ cache operational
Storage path exists ≠ storage writable
```

Where possible perform actual read/write tests.

Ensure health indicators are connected to real probe results rather than static green indicators.

---

# 14. QUEUE SYSTEM

Inspect:

```text
QUEUE_CONNECTION
jobs
failed_jobs
queue configuration
job classes
dispatch logic
```

Verify:

```powershell
php artisan queue:failed
php artisan queue:work --once
```

Do not leave a worker running indefinitely unless explicitly required.

Determine whether production requires:

```text
Supervisor
systemd
Horizon
```

Document the exact recommended configuration.

Production worker command should be verified against the actual project:

```bash
php artisan queue:work --sleep=3 --tries=3
```

Do not assume Redis if the application is configured for database queues.

---

# 15. SCHEDULER

Run:

```powershell
php artisan schedule:list
```

Inspect scheduled tasks.

Verify:

- trending recalculation
- newsletter/digest jobs
- notifications
- cleanup jobs
- any other scheduled operations

Document the actual cron requirement:

```cron
* * * * * cd /path-to-app && php artisan schedule:run >> /dev/null 2>&1
```

Only mark the scheduler as production-verified if an actual production cron has been confirmed.

---

# 16. EMAIL SYSTEM

Inspect:

```text
MAIL_MAILER
MAIL_HOST
MAIL_PORT
MAIL_USERNAME
MAIL_ENCRYPTION
MAIL_FROM_ADDRESS
```

Never reveal passwords.

If local uses:

```text
MAIL_MAILER=log
```

report:

```text
LOCAL EMAIL DRIVER: LOG
PRODUCTION SMTP: NOT YET VERIFIED
```

If possible test a real transactional email after production configuration.

Distinguish:

```text
credentials configured
SMTP connection successful
email actually delivered
```

These are different verification levels.

---

# 17. WEB PUSH

Inspect:

```text
push subscriptions
service worker
push controller
VAPID configuration
notification jobs
browser subscription flow
```

Verify graceful fallback when VAPID credentials are unavailable.

Do not claim push is operational unless a real browser subscription/send test succeeds.

---

# 18. AUDIO / TTS

Inspect:

- uploaded audio
- TTS service
- AWS Polly or other external integration
- audio player
- fallback behavior
- storage paths
- authorization

Clearly distinguish:

```text
uploaded audio
external TTS generated audio
browser audio playback
```

Do not report external TTS as operational unless the external service was actually tested.

---

# 19. MEDIA SECURITY

Inspect:

```text
AdminMediaController
media validation
storage paths
upload handling
download handling
public URLs
```

Verify SVG is intentionally disabled if that is the chosen security posture.

Verify filename sanitization.

Test for:

```text
../ traversal
absolute paths
null bytes
double extensions
malicious MIME types
HTML disguised as images
SVG upload
oversized files
unsupported extensions
```

Verify uploaded content cannot execute as PHP.

---

# 20. SEARCH

Verify:

- title search
- excerpt search
- body search
- boolean search if implemented
- category filter
- date filter
- pagination
- autocomplete
- debounce
- empty result handling

Inspect whether the implementation is:

```text
database LIKE search
FULLTEXT
PostgreSQL FTS
custom search
```

Document the actual implementation.

Do not claim “full-text search” unless the actual code uses an appropriate full-text mechanism.

---

# 21. SEO FORENSIC AUDIT

Verify actual output from rendered pages:

```text
<title>
meta description
canonical
robots
OpenGraph
Twitter cards
JSON-LD
```

Test:

```text
homepage
article
category
author
ranking
search
```

Verify:

```text
/sitemap.xml
/robots.txt
```

Check:

- canonical correctness
- HTTPS
- trailing slash behavior
- pagination
- duplicate canonical URLs
- noindex rules
- redirect loops
- 301/302 handling
- nonexistent URLs

---

# 22. REDIRECT MANAGEMENT

Inspect redirect implementation.

Verify protection against:

```text
self redirect
redirect loops
malformed targets
open redirects
```

Test:

```text
old URL → new URL
invalid redirect
self redirect
external redirect attempt
```

Ensure only intended redirect behavior is allowed.

---

# 23. ARTICLE CMS

Perform actual workflow testing:

```text
Create draft
↓
Save
↓
Edit
↓
Upload media
↓
Preview
↓
Publish
↓
Open public URL
↓
Edit published article
↓
Verify changes publicly
```

Test:

- title
- slug
- excerpt
- body
- cover image
- author
- category
- tags
- SEO fields
- publish status
- scheduled publishing if implemented

Verify TipTap/HTML editor behavior if present.

---

# 24. COMMENTS

Verify:

- comment creation
- moderation
- nested replies
- likes
- authorization
- spam/rate limiting if implemented
- deletion
- admin moderation

Ensure unauthorized users cannot modify another user's comment.

---

# 25. NEWSLETTER

Verify:

- subscription
- validation
- duplicate subscription handling
- double opt-in
- token security
- unsubscribe
- preferences
- campaign creation
- segments
- sending architecture

Do not send a real mass campaign during testing.

---

# 26. READER FEATURES

Test:

```text
Register
Login
Bookmark
Remove bookmark
Reading history
Follow author
Follow category
Follow topic/tag if implemented
Notifications
Logout
```

Verify authorization and persistence.

---

# 27. FRONTEND BUILD

Run:

```powershell
npm run build
```

Record:

```text
CSS size
JS size
build time
warnings
errors
```

Do not report “zero warnings” unless the build output actually confirms it.

---

# 28. LARAVEL CACHE VALIDATION

Safely test:

```powershell
php artisan optimize:clear
php artisan route:cache
php artisan view:cache
```

Do not leave stale development cache.

Verify route serialization.

Verify Blade compilation.

---

# 29. PERFORMANCE AUDIT

Do not use bundle size alone as a performance claim.

Inspect:

```text
LCP
CLS
INP
TTFB
TBT where available
image sizes
font loading
JavaScript execution
lazy loading
database queries
N+1 queries
cache usage
```

Pay special attention to the new premium APEX homepage because it contains:

- animation
- market ticker
- interactive content
- gradients
- hero imagery
- charts
- story rails
- video/podcast modules

Ensure animations use efficient CSS transforms/opacity where possible.

---

# 30. RESPONSIVE AUDIT

Verify at minimum:

```text
1920 × 1080
1440 × 900
1280 × 800
1024 × 768
768 × 1024
390 × 844
375 × 812
```

Check:

- header
- navigation
- mega menu
- hero
- article cards
- ticker
- tables
- charts
- admin sidebar
- editor
- forms
- modals
- footer

No horizontal overflow.

---

# 31. ACCESSIBILITY

Verify:

- semantic HTML
- heading hierarchy
- alt text
- form labels
- keyboard navigation
- visible focus
- modal focus handling
- escape key behavior
- ARIA where appropriate
- contrast
- reduced motion
- screen-reader-friendly controls

Verify:

```css
@media (prefers-reduced-motion: reduce)
```

is respected wherever meaningful animation exists.

---

# 32. SECURITY FINAL PASS

Search and inspect for:

```text
XSS
CSRF
SQL injection
mass assignment
IDOR
authorization bypass
path traversal
stored XSS
open redirects
SSRF
unsafe iframe/embed URLs
javascript: URLs
data: URLs
session fixation
password exposure
debug exposure
directory listing
.env exposure
```

Inspect Laravel:

```text
$fillable
$guarded
validation rules
policies
middleware
rate limits
signed URLs where appropriate
```

---

# 33. WEB SERVER / DEPLOYMENT CONFIGURATION

Document exact production requirements for:

```text
Nginx or Apache
PHP-FPM
PHP extensions
SSL
HTTPS redirect
document root
public/index.php
storage permissions
bootstrap/cache permissions
queue worker
scheduler
database
mail
cache
```

The document root MUST point to:

```text
C:\NewsBlog\public
```

for local Windows testing, and to the equivalent Laravel `public` directory on Linux production.

Never expose the Laravel project root publicly.

---

# 34. BACKUP / ROLLBACK

Before declaring deployment-ready, document:

```text
database backup
media backup
.env secret backup strategy
migration rollback strategy
release rollback
application rollback
```

Do not run destructive rollback commands against production.

---

# 35. PRODUCTION SMOKE TEST

If a real production/staging URL is available, perform browser/HTTP verification.

Test:

```text
Homepage
Category
Article
Author
Ranking
Search
Login
Register
Bookmark
Comment
Newsletter
Admin login
Admin dashboard
Article editor
Media upload
Publish
Sitemap
Robots
404
Redirect
```

Verify HTTP responses:

```text
200 homepage
200 article
200 category
200 sitemap.xml
200 robots.txt
301 intended redirect
404 nonexistent URL
```

If no production URL exists, explicitly report:

```text
LIVE PRODUCTION SMOKE TEST: NOT AVAILABLE
```

Do not mark it PASS.

---

# 36. PRODUCTION CONFIGURATION CLASSIFICATION

Every external dependency must receive one of these statuses:

```text
CODE VERIFIED
CONFIGURED
RUNTIME VERIFIED
NOT CONFIGURED
NOT TESTED
DEGRADED
BLOCKED
```

Apply this to:

```text
Database
SMTP
Queue
Scheduler
Cache
Storage
VAPID
TTS
Analytics
Search
CDN
Sentry/Bugsnag if applicable
```

---

# 37. TEST SUITE

Run:

```powershell
php artisan test
```

Record the real result.

Report:

```text
Tests:
Assertions:
Failures:
Errors:
Skipped:
Duration:
```

Never write:

```text
35 / 145
```

unless the actual command confirms it.

---

# 38. FINAL SCORECARD

Create a final table with:

| Area | Status | Evidence | Remaining Work |
|---|---|---|---|
| Environment | | | |
| Authentication | | | |
| Sessions | | | |
| RBAC | | | |
| Database | | | |
| Database compatibility | | | |
| Trending | | | |
| Recommendations | | | |
| Analytics | | | |
| System Health | | | |
| Queue | | | |
| Scheduler | | | |
| Mail | | | |
| Web Push | | | |
| TTS | | | |
| Media Security | | | |
| CMS | | | |
| Search | | | |
| SEO | | | |
| Redirects | | | |
| Comments | | | |
| Newsletter | | | |
| Reader Features | | | |
| Accessibility | | | |
| Security | | | |
| Performance | | | |
| Responsive | | | |
| Build | | | |
| Production Smoke Test | | | |
| Backup/Rollback | | | |

Use only:

```text
PASS
WARN
FAIL
NOT VERIFIED
NOT APPLICABLE
```

Do not use subjective scores such as:

```text
9/10
95%
Excellent
Best
Production Ready
```

unless the status is objectively supported.

---

# 39. FINAL DEPLOYMENT DECISION

Use exactly one of these classifications.

## OPTION A — CODE READY, ENVIRONMENT NOT VERIFIED

```text
FINAL STATUS:

CODE READY FOR PRODUCTION DEPLOYMENT
PRODUCTION ENVIRONMENT VERIFICATION REQUIRED
```

Use this when the application passes code-level verification but no real production deployment has been tested.

## OPTION B — DEPLOYMENT READY WITH WARNINGS

```text
FINAL STATUS:

DEPLOYMENT READY WITH DOCUMENTED WARNINGS
NO P0 CODE BLOCKERS
PRODUCTION CONFIGURATION REQUIRED
```

Use this when deployment prerequisites remain but no code-level blocker exists.

## OPTION C — PRODUCTION DEPLOYMENT VERIFIED

Only use this if a real staging/production environment was actually tested:

```text
FINAL STATUS:

PRODUCTION DEPLOYMENT VERIFIED
ALL CRITICAL RUNTIME SYSTEMS OPERATIONAL
```

Do NOT use Option C based solely on local tests.

---

# 40. FINAL REPORT REQUIREMENTS

Create a detailed report containing:

1. Audit date
2. Application version
3. Laravel version
4. PHP version
5. Node/Vite version
6. Database driver
7. Environment tested
8. Commands executed
9. Actual test results
10. Files inspected
11. Files changed
12. Database changes
13. Security findings
14. Performance findings
15. Runtime findings
16. Environment requirements
17. Remaining blockers
18. Production deployment checklist
19. Final scorecard
20. Final deployment classification

For every important PASS, provide evidence.

For every WARN, explain exactly what remains.

For every FAIL, explain:

```text
Problem
Impact
Evidence
Recommended Fix
Priority
```

---

# 41. IMPORTANT FINAL INSTRUCTION

The previous audit claimed:

> PRODUCTION READY FOR DEPLOYMENT — CODE VERIFIED, ENVIRONMENT CONFIGURATION REQUIRED

Your job is to independently verify whether that statement is justified.

Do not agree with the previous report automatically.

If the application is actually ready at code level, say so.

If production runtime has not been verified, explicitly say so.

If a blocker is discovered, fix it only when the fix is safe and minimal, then retest.

Do not hide unresolved problems to make the report look successful.

The purpose of this audit is **truthful production readiness**, not a green report.

---

# 42. EXPECTED FINAL OUTPUT

At the very end, provide:

```text
========================================
APEX MEDIA v2.4
FINAL FORENSIC PRODUCTION AUDIT
========================================

Code Status:
Security Status:
Database Status:
Test Status:
Build Status:
Queue Status:
Scheduler Status:
Mail Status:
Push Status:
TTS Status:
SEO Status:
Analytics Status:
Performance Status:
Production Smoke Test:
Backup/Rollback Status:

P0 Blockers:
P1 Blockers:
P2/P3 Items:

FINAL STATUS:
[ONE OF THE THREE APPROVED CLASSIFICATIONS]

========================================
```

Then provide:

```text
EXACT FILES CHANGED
```

and:

```text
EXACT COMMANDS EXECUTED
```

and:

```text
PRODUCTION DEPLOYMENT CHECKLIST
```

Do not claim anything was tested unless you actually tested it.

# END OF MASTER AUDIT PROMPT
