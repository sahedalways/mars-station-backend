<?php

namespace App\Services\Api;

use App\Models\Query;

class QueryApiService
{
    public function create(array $data): Query
    {
        return Query::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'preferred_contact' => $data['preferred_contact'] ?? null,
            'selected_services' => $data['selected_services'] ?? null,
            'query' => $data['query'],
            'status' => 'new',
        ]);
    }

    public function getAll(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Query::latest()->paginate($perPage);
    }

    public function getById(int $id): ?Query
    {
        return Query::with('messages', 'attachments')->find($id);
    }
}
