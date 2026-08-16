<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'version_id',
        'token',
        'is_active',
        'otp_enabled',
        'disabled_at',
        'regenerated_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'otp_enabled' => 'boolean',
        'disabled_at' => 'datetime',
        'regenerated_at' => 'datetime',
    ];

    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    public function version()
    {
        return $this->belongsTo(AgreementVersion::class);
    }

    public function accessLogs()
    {
        return $this->hasMany(AgreementAccessLog::class);
    }

    public function disable(): void
    {
        $this->update([
            'is_active' => false,
            'disabled_at' => now(),
        ]);
    }

    public function publicUrl(): string
    {
        return route('agreement.view', ['token' => $this->token]);
    }
}
