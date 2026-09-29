<?php

namespace App\Actions\Onboarding;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class SaveOnboardingAction
{
    use AsAction;

    public function handle(User $user, array $data): void
    {
        $user->update([
            'bio'                  => $data['bio'] ?? null,
            'country'              => $data['country'] ?? null,
            'region'               => $data['region'] ?? null,
            'accepts_all_requests' => (bool) ($data['accepts_all_requests'] ?? false),
            'current_game_id'      => ($data['current_game_id'] ?? null) ?: null,
            'last_active_at'       => now(),
        ]);

        $user->languages()->sync($data['language_ids'] ?? []);

        $sync = [];

        foreach (($data['favorite_game_ids'] ?? []) as $index => $gameId) {
            $sync[(int) $gameId] = [
                'is_favorite'       => true,
                'favorite_position' => $index + 1,
                'hours'             => 0,
            ];
        }

        $currentGameId = ($data['current_game_id'] ?? null) ?: null;
        if ($currentGameId && ! isset($sync[(int) $currentGameId])) {
            $sync[(int) $currentGameId] = [
                'is_favorite'       => false,
                'favorite_position' => null,
                'hours'             => 0,
            ];
        }

        $user->games()->sync($sync);
    }
}
