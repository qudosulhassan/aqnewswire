<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsletterCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'template_id',
        'segment_id',
        'content',
        'status',
        'scheduled_for',
        'sent_at',
        'total_recipients',
        'total_opened',
        'total_clicked',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
        'total_recipients' => 'integer',
        'total_opened' => 'integer',
        'total_clicked' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(NewsletterTemplate::class, 'template_id');
    }

    public function segment(): BelongsTo
    {
        return $this->belongsTo(NewsletterSegment::class, 'segment_id');
    }
}
