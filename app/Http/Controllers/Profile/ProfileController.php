<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(string $psn_id): View
    {
        $user = User::where('psn_id', $psn_id)
            ->with(['languages', 'favoriteGames', 'currentGame', 'games'])
            ->firstOrFail();

        return view('profile.show', compact('user'));
    }
}
