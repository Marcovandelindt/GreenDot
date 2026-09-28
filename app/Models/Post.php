<?php

namespace App\Models;

use App\Enums\PostType;
use App\Enums\ReactionEmoji;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'game_id',
        'type',
        'caption',
        'media_url',
        'trophy_name',
        'trophy_rarity',
        'trophy_rarity_percent',
        'pinned_position',
    ];

    protected $casts = [
        'type'                  => PostType::class,
        'trophy_rarity_percent' => 'float',
        'pinned_position'       => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

    public function reactionCountFor(ReactionEmoji $emoji): int
    {
        return $this->reactions->where('emoji', $emoji)->count();
    }
}
