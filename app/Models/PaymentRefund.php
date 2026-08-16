<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'stripe_refund_id',
        'amount_pence',
        'currency',
        'status',
        'reason',
        'note',
        'admin_id',
        'otp_request_id',
        'processed_at',
    ];

    protected $casts = [
        'amount_pence' => 'integer',
        'processed_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'succeeded';
    }

    public function formattedAmount(): string
    {
        return Money::format($this->amount_pence, $this->currency);
    }
}
