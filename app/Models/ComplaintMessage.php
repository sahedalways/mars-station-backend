<?php

namespace App\Models;

use App\Enums\MessageSenderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'sender_type',
        'admin_id',
        'body',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'sender_type' => MessageSenderType::class,
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class, 'message_id');
    }
}
