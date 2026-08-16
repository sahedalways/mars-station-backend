<?php

namespace App\Models;

use App\Enums\AgreementPaymentType;
use App\Enums\AgreementStatus;
use App\Support\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agreement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agreement_number',
        'title',
        'client_name',
        'client_email',
        'client_mobile',
        'validity_date',
        'payment_type',
        'status',
        'is_archived',
        'archived_at',
        'created_by',
    ];

    protected $casts = [
        'validity_date' => 'date',
        'payment_type' => AgreementPaymentType::class,
        'status' => AgreementStatus::class,
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function versions()
    {
        return $this->hasMany(AgreementVersion::class);
    }

    public function currentVersion()
    {
        return $this->hasOne(AgreementVersion::class)->latestOfMany();
    }

    public function attachments()
    {
        return $this->hasMany(AgreementAttachment::class);
    }

    public function links()
    {
        return $this->hasMany(AgreementLink::class);
    }

    public function activeLink()
    {
        return $this->hasOne(AgreementLink::class)->where('is_active', true)->latestOfMany();
    }

    public function accessLogs()
    {
        return $this->hasMany(AgreementAccessLog::class);
    }

    public function otpSessions()
    {
        return $this->hasMany(AgreementOtpSession::class);
    }

    public function milestones()
    {
        return $this->hasMany(AgreementMilestone::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(AgreementSubscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function isExpired(): bool
    {
        return $this->validity_date !== null && $this->validity_date->isPast();
    }

    public function hasSignature(): bool
    {
        return $this->currentVersion?->status === 'signed';
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function nextMilestone()
    {
        return $this->milestones()
            ->where('status', 'pending')
            ->orderBy('order_index')
            ->first();
    }

    public function amountTotalPence(?int $current = 0): int
    {
        return match ($this->payment_type) {
            AgreementPaymentType::Full => $current,
            AgreementPaymentType::Milestone => $this->milestones()->sum('amount_pence'),
            AgreementPaymentType::Subscription => $this->subscriptions()->sum('amount_pence'),
            default => 0,
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return Money::format($this->amountTotalPence());
    }

    public function isPaid(): bool
    {
        return match ($this->payment_type) {
            AgreementPaymentType::None => true,
            AgreementPaymentType::Full => $this->payments()
                ->where('type', 'full')
                ->where('status', 'succeeded')
                ->exists(),
            AgreementPaymentType::Milestone => $this->milestones()
                    ->where('status', 'paid')
                    ->count() >= 1
                && $this->milestones()->where('status', 'pending')->doesntExist(),
            AgreementPaymentType::Subscription => $this->subscriptions()
                    ->whereIn('status', ['active', 'trialing', 'past_due'])
                    ->exists(),
        };
    }
}
