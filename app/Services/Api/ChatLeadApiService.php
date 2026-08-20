<?php

namespace App\Services\Api;

use App\Models\ChatLead;

class ChatLeadApiService
{
    public function create(array $data): ChatLead
    {
        return ChatLead::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'agent' => $data['agent'] ?? null,
        ]);
    }
}
