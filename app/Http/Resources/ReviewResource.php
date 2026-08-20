<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'rating' => $this->rating,
            'description' => $this->description,
            'status' => $this->status?->value,
            'dp_path' => $this->dp_path ? asset('storage/' . $this->dp_path) : null,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
