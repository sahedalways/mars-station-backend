<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreGetServiceRequest;
use App\Http\Resources\GetServiceResource;
use App\Services\Api\GetServiceApiService;
use Illuminate\Http\JsonResponse;

class GetServiceApiController extends Controller
{
    use ApiTraits;

    public function __construct(
        private readonly GetServiceApiService $service
    ) {}

    public function store(StoreGetServiceRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('attachments')) {
            $data['attachments'] = $request->file('attachments');
        }

        $getService = $this->service->create($data);

        return $this->success(
            new GetServiceResource($getService->load('attachments')),
            'Service request submitted successfully',
            201
        );
    }

    public function index(): JsonResponse
    {
        $requests = $this->service->getAll();

        return $this->paginated($requests, 'Get service requests retrieved');
    }

    public function show(int $id): JsonResponse
    {
        $request = $this->service->getById($id);

        if (!$request) {
            return $this->error('Request not found', 404);
        }

        return $this->success(
            new GetServiceResource($request),
            'Request retrieved'
        );
    }
}
