<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Services\Api\ComplaintApiService;
use Illuminate\Http\JsonResponse;

class ComplaintApiController extends Controller
{
    use ApiTraits;

    public function __construct(
        private readonly ComplaintApiService $service
    ) {}

    public function store(StoreComplaintRequest $request): JsonResponse
    {
        $complaint = $this->service->create($request->validated());

        return $this->success(
            new ComplaintResource($complaint),
            'Complaint submitted successfully',
            201
        );
    }

    public function index(): JsonResponse
    {
        $complaints = $this->service->getAll();

        return $this->paginated($complaints, 'Complaints retrieved');
    }

    public function show(int $id): JsonResponse
    {
        $complaint = $this->service->getById($id);

        if (!$complaint) {
            return $this->error('Complaint not found', 404);
        }

        return $this->success(
            new ComplaintResource($complaint),
            'Complaint retrieved'
        );
    }
}
