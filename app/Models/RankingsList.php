<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RankingsList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'year',
        'subtitle',
        'description',
        'cover_image',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'year' => 'integer',
        'is_featured' => 'boolean',
    ];

    public static function booted(): void
    {
        static::creating(function ($list) {
            if (empty($list->slug)) {
                $list->slug = Str::slug($list->title . ' ' . $list->year);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RankingItem::class)->orderBy('rank', 'asc');
    }
}
