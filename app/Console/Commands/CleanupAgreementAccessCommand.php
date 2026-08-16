<?php

namespace App\Console\Commands;

use App\Models\AgreementAccessLog;
use App\Models\AgreementOtpSession;
use Illuminate\Console\Command;

class CleanupAgreementAccessCommand extends Command
{
    protected $signature = 'agreements:cleanup-access';

    protected $description = 'Delete expired agreement access logs and expired OTP sessions';

    public function handle(): int
    {
        $cutoff = now()->subDays(30);

        $logs = AgreementAccessLog::query()
            ->where('created_at', '<', $cutoff)
            ->delete();

        $sessions = AgreementOtpSession::query()
            ->whereNotNull('session_expires_at')
            ->where('session_expires_at', '<', now()->subDays(30))
            ->delete();

        $this->info("Cleaned up {$logs} access logs and {$sessions} expired sessions.");

        return Command::SUCCESS;
    }
}
