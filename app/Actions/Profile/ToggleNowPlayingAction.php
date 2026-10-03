<?php

namespace App\Actions\Profile;

use App\Models\Game;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleNowPlayingAction
{
    use AsAction;

    public function handle(User $user, Game $game): bool
    {
        $pivot = $user->games()->where('game_id', $game->id)->first()?->pivot;

        if ($pivot && $pivot->is_playing) {
            $user->games()->updateExistingPivot($game->id, ['is_playing' => false]);
            return false;
        }

        if ($pivot) {
            $user->games()->updateExistingPivot($game->id, ['is_playing' => true]);
        } else {
            $user->games()->attach($game->id, [
                'is_playing'        => true,
                'is_favorite'       => false,
                'favorite_position' => null,
                'hours'             => 0,
            ]);
        }

        $user->update(['last_active_at' => now()]);

        return true;
    }
}
