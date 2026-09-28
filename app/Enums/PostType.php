<?php

namespace App\Enums;

enum PostType: string
{
    case Clip       = 'clip';
    case Trophy     = 'trophy';
    case Screenshot = 'screenshot';
}
