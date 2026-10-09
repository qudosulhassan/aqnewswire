# Forbes-Style Digital Media Platform — Comprehensive CMS & Website Specification

## 1. Project Objective

Build a professional, scalable digital media and publishing platform inspired by the functionality of major business-media websites such as Forbes, Entrepreneur, Inc., Fast Company, Fortune, Business Insider, Investopedia, VentureBeat, TechCrunch, and Mashable.

The platform must not be treated as a simple blog. It should function as a complete digital media ecosystem consisting of:

- Content Management System (CMS)
- Editorial workflow
- Author/contributor management
- Multimedia publishing
- Search and discovery
- User accounts
- Newsletters
- Advertising
- Sponsored content
- Affiliate/product content
- Subscriptions
- Rankings and lists
- Company and people profiles
- Events
- Analytics
- SEO management
- Personalization
- Notifications
- Security and role management

The implementation must be modular, scalable, responsive, SEO-friendly, and suitable for a large content publication.

---

# 2. Core Website Structure

## Header

Implement:

- Logo
- Main navigation
- Categories dropdown / mega menu
- Search
- Login/Register
- Subscribe button
- Newsletter button
- Trending/Latest link
- Mobile hamburger menu
- Dark/light mode
- Social media links

Navigation must be manageable from the admin panel.

## Main Categories

Support configurable categories such as:

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

Administrators must be able to create, edit, reorder, hide, and delete categories.

---

# 3. Homepage

The homepage must be dynamic and modular rather than a static blog listing.

## Hero Section

Support:

- Featured story
- Large featured image
- Category
- Headline
- Short description
- Author
- Publication date
- Reading time
- Trending indicator
- Video option

## Homepage Content Modules

Provide configurable modules for:

- Breaking News
- Trending
- Latest Stories
- Editor's Picks
- Most Read
- Featured
- Business
- Technology
- AI
- Finance
- Startups
- Leadership
- Interviews
- Videos
- Podcasts
- Lists/Rankings
- Newsletter signup
- Sponsored content
- Advertisement blocks

## Homepage Builder

Create an admin-controlled drag-and-drop homepage builder.

Example structure:

Hero
↓
Breaking News
↓
Latest
↓
Technology
↓
AI
↓
Finance
↓
Trending
↓
Videos
↓
Newsletter
↓
Footer

Administrators must be able to:

- Add sections
- Remove sections
- Reorder sections
- Configure section titles
- Select content source
- Set article limits
- Choose layout
- Schedule homepage changes
- Preview changes before publishing

---

# 4. Article System

The article system is the core of the platform.

## Article Header

Every article should support:

- Category
- H1 title
- Subtitle/deck
- Featured image
- Image caption
- Author
- Author profile link
- Published date
- Updated date
- Reading time
- Social sharing
- Bookmark/save
- Print
- Listen to article
- Font-size controls

## Article Body

The editor must support:

- Paragraphs
- H2/H3/H4 headings
- Images
- Image galleries
- Videos
- YouTube embeds
- Audio
- Tables
- Quotes
- Pull quotes
- Ordered/unordered lists
- Infographics
- Charts
- Buttons
- Links
- Related articles
- Advertisement blocks
- Affiliate blocks
- Product cards
- Newsletter boxes
- Callout boxes
- FAQ blocks
- Interactive elements
- Embedded social posts
- Downloadable files

## Article Ending

Support:

- Author bio
- Author social links
- Related stories
- More from this author
- Recommended articles
- Newsletter signup
- Comments
- Share buttons
- Tags
- Sources/references

---

# 5. Author / Contributor System

Create a complete author profile system.

## Author Profile Fields

- Profile photo
- Name
- Job title
- Short biography
- Full biography
- Social profiles
- Website
- Expertise
- Published articles
- Videos
- Podcasts
- Followers

## Author Page

Example:

Author Name
Technology Expert

[Photo]

About

Expertise:
AI | Cloud | Cybersecurity

Latest Articles

The author page must be indexable and SEO optimized.

