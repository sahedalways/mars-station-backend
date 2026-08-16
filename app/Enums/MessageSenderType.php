<?php

namespace App\Enums;

enum MessageSenderType: string
{
    case Client = 'client';
    case Admin = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
