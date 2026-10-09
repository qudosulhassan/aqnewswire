# ANTIGRAVITY MASTER PROMPT
# Build a Forbes-Style Professional Digital Media Platform

## ROLE

Act as a senior full-stack architect, Laravel/PHP engineer, database architect, UI/UX designer, SEO engineer, security engineer, and QA engineer.

Your task is to transform the existing project into a professional, scalable digital media publishing platform inspired by the functionality and information architecture of major business-media websites such as Forbes, Entrepreneur, Inc., Fast Company, Fortune, Business Insider, Investopedia, VentureBeat, TechCrunch, and Mashable.

This is NOT a request to copy another website's branding, copyrighted design, text, images, source code, or proprietary assets.

Use those publications only as functional/product references.

The result must be an original brand experience with its own visual identity, reusable components, scalable architecture, and clean code.

---

# 1. CRITICAL RULE — AUDIT FIRST

DO NOT immediately rewrite or replace the existing application.

Before changing anything, inspect the current project.

You MUST first audit:

1. Existing Laravel/PHP framework and version
2. Existing frontend framework
3. Existing database structure
4. Existing migrations
5. Existing models
6. Existing controllers
7. Existing services
8. Existing routes
9. Existing Blade/React/Vue components
10. Existing authentication
11. Existing admin panel
12. Existing roles and permissions
13. Existing article/blog functionality
14. Existing media library
15. Existing SEO implementation
16. Existing sitemap and robots implementation
17. Existing analytics
18. Existing payment/subscription functionality
19. Existing email/newsletter functionality
20. Existing API endpoints
21. Existing storage configuration
22. Existing queues/jobs
23. Existing scheduled tasks
24. Existing caching
25. Existing environment configuration
26. Existing production/deployment configuration

Create an internal implementation map:

- EXISTING AND REUSABLE
- EXISTING BUT NEEDS MODIFICATION
- MISSING AND MUST BE CREATED
- POTENTIALLY CONFLICTING
- DEPRECATED/UNUSED
- HIGH RISK

DO NOT duplicate functionality that already works.

DO NOT delete existing data.

DO NOT change existing production URLs without a redirect/migration strategy.

DO NOT overwrite existing database tables blindly.

DO NOT remove existing working functionality merely to introduce a preferred architecture.

---

# 2. BACKUP & SAFETY

Before destructive database or filesystem operations:

- Verify whether backups exist.
- Do not execute destructive operations without a safe migration path.
- Use Laravel migrations for schema changes.
- Prefer additive migrations.
- Preserve existing records.
- Preserve existing IDs where possible.
- Preserve existing slugs where possible.
- Preserve existing URLs.
- Add redirects when URL changes are unavoidable.
- Never expose credentials, API keys, payment keys, or secrets.

If a required change could break production, flag it clearly and implement the safest compatible approach.

---

# 3. PRIMARY OBJECTIVE

Build a complete digital media platform containing:

- Modern homepage
- Article publishing
- Author system
- Contributor system
- Editorial workflow
- Categories
- Tags
- Topics
- Search
- Trending
- Recommendations
- Newsletters
- User accounts
- Bookmarks
- Following
- Comments
- Video
- Podcasts
- Audio articles
- Rankings
- Company profiles
- People profiles
- Events
- Advertising
- Sponsored content
- Affiliate/product content
- Subscriptions
- Premium content
- Notifications
- Analytics
- SEO
- Sitemap
- Schema
- Redirect management
- Media library
- Role-based permissions
- Homepage builder
- Performance optimization
- Security
- Accessibility

Build the foundation so additional features can be enabled later without rewriting the entire platform.

---

# 4. DEVELOPMENT PRINCIPLES

Follow these principles throughout implementation:

- DRY
- SOLID
- Modular architecture
- Reusable components
- Secure by default
- Mobile-first
- SEO-first
- Accessibility-first
- Performance-first
- Database-efficient
- API-ready
- Scalable
- Maintainable
- Testable

