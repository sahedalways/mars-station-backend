<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Services\Api\ServiceApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceApiController extends Controller
{
    use ApiTraits;

    public function __construct(
        private readonly ServiceApiService $service
    ) {}

    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $perPage = min((int) $request->input('per_page', 15), 50);

        $services = $this->service->getAll($perPage);

        return ServiceResource::collection($services);
    }

    public function active(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $perPage = min((int) $request->input('per_page', 15), 50);

        $services = $this->service->getActive($perPage);

        return ServiceResource::collection($services);
    }

    public function activeFlat(): JsonResponse
    {
        return $this->success(
            $this->service->getActiveFlat(),
            'Active services retrieved'
        );
    }

    public function show(int $id): JsonResponse
    {
        $service = $this->service->getById($id);

        if (!$service) {
            return $this->error('Service not found', 404);
        }

        return $this->success(
            new ServiceResource($service),
            'Service retrieved'
        );
    }
}
