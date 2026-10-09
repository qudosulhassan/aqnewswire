<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class NewsletterSegment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'criteria_type',
        'criteria_payload',
        'is_active',
    ];

    protected $casts = [
        'criteria_payload' => 'array',
        'is_active' => 'boolean',
    ];

    public static function booted(): void
    {
        static::creating(function ($segment) {
            if (empty($segment->slug)) {
                $segment->slug = Str::slug($segment->name);
            }
        });
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(NewsletterCampaign::class, 'segment_id');
    }
}
