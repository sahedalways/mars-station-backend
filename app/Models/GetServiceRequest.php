<?php

namespace App\Models;

use App\Enums\GetServiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GetServiceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'company',
        'preferred_contact',
        'selected_services',
        'additional_notes',
        'status',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'selected_services' => 'array',
        'status' => GetServiceStatus::class,
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function attachments()
    {
        return $this->hasMany(GetServiceAttachment::class);
    }

    public function markRead(): void
    {
        if (! $this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }
}
