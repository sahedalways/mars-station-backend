<?php

namespace App\Enums;

enum GetServiceStatus: string
{
    case New = 'new';
    case Processing = 'processing';
    case Flagged = 'flagged';
    case Signed = 'signed';
    case Completed = 'completed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
