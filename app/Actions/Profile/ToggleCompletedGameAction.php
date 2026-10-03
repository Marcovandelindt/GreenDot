<?php

namespace App\Actions\Profile;

use App\Models\Game;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleCompletedGameAction
{
    use AsAction;

    public function handle(User $user, Game $game): bool
    {
        $pivot = $user->games()->where('game_id', $game->id)->first()?->pivot;

        if ($pivot && $pivot->is_completed) {
            $user->games()->updateExistingPivot($game->id, ['is_completed' => false]);
            return false;
        }

        if ($pivot) {
            $user->games()->updateExistingPivot($game->id, ['is_completed' => true, 'is_played' => true]);
        } else {
            $user->games()->attach($game->id, [
                'is_completed'      => true,
                'is_played'         => true,
                'is_playing'        => false,
                'is_favorite'       => false,
                'favorite_position' => null,
                'hours'             => 0,
            ]);
        }

        return true;
    }
}
