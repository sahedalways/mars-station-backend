<?php

namespace App\Models;

use App\Enums\MilestoneStatus;
use App\Support\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementMilestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'version_id',
        'title',
        'description',
        'amount_pence',
        'order_index',
        'status',
        'paid_at',
        'payment_id',
    ];

    protected $casts = [
        'amount_pence' => 'integer',
        'order_index' => 'integer',
        'status' => MilestoneStatus::class,
        'paid_at' => 'datetime',
    ];

    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    public function version()
    {
        return $this->belongsTo(AgreementVersion::class, 'version_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function isPaid(): bool
    {
        return $this->status === MilestoneStatus::Paid;
    }

    public function formattedAmount(): string
    {
        return Money::format($this->amount_pence);
    }

    public function isUnlocked(): bool
    {
        if ($this->isPaid()) {
            return false;
        }

        $previous = $this->agreement->milestones()
            ->where('order_index', '<', $this->order_index)
            ->where('status', 'pending')
            ->exists();

        return ! $previous;
    }
}
