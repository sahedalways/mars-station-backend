<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'version',
        'title',
        'client_name',
        'client_email',
        'client_mobile',
        'validity_date',
        'content',
        'payment_config',
        'status',
        'signed_name',
        'signed_email',
        'signed_at',
        'signed_ip_address',
        'signed_user_agent',
        'signed_pdf_path',
        'admin_id',
    ];

    protected $casts = [
        'validity_date' => 'date',
        'payment_config' => 'array',
        'signed_at' => 'datetime',
    ];

    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function attachments()
    {
        return $this->hasMany(AgreementAttachment::class, 'version_id');
    }

    public function links()
    {
        return $this->hasMany(AgreementLink::class, 'version_id');
    }

    public function milestones()
    {
        return $this->hasMany(AgreementMilestone::class, 'version_id');
    }

    public function isSigned(): bool
    {
        return $this->status === 'signed';
    }

    public function label(): string
    {
        return 'V'.$this->version;
    }
}
