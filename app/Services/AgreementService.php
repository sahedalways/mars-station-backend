<?php

namespace App\Services;

use App\Enums\AgreementPaymentType;
use App\Enums\AgreementStatus;
use App\Mail\AgreementCompletedMail;
use App\Mail\AgreementSentMail;
use App\Mail\AgreementSignedMail;
use App\Mail\AgreementTerminatedMail;
use App\Models\Admin;
use App\Models\Agreement;
use App\Models\AgreementLink;
use App\Models\AgreementVersion;
use App\Support\AgreementNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgreementService
{
    public function __construct(
        private readonly EmailService $email,
        private readonly ActivityLogService $logs,
    ) {}

    public function create(array $data, Admin $admin, ?array $attachment = null): Agreement
    {
        return DB::transaction(function () use ($data, $admin, $attachment) {
            $agreement = Agreement::create([
                'agreement_number' => AgreementNumber::generate(),
                'title' => $data['title'],
                'client_name' => $data['client_name'],
                'client_email' => $data['client_email'],
                'client_mobile' => $data['client_mobile'] ?? null,
                'validity_date' => $data['validity_date'] ?? null,
                'payment_type' => $data['payment_type'],
                'status' => AgreementStatus::Pending,
                'created_by' => $admin->id,
            ]);

            $paymentConfig = $this->buildPaymentConfig($agreement->payment_type, $data);

            $version = $this->createVersion($agreement, $data, $paymentConfig, $admin);

            $this->syncMilestonesForVersion($agreement, $version, $paymentConfig);

            if ($attachment && isset($attachment['path'])) {
                $agreement->attachments()->create([
                    'version_id' => $version->id,
                    'storage_path' => $attachment['path'],
                    'original_name' => $attachment['original_name'],
                    'mime_type' => $attachment['mime_type'],
                    'size_bytes' => $attachment['size_bytes'],
                    'uploaded_by' => $admin->id,
                ]);
            }

            $link = $this->createLink($agreement, $version, $admin);

            $this->email->send(
                new AgreementSentMail($agreement, $link, $version),
                $agreement->client_email,
                'agreement.sent',
                $agreement,
                $admin
            );

            $this->logs->record('agreement.created', $agreement, [
                'agreement_number' => $agreement->agreement_number,
                'payment_type' => $agreement->payment_type->value,
            ], $admin);

            return $agreement->fresh('versions', 'links', 'milestones', 'subscriptions');
        });
    }

    public function createNewVersion(Agreement $agreement, array $data, Admin $admin): AgreementVersion
    {
        return DB::transaction(function () use ($agreement, $data, $admin) {
            $paymentConfig = $this->buildPaymentConfig($agreement->payment_type, $data);

            $version = $this->createVersion($agreement, $data, $paymentConfig, $admin);

            $this->syncMilestonesForVersion($agreement, $version, $paymentConfig);

            $link = $this->createLink($agreement, $version, $admin);

            $this->email->send(
                new AgreementSentMail($agreement, $link, $version),
                $agreement->client_email,
                'agreement.resent',
                $agreement,
                $admin
            );

            $this->logs->record('agreement.version_created', $agreement, [
                'agreement_number' => $agreement->agreement_number,
                'version' => $version->version,
            ], $admin);

            return $version;
        });
    }

    public function createVersion(
        Agreement $agreement,
        array $data,
        ?array $paymentConfig,
        ?Admin $admin = null
    ): AgreementVersion {
        $next = (int) $agreement->versions()->max('version') + 1;

        $version = AgreementVersion::create([
            'agreement_id' => $agreement->id,
            'version' => $next,
            'title' => $data['title'],
            'client_name' => $data['client_name'],
            'client_email' => $data['client_email'],
            'client_mobile' => $data['client_mobile'] ?? null,
            'validity_date' => $data['validity_date'] ?? null,
            'content' => $data['content'],
            'payment_config' => $paymentConfig,
            'status' => 'pending',
            'admin_id' => $admin?->id,
        ]);

        return $version;
    }

    public function buildPaymentConfig(AgreementPaymentType $type, array $data): ?array
    {
        return match ($type) {
            AgreementPaymentType::Full => [
                'title' => $data['full_title'] ?? $data['title'],
                'amount_pence' => (int) ($data['full_amount_pence'] ?? 0),
            ],
            AgreementPaymentType::Milestone => [
                'milestones' => collect($data['milestones'] ?? [])
                    ->map(fn ($m, $i) => [
                        'title' => $m['title'],
                        'description' => $m['description'] ?? null,
                        'amount_pence' => (int) ($m['amount_pence'] ?? 0),
                        'order_index' => $i + 1,
                    ])
                    ->values()
                    ->all(),
            ],
            AgreementPaymentType::Subscription => [
                'title' => $data['subscription_title'] ?? $data['title'],
                'amount_pence' => (int) ($data['subscription_amount_pence'] ?? 0),
                'frequency' => $data['subscription_frequency'] ?? 'monthly',
            ],
            default => null,
        };
    }

    public function syncMilestonesForVersion(
        Agreement $agreement,
        AgreementVersion $version,
        ?array $paymentConfig
    ): void {
        $agreement->milestones()
            ->where('status', 'pending')
            ->delete();

        if (! $paymentConfig || empty($paymentConfig['milestones'])) {
            return;
        }

        foreach ($paymentConfig['milestones'] as $milestone) {
            $agreement->milestones()->create([
                'version_id' => $version->id,
                'title' => $milestone['title'],
                'description' => $milestone['description'],
                'amount_pence' => $milestone['amount_pence'],
                'order_index' => $milestone['order_index'],
                'status' => 'pending',
            ]);
        }
    }

    public function sendAgreement(Agreement $agreement, AgreementLink $link, AgreementVersion $version, Admin $admin): void
    {
        $this->email->send(
            new AgreementSentMail($agreement, $link, $version),
            $agreement->client_email,
            'agreement.sent',
            $agreement,
            $admin
        );

        $this->logs->record('agreement.resend', $agreement, [
            'agreement_number' => $agreement->agreement_number,
            'version' => $version->version,
        ], $admin);
    }

    public function sendPaymentReminder(Agreement $agreement, AgreementLink $link, AgreementVersion $version, Admin $admin): void
    {
        $this->email->send(
            new \App\Mail\AgreementReminderMail($agreement, $link),
            $agreement->client_email,
            'agreement.reminder',
            $agreement,
            $admin
        );

        $this->logs->record('agreement.payment_reminder', $agreement, [
            'agreement_number' => $agreement->agreement_number,
            'version' => $version->version,
        ], $admin);
    }

    public function createLink(
        Agreement $agreement,
        ?AgreementVersion $version,
        ?Admin $admin = null,
        bool $otpEnabled = false
    ): AgreementLink {
        return AgreementLink::create([
            'agreement_id' => $agreement->id,
            'version_id' => $version?->id,
            'token' => Str::random(64),
            'is_active' => true,
            'otp_enabled' => $otpEnabled,
            'created_by' => $admin?->id,
        ]);
    }

    public function regenerateLink(AgreementLink $link, Admin $admin): AgreementLink
    {
        $link->disable();

        $newLink = $this->createLink($link->agreement, $link->version, $admin, $link->otp_enabled);

        $this->logs->record('agreement.link_regenerated', $link->agreement, [
            'old_link_id' => $link->id,
            'new_link_id' => $newLink->id,
        ], $admin);

        return $newLink;
    }

    public function archive(Agreement $agreement, Admin $admin): void
    {
        $agreement->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);

        $agreement->links()->where('is_active', true)->get()->each->disable();

        $this->logs->record('agreement.archived', $agreement, [
            'agreement_number' => $agreement->agreement_number,
        ], $admin);
    }

    public function restore(Agreement $agreement, Admin $admin): void
    {
        $agreement->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);

        $this->logs->record('agreement.restored', $agreement, [
            'agreement_number' => $agreement->agreement_number,
        ], $admin);
    }

    public function changeStatus(Agreement $agreement, AgreementStatus $status, Admin $admin): void
    {
        $old = $agreement->status;

        $agreement->update(['status' => $status]);

        if ($status === AgreementStatus::Completed) {
            $link = $agreement->activeLink;

            if ($link) {
                $this->email->send(
                    new AgreementCompletedMail($agreement, $link),
                    $agreement->client_email,
                    'agreement.completed',
                    $agreement,
                    $admin
                );
            }
        } elseif ($status === AgreementStatus::Terminated) {
            $this->email->send(
                new AgreementTerminatedMail($agreement),
                $agreement->client_email,
                'agreement.terminated',
                $agreement,
                $admin
            );
        }

        $this->logs->record('agreement.status_changed', $agreement, [
            'from' => $old->value,
            'to' => $status->value,
        ], $admin);
    }

    public function markSigned(Agreement $agreement, AgreementVersion $version): void
    {
        $agreement->update(['status' => AgreementStatus::Signed]);

        $link = $agreement->activeLink;

        if ($link) {
            $this->email->send(
                new AgreementSignedMail($agreement, $link, $version),
                $agreement->client_email,
                'agreement.signed',
                $agreement
            );
        }

        $admin = $agreement->creator;

        if ($admin && $admin->email !== $agreement->client_email) {
            $this->email->send(
                new AgreementSignedMail($agreement, $link, $version),
                $admin->email,
                'agreement.signed',
                $agreement,
                $admin
            );
        }
    }

    public function deletePermanently(Agreement $agreement, Admin $admin): void
    {
        $agreement->forceDelete();

        $this->logs->record('agreement.permanently_deleted', null, [
            'agreement_number' => $agreement->agreement_number,
            'title' => $agreement->title,
        ], $admin);
    }

    public function logEdit(Agreement $agreement, Admin $admin): void
    {
        $this->logs->record('agreement.edited', $agreement, [
            'agreement_number' => $agreement->agreement_number,
            'version' => $agreement->versions()->latest('id')->first()?->version,
        ], $admin);
    }

    public function latestVersion(Agreement $agreement): ?AgreementVersion
    {
        return $agreement->versions()->latest('id')->first();
    }

    public function buildStatusColor(AgreementStatus $status): string
    {
        return match ($status) {
            AgreementStatus::Pending => 'amber',
            AgreementStatus::Expired => 'red',
            AgreementStatus::Signed => 'blue',
            AgreementStatus::InProgress => 'indigo',
            AgreementStatus::Subscribed => 'green',
            AgreementStatus::Unsubscribed => 'gray',
            AgreementStatus::Completed => 'green',
            AgreementStatus::Terminated => 'red',
        };
    }

    public function buildPaymentStatusColor(string $status): string
    {
        return match ($status) {
            'succeeded' => 'green',
            'refunded' => 'gray',
            'partially_refunded' => 'amber',
            'failed' => 'red',
            default => 'amber',
        };
    }
}
