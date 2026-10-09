<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PodcastEpisode extends Model
{
    use HasFactory;

    protected $fillable = [
        'podcast_id',
        'title',
        'slug',
        'episode_number',
        'season',
        'audio_url',
        'duration',
        'transcript',
        'description',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'episode_number' => 'integer',
        'season' => 'integer',
    ];

    public static function booted(): void
    {
        static::creating(function ($episode) {
            if (empty($episode->slug)) {
                $episode->slug = Str::slug($episode->title) . '-' . Str::random(4);
            }
        });
    }

    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }
}
