<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $q = trim((string) $request->string('q'));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $games = Game::where('title', 'like', "%{$q}%")
            ->orWhere('short_title', 'like', "%{$q}%")
            ->orderBy('title')
            ->limit(8)
            ->get(['id', 'slug', 'title', 'short_title', 'cover_url', 'placeholder_color_1', 'placeholder_color_2']);

        return response()->json($games);
    }
}
