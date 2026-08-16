<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceBulletPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'text',
        'order_index',
    ];

    protected $casts = [
        'order_index' => 'integer',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
