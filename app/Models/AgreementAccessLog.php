<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementAccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'link_id',
        'type',
        'email',
        'status',
        'ip_address',
        'user_agent',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    public function link()
    {
        return $this->belongsTo(AgreementLink::class);
    }
}
