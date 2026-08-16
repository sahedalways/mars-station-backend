<?php

namespace App\Console\Commands;

use App\Enums\AgreementStatus;
use App\Models\Agreement;
use App\Services\ActivityLogService;
use Illuminate\Console\Command;

class ExpireAgreementsCommand extends Command
{
    protected $signature = 'agreements:expire';

    protected $description = 'Mark pending agreements past their validity date as expired';

    public function handle(ActivityLogService $logs): int
    {
        $count = 0;

        Agreement::query()
            ->where('is_archived', false)
            ->whereIn('status', [AgreementStatus::Pending->value])
            ->whereNotNull('validity_date')
            ->whereDate('validity_date', '<', now())
            ->chunkById(200, function ($agreements) use (&$count, $logs) {
                foreach ($agreements as $agreement) {
                    $agreement->update(['status' => AgreementStatus::Expired]);
                    $logs->record('agreement.expired', $agreement, ['agreement_number' => $agreement->agreement_number], null, 'system');
                    $count++;
                }
            });

        $this->info("Expired {$count} agreements.");

        return Command::SUCCESS;
    }
}
