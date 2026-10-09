# APEX MEDIA v2.4 — LIVE SERVER PRODUCTION VERIFICATION GATE

**Application:** APEX International Business & Leadership (APEX Media v2.4)  
**Document Purpose:** Post-Deployment Verification & Operational Gate Sign-Off  
**Current Gate Status:**  
```text
CODE READY FOR PRODUCTION DEPLOYMENT
PRODUCTION ENVIRONMENT VERIFICATION REQUIRED
```
*(This status CANNOT be transitioned to "APPROVED FOR PRODUCTION LAUNCH" until every item in this checklist is verified on the live target server with real evidence.)*

---

## 1. SEQUENTIAL DEPLOYMENT PROTOCOL

Execute steps in strict sequence on the production host:

```text
Step 1: Provision Host (Ubuntu 22.04/24.04, PHP 8.2+, Nginx, MySQL/Postgres, Supervisor, Node/npm)
Step 2: Configure Environment (.env based on .env.production.example with real secrets)
Step 3: Deploy Application Code & Assets (composer install --no-dev, npm ci, npm run build)
Step 4: Take Database Snapshot & Migrate (php artisan migrate --force; NEVER migrate:fresh)
Step 5: Link Storage & Configure Permissions (php artisan storage:link, chown www-data)
Step 6: Warm Caches (config:cache, route:cache, view:cache)
Step 7: Configure Nginx & SSL Certbot (/var/www/apex/public document root)
Step 8: Start & Verify Supervisor Queue Worker (supervisorctl status apex-worker)
Step 9: Configure System Crontab (schedule:run)
Step 10: Perform Transactional SMTP Test
Step 11: Execute Live Smoke Tests & Health Probes
```

---

## 2. PRODUCTION VERIFICATION CHECKLIST (LIVE SERVER SIGN-OFF)

All checkboxes below are initially unchecked `[ ]`. Each item must only be marked `[X]` after real command execution and confirmation on the live production server.

### Infrastructure & Server Foundation
- [ ] **Production server provisioned**  
  *Verification:* Linux OS verified (`uname -a`), 2+ CPU, 4GB+ RAM, firewall active (`ufw status` allowing 22, 80, 443).
- [ ] **Production database connected**  
  *Verification:* Database server running, database created with `utf8mb4_unicode_ci`, connection verified (`php artisan db:show`).
- [ ] **Migrations executed successfully**  
  *Verification:* Ran `php artisan migrate --force`. Verified 27 migrations active via `php artisan migrate:status`. Zero use of `migrate:fresh` or `db:wipe`.
- [ ] **APP_DEBUG=false verified**  
  *Verification:* Verified `APP_DEBUG=false` in `.env`. Confirm intentional 500 error page renders generic corporate error without stack trace.
- [ ] **Nginx verified**  
  *Verification:* Nginx configuration syntax valid (`sudo nginx -t`). Document root strictly set to `/var/www/apex/public`.
- [ ] **PHP-FPM verified**  
  *Verification:* Socket running at `/run/php/php8.2-fpm.sock`. Service active (`sudo systemctl status php8.2-fpm`).
- [ ] **HTTPS verified**  
  *Verification:* Valid TLS 1.2/1.3 certificate installed via Certbot with automated renewal (`certbot renew --dry-run`).
- [ ] **HTTP -> HTTPS redirect verified**  
  *Verification:* `curl -I http://YOUR_DOMAIN.com` returns `HTTP/1.1 301 Moved Permanently` to `https://YOUR_DOMAIN.com`.
- [ ] **Storage link verified**  
  *Verification:* `public/storage` symlink points to `storage/app/public` (`ls -la /var/www/apex/public/storage`).
- [ ] **File permissions verified**  
  *Verification:* Application source code is read-only to the web process (owned by deployer, 755 directories, 644 files); write permissions are strictly isolated to `storage/`, `bootstrap/cache/`, and `public/storage` for `www-data`. Direct web access to `.env`, `.git`, and application source directories is blocked with `HTTP 403/404`.

### Background Services, Workers & Messaging
- [ ] **Queue worker actually processing jobs**  
  *Verification:* Supervisor active (`sudo supervisorctl status apex-worker`). Dispatched test job processed and removed from `jobs` table without error.
- [ ] **Scheduler actually executing**  
  *Verification:* Cron entry confirmed in `crontab -l -u www-data`. Logs confirm `php artisan trending:recalculate` and `auth:clear-resets` execute on schedule.
- [ ] **SMTP actual delivery tested**  
  *Verification:* Single transactional test email dispatched via production mailer. Delivered successfully to recipient inbox. Verified SPF, DKIM, DMARC passing.
