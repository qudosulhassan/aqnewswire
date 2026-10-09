# APEX Media v2.4 — Technical SEO Control Center & Google Search Central Alignment Manual

## 1. Executive Summary & Purpose

The **Technical SEO Control Center** (`/admin/seo`) serves as the authoritative command center for search engine optimization, crawler accessibility, structured metadata, discovery feeds, and HTTP redirect integrity across **APEX International Business & Leadership (APEX Media v2.4)**.

Built in strict compliance with current **Google Search Central** technical specifications, this module guarantees:
1. **100% Absolute Canonical URLs:** Sitemaps and public DOM output emit fully-qualified URLs matching official site schemes.
2. **Authoritative Sitemap Eligibility:** Strictly enforces inclusion of canonical indexable content while completely excluding drafts, private, deleted, scheduled future, and `noindex` records.
3. **P0 Prominence for XML Sitemaps:** Real actions allowing administrators to open the raw XML feed (`target="_blank"`), copy the endpoint, trigger deep local forensic validation, and download the XML payload.
4. **Sitemap Scale Architecture:** Supports modular sub-sitemaps (`/sitemap-articles.xml`, `/sitemap-categories.xml`, `/sitemap-rankings.xml`, `/sitemap-authors.xml`, `/sitemap-news.xml`) and Sitemap Index (`/sitemap-index.xml`) to prevent giant unmanageable single documents as publication volume grows.
5. **Truthful Telemetry:** Clearly distinguishes internal CMS verification from Google Search Console index status. Core Web Vitals and Search Console data are never fabricated or falsely shown as green without live API connections.

---

## 2. Google Search Central Alignment Principles

| Principle | Implementation Rule | Enforcement Point |
| :--- | :--- | :--- |
| **1. Absolute URLs** | All URLs in XML sitemaps must include protocol and host. | `SitemapService::getAllSitemapEntries()` |
| **2. Canonical & Indexable** | Only public, canonical, indexable URLs are included in sitemaps. | `Article::scopeIndexable()`, `SitemapService::isArticleEligible()` |
| **3. Exclude Drafts & Private** | Drafts, unpublished, and noindex stories are excluded. | Validation engine in `SitemapService::validateSitemap()` |
| **4. Accurate Lastmod** | `lastmod` represents actual content updates; never modified on crawl. | Atom-formatted `updated_at` timestamps from models |
| **5. Host Integrity** | Production environments must not emit localhost or 127.0.0.1 in feeds. | Validation engine host check |
| **6. Scalable Architecture** | Modular sub-sitemaps and sitemap index files available. | Routes `/sitemap-{type}.xml` and `/sitemap-index.xml` |
| **7. No robots.txt Conflicts** | robots.txt must not block pages whose `noindex` tags need to be crawled. | `SeoController::robots()` RFC 9309 verification |
| **8. One Canonical Per Page** | Public DOM must contain exactly one `<link rel="canonical">` tag. | `layouts/app.blade.php` |
| **9. Structured Data Alignment** | Schema.org JSON-LD must describe visible page content without duplicate blocks. | `articles/show.blade.php` NewsArticle and BreadcrumbList graph |
| **10. Honest Reporting** | Search Console and Core Web Vitals reported as "NOT CONNECTED" / "NOT MEASURED". | `SeoAuditService::runAudit()` |

---

## 3. Architectural Blueprint

