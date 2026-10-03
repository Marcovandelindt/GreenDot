<?php

namespace App\Enums;

enum PostType: string
{
    case Update     = 'update';
    case Clip       = 'clip';
    case Trophy     = 'trophy';
    case Screenshot = 'screenshot';
}
