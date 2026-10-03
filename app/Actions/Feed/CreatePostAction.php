<?php

namespace App\Actions\Feed;

use App\Enums\PostType;
use App\Models\Post;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePostAction
{
    use AsAction;

    public function handle(User $user, array $data): Post
    {
        return $user->posts()->create([
            'game_id' => $data['game_id'] ?? null,
            'type'    => $data['type'] ?? PostType::Update->value,
            'caption' => $data['caption'],
        ]);
    }
}