Avoid:

- Duplicate code
- Hardcoded content
- Hardcoded homepage sections
- Hardcoded advertisements
- Hardcoded categories
- Hardcoded author data
- Hardcoded SEO values
- N+1 database queries
- Unnecessary JavaScript
- Unnecessary dependencies
- Breaking existing APIs
- Breaking existing routes
- Destructive migrations

---

# 5. VISUAL DESIGN

Create an original premium editorial design.

The website should feel:

- Professional
- Modern
- Editorial
- Premium
- Fast
- Clean
- Trustworthy
- Information-dense without feeling cluttered

DO NOT clone Forbes, Entrepreneur, Fortune, or another publication.

Create an original:

- Typography system
- Color system
- Spacing system
- Card design
- Navigation
- Article layout
- Buttons
- Icons
- Components
- Footer
- Mobile navigation

Use a consistent design system throughout the website and admin panel.

---

# 6. FRONTEND HEADER

Implement:

- Logo
- Main navigation
- Category navigation
- Mega menu
- Search
- Login/Register
- Subscribe
- Newsletter CTA
- Trending
- Latest
- Mobile menu
- Dark/light mode
- Social links

All navigation should be manageable from the admin panel.

---

# 7. CATEGORY SYSTEM

Create configurable categories and subcategories.

Example categories:

- Business
- Technology
- AI
- Startups
- Finance
- Investing
- Marketing
- Leadership
- Entrepreneurship
- Careers
- Personal Finance
- Innovation
- Lifestyle
- Education
- News
- Opinions
- Reviews
- Guides
- Lists
- Interviews

Admin must be able to:

- Create
- Edit
- Delete
- Reorder
- Hide/show
- Set parent category
- Configure SEO
- Set featured image
- Configure layout

---

# 8. HOMEPAGE

Build a dynamic homepage.

## Hero

Support:

- Featured article
- Featured image
- Category
- H1 headline
- Description
- Author
- Date
- Reading time
- Trending badge
- Video indicator

## Homepage modules

Support:

- Breaking News
- Featured
- Latest
- Trending
- Most Read
- Editor's Picks
- Technology
- AI
- Business
- Finance
- Startups
- Leadership
- Interviews
- Videos
- Podcasts
- Rankings
- Newsletter
- Sponsored content
- Advertisements

---

# 9. HOMEPAGE BUILDER

Create an admin drag-and-drop homepage builder.

Admin can:

- Add module
- Remove module
- Reorder module
- Configure title
- Select category
- Select topic
- Select manual articles
- Set article count
- Select layout
- Configure desktop layout
- Configure tablet layout
- Configure mobile layout
- Schedule layout changes
- Preview
- Publish

Homepage must NOT require developer code changes for normal content rearrangement.

---

# 10. ARTICLE CMS

Create a powerful article editor.

## Article fields

- Title
- Subtitle
- Slug
- Content
- Excerpt
- Featured image
- Image caption
- Author
- Category
- Subcategory
- Tags
- Topics
- Published date
- Updated date
- Reading time
- Status
- Visibility
- Premium flag
- Sponsored flag
- Affiliate disclosure
- SEO title
- SEO description
- Canonical
- Robots
- OG image
- Schema settings

## Editor blocks

Support:

- Paragraph
- H2
- H3
- H4
- Image
- Gallery
- Video
- Audio
- YouTube
- Social embed
- Table
- Quote
- Pull quote
- Ordered list
- Unordered list
- Callout
- Button
- Related articles
- Newsletter CTA
- Advertisement
- Affiliate/product card
- FAQ
- Chart
- Interactive embed
- Download/file

Use the existing editor if it is already stable. Extend it instead of replacing it unnecessarily.

---

# 11. ARTICLE PAGE

Build a premium editorial article layout.

Header:

- Breadcrumb
- Category
- H1
- Subtitle
- Author
- Author photo
- Published date
- Updated date
- Reading time
- Share
- Save
- Listen

Body:

