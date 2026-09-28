<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddedPlayer extends Model
{
    protected $fillable = ['user_id', 'added_user_id', 'accepted'];

    protected $casts = [
        'accepted' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }
}