---

# 6. Contributor / Guest Writer System

Allow external contributors to submit content.

## Contributor Workflow

Contributor can:

- Apply as contributor
- Create contributor profile
- Submit article
- Save draft
- Submit for review
- Receive editorial feedback
- Edit rejected article
- Track article status
- View published articles

## Workflow

Draft
↓
Submitted
↓
Under Review
↓
Editor Revision
↓
Fact Check
↓
SEO Review
↓
Approved
↓
Scheduled
↓
Published

Admins/editors must be able to move articles between stages.

---

# 7. Admin CMS

Create a professional admin dashboard.

## Dashboard Metrics

Show:

- Total articles
- Published articles
- Drafts
- Scheduled articles
- Pending reviews
- Authors
- Views
- Unique visitors
- Engagement
- Comments
- Subscribers
- Newsletter subscribers
- Revenue
- Ad impressions
- CTR
- Top articles
- Top authors
- Traffic sources

Dashboard should support date filtering and charts.

---

# 8. Article Management

Admin must support:

- Create article
- Edit article
- Duplicate article
- Delete article
- Restore article
- Draft
- Preview
- Schedule
- Publish
- Unpublish
- Update
- Version history
- Revision comparison
- Autosave

Implement permission-aware controls so only authorized users can publish or delete content.

---

# 9. Category Management

Support:

- Categories
- Subcategories
- Tags
- Topics
- Series

Each category should have:

- Name
- Slug
- Description
- SEO title
- SEO description
- SEO settings
- Featured image
- Schema settings
- Custom layout
- Status
- Sort order

---

# 10. Search System

Create a powerful site-wide search.

Search across:

- Articles
- Authors
- Categories
- Tags
- Videos
- Podcasts
- Lists
- Rankings
- Companies
- Topics

## Search Filters

- Date
- Category
- Author
- Content type
- Popularity
- Relevance

Search should provide:

- Search suggestions
- Recent searches
- Popular searches
- Empty-state handling
- Pagination/infinite loading
- SEO-friendly result URLs

---

# 11. Trending System

Implement automated trending calculations.

Support:

- Most viewed
- Most shared
- Most commented
- Fastest growing
- Trending today
- Trending this week

Example:

1. Story A
2. Story B
3. Story C
4. Story D

Allow editors to manually override trending content when required.

---

# 12. Newsletter System

Create a complete newsletter platform.

## Newsletter Types

- Daily newsletter
- Weekly newsletter
- Category newsletter
- Breaking news alerts
- Author newsletter
- Premium newsletter

## Subscriber Preferences

Users should be able to select interests.

## Newsletter Builder

Create a drag-and-drop editor supporting:

- Logo
- Headline
- Article blocks
- Images
- Advertisements
- CTA buttons
- Social links
- Footer
- Sponsored content

## Newsletter Admin

Support:

- Subscriber management
- Segmentation
- Templates
- Campaigns
- Scheduling
- Open rate
- Click rate
- Unsubscribe rate
- Conversion tracking

---

# 13. Video Platform

Support:

- Video articles
- Interviews
- News videos
- Short videos
- Explainers
- Video podcasts
- YouTube embeds
- Video playlists

## Video Admin

Fields:

- Video file/embed
- Thumbnail
- Title
- Description
- Transcript
- Captions
- Duration
- SEO metadata
- Categories
- Related articles

---

# 14. Podcast System

Create:

- Podcast shows
- Episodes
- Hosts
- Guests
- Audio player
- Episode transcript
- Related articles
- Subscribe links

Each podcast episode should have an SEO-friendly page.

---

# 15. Audio Article / Text-to-Speech

Add:

**Listen to this article**

Player controls:

- Play
- Pause
- Progress
- Playback speed
- Skip
- Voice selection where supported

Audio availability should be configurable per article.

---

# 16. Rankings & Lists

Create a ranking/list builder.

Support:

- Top 10
- Top 50
- Top 100
- Company rankings
- Startup rankings
- Influencer rankings
- Best products
- Best companies
- Industry rankings

