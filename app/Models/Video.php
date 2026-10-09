<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail_url',
        'video_url',
        'provider',
        'duration_seconds',
        'category_id',
        'user_id',
        'is_featured',
        'published_at',
        'view_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'duration_seconds' => 'integer',
        'view_count' => 'integer',
    ];

    public static function booted(): void
    {
        static::creating(function ($video) {
            if (empty($video->slug)) {
                $video->slug = Str::slug($video->title) . '-' . Str::random(5);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }
}
