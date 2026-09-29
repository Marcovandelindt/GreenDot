<?php

namespace App\Http\Controllers\Onboarding;

use App\Actions\Onboarding\SaveOnboardingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Onboarding\StoreOnboardingRequest;
use App\Models\Language;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function create(Request $request): View
    {
        $user = $request->user()->load(['languages', 'favoriteGames', 'currentGame']);
        $languages = Language::orderBy('name')->get();

        $selectedLanguageIds = $user->languages->pluck('id')->toArray();

        $favGames = $user->favoriteGames
            ->map(fn ($g) => [
                'id'                  => $g->id,
                'title'               => $g->title,
                'short_title'         => $g->short_title,
                'cover_url'           => $g->cover_url,
                'placeholder_color_1' => $g->placeholder_color_1,
                'placeholder_color_2' => $g->placeholder_color_2,
            ])
            ->values()
            ->all();

        $nowPlaying = $user->currentGame
            ? ['id' => $user->currentGame->id, 'title' => $user->currentGame->title]
            : null;

        return view('onboarding', compact('user', 'languages', 'selectedLanguageIds', 'favGames', 'nowPlaying'));
    }

    public function store(StoreOnboardingRequest $request): RedirectResponse
    {
        SaveOnboardingAction::run($request->user(), $request->validated());

        return redirect()->route('discover');
    }
}
