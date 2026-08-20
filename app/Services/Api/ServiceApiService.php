<?php

namespace App\Services\Api;

use App\Models\Service;

class ServiceApiService
{
    public function getActive(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Service::where('is_active', true)
            ->orderBy('order_index')
            ->with(['bulletPoints', 'projects'])
            ->paginate($perPage);
    }

    public function getAll(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Service::orderBy('order_index')
            ->with(['bulletPoints', 'projects'])
            ->paginate($perPage);
    }

    public function getById(int $id): ?Service
    {
        return Service::with(['bulletPoints', 'projects'])->find($id);
    }

    public function getActiveFlat(): \Illuminate\Support\Collection
    {
        return Service::where('is_active', true)
            ->orderBy('order_index')
            ->get()
            ->map(fn (Service $service) => [
                'id' => $service->id,
                'title' => $service->title,
                'type' => $service->type,
                'icon' => $service->icon,
                'description' => $service->description,
            ]);
    }
}
