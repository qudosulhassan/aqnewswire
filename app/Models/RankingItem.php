<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RankingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rankings_list_id',
        'rank',
        'name',
        'title_or_role',
        'company',
        'net_worth_or_metric',
        'industry',
        'country',
        'bio',
        'photo_url',
    ];

    protected $casts = [
        'rank' => 'integer',
    ];

    public function list(): BelongsTo
    {
        return $this->belongsTo(RankingsList::class, 'rankings_list_id');
    }
}
