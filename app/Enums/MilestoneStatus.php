<?php

namespace App\Enums;

enum MilestoneStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
