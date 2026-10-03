<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'psn_id',
        'email',
        'password',
        'bio',
        'country',
        'region',
        'accepts_all_requests',
        'verified',
        'last_active_at',
        'email_verification_code',
        'email_verification_expires_at',
    ];

    protected $hidden = ['password', 'remember_token', 'email_verification_code'];

    protected function casts(): array
    {
        return [
            'email_verified_at'             => 'datetime',
            'email_verification_expires_at' => 'datetime',
            'last_active_at'                => 'datetime',
            'accepts_all_requests'          => 'boolean',
            'verified'                      => 'boolean',
            'password'                      => 'hashed',
        ];
    }

    public function currentGames(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_user')
            ->wherePivot('is_playing', true)
            ->withPivot(['hours', 'is_favorite', 'is_playing', 'favorite_position']);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_user');
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_user')
            ->withPivot(['hours', 'is_favorite', 'is_playing', 'favorite_position'])
            ->orderByPivot('hours', 'desc');
    }

    public function favoriteGames(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_user')
            ->wherePivot('is_favorite', true)
            ->withPivot(['hours', 'is_favorite', 'is_playing', 'favorite_position'])
            ->orderByPivot('favorite_position');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function pinnedPosts(): HasMany
    {
        return $this->hasMany(Post::class)
            ->whereNotNull('pinned_position')
            ->orderBy('pinned_position');
    }

    public function addedPlayers(): HasMany
    {
        return $this->hasMany(AddedPlayer::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    public function isRecentlyActive(): bool
    {
        return $this->last_active_at?->gt(Carbon::now()->subMinutes(30)) ?? false;
    }

    public function activityLabel(): string
    {
        if ($this->last_active_at === null) {
            return '';
        }

        $minutes = (int) $this->last_active_at->diffInMinutes(now());

        return match(true) {
            $minutes <= 5   => 'Online now',
            $minutes <= 30  => "Active {$minutes} min ago",
            $minutes <= 90  => 'Active ' . (int) ($minutes / 60) . ' h ago',
            $minutes < 1440 => 'Active ' . (int) ($minutes / 60) . ' h ago',
            $minutes < 2880 => 'Active yesterday',
            default         => 'Active ' . (int) ($minutes / 1440) . ' d ago',
        };
    }

    public function initials(): string
    {
        return strtoupper(substr($this->psn_id, 0, 2));
    }

    public function avatarColor(): string
    {
        $palette = [
            '#2F6F5E', '#6B4E2E', '#7A3552', '#3B4F7A',
            '#5A6B2B', '#6E5A2A', '#4A3A6E', '#7A4A2E',
            '#3D5A7A', '#5E3D7A', '#7A3D3D', '#3D7A5E',
        ];

        return $palette[abs(crc32($this->psn_id)) % count($palette)];
    }
}