## Ranking Fields

- Ranking name
- Year
- Category
- Methodology
- Entries
- Score
- Position
- Logo
- Description
- Metrics
- Supporting data

Allow reusable ranking templates.

---

# 17. Interactive Data

Support:

- Charts
- Graphs
- Tables
- Financial data
- Company comparisons
- Interactive rankings
- Calculators
- Polls
- Quizzes
- Surveys

Interactive elements should be embeddable inside articles.

---

# 18. User Accounts

## Registration

Support:

- Email
- Google
- Apple
- Other configured OAuth providers

## User Dashboard

Include:

- My Profile
- Saved Articles
- Reading History
- Following
- Newsletter Preferences
- Notifications
- Subscription
- Comments

## Following

Users can follow:

- Authors
- Topics
- Companies
- Categories

Use following data to create personalized feeds.

---

# 19. Bookmark / Save

Every article should provide:

**Save Article**

Users can access saved content through:

My Account → Saved Articles

Allow users to remove saved items.

---

# 20. Comments & Community

Support:

- Comments
- Replies
- Likes
- Dislikes
- Report
- Moderation
- Verified users
- Author replies
- Admin pinning
- Spam detection

## Comment Admin States

- Pending
- Approved
- Reported
- Spam
- Deleted

Add configurable moderation rules.

---

# 21. Subscription / Premium Content

Support free and premium content.

## Free

- Standard articles
- Limited premium content previews

## Premium

- Premium articles
- Exclusive research
- Special reports
- Premium newsletters
- Archives
- Events
- Ad-free experience

Create configurable paywall rules:

- Metered paywall
- Hard paywall
- Article-specific premium
- Category-specific premium

---

# 22. Advertising System

Do not hardcode advertisements.

Create an Advertisement Management System.

## Ad Types

- Header
- Leaderboard
- Sidebar
- In-content
- Sticky
- Mobile
- Video
- Native
- Sponsored article

## Ad Fields

- Advertiser
- Campaign
- Start date
- End date
- Placement
- Device
- Impressions
- Clicks
- CTR
- Revenue
- Status

Support frequency limits and device-specific placements.

---

# 23. Sponsored Content

Create content types:

- Sponsored
- Partner Content
- Brand Studio
- Advertorial

Sponsored content must be clearly labeled to users.

Support advertiser/brand profiles and campaign tracking.

---

# 24. Newsletter Monetization

Support:

- Sponsored newsletters
- Premium newsletters
- Affiliate links
- Advertisements
- Partner promotions

Track revenue and conversions.

---

# 25. Affiliate / Product Review System

Create a structured product/review system.

## Product Fields

- Product name
- Brand
- Logo
- Price
- Rating
- Pros
- Cons
- Editorial assessment fields
- Affiliate URL
- CTA
- Disclosure
- Images

Allow product cards to be embedded in articles.

Do not automatically fabricate ratings. Ratings must be entered or generated from clearly defined, auditable criteria.

---

# 26. SEO System

SEO must be a first-class CMS feature.

## Technical SEO

Support:

- Canonical URL
- Meta title
- Meta description
- Robots directives
- XML sitemap
- Image sitemap
- News sitemap
- RSS feed
- Breadcrumb schema
- Article schema
- NewsArticle schema
- Organization schema
- Person schema
- WebSite schema
- FAQ schema
- Video schema
- Review schema where appropriate
- Open Graph
- Twitter/X cards
- hreflang
- Redirect manager
- 404 management
- Noindex controls

## SEO Dashboard

Example:

SEO Score: 94/100

- Title
- Meta description
- H1
- Canonical
- Schema
- OG image
- Internal links
- Image alt text
- Sitemap
- Robots

SEO scoring should be configurable and transparent.

---

# 27. Internal Linking Engine

Automatically recommend related content based on:

- Category
- Tags
- Keywords
- Entities
- Semantic similarity

Also allow editors to manually select related content.

---

