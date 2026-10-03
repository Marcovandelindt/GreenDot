<?php

namespace App\Actions\Profile;

use App\Models\Game;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleFavoriteGameAction
{
    use AsAction;

    public function handle(User $user, Game $game): array
    {
        $existing = $user->games()->where('game_id', $game->id)->first();

        if ($existing?->pivot->is_favorite) {
            if ($existing->pivot->hours) {
                $user->games()->updateExistingPivot($game->id, [
                    'is_favorite'       => false,
                    'favorite_position' => null,
                ]);
            } else {
                $user->games()->detach($game->id);
            }

            return ['active' => false, 'count' => $user->favoriteGames()->count()];
        }

        $count = $user->favoriteGames()->count();

        if ($count >= 5) {
            return ['active' => false, 'count' => $count, 'max_reached' => true];
        }

        if ($existing) {
            $user->games()->updateExistingPivot($game->id, [
                'is_favorite'       => true,
                'favorite_position' => $count + 1,
            ]);
        } else {
            $user->games()->attach($game->id, [
                'is_favorite'       => true,
                'favorite_position' => $count + 1,
                'hours'             => 0,
            ]);
        }

        return ['active' => true, 'count' => $count + 1];
    }
}
