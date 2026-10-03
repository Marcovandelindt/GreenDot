<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use Illuminate\View\View;

class GameController extends Controller
{
    public function show(string $slug): View
    {
        $game = Game::where('slug', $slug)->firstOrFail();

        $authUser = auth()->user()->load(['favoriteGames', 'currentGames']);

        $isPlaying     = $authUser->currentGames->contains('id', $game->id);
        $isFavorite    = $authUser->favoriteGames->contains('id', $game->id);
        $favoriteCount = $authUser->favoriteGames->count();

        $players = User::with(['languages'])
            ->whereHas('games', fn($q) => $q->where('game_id', $game->id))
            ->where('id', '!=', $authUser->id)
            ->orderByDesc('last_active_at')
            ->limit(16)
            ->get();

        return view('games.show', compact('game', 'isPlaying', 'isFavorite', 'favoriteCount', 'players'));
    }
}
