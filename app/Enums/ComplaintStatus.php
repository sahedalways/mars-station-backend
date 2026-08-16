<?php

namespace App\Enums;

enum ComplaintStatus: string
{
    case New = 'new';
    case Open = 'open';
    case Flagged = 'flagged';
    case Resolved = 'resolved';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
