# ANTIGRAVITY PHASE 2 MASTER PROMPT
## Upgrade the Existing APEX International Business & Leadership Media Platform

### CRITICAL: CONTINUATION, NOT REBUILD

You are continuing development of the existing Laravel project:

`C:\NewsBlog`

The project already contains a working public media portal and editorial CMS. **Do not rebuild it from scratch. Do not delete existing data. Do not replace working architecture without a strong technical reason.**

Your task is to audit the current implementation, preserve all existing functionality, and implement the missing Phase 2 capabilities described below.

---

# 1. CURRENT BASELINE

The current implementation already includes:

- APEX International Business & Leadership branding
- Public homepage
- Global navigation and category mega-nav
- Financial markets ticker
- Article search
- Breaking News
- Featured/Cover Story
- Editors' Picks
- In-depth article stream
- Trending/Most Read
- Rankings and Lists
- Author profiles
- Newsletter subscription capture
- Article pages
- NewsArticle/Person/Organization schema
- Admin login
- Admin dashboard
- Article CMS
- Draft/Submitted/Published/Archived states
- Cover/Featured flag
- Breaking News flag
- Trending flag
- Editors' Pick flag
- Sponsored By field
- SEO meta fields
- Category manager
- Rankings builder

Reported baseline test result:

`11 passed, 21 assertions`

Existing tests reportedly cover:

- Home page
- Article page
- Category page
- Rankings page
- Search page
- Admin login
- Admin authentication
- Article creation
- Newsletter subscription

Treat this as the current production baseline.

---

# 2. MANDATORY AUDIT FIRST

Before making significant changes:

1. Inspect Laravel/PHP versions.
2. Inspect frontend architecture.
3. Inspect routes.
4. Inspect controllers.
5. Inspect models.
6. Inspect migrations/database schema.
7. Inspect authentication.
8. Inspect roles and permissions.
9. Inspect admin panel.
10. Inspect article CMS.
11. Inspect category system.
12. Inspect ranking system.
13. Inspect author system.
14. Inspect newsletter system.
15. Inspect media/storage.
16. Inspect SEO implementation.
17. Inspect sitemap/robots.
18. Inspect analytics.
19. Inspect queues/jobs.
20. Inspect scheduled tasks.
21. Inspect mail configuration.
22. Inspect APIs.
23. Inspect existing tests.

Create an implementation map:

### ALREADY COMPLETE
Existing working features.

### PARTIALLY COMPLETE
Features that exist but need expansion.

### MISSING
Features that need implementation.

### CONFLICTS/RISKS
Potential compatibility problems.

### DATABASE CHANGES
Required migrations, indexes, relationships.

**Do not duplicate existing functionality. Extend it where possible.**

---

# 3. SAFETY AND PRESERVATION

Mandatory rules:

- Preserve existing articles.
- Preserve existing categories.
- Preserve existing authors.
- Preserve rankings.
- Preserve newsletter subscribers.
- Preserve admin accounts.
- Preserve existing URLs.
- Preserve existing SEO metadata.
- Preserve existing frontend functionality.
- Preserve existing admin functionality.
- Preserve existing tests.

Use Laravel migrations for schema changes.

Prefer additive migrations.

Never use destructive database operations merely to simplify development.

If a change could break existing functionality, implement a safe migration/compatibility strategy first.

---

# 4. PHASE 2 OBJECTIVE

Upgrade the current APEX platform into a complete professional digital media platform.

Implement:

1. Public user accounts
2. User dashboard
3. Bookmarks
4. Reading history
5. Following authors/categories/topics
6. Contributor portal
7. Editorial workflow
8. Internal editorial notes
9. Public comments and moderation
10. Advanced search
11. Trending engine
12. Recommendation engine
13. Media library
14. Video system
15. Podcast system
16. Audio article system
17. Newsletter management
18. Notifications
19. Browser push architecture
20. Advanced SEO management
21. Redirect manager
22. Dynamic XML sitemap
23. News sitemap
24. Dynamic robots.txt
25. Granular roles and permissions
26. Audit logs
27. Advanced analytics
28. Editorial quality checks
29. Improved admin UX