# 28. Newsletter & Lead Generation

Provide configurable signup components.

Example:

Get the latest business news
[Email] [Subscribe]

Allow placement on:

- Homepage
- Article middle
- Article end
- Sidebar
- Popup
- Footer
- Exit-intent where legally appropriate

---

# 29. Personalization

Track user interests based on explicit follows and permitted engagement signals.

Example interests:

- AI
- Startups
- Finance
- Technology

Generate:

**Recommended For You**

Personalization must respect privacy controls and applicable laws.

---

# 30. Notifications

Support:

- Breaking news
- New article
- New author article
- Topic updates
- Newsletter
- Subscription updates

Channels:

- Browser push
- Email
- Mobile app push

Users must be able to control notification preferences.

---

# 31. Social Sharing

Support:

- Facebook
- X
- LinkedIn
- WhatsApp
- Reddit
- Copy link
- Email

Generate optimized social preview metadata and images.

---

# 32. Analytics

## Traffic Analytics

Track:

- Users
- Sessions
- Pageviews
- Returning users
- Traffic sources
- Countries
- Devices

## Content Analytics

Track:

- Top articles
- Top authors
- Top categories
- Average reading time
- Shares
- Saves
- Scroll depth

## Revenue Analytics

Track:

- Ad revenue
- Affiliate revenue
- Subscription revenue
- Sponsored content revenue

Use privacy-conscious analytics and configurable retention.

---

# 33. Editorial Analytics

For each article show:

- Views
- Average reading time
- Scroll depth
- Shares
- Bookmarks
- Newsletter signups
- Revenue where applicable

Allow editors to compare performance across dates, categories, authors, and content types.

---

# 34. Content Scheduling

Support:

- Scheduled publishing
- Auto-unpublish
- Embargo
- Update reminders
- Recurring content
- Seasonal content

Example:

Publish:
September 10
08:00 AM

All scheduling must use the site's configured timezone and display it clearly.

---

# 35. Breaking News System

Allow editors to mark content as:

**BREAKING**

Display breaking news in configurable locations.

Optional actions:

- Homepage alert
- Browser push
- Email alert
- Breaking-news ticker

---

# 36. Live Blog

Create live-event pages with chronological updates.

Example:

LIVE

4:30 PM — Latest update
4:15 PM — New announcement
3:50 PM — Market reaction
3:20 PM — Initial report

Support:

- Text updates
- Images
- Video
- Links
- Embedded posts
- Editor timestamps
- Pinning important updates

---

# 37. Editorial Tools

The CMS editor must include:

- Rich text editor
- Media library
- Image cropper
- Image compression
- Alt text
- Captions
- Author assignment
- Category assignment
- Tags
- SEO analyzer
- Internal links
- Related content
- Preview
- Revision history
- Autosave
- Scheduled publishing

---

# 38. Media Library

Central media management for:

- Images
- Videos
- Audio
- Documents
- Infographics
- PDFs

Search/filter by:

- Filename
- Author
- Date
- Category
- Content type
- Usage

Show where an asset is currently used.

---

# 39. Events System

Create:

- Events
- Conferences
- Webinars
- Workshops
- Tickets
- Speakers
- Sponsors
- Event pages
- Registration

Each event should have its own SEO-friendly page.

---

# 40. Speaker System

Speaker profiles should support:

- Photo
- Name
- Title
- Company
- Biography
- Social links
- Sessions
- Events

---

# 41. Company Profiles

Create SEO-friendly company profile pages.

Fields:

- Company name
- Logo
- Industry
- Founded
- CEO
- Employees
- Location
- Website
- Funding
- Revenue where legally/publicly sourced
- Stock information where applicable
- News
- Articles
- Executives

Create:

**Company → Related News**

relationships.

Do not fabricate financial/company information. Data sources should be documented.

---

# 42. People Profiles

Create structured profiles for notable people.

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

Any sensitive or disputed information must be handled carefully and sourced.

---

# 43. Topic Pages

Example:

Artificial Intelligence

