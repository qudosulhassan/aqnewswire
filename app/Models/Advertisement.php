<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'zone',
        'image_url',
        'link_url',
        'raw_html',
        'is_active',
        'impressions',
        'clicks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'impressions' => 'integer',
        'clicks' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeZone(Builder $query, string $zone): Builder
    {
        return $query->where('zone', $zone);
    }
}