Every feature must work end-to-end.

**Do not create UI-only placeholders.**

---

# 5. PUBLIC USER ACCOUNTS

Create a public account system separate from staff/admin authentication where appropriate.

Support:

- Registration
- Login
- Logout
- Password reset
- Email verification
- Session security
- Optional OAuth only when properly configured

Account page:

`/account`

Include:

- Profile
- Saved Articles
- Reading History
- Following
- Notifications
- Newsletter Preferences
- Account Settings

Users may only modify their own accounts.

---

# 6. BOOKMARKS

Add `Save Article` to article pages.

Users can:

- Save
- Unsave
- View saved articles
- Search saved articles
- Sort saved articles

Prevent duplicate bookmarks with a database unique constraint.

Guests should receive a clear login/register prompt.

---

# 7. READING HISTORY

Track authenticated users' article history.

Store:

- User
- Article
- First read
- Last read
- Reading progress where practical

Do not create unlimited duplicate rows.

Allow:

`Account → Reading History → Clear History`

Respect privacy settings.

---

# 8. FOLLOWING

Users can follow:

- Authors
- Categories
- Topics

Prepare the architecture for future company following.

Buttons:

`Follow`

`Following`

Use follows for:

- Personalized content
- Notifications
- User dashboard

Prevent duplicate follows.

---

# 9. CONTRIBUTOR PORTAL

Create a real contributor system.

Contributor application fields:

- Name
- Email
- Bio
- Expertise
- Website
- Social profiles
- Portfolio
- Application message

Admin can:

- Approve
- Reject
- Suspend
- Restore
- Assign contributor role

Contributor dashboard:

`/contributor`

Sections:

- Dashboard
- My Articles
- Drafts
- Submitted
- Revision Requested
- Approved
- Published
- Profile

Contributor actions:

- Create draft
- Save draft
- Edit draft
- Submit for review
- View editorial status
- Respond to revisions

Contributors must not publish directly unless explicitly authorized.

---

# 10. EDITORIAL WORKFLOW

Expand the existing lifecycle to:

`Draft → Submitted for Review → Revision Requested → Under Editorial Review → SEO Review → Approved → Scheduled → Published → Archived`

Implement:

- Editor assignment
- Reviewer assignment
- Internal notes
- Revision requests
- Approval
- Rejection
- Scheduling
- Publishing
- Archiving

Maintain compatibility with the existing workflow.

---

# 11. INTERNAL EDITORIAL NOTES

Create private editorial notes separate from public comments.

Support:

- Editor note
- Revision request
- Fact-check note
- SEO note
- Author mention

Never expose private notes publicly.

Only authorized staff and intended contributors/authors may see relevant notes.

---

# 12. COMMENTS AND MODERATION

Create public comments with:

- Comments
- Replies
- Likes
- Reports
- Author replies
- Admin replies
- Pinning
- Moderation
- Spam protection

Statuses:

- Pending
- Approved
- Reported
- Rejected
- Spam
- Deleted

Implement:

- Rate limiting
- Server-side authorization
- Safe HTML sanitization
- Abuse reporting

Never trust user-submitted HTML.

---

# 13. ADVANCED SEARCH

Upgrade the current search.

Search:

- Articles
- Authors
- Categories
- Tags
- Topics
- Videos
- Podcasts
- Rankings
- Companies when available
- People when available

Features:

- Autocomplete
- Search suggestions
- Typo-tolerant matching where practical
- Relevance ranking
- Recent searches
- Popular searches
- Pagination

Filters:

- Content type
- Category
- Author
- Date
- Topic
- Popularity

Use indexed database search for the current scale unless a dedicated search engine is actually required.

Architect the search service so another search provider can be introduced later.

---

# 14. TRENDING ENGINE

Replace purely manual/static trending with configurable scoring.

Use signals such as:

- Recent pageviews
- Total pageviews
- Shares
- Bookmarks
- Comments
- Reading engagement
- Recency

Use time decay.

Create a `TrendingService` and scheduled job.

Admin controls:

- Feature
- Exclude
- Override
- Reset

Do not fabricate engagement numbers.

---

# 15. RECOMMENDATION ENGINE

Create reusable recommendations using:

- Category
- Tags
- Topics
- Author
- Trending
- Popularity
- User follows
- Reading history
- Semantic similarity if available

Create a reusable service such as:

`RecommendationService`

Support:

- Homepage recommendations
- Article recommendations
- User dashboard recommendations
- Category recommendations

Avoid excessive repetition of content the user already read.

---

# 16. MEDIA LIBRARY

Create:

`/admin/media`

Support:

- Images
- Videos
- Audio
- PDFs
- Documents
- Infographics

Features:

- Upload
- Search
- Filter
- Sort
- Preview
- Edit metadata
- Replace
- Delete
- Usage tracking
- Copy URL
- Select from editor

Metadata:

- Filename
- Title
- Alt text
- Caption
- Description
- Source
- Copyright
- Uploaded by
- Type
- Dimensions
- File size
- Created date

Do not allow deletion of assets required by published content without a safe replacement strategy.

---

# 17. IMAGE OPTIMIZATION

Implement where practical:

- WebP
- AVIF
- Responsive image sizes
- Thumbnail generation
- Compression
- Lazy loading
- Crop
- Focal point

Preserve originals where necessary.

---

# 18. VIDEO SYSTEM

Create:

`/videos`

`/videos/{slug}`

Admin video fields:

- Title
- Slug
- Description
- Thumbnail
- Video URL/file
- Provider
- Duration
- Transcript
- Captions
- Category
- Tags
- Author
- SEO

Support uploaded videos and safe external providers such as YouTube/Vimeo.

Add VideoObject schema where appropriate.

---

# 19. PODCAST SYSTEM

Create:

`/podcasts`

`/podcasts/{slug}`

Support:

- Podcast shows
- Episodes
- Hosts
- Guests
- Audio
- Transcript
- Cover image
- Episode number
- Season
- Publish date
- Related articles
- External listening links

Add appropriate structured data.

---

# 20. AUDIO ARTICLES

Add:

`Listen to this article`

Player:

- Play
- Pause
- Seek
- Speed
- Progress
- Resume

Generate audio asynchronously.

Cache generated audio.

Use a provider abstraction for TTS if integrated.

Do not hardcode API keys.

If no TTS provider is configured, provide a proper disabled/configurable state rather than a fake player.

---

# 21. NEWSLETTER MANAGEMENT

Keep the current newsletter subscription functionality.

Expand it into:

`/admin/newsletters`

Sections:

- Subscribers
- Segments
- Campaigns
- Templates
- Analytics

Subscriber fields:

- Email
- Name where available
- Status
- Interests
- Source
- Subscription date
- Unsubscribe date

Support:

- Subscribe
- Confirmation where required
- Unsubscribe
- Preference center
- Segmentation

Campaign workflow:

`Draft → Preview → Test Send → Scheduled → Sent`

Use queues for large campaigns.

Never send large campaigns synchronously.

---

# 22. NEWSLETTER BUILDER

Reusable blocks:

- Logo
- Headline
- Article card
- Image
- CTA
- Advertisement
- Sponsored content
- Footer
- Social links

Editors should be able to build newsletters without coding.

---

# 23. NOTIFICATION SYSTEM

Create user notifications.

Types:

- Breaking news
- New article from followed author
- New article in followed topic/category
- Newsletter
- Account/system
- Subscription events when subscriptions are added

Fields:

- User
- Type
- Title
- Message
- URL
- Read timestamp
- Created timestamp

Support:

- Mark read
- Mark all read
- Delete where appropriate

---

# 24. BROWSER PUSH ARCHITECTURE

Prepare browser push support.

Requirements:

