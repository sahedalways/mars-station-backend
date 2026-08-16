<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Support\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'version_id',
        'type',
        'milestone_id',
        'stripe_payment_intent_id',
        'stripe_invoice_id',
        'stripe_subscription_id',
        'amount_pence',
        'currency',
        'status',
        'payment_method',
        'payment_method_brand',
        'payment_method_last4',
        'refunded_amount_pence',
        'paid_at',
        'failed_at',
        'metadata',
        'idempotency_key',
    ];

    protected $casts = [
        'type' => PaymentType::class,
        'status' => PaymentStatus::class,
        'amount_pence' => 'integer',
        'refunded_amount_pence' => 'integer',
        'paid_at' => 'datetime',
        'failed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    public function version()
    {
        return $this->belongsTo(AgreementVersion::class, 'version_id');
    }

    public function milestone()
    {
        return $this->belongsTo(AgreementMilestone::class);
    }

    public function refunds()
    {
        return $this->hasMany(PaymentRefund::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === PaymentStatus::Succeeded;
    }

    public function refundableAmountPence(): int
    {
        return max(0, $this->amount_pence - $this->refunded_amount_pence);
    }

    public function formattedAmount(): string
    {
        return Money::format($this->amount_pence, $this->currency);
    }

    public function formattedRefundedAmount(): string
    {
        return Money::format($this->refunded_amount_pence, $this->currency);
    }
}
