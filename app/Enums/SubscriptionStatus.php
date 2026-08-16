<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Trialing = 'trialing';
    case Active = 'active';
    case PastDue = 'past_due';
    case Incomplete = 'incomplete';
    case IncompleteExpired = 'incomplete_expired';
    case Canceled = 'canceled';
    case Unpaid = 'unpaid';
    case Paused = 'paused';
    case Ended = 'ended';

    public function label(): string
    {
        return match ($this) {
            self::Trialing => 'Trialing',
            self::Active => 'Active',
            self::PastDue => 'Past Due',
            self::Incomplete => 'Incomplete',
            self::IncompleteExpired => 'Incomplete Expired',
            self::Canceled => 'Cancelled',
            self::Unpaid => 'Unpaid',
            self::Paused => 'Paused',
            self::Ended => 'Ended',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
