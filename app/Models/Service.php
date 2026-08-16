<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'icon',
        'title',
        'type',
        'description',
        'order_index',
        'is_active',
    ];

    protected $casts = [
        'order_index' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bulletPoints()
    {
        return $this->hasMany(ServiceBulletPoint::class)->orderBy('order_index');
    }

    public function projects()
    {
        return $this->hasMany(ServiceProject::class)->orderBy('order_index');
    }
}
