<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'query_id',
        'message_id',
        'storage_path',
        'original_name',
        'mime_type',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function query()
    {
        return $this->belongsTo(Query::class);
    }

    public function message()
    {
        return $this->belongsTo(QueryMessage::class, 'message_id');
    }
}
