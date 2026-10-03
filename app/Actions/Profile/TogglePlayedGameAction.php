<?php

namespace App\Actions\Profile;

use App\Models\Game;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class TogglePlayedGameAction
{
    use AsAction;

    public function handle(User $user, Game $game): bool
    {
        $pivot = $user->games()->where('game_id', $game->id)->first()?->pivot;

        if ($pivot && $pivot->is_played) {
            $user->games()->updateExistingPivot($game->id, ['is_played' => false]);
            return false;
        }

        if ($pivot) {
            $user->games()->updateExistingPivot($game->id, ['is_played' => true]);
        } else {
            $user->games()->attach($game->id, [
                'is_played'         => true,
                'is_playing'        => false,
                'is_completed'      => false,
                'is_favorite'       => false,
                'favorite_position' => null,
                'hours'             => 0,
            ]);
        }

        return true;
    }
}
