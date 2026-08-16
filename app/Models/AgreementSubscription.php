<?php

namespace App\Models;

use App\Enums\SubscriptionFrequency;
use App\Enums\SubscriptionStatus;
use App\Support\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'version_id',
        'title',
        'amount_pence',
        'frequency',
        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_price_id',
        'status',
        'cancel_at_period_end',
        'current_period_start',
        'current_period_end',
        'started_at',
        'canceled_at',
        'ended_at',
    ];

    protected $casts = [
        'amount_pence' => 'integer',
        'frequency' => SubscriptionFrequency::class,
        'status' => SubscriptionStatus::class,
        'cancel_at_period_end' => 'boolean',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'started_at' => 'datetime',
        'canceled_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    public function version()
    {
        return $this->belongsTo(AgreementVersion::class, 'version_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'agreement_id', 'agreement_id')
            ->where('type', 'subscription');
    }

    public function isActive(): bool
    {
        return in_array($this->status, [
            SubscriptionStatus::Active,
            SubscriptionStatus::Trialing,
            SubscriptionStatus::PastDue,
        ], true);
    }

    public function formattedAmount(): string
    {
        return Money::format($this->amount_pence);
    }
}
