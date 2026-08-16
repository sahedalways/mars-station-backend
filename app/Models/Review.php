<?php

namespace App\Models;

use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dp_path',
        'name',
        'position',
        'rating',
        'description',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'status' => ReviewStatus::class,
    ];

    public function isApproved(): bool
    {
        return $this->status === ReviewStatus::Approved;
    }

    public function scopeApproved($query)
    {
        return $query->where('status', ReviewStatus::Approved->value);
    }
}
