<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Game extends Model
{
    protected $fillable = [
        'title',
        'short_title',
        'slug',
        'release_year',
        'cover_url',
        'placeholder_color_1',
        'placeholder_color_2',
        'external_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Game $game): void {
            if (empty($game->slug)) {
                $game->slug = Str::slug($game->title);
            }
        });
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'game_user')
            ->withPivot(['hours', 'is_favorite', 'favorite_position']);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function placeholderGradient(): string
    {
        $c1 = $this->placeholder_color_1 ?? '#1a1a2e';
        $c2 = $this->placeholder_color_2 ?? '#16213e';

        return "linear-gradient(160deg, {$c1} 0%, {$c2} 100%)";
    }

    public function coverStyle(): string
    {
        if ($this->cover_url) {
            return "background: url('{$this->cover_url}') center/cover no-repeat";
        }

        return "background: {$this->placeholderGradient()}";
    }
}
