<?php

namespace App\Enums;

enum SubscriptionFrequency: string
{
    case Monthly = 'monthly';
    case Yearly = 'yearly';

    public function label(): string
    {
        return match ($this) {
            self::Monthly => 'Monthly',
            self::Yearly => 'Yearly',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
