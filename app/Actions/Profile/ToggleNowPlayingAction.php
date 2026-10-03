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
        if ((int) $user->current_game_id === $game->id) {
            $user->update(['current_game_id' => null]);
            return false;
        }

        $user->update(['current_game_id' => $game->id, 'last_active_at' => now()]);

        if (! $user->games()->where('game_id', $game->id)->exists()) {
            $user->games()->attach($game->id, [
                'is_favorite'       => false,
                'favorite_position' => null,
                'hours'             => 0,
            ]);
        }

        return true;
    }
}