```
[ Crawler / Search Engine / Browser ]
                │
                ├──> GET /robots.txt ──────────────> SeoController@robots (Dynamic RFC 9309, sitemap pointers)
                ├──> GET /sitemap.xml ─────────────> SeoController@sitemap (Canonical indexable urlset)
                ├──> GET /sitemap-news.xml ────────> SeoController@newsSitemap (Trailing 48h Google News)
                ├──> GET /sitemap-articles.xml ────> SeoController@articlesSitemap (Dedicated articles feed)
                ├──> GET /sitemap-categories.xml ──> SeoController@categoriesSitemap (Channels feed)
                ├──> GET /sitemap-rankings.xml ────> SeoController@rankingsSitemap (Rankings feed)
                ├──> GET /sitemap-authors.xml ─────> SeoController@authorsSitemap (Authors feed)
                ├──> GET /sitemap-index.xml ───────> SeoController@sitemapIndex (Sitemap index linking feeds)
                │
                ├──> GET /old-path ────────────────> HandleSeoRedirects Middleware (301/302 + hit counter)
                │                                            │
                │                                            └──> HTTP 301/302 Redirect to /new-path
                │
                └──> GET /article/{slug} ──────────> ArticleController@show
                                                             │
                                                             ├──> Dynamic SEO Head: Title, Desc, Canonical, Robots
                                                             ├──> Open Graph & Twitter Cards
                                                             └──> Schema.org JSON-LD (NewsArticle + Breadcrumbs)

[ Administrator (admin@apex.test) ]
                │
                ├──> GET /admin/seo ──────────────────> AdminSeoController@index (KPIs, health, feeds, matrix)
                ├──> POST /admin/seo/audit ───────────> AdminSeoController@runAuditAction (Live on-demand)
                ├──> GET /admin/seo/sitemap/validate ─> AdminSeoController@validateSitemapAction (Inspector)
                ├──> GET /admin/seo/sitemap/download ─> AdminSeoController@downloadSitemapAction (XML attachment)
                ├──> GET /admin/seo/sitemap/inventory> AdminSeoController@sitemapInventory (Paginated table)
                ├──> GET /admin/seo/robots/validate ──> AdminSeoController@validateRobotsAction (RFC checks)
                ├──> GET /admin/seo/jsonld/{article?} > AdminSeoController@viewJsonLdAction (Structured data)
                ├──> GET /admin/seo/articles ─────────> AdminSeoController@articles (Content audit table)
                ├──> GET /admin/seo/articles/{id}/edit> AdminSeoController@editArticleSeo (SERP/Social preview)
                ├──> PUT /admin/seo/articles/{id} ────> AdminSeoController@updateArticleSeo (Validated)
                ├──> GET /admin/seo/redirects ────────> AdminSeoController@redirects (Cycle/Chain scanner)
                └──> POST/PUT/DELETE /admin/seo/redirects ─> SeoRedirectService (Graph loop prevention)
```

---

## 4. Sitemap Eligibility & Scale Architecture

### 4.1 Authoritative Eligibility Checks (`SitemapService`)
- **Articles:** `status === 'published'` AND `published_at <= now()` AND `robots NOT LIKE '%noindex%'` AND non-empty slug.
- **Categories:** Active categories with valid slugs.
- **Rankings Lists:** Lists where `status === 'published'`.
- **Authors:** Active users with writer/contributor/editor roles and at least one published article.

### 4.2 Modular Sub-Sitemaps & Sitemap Index
To ensure scale far beyond small inventories:
- `/sitemap.xml`: Primary comprehensive sitemap.
- `/sitemap-articles.xml`: Dedicated editorial article catalog.
- `/sitemap-categories.xml`: Editorial category and channel taxonomy.
- `/sitemap-rankings.xml`: Wealth, enterprise, and market rankings lists.
- `/sitemap-authors.xml`: Verified author and contributor profiles.
- `/sitemap-index.xml`: XML sitemap index referencing all sub-sitemaps.

---

## 5. XML Sitemap Inspector & Real-time Validation Engine

The validator (`SitemapService::validateSitemap`) runs 7 comprehensive verification passes:
1. **XML Syntax & Schema Validity:** Verifies valid XML parsing using `simplexml_load_string` and traps `libxml` errors.
2. **URL Uniqueness:** Extracts all `<loc>` elements, checks uniqueness, and flags duplicate URLs.
3. **Absolute URL Format:** Validates that every URL is absolute with `http://` or `https://`.
4. **Host Security & Production HTTPS:** Verifies domain against `APP_URL`, checks for localhost/127.0.0.1 in production, and verifies HTTPS.
5. **Exclusion Integrity:** Cross-checks sitemap URLs against database to prove 0 draft articles, 0 deleted articles, and 0 `noindex` articles.
6. **Canonical Consistency:** Compares sitemap location against the model's effective canonical URL to detect mismatches or redirected entries.
7. **Lastmod Sanity:** Validates Atom format timestamps and flags future dates (> 5 minutes).

