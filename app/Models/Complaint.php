<?php

namespace App\Models;

use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'description',
        'status',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'status' => ComplaintStatus::class,
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(ComplaintMessage::class);
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    public function markRead(): void
    {
        if (! $this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }
}
