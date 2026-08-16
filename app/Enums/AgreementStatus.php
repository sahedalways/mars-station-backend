<?php

namespace App\Enums;

enum AgreementStatus: string
{
    case Pending = 'pending';
    case Expired = 'expired';
    case Signed = 'signed';
    case InProgress = 'in_progress';
    case Subscribed = 'subscribed';
    case Unsubscribed = 'unsubscribed';
    case Completed = 'completed';
    case Terminated = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Expired => 'Expired',
            self::Signed => 'Signed',
            self::InProgress => 'In Progress',
            self::Subscribed => 'Subscribed',
            self::Unsubscribed => 'Unsubscribed',
            self::Completed => 'Completed',
            self::Terminated => 'Terminated',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
