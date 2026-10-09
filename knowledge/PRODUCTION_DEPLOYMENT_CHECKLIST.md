# APEX MEDIA v2.4 — PRODUCTION DEPLOYMENT CHECKLIST

**Application:** APEX International Business & Leadership (APEX Media v2.4)  
**Framework:** Laravel 12.69.3 / PHP 8.2.31 / Tailwind CSS v4 / Vite 7.3.6  
**Document Status:** Pre-Deployment Engineering Specification  
**Classification:** CODE READY FOR PRODUCTION DEPLOYMENT — PRODUCTION ENVIRONMENT VERIFICATION REQUIRED

---

## 1. SERVER REQUIREMENTS

- **Operating System:** Ubuntu 22.04 LTS / 24.04 LTS or Debian 12 (Bookworm) recommended.
- **Compute:** Minimum 2 vCPU cores, 4 GB RAM (8 GB recommended for concurrent traffic).
- **Disk:** 40 GB+ NVMe/SSD storage.
- **Firewall (UFW):**
  - Allow Port 22 (SSH)
  - Allow Port 80 (HTTP)
  - Allow Port 443 (HTTPS)
  - Deny all direct database and Redis ports from public ingress.

---

## 2. PHP REQUIREMENTS & RUNTIME CONFIGURATION

- **PHP Version:** PHP >= 8.2 (Tested on PHP 8.2.31).
- **Required Core & PECL Extensions:**
  - `pdo` and `pdo_mysql` (or `pdo_pgsql`)
  - `openssl`
  - `mbstring`
  - `tokenizer`
  - `xml` / `dom`
  - `ctype`
  - `json`
  - `bcmath`
  - `curl`
  - `fileinfo`
  - Optional for Redis: `php-redis`
- **Recommended `php.ini` Settings:**
  ```ini
  memory_limit = 256M
  upload_max_filesize = 15M
  post_max_size = 20M
  max_execution_time = 60
  expose_php = Off
  opcache.enable = 1
  opcache.memory_consumption = 128
  opcache.interned_strings_buffer = 16
  opcache.max_accelerated_files = 10000
  opcache.validate_timestamps = 0
  ```

---

## 3. DATABASE REQUIREMENTS & ENGINE TARGETS

- **Target Engine:** MySQL 8.0+ (InnoDB, `utf8mb4_unicode_ci`) or PostgreSQL 15+.
- **Database User Privileges:** Standard `SELECT`, `INSERT`, `UPDATE`, `DELETE`, `CREATE`, `ALTER`, `INDEX`, `DROP` on application database only.
- **Connection Configuration:** Set `strict => true` in `config/database.php`.
- **CRITICAL OPERATIONAL RULE:**
  - **NEVER RUN `php artisan migrate:fresh` on production.**
  - **NEVER RUN `php artisan db:wipe` on production.**
  - Only execute non-destructive migrations using `php artisan migrate --force`.

---

## 4. ENVIRONMENT (.ENV) CONFIGURATION

Deploy using `.env.production.example` as the canonical template:
```bash
cp .env.production.example /var/www/apex/.env
```
Ensure the following variables are strictly populated:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://YOUR_DOMAIN.com`
- Set application key:
  - **Initial Provisioning Only:** Generate key once:
    ```bash
    php artisan key:generate --force
    ```
  - **Existing / Upgrading Deployments:** PRESERVE the established production `APP_KEY`. Do not regenerate it, as doing so invalidates encrypted data, session payloads, and password reset tokens.
- Configure production database:
  - `DB_CONNECTION=mysql`
  - `DB_HOST=127.0.0.1` (or managed DB endpoint)
  - `DB_DATABASE=YOUR_DB`
  - `DB_USERNAME=YOUR_USER`
  - `DB_PASSWORD=YOUR_STRONG_PASSWORD`
