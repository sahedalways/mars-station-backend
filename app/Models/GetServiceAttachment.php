<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetServiceAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'get_service_request_id',
        'storage_path',
        'original_name',
        'mime_type',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function request()
    {
        return $this->belongsTo(GetServiceRequest::class, 'get_service_request_id');
    }
}
