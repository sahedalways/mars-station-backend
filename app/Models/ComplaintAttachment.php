<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'message_id',
        'storage_path',
        'original_name',
        'mime_type',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function message()
    {
        return $this->belongsTo(ComplaintMessage::class, 'message_id');
    }
}