- Configure cookie and session security:
  - `SESSION_DRIVER=database`
  - `SESSION_SECURE_COOKIE=true`
  - `SESSION_SAME_SITE=lax`

---

## 5. NGINX CONFIGURATION

Create `/etc/nginx/sites-available/apex.conf`:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name YOUR_DOMAIN.com www.YOUR_DOMAIN.com;
    return 301 https://YOUR_DOMAIN.com$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name YOUR_DOMAIN.com;

    # SSL Certificates (managed via Certbot)
    ssl_certificate /etc/letsencrypt/live/YOUR_DOMAIN.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/YOUR_DOMAIN.com/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;

    # Document Root — MUST point to public directory
    root /var/www/apex/public;
    index index.php index.html;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    # Content-Security-Policy tailored to APEX resources (Alpine.js, Tailwind, Bunny fonts, UI Avatars, and local media)
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.bunny.net; font-src 'self' https://fonts.bunny.net data:; img-src 'self' data: https: blob:; media-src 'self' https: blob:; connect-src 'self'; frame-ancestors 'none';" always;

    charset utf-8;

    # Gzip Compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny direct access to hidden files (.env, .git, etc.)
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static asset caching
    location ~* \.(jpg|jpeg|png|webp|gif|svg|ico|css|js|woff|woff2|ttf)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}
```

Enable site and verify:
```bash
sudo ln -s /etc/nginx/sites-available/apex.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 6. PHP-FPM CONFIGURATION

Inspect pool configuration at `/etc/php/8.2/fpm/pool.d/www.conf`:
```ini
[www]
user = www-data
group = www-data
listen = /run/php/php8.2-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = dynamic
pm.max_children = 25
pm.start_servers = 5
pm.min_spare_servers = 3
pm.max_spare_servers = 8
pm.max_requests = 500
```
Restart PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

---

## 7. SSL / HTTPS REQUIREMENTS

