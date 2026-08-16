<?php

namespace App\Enums;

enum PaymentType: string
{
    case Full = 'full';
    case Milestone = 'milestone';
    case Subscription = 'subscription';

    public function label(): string
    {
        return match ($this) {
            self::Full => 'Full Payment',
            self::Milestone => 'Milestone Payment',
            self::Subscription => 'Subscription Payment',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