- User-initiated permission request
- Secure subscription storage
- Unsubscribe
- Expired subscription cleanup
- User preferences

Targeted notifications:

- All opted-in users
- Category followers
- Topic followers
- Author followers

Do not require an external provider unless configured.

---

# 25. ADVANCED SEO MANAGEMENT

Create:

`/admin/seo`

Sections:

- Overview
- Articles
- Categories
- Authors
- Topics
- Rankings
- Redirects
- Sitemap
- Robots
- Schema
- Broken Links

Support:

- Meta title
- Meta description
- Canonical
- Robots
- OG title
- OG description
- OG image
- X/Twitter card
- Schema type

Warnings:

- Missing title
- Missing meta description
- Missing H1
- Missing canonical
- Missing OG image
- Missing alt text
- Duplicate title
- Duplicate description
- Poor title length
- Broken links

Do not represent internal SEO scoring as a Google ranking.

---

# 26. REDIRECT MANAGER

Create:

`/admin/seo/redirects`

Fields:

- Source
- Destination
- Status code
- Active/inactive
- Hit count
- Created by
- Created date

Support:

- 301
- 302

Prevent:

- Redirect loops
- Self-redirects
- Invalid destinations

---

# 27. XML SITEMAPS

Create dynamic sitemap generation.

Support:

- Sitemap index
- Article sitemap
- Category sitemap
- Author sitemap
- Topic sitemap
- Image sitemap where useful
- News sitemap where eligible

Only include indexable public content.

Exclude:

- Drafts
- Private content
- Deleted content
- Noindex pages

Use correct production HTTPS/canonical configuration.

---

# 28. ROBOTS.TXT

Create a dynamic/configurable robots.txt.

Provide an admin preview.

Never accidentally block the whole production website.

---

# 29. NEWS SEO

For eligible articles:

- NewsArticle schema
- Publication date
- Modified date
- Author
- Publisher
- Headline
- Image
- News sitemap support

Do not make false claims about search-engine eligibility.

---

# 30. GRANULAR ROLES & PERMISSIONS

Audit the existing staff authentication.

Support roles:

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
- Subscriber
- User

Permissions:

- View
- Create
- Edit
- Review
- Publish
- Schedule
- Delete
- Restore
- Manage
- Configure

Use server-side authorization policies/gates.

Hiding buttons is NOT sufficient authorization.

---

# 31. AUDIT LOGGING

Create admin audit logs for important actions:

- Login
- Failed login where appropriate
- Article creation
- Article editing
- Publishing
- Unpublishing
- Delete/restore
- Category changes
- Ranking changes
- Role changes
- Permission changes
- SEO changes
- Redirect changes
- Newsletter actions
- User management

Fields:

- User
- Action
- Entity
- Entity ID
- IP where appropriate
- User agent where appropriate
- Timestamp
- Safe before/after values

Never log passwords, tokens, API keys, or secrets.

---

# 32. ADMIN DASHBOARD UPGRADE

Preserve the current dashboard.

Add:

### Content
- Published
- Draft
- Pending review
- Scheduled
- Archived

### Audience
- Pageviews
- Visitors
- Registered users
- Newsletter subscribers

### Engagement
- Bookmarks
- Comments
- Shares
- Reading time
- Top articles

### Editorial
- Pending reviews
- Revision requests
- Contributor submissions

Do not fabricate revenue or traffic data.

---

# 33. ANALYTICS

Create an analytics abstraction.

Track appropriate events:

- Pageview
- Article view
- Search
- Share
- Bookmark
- Comment
- Newsletter signup
- Follow
- Video play
- Podcast play

Admin reports:

- Today
- 7 days
- 30 days
- Custom range

Reports:

- Top articles
- Top categories
- Top authors
- Top searches
- Engagement
- User growth

Keep analytics privacy-conscious.

---

# 34. ARTICLE ANALYTICS

Show:

- Views
- Unique visitors where supported
- Average reading time
- Scroll depth if implemented
- Shares
- Saves
- Comments
- Newsletter conversions

