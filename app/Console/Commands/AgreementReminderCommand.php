<?php

namespace App\Console\Commands;

use App\Enums\AgreementStatus;
use App\Mail\AgreementReminderMail;
use App\Models\Agreement;
use App\Services\EmailService;
use Illuminate\Console\Command;

class AgreementReminderCommand extends Command
{
    protected $signature = 'agreements:remind';

    protected $description = 'Send reminder emails for pending unsigned agreements older than a configurable age';

    public function handle(EmailService $email): int
    {
        $days = 3;
        $cutoff = now()->subDays($days);

        $agreements = Agreement::query()
            ->with('activeLink')
            ->where('is_archived', false)
            ->where('status', AgreementStatus::Pending->value)
            ->where('created_at', '<=', $cutoff)
            ->get();

        $count = 0;

        foreach ($agreements as $agreement) {
            $link = $agreement->activeLink;

            if (! $link) {
                continue;
            }

            $email->send(
                new AgreementReminderMail($agreement, $link),
                $agreement->client_email,
                'agreement.reminder',
                $agreement
            );

            $count++;
        }

        $this->info("Sent {$count} agreement reminders.");

        return Command::SUCCESS;
    }
}