Generate SSL certificates via Certbot:
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d YOUR_DOMAIN.com -d www.YOUR_DOMAIN.com
```
Verify automated renewal:
```bash
sudo certbot renew --dry-run
```

---

## 8. STORAGE & PERMISSIONS CONFIGURATION

1. **Create Storage Symlink:**
   ```bash
   php artisan storage:link
   ```
2. **Least-Privilege File Ownership & Permissions:**
   - **Application Source Code (Restricted):**
     Keep the application source code owned by the deployment user (e.g., `deployer`) and read-only to the web process:
     ```bash
     sudo chown -R deployer:www-data /var/www/apex
     sudo find /var/www/apex -type d -exec chmod 755 {} +
     sudo find /var/www/apex -type f -exec chmod 644 {} +
     ```
   - **Minimum Required Writable Locations Only:**
     Isolate write permissions strictly to the directories requiring runtime writes (`storage/`, `bootstrap/cache/`, `public/storage`):
     ```bash
     sudo chown -R www-data:www-data /var/www/apex/storage /var/www/apex/bootstrap/cache
     sudo chmod -R 775 /var/www/apex/storage /var/www/apex/bootstrap/cache
     ```
3. **Upload Isolation:** Ensure the Nginx web server forbids execution of PHP scripts within `public/storage/` by disallowing `.php` files in storage locations.

---

## 9. QUEUE CONFIGURATION

The application utilizes database-driven background queues (`QUEUE_CONNECTION=database`).
- Tables required: `jobs`, `failed_jobs` (created via migrations `0001_01_01_000002_create_jobs_table.php`).
- Failed jobs inspection: `php artisan queue:failed`.
- Retry failed jobs: `php artisan queue:retry all`.

---

## 10. SUPERVISOR QUEUE WORKER CONFIGURATION

Create `/etc/supervisor/conf.d/apex-worker.conf`:
```ini
[program:apex-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/apex/artisan queue:work database --sleep=3 --tries=3 --max-time=3600 --timeout=90
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/apex-worker.log
stopwaitsecs=3600
```

Start and verify workers:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart apex-worker:*
sudo supervisorctl status
```

---

## 11. CRON & SCHEDULER CONFIGURATION

Add the standard Laravel scheduler entry to `www-data`'s crontab:
```bash
sudo crontab -u www-data -e
```
Add line:
```cron
* * * * * cd /var/www/apex && php artisan schedule:run >> /dev/null 2>&1
```

Verify defined scheduled tasks:
```bash
php artisan schedule:list
```
*Expected Tasks:*
- `php artisan trending:recalculate` (Runs hourly at :00)
- `php artisan auth:clear-resets` (Runs every 15 minutes)

---

## 12. SMTP & TRANSACTIONAL EMAIL CONFIGURATION

Configure production SMTP credentials in `/var/www/apex/.env`:
- `MAIL_MAILER=smtp`
- `MAIL_HOST=smtp.sendgrid.net` (or Amazon SES / Postmark)
- `MAIL_PORT=587`
- `MAIL_USERNAME=YOUR_SMTP_USER`
- `MAIL_PASSWORD=YOUR_SMTP_KEY`
- `MAIL_ENCRYPTION=tls`
- `MAIL_FROM_ADDRESS="editorial@YOUR_DOMAIN.com"`
- `MAIL_FROM_NAME="APEX International Business & Leadership"`

**DNS Requirements for Deliverability:**
- **SPF:** `v=spf1 include:sendgrid.net ~all`
- **DKIM:** TXT record matching DNS selector.
- **DMARC:** `v=DMARC1; p=quarantine; pct=100; rua=mailto:dmarc@YOUR_DOMAIN.com`

**Controlled Test Email:**
```bash
php artisan tinker --execute="Mail::raw('APEX Production Mailer Probe', fn(\$m) => \$m->to('devops@YOUR_DOMAIN.com')->subject('APEX Probe'));"
```

---

## 13. ASSET BUILD PROCEDURE

If building on the production server, ensure Node.js >= 18.x / 20.x LTS is installed (via NodeSource, not default Ubuntu repository):
```bash
# Optional: Install Node.js 20 LTS if building assets on-host
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Build production bundles
npm ci
npm run build
```
*(Alternatively, compile assets during CI/CD and deploy the prebuilt `public/build/` directory directly to avoid installing Node/npm on the production server).*

Verify generated manifest and compiled bundles:
```bash
ls -la public/build/manifest.json
ls -la public/build/assets/
```

---

## 14. DATABASE MIGRATION PROCEDURE & BACKUP INTEGRITY

Target Engine: **MySQL 8.0+** (InnoDB, `utf8mb4_unicode_ci`).

Execute non-destructive production migrations with verified pre-migration backup:
```bash
# 1. Create and verify pre-migration backup snapshot
sudo mkdir -p /var/backups && sudo chmod 700 /var/backups
mysqldump -u YOUR_USER -p YOUR_DB | gzip > /var/backups/apex_pre_migration_$(date +%F_%H%M%S).sql.gz

# Verify backup existence, size and gzip archive integrity
ls -lh /var/backups/apex_pre_migration_*.sql.gz
gzip -t /var/backups/apex_pre_migration_*.sql.gz

# 2. Run pending migrations safely
php artisan migrate --force

# 3. Verify status
php artisan migrate:status
```
**STRICT WARNING:** Do NOT run `migrate:fresh` or `db:wipe`.


---

## 15. PRODUCTION CACHING & OPTIMIZATION

Execute the following commands after deployment:
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 16. BACKUP PROCEDURE

- **Database Backup (Automated Nightly):**
  ```bash
  mysqldump -u YOUR_USER -p YOUR_DB | gzip > /var/backups/apex_db_$(date +\%F_\%H\%M\%S).sql.gz
  ```
- **Media Assets Backup:**
  Sync `storage/app/public` to an external S3 bucket via AWS CLI or rsync:
  ```bash
  aws s3 sync /var/www/apex/storage/app/public s3://apex-backups/media/
  ```
- **Forensic Status:**
  - Backup Strategy: **DOCUMENTED**
  - Automated Backup Execution: **NOT VERIFIED** (Requires production cron job)
  - Restore Drill Test: **NOT VERIFIED** (Must be validated on staging environment)

---

## 17. ROLLBACK & DEPLOYMENT ARCHITECTURE

Recommended atomic directory structure using symlinks:
```text
/var/www/apex/
├── current -> /var/www/apex/releases/20261001180000
├── shared/
│   ├── .env
│   └── storage/
└── releases/
    ├── 20261001170000 (previous)
    └── 20261001180000 (active)
```

**Rollback Procedure:**
```bash
# Switch symlink to previous release
ln -sfn /var/www/apex/releases/20261001170000 /var/www/apex/current
sudo systemctl reload php8.2-fpm
sudo supervisorctl restart apex-worker:*
```

---

## 18. SECURITY HARDENING CHECKLIST

- [ ] `APP_DEBUG=false` confirmed in production `.env`.
- [ ] Direct web access to `.env`, `.git`, and sensitive files blocked by Nginx.
- [ ] Document root points exclusively to `/var/www/apex/public`.
- [ ] HTTPS enforced with valid SSL and HSTS header.
- [ ] `SESSION_SECURE_COOKIE=true` and `SESSION_SAME_SITE=lax`.
- [ ] SVG uploads disabled in `AdminMediaController`.
- [ ] Filenames sanitized against directory traversal (`Str::slug()`).
- [ ] Rate limits active on `/login` and `/search`.
- [ ] **Standard:** Verified against audited attack surfaces.

---

## 19. FINAL PRODUCTION SMOKE TESTS (POST-DEPLOYMENT)

Execute HTTP probes against the live URL and confirm status codes:

| Endpoint | Expected Status | Purpose |
|:---|:---:|:---|
| `GET /` | `200 OK` | Public homepage & layout |
| `GET /article/{slug}` | `200 OK` | Long-form article page |
| `GET /category/{slug}` | `200 OK` | Taxonomic vertical |
| `GET /rankings` | `200 OK` | Lists & intelligence index |
| `GET /search?q=markets` | `200 OK` | Search engine & query pipeline |
| `GET /admin/login` | `200 OK` | Editorial authentication gateway |
| `GET /sitemap.xml` | `200 OK` | XML sitemap index |
| `GET /robots.txt` | `200 OK` | Crawler directives |
| `GET /legacy/tech-report-2025` | `301 Redirect` | SEO 301 redirection |
| `GET /non-existent-page-path` | `404 Not Found` | Error handler |

---

## 20. POST-DEPLOYMENT VERIFICATION

1. Verify Admin Dashboard health probes:
   - Database connection: Online
   - Cache storage: Operational
   - Storage disk: Writable
   - Queue jobs: 0 failed
2. Inspect log files for unhandled exceptions:
   ```bash
   tail -n 100 /var/www/apex/storage/logs/laravel.log
   tail -n 100 /var/log/nginx/error.log
   tail -n 100 /var/log/supervisor/apex-worker.log
   ```

---

## 21. TROUBLESHOOTING GUIDE

- **502 Bad Gateway:** Check if PHP-FPM is running (`sudo systemctl status php8.2-fpm`) and the socket path matches Nginx config.
- **403 Forbidden:** Check permissions on `/var/www/apex/public` and ensure `index.php` exists.
- **500 Internal Server Error:** Check `storage/logs/laravel.log` and ensure `storage/` and `bootstrap/cache/` are writable by `www-data`.
- **Assets 404 / Missing Styling:** Run `npm run build` and ensure `public/build/manifest.json` exists and is readable.
- **Queue Jobs Not Processing:** Check Supervisor status (`sudo supervisorctl status apex-worker`) and inspect `failed_jobs` table.
