<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function __invoke(): View
    {
        $posts = Post::with(['user', 'game', 'reactions'])
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('feed.index', compact('posts'));
    }
}
