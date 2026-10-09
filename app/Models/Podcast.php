<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Podcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'host_name',
        'user_id',
    ];

    public static function booted(): void
    {
        static::creating(function ($podcast) {
            if (empty($podcast->slug)) {
                $podcast->slug = Str::slug($podcast->title);
            }
        });
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(PodcastEpisode::class)->orderBy('episode_number', 'desc');
    }
}
