<?php

namespace App\Support;

use App\Models\Agreement;
use Illuminate\Support\Str;

final class AgreementNumber
{
    private const ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public static function generate(int $length = 8): string
    {
        $attempts = 0;

        do {
            $number = Str::upper(Str::random($length, self::ALPHABET));

            $attempts++;
        } while ($attempts < 10 && Agreement::where('agreement_number', $number)->exists());

        return $number;
    }
}
