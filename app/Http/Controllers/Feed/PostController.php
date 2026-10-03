<?php

namespace App\Http\Controllers\Feed;

use App\Actions\Feed\CreatePostAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'caption' => ['required', 'string', 'max:280'],
            'game_id' => ['nullable', 'integer', 'exists:games,id'],
            'type'    => ['nullable', 'string', 'in:update,clip,trophy,screenshot'],
        ]);

        $post = CreatePostAction::run(auth()->user(), $validated);

        $post->load(['user', 'game', 'reactions']);

        return response()->json([
            'id'         => $post->id,
            'caption'    => $post->caption,
            'type'       => $post->type->value,
            'created_at' => $post->created_at->toISOString(),
            'user'       => [
                'psn_id'       => $post->user->psn_id,
                'initials'     => $post->user->initials(),
                'avatar_color' => $post->user->avatarColor(),
                'profile_url'  => route('profile.show', $post->user->psn_id),
            ],
            'game'       => $post->game ? [
                'title'     => $post->game->title,
                'slug'      => $post->game->slug,
                'cover_url' => $post->game->cover_url,
                'placeholder_color_1' => $post->game->placeholder_color_1,
                'placeholder_color_2' => $post->game->placeholder_color_2,
            ] : null,
            'reactions'  => [],
        ]);
    }
}
