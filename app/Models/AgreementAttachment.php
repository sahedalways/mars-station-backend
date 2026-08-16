<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'version_id',
        'storage_path',
        'original_name',
        'mime_type',
        'size_bytes',
        'uploaded_by',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    public function version()
    {
        return $this->belongsTo(AgreementVersion::class);
    }

    public function uploader()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }
}