- Responsive typography
- Optimized media
- Captions
- Tables
- Quotes
- Related content
- Ads
- Newsletter CTA

Footer:

- Author bio
- Author social links
- Related stories
- More from author
- Recommended content
- Tags
- Sources
- Corrections
- Comments
- Newsletter
- Share

---

# 12. EDITORIAL WORKFLOW

Implement:

Draft
→ Submitted
→ Under Review
→ Revision Required
→ Fact Check
→ SEO Review
→ Approved
→ Scheduled
→ Published

Editors must be able to:

- Assign reviewers
- Leave internal notes
- Request changes
- Approve
- Reject
- Schedule
- Publish
- Unpublish

Contributors must not be able to bypass editorial approval unless explicitly granted permission.

---

# 13. AUTHORS

Create author profiles.

Fields:

- Name
- Slug
- Photo
- Job title
- Short bio
- Full bio
- Expertise
- Website
- Social links
- Email visibility setting
- Published articles
- Videos
- Podcasts

Author page:

/authors/{slug}/

Include:

- Profile
- Biography
- Expertise
- Latest articles
- Popular articles
- Videos
- Podcasts

Add Person schema where appropriate.

---

# 14. CONTRIBUTOR SYSTEM

Allow contributor applications and submissions.

Contributor dashboard:

- Profile
- Drafts
- Submitted articles
- Revision requests
- Approved articles
- Published articles
- Status

Admin workflow:

- Application review
- Approval
- Rejection
- Suspension
- Role assignment

---

# 15. SEARCH

Create site-wide search.

Search:

- Articles
- Authors
- Categories
- Tags
- Topics
- Videos
- Podcasts
- Rankings
- Companies
- People

Filters:

- Date
- Category
- Author
- Content type
- Relevance
- Popularity

Add:

- Autocomplete
- Suggested searches
- Recent searches
- Popular searches
- Pagination

Use indexed database/search infrastructure suitable for the project's scale.

---

# 16. TRENDING

Implement a configurable trending algorithm using legitimate engagement signals such as:

- Views
- Recent views
- Shares
- Saves
- Comments
- Reading engagement

Provide:

- Trending today
- Trending this week
- Fast-growing
- Most read

Allow editors to manually feature content.

---

# 17. RELATED & RECOMMENDED CONTENT

Create a recommendation system using:

- Category
- Tags
- Topics
- Entities
- Author
- Recent engagement
- Semantic similarity if infrastructure supports it

Every article should display useful related content.

Avoid misleading clickbait recommendations.

---

# 18. USER ACCOUNTS

Support:

- Registration
- Login
- Logout
- Password reset
- Email verification
- Google OAuth where configured
- Apple OAuth where configured
- Profile

Dashboard:

- Saved articles
- Reading history
- Following
- Newsletter preferences
- Notifications
- Subscription
- Comments

---

# 19. BOOKMARKS

Add Save/Bookmark functionality.

User can:

- Save
- Remove
- View saved articles
- Search saved articles if feasible

Use authenticated user ownership and proper authorization.

---

# 20. FOLLOWING

Allow users to follow:

- Authors
- Categories
- Topics
- Companies

Use follows to personalize feeds and notifications.

---

# 21. COMMENTS

Support:

- Comments
- Replies
- Likes
- Reports
- Moderation
- Author replies
- Admin pinning
- Spam protection

Admin states:

- Pending
- Approved
- Reported
- Spam
- Deleted

Implement rate limits and anti-abuse protection.

---

# 22. NEWSLETTER

Create:

- Subscriber database
- Newsletter preferences
- Segments
- Templates
- Campaigns
- Scheduling
- Analytics

Newsletter types:

- Daily
- Weekly
- Category
- Author
- Breaking news
- Premium

Use background jobs for sending.

Never send email synchronously from a web request if large subscriber lists are involved.

---

# 23. VIDEO

Support:

- Uploaded videos
- External embeds
- Video articles
- Interviews
- Explainers
- Short videos
- Playlists