Do not execute expensive aggregation queries on every article page request.

Use background aggregation where necessary.

---

# 35. CONTENT QUALITY CHECKER

Before publishing, validate:

- Title
- H1
- Content
- Featured image
- Alt text
- Author
- Category
- Slug
- Meta title
- Meta description
- Canonical
- Schema
- Internal links

Show:

- Passed
- Warning
- Required

Only block publication for genuinely mandatory fields.

---

# 36. ADMIN TABLE UX

Upgrade major admin tables to support:

- Search
- Filters
- Sorting
- Pagination
- Status
- Date filtering
- Safe bulk actions
- Export where useful

Use server-side pagination.

Do not load thousands of records into the browser unnecessarily.

---

# 37. PERFORMANCE

Do not degrade existing homepage/article performance.

Check:

- N+1 queries
- Database indexes
- Caching
- Image optimization
- Lazy loading
- JS bundle size
- Pagination
- Queue processing

Do not cache private/personalized data incorrectly.

---

# 38. SECURITY

Mandatory:

- CSRF
- XSS protection
- SQL injection protection
- Authorization policies
- Rate limiting
- Secure password hashing
- Secure sessions
- Email verification
- File validation
- MIME validation
- Safe image processing
- Comment sanitization
- Secure redirects
- Admin 2FA architecture where supported

Never trust:

- User HTML
- Uploaded filenames
- IDs
- Redirect URLs
- Metadata

Validate everything server-side.

---

# 39. TESTING

Run the existing test suite BEFORE changes.

Do not break the existing 11 tests.

Add tests for:

## Users
- Registration
- Login
- Logout
- Password reset
- Email verification

## Bookmarks
- Save
- Unsave
- Duplicate prevention
- Authorization

## Following
- Follow
- Unfollow
- Duplicate prevention

## Contributors
- Application
- Approval
- Submission
- Revision
- Publishing permissions

## Comments
- Create
- Reply
- Report
- Moderation
- Authorization

## Search
- Search
- Filters
- Pagination

## Newsletter
- Subscribe
- Duplicate handling
- Unsubscribe
- Preferences

## SEO
- Canonical
- Meta
- Sitemap
- Robots
- Redirects
- Schema

## Permissions
Verify unauthorized roles cannot:

- Publish
- Delete
- Change roles
- Change SEO
- Manage users
- Change settings

---

# 40. REGRESSION TESTING

After Phase 2, verify:

### Public
- Homepage
- Article
- Category
- Search
- Rankings
- Author
- Newsletter
- New account pages
- Video
- Podcast

### Admin
- Login
- Dashboard
- Article create/edit/publish
- Category management
- Ranking management
- Media
- Newsletter
- SEO
- Users
- Contributors
- Comments

Existing functionality must continue to work.

---

# 41. DATABASE RULES

Inspect the existing schema before adding tables.

Potential structures:

- bookmarks
- reading_histories
- follows
- contributors
- contributor_applications
- editorial_notes
- comments
- comment_reports
- media_assets
- videos
- podcasts
- podcast_episodes
- newsletter_segments
- newsletter_campaigns
- newsletter_events
- notifications
- redirects
- audit_logs
- analytics_events
- article_metrics
- trending_scores
- recommendation caches

Do not create duplicates if equivalent tables already exist.

Use:

- Foreign keys
- Indexes
- Unique constraints
- Soft deletion where appropriate
- Proper timestamps

---

# 42. MIGRATION SAFETY

Every schema change must use migrations.

Before migration:

- Inspect schema.
- Check existing records.
- Determine nullable/default strategy.

After migration:

- Run migration.
- Run tests.
- Verify existing data.
- Verify relationships.

Never use destructive operations merely to simplify development.

---

# 43. DESIGN CONSISTENCY

All new features must match the existing APEX design language.

Reuse:

- Typography
- Colors
- Cards
- Buttons
- Tables
- Forms
- Navigation
- Icons
- Spacing
- Responsive behavior

