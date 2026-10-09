<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'subtitle',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_caption',
        'featured_image_alt',
        'status',
        'published_at',
        'scheduled_at',
        'reading_time_minutes',
        'view_count',
        'is_breaking',
        'is_featured',
        'is_trending',
        'is_editors_pick',
        'is_premium',
        'sponsored_by',
        'sponsor_url',
        'affiliate_disclosure',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'trending_score',
        'audio_url',
        'audio_duration',
        'audio_generated_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'is_breaking' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_editors_pick' => 'boolean',
        'is_premium' => 'boolean',
        'reading_time_minutes' => 'integer',
        'view_count' => 'integer',
    ];

    public static function booted(): void
    {
        static::saving(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title) . '-' . Str::random(5);
            }
            if ($article->content) {
                // calculate reading time dynamically based on configured words per minute setting
                $wordCount = str_word_count(strip_tags($article->content));
                $wpm = (int) (\App\Models\Setting::get('default_reading_words_per_minute', 220) ?: 220);
                $article->reading_time_minutes = max(1, (int) ceil($wordCount / max(50, $wpm)));
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->where('status', 'approved')->with('replies.user');
    }

    public function editorialNotes(): HasMany
    {
        return $this->hasMany(EditorialNote::class)->latest();
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(ArticleRevision::class)->latest();
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeBreaking(Builder $query): Builder
    {
        return $query->where('is_breaking', true);
    }

    public function scopeTrending(Builder $query): Builder
    {
        return $query->where('is_trending', true);
    }

    public function scopeEditorsPick(Builder $query): Builder
    {
        return $query->where('is_editors_pick', true);
    }

    public function scopeIndexable(Builder $query): Builder
    {
        return $query->published()->where(function ($q) {
            $q->whereNull('robots')->orWhere('robots', 'not like', '%noindex%');
        });
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            return str_starts_with($this->featured_image, 'http') ? $this->featured_image : asset('storage/' . $this->featured_image);
        }
        return 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1200&q=80';
    }

    public function getEffectiveMetaTitleAttribute(): string
    {
        if (!empty(trim($this->meta_title ?? ''))) {
            return $this->meta_title;
        }
        return $this->title . ' — AQ NEWSWIRE';
    }

    public function getEffectiveMetaDescriptionAttribute(): string
    {
        if (!empty(trim($this->meta_description ?? ''))) {
            return $this->meta_description;
        }
        if (!empty(trim($this->excerpt ?? ''))) {
            return $this->excerpt;
        }
        return Str::limit(strip_tags($this->content ?? ''), 160);
    }

    public function getUrlAttribute(): string
    {
        $catSlug = $this->category?->slug ?? 'news';
        return route('articles.show', ['category' => $catSlug, 'slug' => $this->slug]);
    }

    public function getEffectiveCanonicalUrlAttribute(): string
    {
        if (!empty(trim($this->canonical_url ?? ''))) {
            return $this->canonical_url;
        }
        return $this->url;
    }

    public function getEffectiveRobotsAttribute(): string
    {
        return !empty(trim($this->robots ?? '')) ? $this->robots : 'index, follow, max-image-preview:large';
    }

    public function getEffectiveOgTitleAttribute(): string
    {
        return !empty(trim($this->og_title ?? '')) ? $this->og_title : $this->effective_meta_title;
    }

    public function getEffectiveOgDescriptionAttribute(): string
    {
        return !empty(trim($this->og_description ?? '')) ? $this->og_description : $this->effective_meta_description;
    }

    public function getEffectiveOgImageAttribute(): string
    {
        if (!empty(trim($this->og_image ?? ''))) {
            return str_starts_with($this->og_image, 'http') ? $this->og_image : asset('storage/' . $this->og_image);
        }
        return $this->image_url;
    }

    public function getEffectiveTwitterTitleAttribute(): string
    {
        return !empty(trim($this->twitter_title ?? '')) ? $this->twitter_title : $this->effective_og_title;
    }

    public function getEffectiveTwitterDescriptionAttribute(): string
    {
        return !empty(trim($this->twitter_description ?? '')) ? $this->twitter_description : $this->effective_og_description;
    }

    public function getEffectiveTwitterImageAttribute(): string
    {
        if (!empty(trim($this->twitter_image ?? ''))) {
            return str_starts_with($this->twitter_image, 'http') ? $this->twitter_image : asset('storage/' . $this->twitter_image);
        }
        return $this->effective_og_image;
    }

    public function getSeoHealthAttribute(): array
    {
        $issues = [];
        if (empty(trim($this->meta_title ?? ''))) {
            $issues[] = 'Missing custom SEO title (using H1 fallback)';
        }
        if (empty(trim($this->meta_description ?? ''))) {
            $issues[] = 'Missing meta description (using excerpt fallback)';
        }
        if (empty(trim($this->canonical_url ?? ''))) {
            $issues[] = 'Using auto-generated canonical route';
        }
        if (empty(trim($this->featured_image ?? '')) && empty(trim($this->og_image ?? ''))) {
            $issues[] = 'Missing social share Open Graph image';
        }
        if ($this->robots && str_contains(strtolower($this->robots), 'noindex')) {
            $issues[] = 'Explicitly marked NOINDEX (excluded from search discovery)';
        }

        $status = 'healthy';
        if (count($issues) >= 3) {
            $status = 'critical';
        } elseif (count($issues) > 0) {
            $status = 'needs_attention';
        }

        return [
            'status' => $status,
            'issues' => $issues,
        ];
    }
}