Fields:

- Title
- Description
- Thumbnail
- Video URL/file
- Transcript
- Captions
- Duration
- Category
- Tags
- SEO

---

# 24. PODCASTS

Support:

- Podcast shows
- Episodes
- Hosts
- Guests
- Audio
- Transcript
- Cover image
- Episode metadata
- Related articles
- External listening links

---

# 25. AUDIO ARTICLES

Add:

Listen to this article

Support:

- Play
- Pause
- Progress
- Speed
- Resume position where feasible

Use a scalable audio generation approach and cache generated audio.

---

# 26. RANKINGS & LISTS

Create a ranking engine.

Examples:

- Top 10
- Top 50
- Top 100
- Startups
- Companies
- Products
- Industry rankings

Fields:

- Name
- Year
- Category
- Methodology
- Entries
- Position
- Score
- Metrics
- Logo
- Description

Never fabricate ranking data.

Allow transparent methodology fields and source/reference fields.

---

# 27. COMPANY PROFILES

Create:

/company/{slug}/

Fields:

- Name
- Logo
- Industry
- Founded
- Headquarters
- Website
- Executives
- Funding where sourced
- Revenue where sourced
- Stock information where applicable
- Articles
- News
- Related topics

Only display factual company/financial information from verified sources or editorially entered data.

---

# 28. PEOPLE PROFILES

Create:

/people/{slug}/

Fields:

- Name
- Photo
- Position
- Company
- Biography
- Career
- Articles
- Interviews
- Social links

Do not publish unverified sensitive claims.

---

# 29. TOPIC PAGES

Create:

/topics/{slug}/

Example:

Artificial Intelligence

Include:

- Latest
- Popular
- Guides
- Companies
- People
- Rankings
- Videos
- Podcasts
- Related topics

---

# 30. EVENTS

Create:

- Events
- Conferences
- Webinars
- Workshops
- Tickets
- Registration
- Sponsors
- Speakers

Event page:

/events/{slug}/

---

# 31. AD MANAGEMENT

Create a complete ad management system.

Ad placements:

- Header
- Leaderboard
- Sidebar
- In-content
- Sticky
- Mobile
- Video
- Native

Fields:

- Campaign
- Advertiser
- Placement
- Device
- Start
- End
- Impressions
- Clicks
- CTR
- Revenue
- Status

Do not hardcode ad HTML throughout the application.

Use reusable ad-placement components.

---

# 32. SPONSORED CONTENT

Support:

- Sponsored
- Partner content
- Brand studio
- Advertorial

Clearly label sponsored content.

Add disclosure settings and campaign tracking.

---

# 33. AFFILIATE / PRODUCT CONTENT

Create structured product cards.

Fields:

- Product
- Brand
- Price
- Pros
- Cons
- Editorial information
- Affiliate URL
- CTA
- Disclosure
- Images

Affiliate disclosures must be visible where legally/ethically appropriate.

---

# 34. SUBSCRIPTIONS

Support configurable plans.

Example:

Free
Premium
Enterprise/Professional if needed

Premium features can include:

- Premium articles
- Exclusive reports
- Premium newsletters
- Archives
- Ad-free experience
- Special events

Implement:

- Plans
- Subscriptions
- Billing status
- Renewal
- Cancellation
- Grace periods
- Entitlements
- Paywall rules

Reuse existing payment integrations if present.

Do not add payment providers or keys without inspecting the existing system and requirements.

---

# 35. SEO

SEO is a critical module.

Every indexable content type should support:

- SEO title
- Meta description
- Canonical
- Robots
- OG title
- OG description
- OG image
- X/Twitter card
- Schema
- Breadcrumbs

Implement:

- XML sitemap
- News sitemap
- Image sitemap where useful
- RSS
- Robots.txt
- Redirect manager
- 404 handling
- Canonical controls
- Noindex controls
- Broken-link detection

Schema types:

- Organization
- WebSite
- Article
- NewsArticle
- Person
- BreadcrumbList
- VideoObject
- FAQPage where content genuinely qualifies
- Product/Review only where appropriate

Do not generate misleading structured data.

---

# 36. SEO DASHBOARD

For each article show a checklist:

- SEO title
- Meta description
- H1
- Slug
- Canonical
- Robots
- Featured image
- Alt text
- Internal links
- Schema
- OG image

Display warnings and actionable recommendations.

Do not claim a numerical SEO score is an official search-engine ranking.

---

# 37. INTERNAL LINKING

Provide:

- Automatic recommendations
- Manual related links
- Anchor text editing
- Related article blocks
- Topic links
- Entity links

Avoid excessive or irrelevant internal links.

---

# 38. NOTIFICATIONS

Support:

- Breaking news
- New article
- New author article
- Topic updates
- Newsletter
- Subscription events

Channels:

- Email
- Browser push
- Mobile push when mobile app exists

Users must control preferences.

---

# 39. ANALYTICS

Dashboard sections:

## Traffic

- Users
- Sessions
- Pageviews
- Returning users
- Traffic source
- Country
- Device

## Content

- Top articles
- Top authors
- Top categories
- Reading time
- Scroll depth
- Shares
- Saves

## Revenue

- Advertising
- Affiliate
- Subscriptions
- Sponsored content

Use privacy-conscious analytics.

---

# 40. ARTICLE ANALYTICS

Each article should show:

- Views
- Unique visitors where available
- Average reading time
- Scroll depth
- Shares
- Saves
- Comments
- Newsletter conversions
- Revenue where applicable

Add date-range comparison.

---

# 41. BREAKING NEWS

Add:

- Breaking badge
- Breaking ticker
- Homepage alert
- Push notification
- Email alert

All controlled by editors.

---

# 42. LIVE BLOG

Create chronological live updates.

Support:

- Timestamp
- Text
- Image
- Video
- Links
- Embeds
- Pin update
- Delete/update correction

---

# 43. MEDIA LIBRARY

Centralize:

- Images
- Videos
- Audio
- PDFs
- Documents
- Infographics

Support:

- Search
- Filter
- Upload
- Replace
- Metadata
- Alt text
- Caption
- Copyright/source field
- Usage tracking

---

# 44. DIGITAL MAGAZINE

Optional module:

- Issues
- Covers
- Articles
- Archive
- PDF
- Digital reader

Make it feature-flagged if it is not required for initial launch.

---

# 45. GAMES / INTERACTIVE ENGAGEMENT

Optional modules:

- Quizzes
- Polls
- Crossword
- Word games
- Daily challenge

Keep these modular so they do not complicate the core CMS.

---

# 46. ACCESSIBILITY

Target WCAG 2.2 AA where practical.

Implement:

- Semantic HTML
- Keyboard navigation
- Visible focus
- Screen-reader labels
- Alt text
- Captions
- Transcripts
- Accessible forms
- High contrast
- Reduced motion
- Accessible menus
- Accessible modals
- Accessible tables

---

# 47. PERFORMANCE

Target strong Core Web Vitals.

Implement:

- WebP/AVIF
- Responsive images
- Lazy loading
- CDN
- Browser caching
- Server caching
- Query optimization
- Database indexes
- Queue processing
- Minified assets
- Code splitting where appropriate
- Critical CSS
- Efficient pagination

Avoid unnecessary third-party scripts.

---

# 48. SECURITY

Implement:

- 2FA for privileged users
- Role-based permissions
- CSRF protection
- XSS protection
- SQL injection protection through framework/database abstractions
- Rate limiting
- Session management
- Secure cookies
- Password hashing
- Audit logs
- Login monitoring
- API authentication
- File upload validation
- MIME/type validation
- Image processing safety
- Secure headers

Never expose:

- API secrets
- Database credentials
- Payment keys
- OAuth secrets
- SMTP passwords
- Internal tokens

---

# 49. ROLES & PERMISSIONS