Do not introduce unrelated visual systems.

---

# 44. NO FAKE DATA

Never fabricate:

- Users
- Views
- Revenue
- Companies
- Net worth
- Rankings
- Financial figures
- Subscribers
- Analytics
- Engagement

If development seed data is needed, label it clearly as demo/test data.

---

# 45. NO HARDCODED CONTENT

Do not hardcode:

- Categories
- Navigation
- Authors
- Trending articles
- Advertisements
- Rankings
- Permissions
- Newsletter lists

Use database/configuration-driven systems.

---

# 46. FEATURE FLAGS

Where practical, make advanced features configurable:

- Comments enabled
- Video enabled
- Podcasts enabled
- Audio enabled
- Registration enabled
- Contributor submissions enabled
- Push notifications enabled

This allows gradual rollout.

---

# 47. DOCUMENTATION

Update/create:

`README.md`

Document:

- Architecture
- Setup
- Migration
- Seed process
- Admin login
- User registration
- Contributor workflow
- Newsletter configuration
- Media configuration
- Queue configuration
- Scheduler
- SEO
- Environment variables
- Testing
- Deployment

Never include real credentials or secrets.

Update `.env.example` with safe placeholders where new configuration is required.

---

# 48. IMPLEMENTATION ORDER

Use this order unless the existing architecture requires a safer dependency order:

1. Audit
2. Run existing tests
3. User accounts
4. User dashboard
5. Bookmarks
6. Reading history
7. Following
8. Contributor system
9. Editorial workflow
10. Editorial notes
11. Comments/moderation
12. Advanced search
13. Trending
14. Recommendations
15. Media library
16. Video
17. Podcasts
18. Audio
19. Newsletter management
20. Notifications
21. Browser push architecture
22. SEO management
23. Redirect manager
24. Sitemap/robots
25. Roles/permissions
26. Audit logs
27. Analytics
28. Content quality checks
29. UI/UX polish
30. Full regression testing

After each major module:

- Run relevant tests.
- Fix failures.
- Verify existing pages.
- Continue.

---

# 49. COMPLETION STANDARD

A feature is NOT complete merely because:

- A database table exists.
- A controller exists.
- A route exists.
- A UI page exists.
- A button exists.

A feature is complete only when:

- Database support exists
- Migration exists
- Backend logic exists
- Validation exists
- Authorization exists
- Frontend exists
- Admin functionality exists where required
- Error handling exists
- Tests exist
- Existing functionality still works

---

# 50. FINAL REPORT

After implementation, provide:

## A. Existing Features Preserved

List the original APEX functionality that remains working.

## B. Phase 2 Features Completed

For every feature:

- Description
- Routes
- Admin pages
- Database changes
- Main files changed
- Tests

## C. Database Changes

List:

- Migrations
- Tables
- Columns
- Indexes
- Relationships

## D. Routes

List new public/admin routes.

## E. Roles & Permissions

List implemented permissions.

## F. Tests

Report:

- Existing tests
- New tests
- Total tests
- Assertions
- Pass/fail

## G. Remaining Work

Clearly identify anything not implemented.

## H. Configuration Required

Identify:

- Mail
- Queue
- Storage
- TTS
- Push notifications
- Analytics
- External services

Never invent credentials.

---

# 51. FINAL COMMAND

START WITH THE AUDIT.

1. Inspect `C:\NewsBlog`.
2. Run the current tests before changing anything.
3. Produce the implementation map.
4. Preserve all existing working functionality.
5. Implement Phase 2 incrementally.
6. Test after each major module.
7. Fix regressions immediately.
8. Do not delete production data.
9. Do not unnecessarily change existing URLs.
10. Do not rebuild the current APEX website.
11. Do not use fake production data.
12. Do not claim a feature is complete if it is only a UI mock.

The goal is to evolve the existing APEX International Business & Leadership platform into a scalable, secure, SEO-friendly, high-performance, professional digital media platform.
