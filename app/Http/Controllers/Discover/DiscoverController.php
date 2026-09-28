<?php

namespace App\Http\Controllers\Discover;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class DiscoverController extends Controller
{
    public function __invoke(): View
    {
        $players = User::with(['languages', 'favoriteGames', 'currentGame', 'games'])
            ->orderByDesc('last_active_at')
            ->get()
            ->map(fn(User $u) => [
                'id'                   => $u->id,
                'psn_id'               => $u->psn_id,
                'initials'             => $u->initials(),
                'avatar_color'         => $u->avatarColor(),
                'languages'            => $u->languages->pluck('name')->all(),
                'country'              => $u->country,
                'region'               => $u->region,
                'activity_label'       => $u->activityLabel(),
                'is_recently_active'   => $u->isRecentlyActive(),
                'accepts_all_requests' => $u->accepts_all_requests,
                'verified'             => $u->verified,
                'current_game'         => $u->currentGame ? [
                    'title'    => $u->currentGame->title,
                    'gradient' => $u->currentGame->placeholderGradient(),
                ] : null,
                'favorite_games'       => $u->favoriteGames->take(3)->map(fn($g) => [
                    'title'       => $g->title,
                    'short_title' => strtoupper($g->short_title ?? $g->title),
                    'gradient'    => $g->placeholderGradient(),
                ])->values()->all(),
                'game_titles'          => $u->games->pluck('title')
                    ->merge($u->currentGame ? [$u->currentGame->title] : [])
                    ->unique()
                    ->values()
                    ->all(),
                'profile_url'          => route('profile.show', $u->psn_id),
            ])
            ->all();

        return view('discover', compact('players'));
    }
}
