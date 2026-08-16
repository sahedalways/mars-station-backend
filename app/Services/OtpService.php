<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Random\RandomException;

class OtpService
{
    public function generate(int $length = 6): string
    {
        $fixed = $this->fixedCode();

        if ($fixed !== null) {
            return $fixed;
        }

        try {
            return (string) random_int(10 ** ($length - 1), 10 ** $length - 1);
        } catch (RandomException $e) {
            return (string) random_int(100000, 999999);
        }
    }

    public function hash(string $otp): string
    {
        return Hash::make($otp);
    }

    public function verify(string $otp, string $hash): bool
    {
        $fixed = $this->fixedCode();

        if ($fixed !== null && hash_equals($fixed, $otp)) {
            return true;
        }

        return Hash::check($otp, $hash);
    }

    private function fixedCode(): ?string
    {
        if (app()->environment('production')) {
            return null;
        }

        $code = config('mars.otp.fixed_code');

        return filled($code) ? (string) $code : null;
    }
}
