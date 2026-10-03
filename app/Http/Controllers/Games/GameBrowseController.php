<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GameBrowseController extends Controller
{
    public function __invoke(): View
    {
        return view('games.index');
    }
}