Include:

- Latest AI News
- AI Companies
- AI Leaders
- AI Startups
- AI Guides
- AI Rankings
- AI Videos
- AI Podcasts

Topic pages should act as content hubs and be SEO optimized.

---

# 44. Digital Magazine System

Optional expansion:

- Digital magazine
- Magazine issues
- Cover
- Articles
- Archive
- PDF/digital reader
- Subscription

---

# 45. Games / Engagement

Optional engagement features:

- Quiz
- Crossword
- Word games
- Daily challenge
- Poll
- Knowledge test

These should be modular and can be enabled/disabled.

---

# 46. Accessibility

Implement:

- Keyboard navigation
- Screen reader support
- Alt text
- Captions
- Transcripts
- High contrast
- Font controls
- Reduced motion
- Accessible forms
- Semantic HTML
- Visible focus states

Target WCAG 2.2 AA where practical.

---

# 47. Performance

Target excellent Core Web Vitals.

Implement:

- WebP/AVIF
- Lazy loading
- CDN
- Browser caching
- Server caching
- Minified CSS/JS
- Critical CSS
- Responsive images
- Preloading where appropriate
- Database optimization
- Efficient queries
- Image optimization
- Pagination/infinite loading where appropriate

Avoid unnecessary JavaScript and third-party scripts.

---

# 48. Security

Admin/security features:

- 2FA
- Role permissions
- Login protection
- CSRF protection
- XSS protection
- Rate limiting
- Audit logs
- Security logs
- Session management
- Database backups
- Restore procedures
- API authentication

Never expose secrets or payment credentials in frontend code.

---

# 49. Roles & Permissions

Create granular roles such as:

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

Permissions should be granular by module and action:

- View
- Create
- Edit
- Review
- Publish
- Schedule
- Delete
- Restore
- Manage settings

---

# 50. Recommended Admin Navigation

## DASHBOARD

- Dashboard
- Overview
- Analytics

## CONTENT

- Articles
- Drafts
- Scheduled
- Categories
- Tags
- Topics
- Breaking News
- Live Blogs
- Series

## PEOPLE

- Authors
- Contributors
- Speakers
- Users

## MEDIA

- Images
- Videos
- Audio
- Documents
- Media Library

## RANKINGS

- Lists
- Rankings
- Companies
- People

## ENGAGEMENT

- Comments
- Polls
- Quizzes
- Bookmarks
- Notifications

## NEWSLETTER

- Subscribers
- Campaigns
- Templates
- Analytics

## MONETIZATION

- Advertisements
- Campaigns
- Sponsored Content
- Affiliate
- Subscriptions

## EVENTS

- Events
- Speakers
- Tickets
- Sponsors

## SEO

- SEO Dashboard
- Meta
- Sitemap
- Robots.txt
- Schema
- Redirects
- Canonicals
- Broken Links

## ANALYTICS

- Traffic
- Content
- Authors
- Revenue
- SEO

## SETTINGS

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

# 51. Recommended Implementation Roadmap

Do not attempt to build every advanced feature in one deployment.

## Phase 1 — Essential Publishing Platform

Implement:

- Professional homepage
- Categories
- Articles
- Authors
- Search
- Tags
- Media library
- Comments
- Newsletter
- SEO
- Sitemap
- Schema
- Analytics
- Advertising
- Responsive frontend
- Admin CMS
- Role permissions

## Phase 2 — Professional Media Platform

Implement:

- Contributor system
- Editorial workflow
- Scheduling
- Premium content
- User accounts
- Bookmarks
- Personalized feed
- Podcasts
- Videos
- Rankings
- Company profiles
- Topic pages
- Push notifications
- Advanced analytics

## Phase 3 — Large-Scale Media Platform

Implement:

- Advanced personalization
- AI-assisted editorial tools
- Automated recommendations
- Live blogs
- Interactive data
- Financial/company databases
- Events
- Membership
- Advanced subscriptions
- Digital magazine
- Mobile applications
- Games
- Enterprise advertising
- Brand Studio
- Data/licensing APIs

