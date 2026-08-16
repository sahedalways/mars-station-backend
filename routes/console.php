<?php

use App\Console\Commands\AgreementReminderCommand;
use App\Console\Commands\CleanupAgreementAccessCommand;
use App\Console\Commands\CleanupOtpsCommand;
use App\Console\Commands\ExpireAgreementsCommand;
use App\Console\Commands\SyncSubscriptionsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ExpireAgreementsCommand::class)->dailyAt('00:05');
Schedule::command(AgreementReminderCommand::class)->dailyAt('09:00');
Schedule::command(CleanupOtpsCommand::class)->hourly();
Schedule::command(CleanupAgreementAccessCommand::class)->hourly();
Schedule::command(SyncSubscriptionsCommand::class)->everyFifteenMinutes();
