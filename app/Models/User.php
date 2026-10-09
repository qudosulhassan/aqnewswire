<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLES = [
        'super_admin' => 'Super Administrator',
        'admin' => 'Administrator',
        'editor' => 'Executive Editor',
        'managing_editor' => 'Managing Editor',
        'writer' => 'Staff Writer',
        'contributor' => 'Contributor',
        'subscriber' => 'Subscriber',
        'reader' => 'Reader',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'slug',
        'title',
        'bio',
        'avatar',
        'website',
        'twitter',
        'linkedin',
        'is_verified',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public static function booted(): void
    {
        static::creating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = Str::slug($user->name) . '-' . Str::random(4);
            }
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class)->orderByDesc('last_read_at');
    }

    public function follows(): HasMany
    {
        return $this->hasMany(Follow::class);
    }

    public function followers(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(UserNotification::class)->latest();
    }

    public function contributorApplication(): HasOne
    {
        return $this->hasOne(ContributorApplication::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isEditor(): bool
    {
        return in_array($this->role, ['admin', 'super_admin', 'editor', 'managing_editor']);
    }

    public function isWriter(): bool
    {
        return in_array($this->role, ['admin', 'super_admin', 'editor', 'managing_editor', 'writer']);
    }

    public function isContributor(): bool
    {
        return in_array($this->role, ['admin', 'super_admin', 'editor', 'managing_editor', 'writer', 'contributor']);
    }

    public function canAccessAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin', 'editor', 'managing_editor', 'writer', 'contributor', 'seo_manager', 'moderator']);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return str_starts_with($this->avatar, 'http') ? $this->avatar : asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0f172a&color=fff';
    }

    public function getRoleNameAttribute(): string
    {
        return self::ROLES[$this->role] ?? ucfirst(str_replace('_', ' ', $this->role ?? 'User'));
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'bg-purple-100 text-purple-700 border-purple-200',
            'admin' => 'bg-red-100 text-red-700 border-red-200',
            'editor', 'managing_editor' => 'bg-blue-100 text-blue-700 border-blue-200',
            'writer' => 'bg-amber-100 text-amber-700 border-amber-200',
            'contributor' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