---

# 52. Technical Architecture Requirements

The implementation should be designed as a modular system.

## Principles

- Keep frontend and backend concerns separated where appropriate.
- Reuse existing database structures where possible.
- Do not break existing production functionality.
- Do not duplicate data unnecessarily.
- Use database migrations for schema changes.
- Preserve existing content and URLs unless a migration/redirect plan exists.
- Use background jobs for heavy tasks.
- Use caching for expensive queries.
- Use queues for email, notifications, media processing, and other asynchronous operations.
- Build reusable components rather than duplicating UI.
- Keep feature flags/configuration available for major optional modules.

## Backward Compatibility

Before modifying an existing system:

1. Inspect current database schema.
2. Inspect existing models/controllers/services.
3. Inspect current routes.
4. Inspect current frontend components.
5. Inspect existing authentication and authorization.
6. Inspect existing SEO implementation.
7. Inspect existing migrations.
8. Identify dependencies and integration points.
9. Back up data.
10. Implement changes incrementally.

Never replace an existing working module simply because a new architecture is preferred unless compatibility and migration have been addressed.

---

# 53. Database-Level Requirements

Create normalized, scalable structures for at least:

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
- analytics/events as appropriate
- audit_logs

Use foreign keys, indexes, unique constraints, and soft deletion where appropriate.

---

# 54. API Requirements

Where APIs are required, provide secure endpoints for:

- Authentication
- Articles
- Categories
- Tags
- Authors
- Search
- Media
- Comments
- Bookmarks
- User follows
- Notifications
- Newsletter
- Subscriptions
- Rankings
- Companies
- Events

Use authorization on every protected endpoint.

Do not expose admin functionality through public endpoints.

---

# 55. Editorial Quality Controls

Before publication, the system should check:

- Title exists
- H1 exists
- Content exists
- Featured image exists where required
- Alt text exists
- Author exists
- Category exists
- URL slug exists
- Meta title exists
- Meta description exists
- Canonical exists/configured
- Schema can be generated
- No broken internal links where detectable
- No duplicate slug
- Content status is valid

Provide warnings rather than silently blocking publication unless a field is genuinely mandatory.

---

# 56. SEO URL Structure

Use clean, permanent URLs.

Examples:

/business/
/technology/
/ai/
/authors/john-smith/
/company/openai/
/topics/artificial-intelligence/
/article/example-title/
/rankings/top-startups-2026/
/events/example-event/

Do not change existing URLs without a redirect/migration plan.

---

# 57. Mobile Experience

The website must be fully responsive.

Mobile should include:

- Sticky header
- Search
- Hamburger menu
- Optimized article typography
- Fast image loading
- Sticky share/save controls where appropriate
- Mobile ads
- Mobile newsletter signup
- Touch-friendly controls
- Accessible navigation

---

# 58. Content Discovery

Every article should connect users to additional content through:

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

The goal is to increase useful content discovery without creating misleading clickbait.

---

# 59. Editorial Transparency

Support:

- Published date
- Updated date
- Author attribution
- Contributor attribution
- Corrections
- Editorial notes
- Source/reference links
- Sponsored disclosure
- Affiliate disclosure

Provide a correction workflow so editors can record and display material corrections.

---

# 60. Final Implementation Rule

The platform should feel like a **professional digital media company**, not a generic WordPress-style blog.

The architecture should support the progression:

Blog
→ Digital Publication
→ Business Media Platform
→ Multi-author Media Network
→ Subscription & Advertising Business
→ Data/Ranking/Events Platform

Build the foundation correctly so future modules can be added without rewriting the entire system.

Before implementation, audit the existing application and identify:

1. What already exists.
2. What can be reused.
3. What must be modified.
4. What must be newly created.
5. What database migrations are required.
6. What existing URLs must remain unchanged.
7. What dependencies/integrations are required.
8. What risks could affect current production functionality.

Then implement incrementally, test each module, and preserve existing working functionality.
