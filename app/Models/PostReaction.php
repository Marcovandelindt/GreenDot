<?php

namespace App\Models;

use App\Enums\ReactionEmoji;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostReaction extends Model
{
    protected $fillable = ['post_id', 'user_id', 'emoji'];

    protected $casts = [
        'emoji' => ReactionEmoji::class,
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
