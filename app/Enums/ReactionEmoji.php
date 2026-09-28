<?php

namespace App\Enums;

enum ReactionEmoji: string
{
    case Fire  = 'fire';
    case Laugh = 'laugh';
    case Wow   = 'wow';
    case Clap  = 'clap';
    case Heart = 'heart';

    public function toEmoji(): string
    {
        return match($this) {
            self::Fire  => '🔥',
            self::Laugh => '😂',
            self::Wow   => '😮',
            self::Clap  => '👏',
            self::Heart => '💚',
        };
    }
}
