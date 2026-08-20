<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'icon' => $this->icon,
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
            'order_index' => $this->order_index,
            'is_active' => $this->is_active,
            'bullet_points' => ServiceBulletPointResource::collection($this->whenLoaded('bulletPoints')),
            'projects' => ServiceProjectResource::collection($this->whenLoaded('projects')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