Create or extend granular roles:

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

Use policies/gates or the project's existing authorization system.

---

# 50. ADMIN NAVIGATION

Implement:

DASHBOARD
- Overview
- Analytics

CONTENT
- Articles
- Drafts
- Scheduled
- Categories
- Tags
- Topics
- Series
- Breaking News
- Live Blogs

PEOPLE
- Authors
- Contributors
- Speakers
- Users

MEDIA
- Images
- Videos
- Audio
- Documents
- Media Library

RANKINGS
- Lists
- Rankings
- Companies
- People

ENGAGEMENT
- Comments
- Polls
- Quizzes
- Bookmarks
- Notifications

NEWSLETTER
- Subscribers
- Campaigns
- Templates
- Analytics

MONETIZATION
- Advertisements
- Campaigns
- Sponsored Content
- Affiliate
- Subscriptions

EVENTS
- Events
- Speakers
- Tickets
- Sponsors

SEO
- SEO Dashboard
- Meta
- Sitemap
- Robots.txt
- Schema
- Redirects
- Canonicals
- Broken Links

ANALYTICS
- Traffic
- Content
- Authors
- Revenue
- SEO

SETTINGS
- General
- Users & Roles
- Navigation
- Homepage
- Social
- Email
- API
- Security
- Integrations

---

# 51. DATABASE DESIGN

Inspect existing tables first.

Add normalized structures only where needed.

Potential entities:

- users
- roles
- permissions
- articles
- article_revisions
- article_versions
- categories
- category_article
- tags
- tag_article
- topics
- authors
- contributors
- media
- article_media
- videos
- podcasts
- podcast_episodes
- comments
- comment_reports
- bookmarks
- follows
- newsletters
- newsletter_subscribers
- newsletter_campaigns
- advertisements
- ad_campaigns
- sponsored_content
- affiliate_products
- subscriptions
- subscription_plans
- rankings
- ranking_entries
- companies
- people
- events
- speakers
- notifications
- redirects
- seo_metadata
- audit_logs

Do not create duplicate tables if equivalent existing structures already exist.

Use:

- Foreign keys
- Indexes
- Unique constraints
- Soft deletion where appropriate
- Proper timestamps
- Status fields
- Referential integrity

---

# 52. API

Where needed, create secure APIs for:

- Authentication
- Articles
- Categories
- Tags
- Authors
- Search
- Media
- Comments
- Bookmarks
- Follows
- Notifications
- Newsletter
- Subscriptions
- Rankings
- Companies
- Events

Protected endpoints must enforce authorization.

Do not expose admin operations publicly.

---

# 53. EDITORIAL QUALITY CHECKS

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
- Duplicate slug
- Publication status

Use warnings where appropriate.

Only truly required fields should block publication.

---

# 54. URL STRUCTURE

Preferred examples:

/business/
/technology/
/ai/
/authors/john-smith/
/company/example-company/
/people/example-person/
/topics/artificial-intelligence/
/article/example-title/
/rankings/top-startups-2026/
/events/example-event/

If existing URLs differ, preserve them unless there is a properly tested migration and redirect strategy.

---

# 55. MOBILE

Fully responsive.

Mobile features:

- Sticky header
- Search
- Hamburger menu
- Optimized article typography
- Fast image loading
- Save/share
- Mobile ads
- Newsletter
- Touch-friendly controls

---

# 56. CONTENT DISCOVERY

Every article should provide useful discovery through:

- Related articles
- More from author
- More from category
- Topic pages
- Company pages
- People pages
- Trending
- Most read
- Recommended for you
- Latest stories

Do not use deceptive clickbait.

---

# 57. EDITORIAL TRANSPARENCY

Support:

- Published date
- Updated date
- Author attribution
- Contributor attribution
- Corrections
- Editorial notes
- Sources
- Sponsored disclosure
- Affiliate disclosure

Add a correction workflow.

---

# 58. ADMIN UX

Admin UI must be:

