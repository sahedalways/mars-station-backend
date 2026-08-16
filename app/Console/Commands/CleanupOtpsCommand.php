<?php

namespace App\Console\Commands;

use App\Models\AdminOtpRequest;
use App\Models\AgreementOtpSession;
use Illuminate\Console\Command;

class CleanupOtpsCommand extends Command
{
    protected $signature = 'otps:cleanup';

    protected $description = 'Delete expired, unconsumed OTP records';

    public function handle(): int
    {
        $cutoff = now()->subDay();

        $admin = AdminOtpRequest::query()
            ->whereNull('consumed_at')
            ->where('expires_at', '<', $cutoff)
            ->delete();

        $agreement = AgreementOtpSession::query()
            ->whereNull('consumed_at')
            ->where('expires_at', '<', $cutoff)
            ->delete();

        $this->info("Deleted {$admin} admin OTP requests and {$agreement} agreement OTP sessions.");

        return Command::SUCCESS;
    }
}
