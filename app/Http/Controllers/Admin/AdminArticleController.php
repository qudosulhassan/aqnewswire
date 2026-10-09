<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleRevision;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminArticleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Article::with(['author', 'category', 'tags']);

        // Contributor scoping if applicable
        if ($user && $user->role === 'contributor') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('author_id')) {
            $query->where('user_id', $request->author_id);
        }

        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }

        if ($request->filled('flag')) {
            switch ($request->flag) {
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'breaking':
                    $query->where('is_breaking', true);
                    break;
                case 'trending':
                    $query->where('is_trending', true);
                    break;
                case 'editors_pick':
                    $query->where('is_editors_pick', true);
                    break;
            }
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('subtitle', 'like', "%{$term}%")
                  ->orWhere('slug', 'like', "%{$term}%");
            });
        }

        // Stats for quick filter tabs
        $statsQuery = Article::query();
        if ($user && $user->role === 'contributor') {
            $statsQuery->where('user_id', $user->id);
        }
        $statusCounts = [
            'all' => (clone $statsQuery)->count(),
            'published' => (clone $statsQuery)->where('status', 'published')->count(),
            'draft' => (clone $statsQuery)->where('status', 'draft')->count(),
            'scheduled' => (clone $statsQuery)->where('status', 'scheduled')->count(),
            'submitted' => (clone $statsQuery)->where('status', 'submitted')->count(),
            'archived' => (clone $statsQuery)->where('status', 'archived')->count(),
        ];

        $articles = $query->latest('updated_at')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $authors = User::whereIn('role', ['admin', 'super_admin', 'editor', 'managing_editor', 'writer', 'contributor'])->orderBy('name')->get();

        return view('admin.articles.index', compact('articles', 'categories', 'tags', 'authors', 'statusCounts'));
    }

    public function create()
    {
        $user = Auth::user();
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        
        $authors = collect([$user]);
        if ($user && ($user->isAdmin() || $user->isEditor())) {
            $authors = User::whereIn('role', ['admin', 'super_admin', 'editor', 'managing_editor', 'writer', 'contributor'])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        $wpm = (int) (Setting::get('default_reading_words_per_minute', 220) ?: 220);
        $siteTimezone = Setting::get('site_timezone', config('app.timezone', 'UTC'));

        return view('admin.articles.create', compact('categories', 'tags', 'authors', 'wpm', 'siteTimezone'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $isPrivileged = $user && ($user->isAdmin() || $user->isEditor());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'featured_image_caption' => 'nullable|string|max:255',
            'featured_image_alt' => 'nullable|string|max:255',
            'status' => 'required|in:draft,submitted,published,scheduled,archived',
            'scheduled_at' => 'nullable|date',
            'published_at' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id',
            'is_breaking' => 'boolean',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'is_editors_pick' => 'boolean',
            'is_premium' => 'boolean',
            'sponsored_by' => 'nullable|string|max:255',
            'sponsor_url' => 'nullable|url|max:500',
            'affiliate_disclosure' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
            'robots' => 'nullable|string|max:100',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|string|max:500',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'new_tags' => 'nullable|string|max:500',
        ]);

        // Author assignment
        if ($isPrivileged && !empty($validated['user_id'])) {
            $authorId = (int) $validated['user_id'];
        } else {
            $authorId = Auth::id() ?? 1;
        }
        $validated['user_id'] = $authorId;

        // Role restriction for publication
        if (!$isPrivileged && in_array($validated['status'], ['published', 'scheduled'])) {
            $validated['status'] = 'submitted';
        }

        // Slug handling
        $slugBase = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        if (empty($slugBase)) {
            $slugBase = 'article-' . Str::random(6);
        }
        $slug = $slugBase;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = "{$slugBase}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        // Timestamps for status
        if ($validated['status'] === 'published') {
            $validated['published_at'] = !empty($validated['published_at']) ? Carbon::parse($validated['published_at']) : now();
            $validated['scheduled_at'] = null;
        } elseif ($validated['status'] === 'scheduled') {
            $validated['scheduled_at'] = !empty($validated['scheduled_at']) ? Carbon::parse($validated['scheduled_at']) : now()->addDay();
        } else {
            $validated['scheduled_at'] = null;
        }

        // Flags
        $validated['is_breaking'] = $request->boolean('is_breaking');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['is_editors_pick'] = $request->boolean('is_editors_pick');
        $validated['is_premium'] = $request->boolean('is_premium');

        unset($validated['tags'], $validated['new_tags']);

        $article = Article::create($validated);

        // Tags processing
        $tagIds = $request->input('tags', []);
        if ($request->filled('new_tags')) {
            $newTagNames = array_map('trim', explode(',', $request->input('new_tags')));
            foreach ($newTagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
        }
        if (!empty($tagIds)) {
            $article->tags()->sync(array_unique($tagIds));
        }

        // Create initial revision snapshot
        $article->revisions()->create([
            'user_id' => Auth::id(),
            'title' => $article->title,
            'subtitle' => $article->subtitle,
            'excerpt' => $article->excerpt,
            'content' => $article->content,
            'version' => 1,
            'change_summary' => 'Initial creation',
        ]);

        AuditLog::record('article_created', 'Article', $article->id, "Created article '{$article->title}' with status '{$article->status}'");

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Article created successfully!',
                'article_id' => $article->id,
                'edit_url' => route('admin.articles.edit', $article),
            ]);
        }

        if ($request->input('action') === 'continue_editing') {
            return redirect()->route('admin.articles.edit', $article)->with('success', 'Article created successfully! Continue editing.');
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully!');
    }

    public function show(Article $article)
    {
        return redirect()->route('admin.articles.edit', $article);
    }

    public function edit(Article $article)
    {
        $user = Auth::user();
        $isPrivileged = $user && ($user->isAdmin() || $user->isEditor());

        if (!$isPrivileged && $article->user_id !== $user->id) {
            abort(403, 'Unauthorized to edit this article.');
        }

        $article->load([
            'author',
            'category',
            'tags',
            'editorialNotes.user',
            'revisions' => function ($q) {
                $q->with('user')->latest()->take(25);
            },
        ]);

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        $authors = collect([$article->author ?: $user]);
        if ($isPrivileged) {
            $authors = User::whereIn('role', ['admin', 'super_admin', 'editor', 'managing_editor', 'writer', 'contributor'])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        $wpm = (int) (Setting::get('default_reading_words_per_minute', 220) ?: 220);
        $siteTimezone = Setting::get('site_timezone', config('app.timezone', 'UTC'));

        return view('admin.articles.edit', compact('article', 'categories', 'tags', 'authors', 'wpm', 'siteTimezone'));
    }

    public function update(Request $request, Article $article)
    {
        $user = Auth::user();
        $isPrivileged = $user && ($user->isAdmin() || $user->isEditor());

        if (!$isPrivileged && $article->user_id !== $user->id) {
            abort(403, 'Unauthorized to update this article.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'featured_image_caption' => 'nullable|string|max:255',
            'featured_image_alt' => 'nullable|string|max:255',
            'status' => 'required|in:draft,submitted,published,scheduled,archived',
            'scheduled_at' => 'nullable|date',
            'published_at' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id',
            'is_breaking' => 'boolean',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'is_editors_pick' => 'boolean',
            'is_premium' => 'boolean',
            'sponsored_by' => 'nullable|string|max:255',
            'sponsor_url' => 'nullable|url|max:500',
            'affiliate_disclosure' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
            'robots' => 'nullable|string|max:100',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|string|max:500',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'new_tags' => 'nullable|string|max:500',
            'change_summary' => 'nullable|string|max:255',
        ]);

        // Author assignment check
        if ($isPrivileged && !empty($validated['user_id'])) {
            $validated['user_id'] = (int) $validated['user_id'];
        } else {
            unset($validated['user_id']); // Contributor cannot change author
        }

        // Role restriction for publication
        if (!$isPrivileged && in_array($validated['status'], ['published', 'scheduled'])) {
            $validated['status'] = 'submitted';
        }

        // Slug uniqueness check
        $slug = Str::slug($validated['slug']);
        if (empty($slug)) {
            $slug = Str::slug($validated['title']);
        }
        $existing = Article::where('slug', $slug)->where('id', '!=', $article->id)->first();
        if ($existing) {
            $slug = "{$slug}-" . Str::random(4);
        }
        $validated['slug'] = $slug;

        // Timestamps handling
        if ($validated['status'] === 'published') {
            if (!$article->published_at || !empty($validated['published_at'])) {
                $validated['published_at'] = !empty($validated['published_at']) ? Carbon::parse($validated['published_at']) : now();
            }
            $validated['scheduled_at'] = null;
        } elseif ($validated['status'] === 'scheduled') {
            $validated['scheduled_at'] = !empty($validated['scheduled_at']) ? Carbon::parse($validated['scheduled_at']) : ($article->scheduled_at ?? now()->addDay());
        }

        // Boolean flags
        $validated['is_breaking'] = $request->boolean('is_breaking');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['is_editors_pick'] = $request->boolean('is_editors_pick');
        $validated['is_premium'] = $request->boolean('is_premium');

        // Check if content or title changed to create a revision snapshot
        $contentChanged = ($article->title !== $validated['title']) ||
                          ($article->content !== $validated['content']) ||
                          ($article->excerpt !== ($validated['excerpt'] ?? null));

        $changeSummary = $request->input('change_summary') ?: ($contentChanged ? 'Updated article content' : 'Updated article settings');

        unset($validated['tags'], $validated['new_tags'], $validated['change_summary']);

        $article->update($validated);

        // Tags update
        $tagIds = $request->input('tags', []);
        if ($request->filled('new_tags')) {
            $newTagNames = array_map('trim', explode(',', $request->input('new_tags')));
            foreach ($newTagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
        }
        $article->tags()->sync(array_unique($tagIds));

        // Create revision if content changed or user explicitly requested
        if ($contentChanged) {
            $nextVersion = ($article->revisions()->max('version') ?? 0) + 1;
            $article->revisions()->create([
                'user_id' => Auth::id(),
                'title' => $article->title,
                'subtitle' => $article->subtitle,
                'excerpt' => $article->excerpt,
                'content' => $article->content,
                'version' => $nextVersion,
                'change_summary' => $changeSummary,
            ]);

            // Prune old revisions beyond 30 to preserve DB hygiene
            $revisionIds = $article->revisions()->latest()->pluck('id');
            if ($revisionIds->count() > 30) {
                ArticleRevision::whereIn('id', $revisionIds->slice(30))->delete();
            }
        }

        AuditLog::record('article_updated', 'Article', $article->id, "Updated '{$article->title}' (status: {$article->status})");

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Article saved successfully!',
                'article' => [
                    'id' => $article->id,
                    'status' => $article->status,
                    'reading_time_minutes' => $article->reading_time_minutes,
                    'word_count' => str_word_count(strip_tags($article->content)),
                    'updated_at' => $article->updated_at->toIso8601String(),
                ],
            ]);
        }

        if ($request->input('action') === 'continue_editing') {
            return redirect()->route('admin.articles.edit', $article)->with('success', 'Article updated successfully!');
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully!');
    }

    public function autosave(Request $request, Article $article)
    {
        $user = Auth::user();
        $isPrivileged = $user && ($user->isAdmin() || $user->isEditor());

        if (!$isPrivileged && $article->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'featured_image' => 'nullable|string|max:500',
            'featured_image_caption' => 'nullable|string|max:255',
            'featured_image_alt' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
        ]);

        // Filter out nulls so we only save populated fields
        $fieldsToUpdate = array_filter($validated, fn($val) => !is_null($val));

        $significantChange = false;
        if (isset($fieldsToUpdate['content']) && $fieldsToUpdate['content'] !== $article->content) {
            $significantChange = true;
        }

        $article->update($fieldsToUpdate);

        if ($significantChange) {
            $nextVersion = ($article->revisions()->max('version') ?? 0) + 1;
            $article->revisions()->create([
                'user_id' => Auth::id(),
                'title' => $article->title,
                'subtitle' => $article->subtitle,
                'excerpt' => $article->excerpt,
                'content' => $article->content,
                'version' => $nextVersion,
                'change_summary' => 'Autosave snapshot',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Draft autosaved',
            'saved_at' => now()->format('h:i:s A'),
            'iso_time' => now()->toIso8601String(),
            'reading_time' => $article->reading_time_minutes,
            'word_count' => str_word_count(strip_tags($article->content ?? '')),
        ]);
    }

    public function preview(Article $article)
    {
        $user = Auth::user();
        $isPrivileged = $user && ($user->isAdmin() || $user->isEditor());

        if (!$isPrivileged && $article->user_id !== $user->id) {
            abort(403, 'Unauthorized to preview this article.');
        }

        $article->load(['author', 'category', 'tags']);

        return view('admin.articles.preview', compact('article'));
    }

    public function restoreRevision(Article $article, ArticleRevision $revision)
    {
        $user = Auth::user();
        $isPrivileged = $user && ($user->isAdmin() || $user->isEditor());

        if (!$isPrivileged && $article->user_id !== $user->id) {
            abort(403, 'Unauthorized to restore revisions for this article.');
        }

        abort_if($revision->article_id !== $article->id, 404, 'Revision does not belong to this article.');

        // Snapshot current state first
        $nextVersion = ($article->revisions()->max('version') ?? 0) + 1;
        $article->revisions()->create([
            'user_id' => Auth::id(),
            'title' => $article->title,
            'subtitle' => $article->subtitle,
            'excerpt' => $article->excerpt,
            'content' => $article->content,
            'version' => $nextVersion,
            'change_summary' => "Snapshot before restoring revision #{$revision->version}",
        ]);

        // Restore values
        $article->update([
            'title' => $revision->title,
            'subtitle' => $revision->subtitle,
            'excerpt' => $revision->excerpt,
            'content' => $revision->content,
        ]);

        AuditLog::record('article_revision_restored', 'Article', $article->id, "Restored revision #{$revision->version} from {$revision->created_at->toFormattedDateString()}");

        return redirect()->route('admin.articles.edit', $article)->with('success', "Restored revision #{$revision->version} successfully.");
    }

    public function destroy(Article $article)
    {
        $user = Auth::user();
        $isPrivileged = $user && ($user->isAdmin() || $user->isEditor());

        if (!$isPrivileged && $article->user_id !== $user->id) {
            abort(403, 'Unauthorized to delete this article.');
        }

        $title = $article->title;
        AuditLog::record('article_deleted', 'Article', $article->id, "Deleted article '{$title}'");
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', "Article '{$title}' removed successfully.");
    }

    public function toggleStatus(Article $article)
    {
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isEditor())) {
            abort(403, 'Only Editors and Administrators can toggle article publication status directly.');
        }

        $newStatus = $article->status === 'published' ? 'draft' : 'published';
        $article->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? ($article->published_at ?? now()) : $article->published_at,
        ]);

        AuditLog::record('article_status_toggled', 'Article', $article->id, "Status changed to {$newStatus}");

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'status' => $newStatus]);
        }
        return back()->with('success', "Article status switched to {$newStatus}.");
    }

    public function toggleFeatured(Article $article)
    {
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isEditor())) {
            abort(403, 'Unauthorized.');
        }

        $article->update(['is_featured' => !$article->is_featured]);
        AuditLog::record('article_flag_toggled', 'Article', $article->id, "Featured flag: " . ($article->is_featured ? 'true' : 'false'));

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'is_featured' => $article->is_featured]);
        }
        return back()->with('success', 'Featured editorial flag updated.');
    }

    public function toggleBreaking(Article $article)
    {
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isEditor())) {
            abort(403, 'Unauthorized.');
        }

        $article->update(['is_breaking' => !$article->is_breaking]);
        AuditLog::record('article_flag_toggled', 'Article', $article->id, "Breaking flag: " . ($article->is_breaking ? 'true' : 'false'));

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'is_breaking' => $article->is_breaking]);
        }
        return back()->with('success', 'Breaking news flag updated.');
    }
}
