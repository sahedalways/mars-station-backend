<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'preferred_contact' => $this->preferred_contact,
            'selected_services' => $this->selected_services,
            'additional_notes' => $this->additional_notes,
            'status' => $this->status?->value,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
