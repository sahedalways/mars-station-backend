<?php

namespace App\Enums;

enum QueryStatus: string
{
    case New = 'new';
    case Open = 'open';
    case Flagged = 'flagged';
    case Responded = 'responded';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
