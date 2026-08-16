<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'to_email',
        'subject',
        'mailable',
        'event_type',
        'emailable_type',
        'emailable_id',
        'status',
        'error',
        'sent_at',
        'admin_id',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function emailable()
    {
        return $this->morphTo();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
