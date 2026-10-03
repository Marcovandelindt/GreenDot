<?php

namespace App\Actions\Profile;

use App\Models\Game;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateGameHoursAction
{
    use AsAction;

    public function handle(User $user, Game $game, int $hours): void
    {
        $pivot = $user->games()->where('game_id', $game->id)->first()?->pivot;

        if ($pivot) {
            $user->games()->updateExistingPivot($game->id, ['hours' => $hours]);
        } else {
            $user->games()->attach($game->id, [
                'hours'             => $hours,
                'is_playing'        => false,
                'is_played'         => false,
                'is_completed'      => false,
                'is_favorite'       => false,
                'favorite_position' => null,
            ]);
        }
    }
}
