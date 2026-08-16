<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementOtpSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'link_id',
        'email',
        'otp_hash',
        'expires_at',
        'consumed_at',
        'verified_at',
        'session_expires_at',
        'attempts',
        'max_attempts',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
        'verified_at' => 'datetime',
        'session_expires_at' => 'datetime',
        'attempts' => 'integer',
        'max_attempts' => 'integer',
    ];

    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    public function link()
    {
        return $this->belongsTo(AgreementLink::class);
    }

    public function isValid(): bool
    {
        return $this->consumed_at === null
            && $this->expires_at->isFuture()
            && $this->attempts < $this->max_attempts;
    }

    public function grantsAccess(): bool
    {
        return $this->verified_at !== null
            && $this->session_expires_at !== null
            && $this->session_expires_at->isFuture();
    }
}