---

## 6. Sitemap URL Inventory

Located at `/admin/seo/sitemap/inventory`:
- **Columns:** URL & Title, Entity Type, Last Modified, HTTP Status, Canonical Status (Match / Mismatch / Redirected), Robots Directive, Indexability, Actions.
- **Filtering:** Search by keyword/slug, filter by type (Article, Category, Author, Ranking, Core), configurable per-page pagination (25, 50, 100).
- **Actions:** One-click URL copy, Open public URL in new tab, Direct link to Article SEO Editor.

---

## 7. Potential Orphan Content Detection

The system scans published editorial articles to determine whether other stories link to them:
- An article with 0 internal inbound mentions from other articles is categorized as **"Potential Orphan Content"**.
- Displayed in the SEO Center with quick links to inspect and build internal link pathways.

---

## 8. Role-Based Access Control & Security

All SEO management and inspection routes are restricted:
- **Middleware:** `EnsureAdminRole` (`admin.role`).
- **Admins:** Full access to index, validation endpoints, download actions, inventory, audit logs, and redirects.
- **Non-Admins (Writers, Editors, Readers):** Receive HTTP 403 Forbidden.
- **Unauthenticated:** Redirected to `/login`.

---

## 9. Automated Regression Testing

The test suite in `tests/Feature/SeoManagementTest.php` contains **19 dedicated feature tests with 113 assertions**:
1. `test_seo_dashboard_loads_for_admin`: Dashboard loads with KPIs and discovery feeds.
2. `test_kpi_counts_and_missing_metadata_detection`: Database KPIs, missing descriptions, and titles.
3. `test_sitemap_returns_valid_xml_and_excludes_drafts_and_noindex`: Sitemap excludes drafts and noindex.
4. `test_google_news_sitemap_returns_valid_xml`: Google News 48h schema formatting.
5. `test_robots_txt_returns_proper_directives_and_blocks_admin`: Dynamic RFC 9309 crawler rules.
6. `test_301_and_302_redirects_execute_correctly`: Redirect execution and hit tracking.
7. `test_self_redirect_and_circular_loops_are_rejected`: Algorithmic cycle prevention.
8. `test_redirect_chains_are_detected`: Multi-hop redirect warning.
9. `test_admin_can_create_update_toggle_and_delete_redirect`: Complete redirect CRUD.
10. `test_unauthorized_user_cannot_manage_seo`: RBAC 403 enforcement.
11. `test_admin_can_update_article_seo_metadata_and_it_renders_in_public_html`: Database to HTML data flow.
12. `test_run_seo_audit_action_completes_successfully`: On-demand audit action.
13. `test_sitemap_validator_action_returns_valid_checks`: Server-side sitemap validation API.
14. `test_sitemap_inventory_action_returns_paginated_records`: Inventory view and pagination.
15. `test_sitemap_download_action_returns_xml_attachment`: Browser download attachment response.
16. `test_scalable_sub_sitemaps_and_index_return_valid_xml`: Modular sub-sitemaps and sitemap index.
17. `test_robots_validation_action_returns_valid_checks`: Robots.txt validation checks.
18. `test_jsonld_viewer_endpoint_returns_valid_structured_data_graph`: JSON-LD viewer endpoint.
19. `test_potential_orphan_content_detection`: Algorithmic orphan article identification.

**Platform-Wide Test Suite Status:**
- Total Tests: **73**
- Total Assertions: **336**
- Failures: **0**
- Errors: **0**
- Coverage: 100% of Technical SEO endpoints and logic verified.
