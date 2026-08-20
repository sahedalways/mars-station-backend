<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreQueryRequest;
use App\Http\Resources\QueryResource;
use App\Services\Api\QueryApiService;
use Illuminate\Http\JsonResponse;

class QueryApiController extends Controller
{
    use ApiTraits;

    public function __construct(
        private readonly QueryApiService $service
    ) {}

    public function store(StoreQueryRequest $request): JsonResponse
    {
        $query = $this->service->create($request->validated());

        return $this->success(
            new QueryResource($query),
            'Query submitted successfully',
            201
        );
    }

    public function index(): JsonResponse
    {
        $queries = $this->service->getAll();

        return $this->paginated($queries, 'Queries retrieved');
    }

    public function show(int $id): JsonResponse
    {
        $query = $this->service->getById($id);

        if (!$query) {
            return $this->error('Query not found', 404);
        }

        return $this->success(
            new QueryResource($query),
            'Query retrieved'
        );
    }
}
