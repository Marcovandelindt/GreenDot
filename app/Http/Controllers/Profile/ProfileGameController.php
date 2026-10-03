<?php

namespace App\Http\Controllers\Profile;

use App\Actions\Profile\ToggleFavoriteGameAction;
use App\Actions\Profile\ToggleNowPlayingAction;
use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\JsonResponse;

class ProfileGameController extends Controller
{
    public function togglePlaying(Game $game): JsonResponse
    {
        $active = ToggleNowPlayingAction::run(auth()->user(), $game);

        return response()->json(['active' => $active]);
    }

    public function toggleFavorite(Game $game): JsonResponse
    {
        return response()->json(
            ToggleFavoriteGameAction::run(auth()->user(), $game)
        );
    }
}
