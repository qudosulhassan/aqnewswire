<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\AuthController as ReaderAuthController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminContributorController;
use App\Http\Controllers\Admin\AdminEditorialNoteController;
use App\Http\Controllers\Admin\AdminMediaController;
use App\Http\Controllers\Admin\AdminNewsletterController;
use App\Http\Controllers\Admin\AdminPodcastController;
use App\Http\Controllers\Admin\AdminRankingsController;
use App\Http\Controllers\Admin\AdminSeoController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminVideoController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController as StaffAuthController;
use App\Http\Controllers\Admin\DashboardController as StaffDashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Contributor\ApplicationController as ContributorApplicationController;
use App\Http\Controllers\Contributor\ContributorDashboardController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\RankingsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Media Portal Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/rankings', [RankingsController::class, 'index'])->name('rankings.index');
Route::get('/rankings/{slug}', [RankingsController::class, 'show'])->name('rankings.show');
Route::get('/author/{slug}', [AuthorController::class, 'show'])->name('authors.show');
Route::get('/contact', function () { return view('pages.contact'); })->name('contact');
Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/preferences', [NewsletterController::class, 'preferences'])->name('newsletter.preferences');
Route::post('/newsletter/preferences', [NewsletterController::class, 'updatePreferences'])->name('newsletter.preferences.update');
Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::post('/push/subscribe', [PushSubscriptionController::class, 'subscribe'])->name('push.subscribe');
Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'unsubscribe'])->name('push.unsubscribe');

// Multimedia: Video & Podcasts
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/videos/{slug}', [VideoController::class, 'show'])->name('videos.show');
Route::get('/podcasts', [PodcastController::class, 'index'])->name('podcasts.index');
Route::get('/podcasts/{slug}', [PodcastController::class, 'show'])->name('podcasts.show');
Route::get('/podcasts/{podcastSlug}/{episodeSlug}', [PodcastController::class, 'episode'])->name('podcasts.episode');

// SEO, Sitemaps & Robots
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/sitemap-news.xml', [SeoController::class, 'newsSitemap'])->name('seo.news_sitemap');
Route::get('/sitemap-articles.xml', [SeoController::class, 'articlesSitemap'])->name('seo.articles_sitemap');
Route::get('/sitemap-categories.xml', [SeoController::class, 'categoriesSitemap'])->name('seo.categories_sitemap');
Route::get('/sitemap-rankings.xml', [SeoController::class, 'rankingsSitemap'])->name('seo.rankings_sitemap');
Route::get('/sitemap-authors.xml', [SeoController::class, 'authorsSitemap'])->name('seo.authors_sitemap');
Route::get('/sitemap-index.xml', [SeoController::class, 'sitemapIndex'])->name('seo.sitemap_index');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');

// Contributor Application
Route::get('/become-a-contributor', [ContributorApplicationController::class, 'show'])->name('contributor.apply');
Route::post('/become-a-contributor', [ContributorApplicationController::class, 'store'])->name('contributor.apply.store');