- Clean
- Fast
- Responsive
- Consistent
- Searchable
- Filterable
- Paginated
- Accessible

Every major data table should support:

- Search
- Filters
- Sort
- Pagination
- Bulk actions where safe
- Export where useful

Forms should provide:

- Validation
- Clear error messages
- Autosave where appropriate
- Unsaved-change warnings
- Success confirmation

---

# 59. FRONTEND COMPONENT SYSTEM

Create reusable components for:

- Header
- Footer
- Navigation
- Mega menu
- Article card
- Featured article
- Trending list
- Author card
- Category card
- Video card
- Podcast card
- Newsletter signup
- Advertisement
- Product card
- Ranking card
- Company card
- Person card
- Related articles
- Share controls
- Bookmark control
- Audio player
- Comments
- Pagination
- Search
- Breadcrumbs

Avoid duplicated markup.

---

# 60. EMAIL SYSTEM

Create reusable templates for:

- Welcome email
- Email verification
- Password reset
- Newsletter
- Breaking news
- New article
- Subscription confirmation
- Payment events
- Contributor status
- Editorial revision request

Use queues/background jobs for bulk sending.

---

# 61. CACHE STRATEGY

Cache where useful:

- Navigation
- Categories
- Trending
- Homepage modules
- Popular articles
- Topic pages
- Company profiles
- Rankings

Invalidate cache intelligently when content changes.

Do not cache personalized/private content incorrectly.

---

# 62. QUEUE/JOB STRATEGY

Use background jobs for:

- Newsletter sending
- Email
- Image processing
- Audio generation
- Video processing
- Sitemap generation where necessary
- Analytics aggregation
- Notifications
- Recommendation processing
- Heavy imports

---

# 63. CRON/SCHEDULED TASKS

Where supported, configure scheduled jobs for:

- Trending calculations
- Sitemap refresh
- Newsletter scheduling
- Subscription reminders
- Analytics aggregation
- Broken-link scans
- Content update reminders
- Cache refresh

Do not add schedules that conflict with existing production jobs.

---

# 64. TESTING

Create/extend automated tests for:

## Unit

- Models
- Services
- Ranking logic
- Recommendation logic
- SEO generation
- Permissions

## Feature

- Article CRUD
- Publishing
- Scheduling
- Contributor workflow
- Search
- Bookmarks
- Comments
- Newsletter
- Subscription
- Admin permissions

## Frontend/E2E where supported

Test:

- Homepage
- Article page
- Search
- Login
- Registration
- Save article
- Comments
- Admin publishing
- Mobile navigation

---

# 65. PRE-DEPLOYMENT AUDIT

Before considering the implementation complete, verify:

## Functionality

- Homepage works
- Navigation works
- Articles work
- Search works
- Authors work
- Categories work
- Tags work
- Comments work
- Newsletter works
- Admin works
- Permissions work
- Media works

## SEO

- Canonicals
- Meta
- Schema
- Sitemap
- Robots
- RSS
- Redirects
- 404

## Performance

- No obvious N+1 queries
- Images optimized
- Caching active where appropriate
- No unnecessary scripts
- Mobile performance tested

## Security

- Authorization
- CSRF
- XSS
- Rate limiting
- Upload validation
- Secure sessions
- No exposed secrets

## Database

- Migrations succeed
- Foreign keys valid
- Indexes present
- No duplicate data
- Existing records preserved

---

# 66. DEPLOYMENT SAFETY

Before production deployment:

1. Back up database.
2. Back up storage.
3. Put application into maintenance mode if required.
4. Run dependency installation safely.
5. Run migrations.
6. Build assets.
7. Clear/rebuild caches.
8. Run automated tests.
9. Run smoke tests.
10. Verify homepage.
11. Verify article page.
12. Verify login.
13. Verify admin.
14. Verify search.
15. Verify sitemap.
16. Verify robots.txt.
17. Verify canonical URLs.
18. Verify media.
19. Verify queues.
20. Verify scheduled jobs.
21. Disable maintenance mode.
22. Monitor logs.

