# Production Deployment Guide & Server Operations

This guide provides end-to-end instructions for deploying the APEX Media platform to a production Linux server (Ubuntu 22.04 LTS or 24.04 LTS recommended).

---

## 1. Production Server Prerequisites

| Component | Minimum Version | Recommended Version |
| :--- | :--- | :--- |
| **Operating System** | Ubuntu 22.04 LTS | Ubuntu 24.04 LTS |
| **Web Server** | Nginx 1.18+ | Nginx 1.24+ |
| **PHP Engine** | PHP 8.2 | PHP 8.2 or 8.3 |
| **PHP Extensions** | `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pcre`, `pdo_mysql` / `pdo_pgsql`, `tokenizer`, `xml` | Standard `php8.2-fpm` bundle |
| **Database** | MySQL 8.0+ or PostgreSQL 14+ | MySQL 8.0+ with InnoDB |
| **Process Manager** | Supervisor 4.2+ | `supervisor` |
| **Node.js** | Node 20.x LTS | Node 22.x LTS |
| **Composer** | Composer 2.7+ | Composer 2.x |

---

## 2. Server Configuration

### Document Root Directive
The web server document root **MUST** point strictly to the `public/` directory:
```
/var/www/apex/public
```
*Never expose the project root directory publicly.*

### Nginx Server Block (`/etc/nginx/sites-available/apex`)
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name apexmedia.com www.apexmedia.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name apexmedia.com www.apexmedia.com;

    root /var/www/apex/public;
    index index.php index.html;

    ssl_certificate /etc/letsencrypt/live/apexmedia.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/apexmedia.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.bunny.net; font-src 'self' https://fonts.bunny.net data:; img-src 'self' data: https: blob:; media-src 'self' https: blob:; connect-src 'self'; frame-ancestors 'none';" always;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to hidden files (.env, .git)
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 3. Production Environment File (`.env`)

```ini
APP_NAME="APEX Media"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://apexmedia.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apex_production
DB_USERNAME=apex_user
DB_PASSWORD=YOUR_STRONG_DATABASE_PASSWORD

BROADCAST_DRIVER=log
CACHE_STORE=database
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Production Mailer (Mailgun / SES / Postmark / SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@apexmedia.com
MAIL_PASSWORD=YOUR_SMTP_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="editorial@apexmedia.com"
MAIL_FROM_NAME="APEX Executive Briefings"
```

---

## 4. Background Workers & Queue Management (Supervisor)

Create `/etc/supervisor/conf.d/apex-worker.conf`:

```ini
[program:apex-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/apex/artisan queue:work database --sleep=3 --tries=3 --max-time=3600 --timeout=90
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/apex-worker.log
stopwaitsecs=3600
```

Apply and start Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start apex-worker:*
```

---

## 5. Scheduled Tasks & Crontab

Add the Laravel scheduler to the web server crontab:
```bash
sudo crontab -u www-data -e
```

Insert the cron entry:
```cron
* * * * * cd /var/www/apex && php artisan schedule:run >> /dev/null 2>&1
```

This runs:
- `php artisan trending:recalculate` (hourly)
- `php artisan auth:clear-resets` (every 15 minutes)

---

## 6. Deployment Procedure (Step-by-Step)

```bash
# 1. Clone repository
cd /var/www
git clone <repo-url> apex
cd /var/www/apex

# 2. Install PHP Composer dependencies (no dev packages, optimized autoloader)
composer install --no-dev --optimize-autoloader

# 3. Install Node packages and compile frontend production bundle
npm ci
npm run build

# 4. Set directory permissions (least privilege: source code read-only, storage writable)
sudo chown -R deployer:www-data /var/www/apex
sudo find /var/www/apex -type d -exec chmod 755 {} +
sudo find /var/www/apex -type f -exec chmod 644 {} +
sudo chown -R www-data:www-data /var/www/apex/storage /var/www/apex/bootstrap/cache
sudo chmod -R 775 /var/www/apex/storage /var/www/apex/bootstrap/cache

# 5. Create storage symlink
php artisan storage:link

# 6. Run database migrations safely (do NOT use migrate:fresh)
php artisan migrate --force

# 7. Optimize and cache routes, views, configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Restart background queue workers
sudo supervisorctl restart apex-worker:*
sudo systemctl reload nginx
```

---

## 7. Backup & Rollback Playbook

### Automated Daily Database Backup
```bash
# Add to crontab: daily at 2:00 AM
0 2 * * * mysqldump -u apex_user -p'YOUR_PASSWORD' apex_production | gzip > /backups/apex_db_$(date +\%F).sql.gz
```

### Application Rollback
In case of a breaking release:
1. Revert Git release tag: `git checkout <previous-tag>`
2. Re-run `composer install --no-dev --optimize-autoloader`
3. Re-run `npm run build`
4. Run `php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache`
5. Restart workers: `sudo supervisorctl restart apex-worker:*`
