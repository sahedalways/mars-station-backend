<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'picture_path',
        'title',
        'type',
        'view_link',
        'order_index',
    ];

    protected $casts = [
        'order_index' => 'integer',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getThumbnailAttribute(): ?string
    {
        if (! $this->picture_path) {
            return null;
        }

        return Storage::disk('public')->url($this->picture_path);
    }
}
