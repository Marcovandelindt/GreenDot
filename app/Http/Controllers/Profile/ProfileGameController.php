<?php

namespace App\Http\Controllers\Profile;

use App\Actions\Profile\ToggleCompletedGameAction;
use App\Actions\Profile\ToggleFavoriteGameAction;
use App\Actions\Profile\ToggleNowPlayingAction;
use App\Actions\Profile\TogglePlayedGameAction;
use App\Actions\Profile\UpdateGameHoursAction;
use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function togglePlayed(Game $game): JsonResponse
    {
        $active = TogglePlayedGameAction::run(auth()->user(), $game);

        return response()->json(['active' => $active]);
    }

    public function toggleCompleted(Game $game): JsonResponse
    {
        $active = ToggleCompletedGameAction::run(auth()->user(), $game);

        return response()->json(['active' => $active]);
    }

    public function updateHours(Request $request, Game $game): JsonResponse
    {
        $hours = max(0, (int) $request->input('hours', 0));

        UpdateGameHoursAction::run(auth()->user(), $game, $hours);

        return response()->json(['hours' => $hours]);
    }
}