Do not deploy destructive changes without rollback planning.

---

# 67. PHASED IMPLEMENTATION

Do not attempt all modules simultaneously.

## PHASE 1 — CORE

Build first:

- Audit
- Design system
- Homepage
- Header/footer
- Categories
- Articles
- Authors
- Search
- Tags
- Media
- SEO
- Sitemap
- Schema
- Admin CMS
- Roles/permissions
- Analytics foundation
- Responsive frontend

## PHASE 2 — PROFESSIONAL MEDIA

Then build:

- Contributors
- Editorial workflow
- Scheduling
- Comments
- User accounts
- Bookmarks
- Following
- Newsletter
- Videos
- Podcasts
- Audio
- Trending
- Recommendations
- Rankings
- Company profiles
- Topic pages
- Notifications

## PHASE 3 — MONETIZATION & ADVANCED PLATFORM

Then build:

- Subscriptions
- Premium content
- Paywall
- Advertising
- Sponsored content
- Affiliate/product system
- Events
- Advanced analytics
- Personalization
- Live blogs
- Interactive data

## PHASE 4 — ENTERPRISE

Optional:

- Digital magazine
- Mobile apps
- Games
- Brand Studio
- Data APIs
- Enterprise advertising
- Advanced company/financial datasets
- Advanced recommendation engine

---

# 68. IMPORTANT IMPLEMENTATION BEHAVIOR

When implementing each phase:

1. Inspect existing code.
2. Reuse existing architecture where possible.
3. Create migrations.
4. Create/update models.
5. Create services where business logic belongs.
6. Create controllers/actions.
7. Create policies/permissions.
8. Create routes.
9. Create frontend components.
10. Create admin UI.
11. Add validation.
12. Add tests.
13. Run lint/static checks where available.
14. Test affected functionality.
15. Verify no regressions.
16. Continue to the next module.

Do not mark a feature complete merely because its UI exists.

A feature is complete only when:

- Database support exists
- Backend logic exists
- Permissions exist
- Frontend exists
- Admin functionality exists where needed
- Validation exists
- Error handling exists
- SEO exists where relevant
- Tests exist
- Existing functionality still works

---

# 69. FINAL QUALITY STANDARD

The final product should be capable of evolving from:

Simple Blog
→ Professional Publication
→ Multi-author Digital Media Platform
→ Subscription/Advertising Business
→ Rankings/Data Platform
→ Events & Research Platform

The implementation must be original, scalable, secure, accessible, SEO-friendly, fast, maintainable, and production-ready.

DO NOT clone another publication.

DO NOT copy proprietary code, text, images, logos, or branding.

Use functional inspiration only.

---

# 70. FINAL INSTRUCTION TO ANTIGRAVITY

START WITH THE AUDIT.

Do not immediately begin writing large amounts of code.

First inspect the existing application and produce a concise implementation report containing:

### A. Existing Architecture
- Framework/version
- Frontend
- Database
- Authentication
- Admin
- Existing content system
- Existing SEO
- Existing integrations

### B. Existing Features That Can Be Reused

### C. Features That Need Modification

### D. Features That Are Missing

### E. Database Changes Required

### F. Routes/URLs That Must Be Preserved

### G. Potential Conflicts/Risks

### H. Recommended Implementation Order

After the audit, begin Phase 1.

For each phase:

- Implement
- Test
- Verify
- Report completed modules
- Report remaining modules
- Do not proceed with destructive changes without confirmation/safe migration

The priority is:

1. Preserve existing functionality.
2. Preserve existing data.
3. Preserve SEO and URLs.
4. Build a clean scalable architecture.
5. Implement the new media-platform functionality.
6. Maintain excellent frontend UX.
7. Maintain excellent admin UX.
8. Ensure security, performance, accessibility, and SEO.
9. Test before declaring completion.

NEVER say "implemented" when only the interface has been created.

Every feature must be functional end-to-end.