/*
|--------------------------------------------------------------------------
| Reader Authentication & Engagement Routes
|--------------------------------------------------------------------------
*/
Route::get('/register', [ReaderAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [ReaderAuthController::class, 'register'])->name('register.submit');
Route::get('/login', [ReaderAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [ReaderAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [ReaderAuthController::class, 'logout'])->name('logout');

// Interactive Engagement (Bookmarks, Follow, Comments)
Route::post('/bookmark/{article}', [BookmarkController::class, 'toggle'])->name('bookmark.toggle');
Route::post('/follow', [FollowController::class, 'toggle'])->name('follow.toggle');
Route::post('/article/{article}/comment', [CommentController::class, 'store'])->middleware('throttle:20,1')->name('comment.store');
Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
Route::post('/comment/{comment}/like', [CommentController::class, 'like'])->name('comment.like');
Route::post('/comment/{comment}/report', [CommentController::class, 'report'])->name('comment.report');

// Reader Account Dashboard (Protected)
Route::prefix('account')->name('account.')->middleware('auth')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('dashboard');
    Route::get('/saved', [AccountController::class, 'bookmarks'])->name('bookmarks');
    Route::get('/history', [AccountController::class, 'readingHistory'])->name('history');
    Route::post('/history/clear', [AccountController::class, 'clearReadingHistory'])->name('history.clear');
    Route::get('/following', [AccountController::class, 'following'])->name('following');
    Route::get('/notifications', [AccountController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{notification}/read', [AccountController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [AccountController::class, 'markAllNotificationsRead'])->name('notifications.mark-all-read');
    Route::get('/settings', [AccountController::class, 'settings'])->name('settings');
    Route::post('/settings', [AccountController::class, 'updateProfile'])->name('settings.update');
});

// Contributor Portal (Protected)
Route::prefix('contributor')->name('contributor.')->middleware(['auth', 'contributor.role'])->group(function () {
    Route::get('/', [ContributorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/articles/create', [ContributorDashboardController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ContributorDashboardController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ContributorDashboardController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ContributorDashboardController::class, 'update'])->name('articles.update');
});

/*
|--------------------------------------------------------------------------
| Editorial Staff CMS & Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [StaffAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StaffAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [StaffAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/', [StaffDashboardController::class, 'index'])->name('dashboard');

        // Operations & Maintenance Quick Actions
        Route::post('/quick-actions/clear-cache', [StaffDashboardController::class, 'clearCache'])->name('quick.clearCache');
        Route::post('/quick-actions/recalculate-trending', [StaffDashboardController::class, 'recalculateTrending'])->name('quick.recalculateTrending');

        // Articles & Internal Editorial Notes
        Route::get('/articles/{article}/preview', [AdminArticleController::class, 'preview'])->name('articles.preview');
        Route::post('/articles/{article}/autosave', [AdminArticleController::class, 'autosave'])->name('articles.autosave');
        Route::post('/articles/{article}/revisions/{revision}/restore', [AdminArticleController::class, 'restoreRevision'])->name('articles.revisions.restore');
        Route::resource('articles', AdminArticleController::class);
        Route::post('/articles/{article}/notes', [AdminEditorialNoteController::class, 'store'])->name('articles.notes.store');
        Route::post('/articles/{article}/notes/{note}/resolve', [AdminEditorialNoteController::class, 'toggleResolve'])->name('articles.notes.resolve');
        Route::delete('/articles/{article}/notes/{note}', [AdminEditorialNoteController::class, 'destroy'])->name('articles.notes.destroy');
        Route::post('/articles/{article}/toggle-status', [AdminArticleController::class, 'toggleStatus'])->name('articles.toggleStatus');
        Route::post('/articles/{article}/toggle-featured', [AdminArticleController::class, 'toggleFeatured'])->name('articles.toggleFeatured');
        Route::post('/articles/{article}/toggle-breaking', [AdminArticleController::class, 'toggleBreaking'])->name('articles.toggleBreaking');

        // Channels & Categories
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        // Lists & Rankings
        Route::get('/rankings', [AdminRankingsController::class, 'index'])->name('rankings.index');
        Route::get('/rankings/create', [AdminRankingsController::class, 'create'])->name('rankings.create');
        Route::post('/rankings', [AdminRankingsController::class, 'store'])->name('rankings.store');
        Route::get('/rankings/{ranking}/edit', [AdminRankingsController::class, 'edit'])->name('rankings.edit');
        Route::post('/rankings/{ranking}/items', [AdminRankingsController::class, 'storeItem'])->name('rankings.items.store');
        Route::delete('/rankings/items/{item}', [AdminRankingsController::class, 'destroyItem'])->name('rankings.items.destroy');

        // Contributors Management
        Route::get('/contributors', [AdminContributorController::class, 'index'])->name('contributors.index');
        Route::get('/contributors/export', [AdminContributorController::class, 'exportCsv'])->name('contributors.export');
        Route::get('/contributors/{application}', [AdminContributorController::class, 'show'])->name('contributors.show');
        Route::post('/contributors/{application}/approve', [AdminContributorController::class, 'approve'])->name('contributors.approve');
        Route::post('/contributors/{application}/reject', [AdminContributorController::class, 'reject'])->name('contributors.reject');
        Route::post('/contributors/{application}/review', [AdminContributorController::class, 'moveToReview'])->name('contributors.review');
        Route::post('/contributors/{application}/request-changes', [AdminContributorController::class, 'requestChanges'])->name('contributors.requestChanges');

        // Media Library
        Route::get('/media/picker', [AdminMediaController::class, 'pickerList'])->name('media.picker');
        Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
        Route::delete('/media/{medium}', [AdminMediaController::class, 'destroy'])->name('media.destroy');

        // Videos CMS
        Route::get('/videos', [AdminVideoController::class, 'index'])->name('videos.index');
        Route::get('/videos/create', [AdminVideoController::class, 'create'])->name('videos.create');
        Route::post('/videos', [AdminVideoController::class, 'store'])->name('videos.store');
        Route::delete('/videos/{video}', [AdminVideoController::class, 'destroy'])->name('videos.destroy');

        // Podcasts CMS
        Route::get('/podcasts', [AdminPodcastController::class, 'index'])->name('podcasts.index');
        Route::post('/podcasts', [AdminPodcastController::class, 'store'])->name('podcasts.store');
        Route::post('/podcasts/{podcast}/episodes', [AdminPodcastController::class, 'storeEpisode'])->name('podcasts.episodes.store');

        // Comments Moderation
        Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
        Route::post('/comments/{comment}/status', [AdminCommentController::class, 'updateStatus'])->name('comments.status');
        Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

        // SEO Architecture & Health (Restricted to Administrators)
        Route::middleware('admin.role')->group(function () {
            Route::get('/seo', [AdminSeoController::class, 'index'])->name('seo.index');
            Route::post('/seo/audit', [AdminSeoController::class, 'runAuditAction'])->name('seo.audit');
            Route::get('/seo/sitemap/validate', [AdminSeoController::class, 'validateSitemapAction'])->name('seo.sitemap.validate');
            Route::get('/seo/sitemap/download', [AdminSeoController::class, 'downloadSitemapAction'])->name('seo.sitemap.download');
            Route::get('/seo/sitemap/inventory', [AdminSeoController::class, 'sitemapInventory'])->name('seo.sitemap.inventory');
            Route::get('/seo/robots/validate', [AdminSeoController::class, 'validateRobotsAction'])->name('seo.robots.validate');
            Route::get('/seo/jsonld/{article?}', [AdminSeoController::class, 'viewJsonLdAction'])->name('seo.jsonld');
            Route::get('/seo/articles', [AdminSeoController::class, 'articles'])->name('seo.articles');
            Route::get('/seo/articles/{article}/edit', [AdminSeoController::class, 'editArticleSeo'])->name('seo.articles.edit');
            Route::put('/seo/articles/{article}', [AdminSeoController::class, 'updateArticleSeo'])->name('seo.articles.update');
            Route::get('/seo/redirects', [AdminSeoController::class, 'redirects'])->name('seo.redirects');
            Route::post('/seo/redirects', [AdminSeoController::class, 'storeRedirect'])->name('seo.redirects.store');
            Route::put('/seo/redirects/{redirect}', [AdminSeoController::class, 'updateRedirect'])->name('seo.redirects.update');
            Route::post('/seo/redirects/{redirect}/toggle', [AdminSeoController::class, 'toggleRedirect'])->name('seo.redirects.toggle');
            Route::post('/seo/redirects/test', [AdminSeoController::class, 'testRedirect'])->name('seo.redirects.test');
            Route::delete('/seo/redirects/{redirect}', [AdminSeoController::class, 'destroyRedirect'])->name('seo.redirects.destroy');
        });

        // Newsletters Audience Management & Campaigns
        Route::get('/newsletters', [AdminNewsletterController::class, 'index'])->name('newsletters.index');
        Route::get('/newsletters/export', [AdminNewsletterController::class, 'exportCsv'])->name('newsletters.export');
        Route::post('/newsletters/{subscriber}/toggle', [AdminNewsletterController::class, 'toggleStatus'])->name('newsletters.toggle');
        Route::delete('/newsletters/{subscriber}', [AdminNewsletterController::class, 'destroy'])->name('newsletters.destroy');
        Route::get('/newsletters/campaigns', [AdminNewsletterController::class, 'campaigns'])->name('newsletters.campaigns');
        Route::post('/newsletters/campaigns', [AdminNewsletterController::class, 'storeCampaign'])->name('newsletters.campaigns.store');
        Route::get('/newsletters/segments', [AdminNewsletterController::class, 'segments'])->name('newsletters.segments');
        Route::post('/newsletters/segments', [AdminNewsletterController::class, 'storeSegment'])->name('newsletters.segments.store');
        Route::get('/newsletters/templates', [AdminNewsletterController::class, 'templates'])->name('newsletters.templates');
        Route::post('/newsletters/templates', [AdminNewsletterController::class, 'storeTemplate'])->name('newsletters.templates.store');
        Route::get('/newsletters/analytics', [AdminNewsletterController::class, 'analytics'])->name('newsletters.analytics');
        Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');

        // Audit Trail Logs
        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit.index');

        // Users & Roles Administration (Strictly Restricted to Admin Roles)
        Route::middleware('admin.role')->group(function () {
            Route::post('/users/bulk', [AdminUserController::class, 'bulk'])->name('users.bulk');
            Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggleStatus');
            Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.resetPassword');
            Route::resource('users', AdminUserController::class);

            // General Settings Management (Strictly Restricted to Admin Roles)
            Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings');
            Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
            Route::get('/settings/export', [AdminSettingController::class, 'export'])->name('settings.export');
            Route::post('/settings/import', [AdminSettingController::class, 'import'])->name('settings.import');
            Route::post('/settings/reset', [AdminSettingController::class, 'resetGroup'])->name('settings.reset');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Public Editorial Dynamic Routing (Articles & Channels)
|--------------------------------------------------------------------------
|
| 1. /{category}/{slug} -> Article Detail (e.g. /economy/inside-the-next-frontier-...)
| 2. /{slug}            -> Category Channel (e.g. /economy, /markets, /tech)
|
| Note: Defined at the end of routes to prevent shadowing static/admin routes.
*/
Route::get('/{category}/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/{slug}', [CategoryController::class, 'show'])->name('categories.show');