- [ ] **Database backup created**  
  *Verification:* Production dump executed (`mysqldump -u ... | gzip > /var/backups/apex_initial.sql.gz`). Backup file exists and is non-empty.
- [ ] **Backup restore tested**  
  *Verification:* Dump successfully imported and verified against an isolated staging/test database schema without errors.

### Public & Reader HTTP Smoke Tests
- [ ] **Homepage HTTP 200**  
  *Verification:* `curl -I https://YOUR_DOMAIN.com/` returns `HTTP 200 OK`. Layout, tickers, hero, and breaking news render.
- [ ] **Article HTTP 200**  
  *Verification:* `curl -I https://YOUR_DOMAIN.com/article/inside-the-next-frontier-autonomous-enterprise-ai` returns `HTTP 200 OK`.
- [ ] **Category HTTP 200**  
  *Verification:* `curl -I https://YOUR_DOMAIN.com/category/technology` returns `HTTP 200 OK`.
- [ ] **Rankings HTTP 200**  
  *Verification:* `curl -I https://YOUR_DOMAIN.com/rankings` returns `HTTP 200 OK`.
- [ ] **Search HTTP 200**  
  *Verification:* `curl -I https://YOUR_DOMAIN.com/search?q=markets` returns `HTTP 200 OK`. Autocomplete endpoint operational.
- [ ] **Sitemap verified**  
  *Verification:* `curl -I https://YOUR_DOMAIN.com/sitemap.xml` returns `HTTP 200 OK` (`text/xml`). URLs in sitemap use production `https://` domain.
- [ ] **Robots verified**  
  *Verification:* `curl -I https://YOUR_DOMAIN.com/robots.txt` returns `HTTP 200 OK`. Disallow rules on `/admin/` and `/dashboard/` present.
- [ ] **404 verified**  
  *Verification:* `curl -I https://YOUR_DOMAIN.com/non-existent-probe-slug` returns `HTTP 404 Not Found` with branded error layout.
- [ ] **Legacy redirect verified**  
  *Verification:* `curl -I https://YOUR_DOMAIN.com/legacy/tech-report-2025` returns `HTTP 301 Moved Permanently`.
- [ ] **Article images render**  
  *Verification:* Media uploaded via storage symlink load over HTTPS with valid `200 OK` image headers.
- [ ] **Mobile navigation verified**  
  *Verification:* Responsive drawer tested at 375px / 390px / 768px viewports. No horizontal overflow.
- [ ] **Newsletter subscription verified**  
  *Verification:* Real subscriber email added via footer terminal. Record written to `newsletter_subscribers` table.

### Security, Admin & Operations
- [ ] **Admin login verified**  
  *Verification:* Production editorial login gateway at `/admin/login` loads and authenticates admin credentials over HTTPS.
- [ ] **RBAC verified**  
  *Verification:* Contributor accounts cannot access `/admin` dashboard or publish articles directly. Unauthorized access returns `HTTP 403`.
- [ ] **Production error logs checked**  
  *Verification:* `storage/logs/laravel.log`, `/var/log/nginx/error.log`, and `/var/log/supervisor/apex-worker.log` show zero unhandled exceptions.
- [ ] **Final security headers checked**  
  *Verification:* `X-Frame-Options`, `X-Content-Type-Options`, `X-XSS-Protection`, `Referrer-Policy`, and `Strict-Transport-Security` headers confirmed via `curl -I`.
- [ ] **Production Core Web Vitals measured**  
  *Verification:* Audited on live URL using Google Lighthouse / PageSpeed Insights (LCP <= 2.5s, CLS <= 0.1, INP <= 200ms).
- [ ] **Final production gate report updated**  
  *Verification:* Once all items above are checked, record the sign-off engineer name, deployment timestamp, and update gate status.

---

## 3. FINAL GATE TRANSITION PROTOCOL

### Current Status (Pre-Deployment):
```text
CODE READY FOR PRODUCTION DEPLOYMENT
PRODUCTION ENVIRONMENT VERIFICATION REQUIRED
```

### Approved Launch Status (Post-Verification Only):
Only after **ALL 28 CHECKLIST ITEMS ABOVE** are marked as verified with concrete logs/responses, the deployment team may transition the gate status to:

```text
============================================================
APEX MEDIA v2.4 — LIVE PRODUCTION LAUNCH SIGN-OFF
============================================================
STATUS: APPROVED FOR PRODUCTION LAUNCH
DEPLOYMENT DATE: [Insert Timestamp YYYY-MM-DD HH:MM UTC]
PRODUCTION HOST: [Insert Host / Domain]
VERIFYING ENGINEER: [Insert DevOps / Tech Lead Name]
ALL 28 LIVE VERIFICATION GATES PASSED
============================================================
```
