<?php

namespace App\Enums;

enum AgreementPaymentType: string
{
    case Full = 'full';
    case Milestone = 'milestone';
    case Subscription = 'subscription';
    case None = 'none';

    public function label(): string
    {
        return match ($this) {
            self::Full => 'Full Payment',
            self::Milestone => 'Milestone Payment',
            self::Subscription => 'Subscription Payment',
            self::None => 'No Payment Required',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
