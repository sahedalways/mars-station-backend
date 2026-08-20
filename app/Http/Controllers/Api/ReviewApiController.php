<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Services\Api\ReviewApiService;
use Illuminate\Http\JsonResponse;

class ReviewApiController extends Controller
{
    use ApiTraits;

    public function __construct(
        private readonly ReviewApiService $service
    ) {}

    public function store(StoreReviewRequest $request): JsonResponse
    {
        $review = $this->service->create($request->validated());

        return $this->success(
            new ReviewResource($review),
            'Review submitted successfully. It will appear after admin approval.',
            201
        );
    }

    public function approved(): JsonResponse
    {
        $reviews = $this->service->getApproved();

        return $this->paginated($reviews, 'Approved reviews retrieved');
    }

    public function index(): JsonResponse
    {
        $reviews = $this->service->getAll();

        return $this->paginated($reviews, 'Reviews retrieved');
    }

    public function show(int $id): JsonResponse
    {
        $review = $this->service->getById($id);

        if (!$review) {
            return $this->error('Review not found', 404);
        }

        return $this->success(
            new ReviewResource($review),
            'Review retrieved'
        );
    }
}
