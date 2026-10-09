# APEX Media v2.4 — Knowledge Base & Documentation Hub

Welcome to the central knowledge repository for **APEX Media v2.4 (International Business & Leadership)**. This folder contains complete, verified technical documentation, architecture blueprints, database schemas, module guides, design specifications, and production deployment manuals for the entire platform.

---

## 📚 Table of Contents

| Document | Description |
| :--- | :--- |
| **[1. System Architecture (`ARCHITECTURE.md`)](./ARCHITECTURE.md)** | Technical stack, directory layout, MVC patterns, service layer, RBAC policies, and middleware pipeline. |
| **[2. Database Schema (`DATABASE_SCHEMA.md`)](./DATABASE_SCHEMA.md)** | Complete breakdown of all 27 migrations, 20 Eloquent models, relationships, indexes, seed data, and cross-driver portability. |
| **[3. Features & Modules (`FEATURES_AND_MODULES.md`)](./FEATURES_AND_MODULES.md)** | Detailed documentation of the Stripe-inspired public homepage, Editorial CMS, Reader platform, Multimedia, SEO, Search, Trending & Recommendation engines. |
| **[4. Design System & UI/UX (`DESIGN_SYSTEM_AND_UI.md`)](./DESIGN_SYSTEM_AND_UI.md)** | Visual direction, color palettes, typography, motion & animation system (`@keyframes marquee`, gradient mesh), and UI component specs. |
| **[5. Production Deployment Guide (`PRODUCTION_DEPLOYMENT_GUIDE.md`)](./PRODUCTION_DEPLOYMENT_GUIDE.md)** | Step-by-step server setup, Nginx configuration, Supervisor daemon for queues, Crontab scheduler, caching, and rollback procedures. |
| **[6. Production Deployment Checklist (`PRODUCTION_DEPLOYMENT_CHECKLIST.md`)](./PRODUCTION_DEPLOYMENT_CHECKLIST.md)** | Comprehensive 21-section pre-deployment and operational checklist for DevOps engineers. |
| **[7. Live Server Verification Gate (`PRODUCTION_LIVE_VERIFICATION_GATE.md`)](./PRODUCTION_LIVE_VERIFICATION_GATE.md)** | Actionable 28-point live server checklist required to sign off on "APPROVED FOR PRODUCTION LAUNCH". |
| **[8. Final Production Gate Report (`FINAL_PRODUCTION_GATE_REPORT.md`)](./FINAL_PRODUCTION_GATE_REPORT.md)** | Final forensic audit gate decision, 30-area scorecard, and operational sign-off. |
| **[9. Testing & Forensic Audit Report (`TESTING_AND_AUDIT_REPORT.md`)](./TESTING_AND_AUDIT_REPORT.md)** | Complete automated test suite results (35 tests, 145 assertions), forensic audit discoveries, resolved blockers, and 30-area scorecard. |
| **[10. Nginx Server Template (`nginx-template.conf`)](./nginx-template.conf)** | Production-ready Nginx server block configuration file for `/etc/nginx/sites-available/apex.conf`. |
| **[11. Supervisor Worker Template (`supervisor-template.conf`)](./supervisor-template.conf)** | Production-ready Supervisor configuration file for `/etc/supervisor/conf.d/apex-worker.conf`. |
| **[12. Users & Roles Management Module (`USERS_AND_ROLES_MODULE.md`)](./USERS_AND_ROLES_MODULE.md)** | Complete IAM, role-based access control (RBAC), user lifecycle, status controls, and security documentation. |
| **[13. SEO Architecture & Discovery Center (`SEO_ARCHITECTURE_AND_DISCOVERY.md`)](./SEO_ARCHITECTURE_AND_DISCOVERY.md)** | Live SEO audit engine, dynamic discovery feeds (sitemaps, robots.txt), Schema.org JSON-LD, Open Graph, and 301/302 redirect cycle-safe manager. |
| **[14. General Settings Module (`GENERAL_SETTINGS_MODULE.md`)](./GENERAL_SETTINGS_MODULE.md)** | Database-backed application settings center, caching layer, configuration groups, safe telemetry diagnostics, and audit logs. |
| **[15. Article Editor 2.0 Module (`ARTICLE_EDITOR_MODULE.md`)](./ARTICLE_EDITOR_MODULE.md)** | Professional editorial workspace, live debounced autosave, WYSIWYG editor with raw HTML toggle, responsive multi-device preview, revision tracking, editorial feedback, and media library integration. |

---

## ⚡ Quick Platform Summary

- **Platform Name:** APEX International Business & Leadership (APEX Media v2.4)
- **Framework & Language:** Laravel 12.69.3, PHP 8.2.31
- **Styling & Frontend:** Tailwind CSS v4, Vite 7.3.6, Alpine.js, Vanilla JS (Zero-dependency components)
- **Test Suite Status:** 107 Tests, 469 Assertions, 100% Passing (0 failures, 0 errors)
- **Asset Build:** Vite compile time verified, `121.43 kB CSS`, `51.52 kB JS`, 0 build warnings
- **Database Status:** 31 Migrations executed, 0 data loss, full SQLite / MySQL / PostgreSQL cross-compatibility
- **Certified Deployment Status:** `DEPLOYMENT READY WITH DOCUMENTED WARNINGS — NO P0 CODE BLOCKERS`

---

## 🛠️ Essential Commands

```powershell
# Run the local development web server
php artisan serve --port=8000

# Run the complete automated test suite
php artisan test

# Compile production frontend assets
npm run build

# Start the Vite development hot-reload server
npm run dev

# Run scheduled background tasks (recaclulate trending, prune resets)
php artisan schedule:run

# Process background queue jobs
php artisan queue:work --sleep=3 --tries=3

# Recalculate engagement gravity trending scores manually
php artisan trending:recalculate

# Clear and rebuild application caches
php artisan optimize:clear
php artisan route:cache
php artisan view:cache
```

---

*Documentation compiled and verified on October 1, 2026 for APEX Media Group.*
