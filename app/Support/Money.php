<?php

namespace App\Support;

final class Money
{
    public static function toPence(float|int|string|null $amount): int
    {
        if ($amount === null || $amount === '') {
            return 0;
        }

        $amount = (string) $amount;

        $negative = str_starts_with(trim($amount), '-');
        $amount = ltrim(trim($amount), '+-');

        if (str_contains($amount, '.')) {
            [$whole, $fraction] = explode('.', $amount, 2);
            $fraction = str_pad(substr($fraction, 0, 2), 2, '0');
        } else {
            $whole = $amount;
            $fraction = '00';
        }

        $pence = ((int) $whole) * 100 + (int) $fraction;

        return $negative ? -$pence : $pence;
    }

    public static function fromPence(int $pence): string
    {
        $sign = $pence < 0 ? '-' : '';
        $pence = abs($pence);

        return $sign.number_format($pence / 100, 2, '.', '');
    }

    public static function format(int $pence, string $currency = 'gbp'): string
    {
        $symbol = match (strtolower($currency)) {
            'usd' => '$',
            'eur' => '€',
            'gbp' => '£',
            default => '£',
        };

        return $symbol.self::fromPence($pence);
    }
}
