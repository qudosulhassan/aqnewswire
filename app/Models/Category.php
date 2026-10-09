<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'parent_id',
        'order',
        'is_featured',
        'is_nav_visible',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_nav_visible' => 'boolean',
        'order' => 'integer',
    ];

    public static function booted(): void
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function publishedArticles(): HasMany
    {
        return $this->articles()->where('status', 'published');
    }

    public function scopeNavVisible($query)
    {
        return $query->where('is_nav_visible', true)->orderBy('order');
    }

    public function followers(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    public function getDisplayDescriptionAttribute(): string
    {
        if (!empty($this->description)) {
            return $this->description;
        }

        return match($this->slug) {
            'business' => 'Strategic corporate governance, global commerce, executive leadership, retail, and corporate restructuring intelligence.',
            'markets' => 'Global capital flows, central bank monetary policy, sovereign debt, equity benchmarks, currencies, and market volatility.',
            'investing' => 'Actionable portfolio strategies, personal finance, fintech, wealth management, ETFs, and institutional trading analysis.',
            'tech', 'technology' => 'Frontier artificial intelligence, enterprise infrastructure, cybersecurity, hardware architectures, and digital disruption.',
            'politics' => 'Executive governance, defense strategy, congressional legislation, diplomacy, and global geopolitical shifts.',
            'lifestyle' => 'Executive career strategy, luxury automotive, workplace architecture, fitness, and modern lifestyle intelligence.',
            'health' => 'Mental health science, clinical longevity, dietetics, medical technology innovations, and executive wellness.',
            'ai' => 'Frontier foundation models, autonomous agent orchestration, neural architectures, and computational breakthroughs redefining industry.',
            'economy' => 'Macroeconomic indicators, global GDP growth, inflation forecasting, labor markets, and monetary policy.',
            'finance' => 'Commercial credit, international banking, corporate debt facilities, and capital markets.',
            'real-estate' => 'Commercial real estate developments, institutional acquisitions, and prime property markets.',
            'energy' => 'Global energy transition, oil and gas logistics, power grid modernization, and clean infrastructure.',
            'climate' => 'Carbon markets, environmental legislation, ESG disclosures, and climate risk management.',
            'bonds' => 'Sovereign yield curve dynamics, treasury refinancing cycles, and high-yield corporate credit.',
            'cryptocurrency' => 'Digital asset markets, Bitcoin infrastructure, institutional custody, and decentralized protocols.',
            'wealth' => 'Private wealth allocation, dynastic family offices, alternative assets, and luxury investments.',
            'fintech' => 'Next-generation payment rails, algorithmic trading, neobanking, and financial software ecosystems.',
            'cybersecurity' => 'Threat intelligence, enterprise security architectures, nation-state cyberdefense, and data privacy.',
            'workplace' => 'Modern corporate headquarter architecture, remote workforce orchestration, and C-suite culture.',
            default => "Authoritative reporting, in-depth analysis, and executive intelligence from AQ NEWSWIRE's {$this->name} desk.",
        };
    }

    public function getUrlAttribute(): string
    {
        return route('categories.show', $this->slug);
    }
}
