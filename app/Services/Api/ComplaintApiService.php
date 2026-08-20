<?php

namespace App\Services\Api;

use App\Models\Complaint;

class ComplaintApiService
{
    public function create(array $data): Complaint
    {
        return Complaint::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'description' => $data['description'],
            'status' => 'new',
        ]);
    }

    public function getAll(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Complaint::latest()->paginate($perPage);
    }

    public function getById(int $id): ?Complaint
    {
        return Complaint::with('messages', 'attachments')->find($id);
    }
}
