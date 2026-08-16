<?php

namespace App\Models;

use App\Enums\QueryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Query extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'query',
        'status',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'status' => QueryStatus::class,
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(QueryMessage::class);
    }

    public function attachments()
    {
        return $this->hasMany(QueryAttachment::class);
    }

    public function markRead(): void
    {
        if (! $this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }
}
