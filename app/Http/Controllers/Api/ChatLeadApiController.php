<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreChatLeadRequest;
use App\Services\Api\ChatLeadApiService;
use Illuminate\Http\JsonResponse;

class ChatLeadApiController extends Controller
{
    use ApiTraits;

    public function __construct(
        private readonly ChatLeadApiService $service
    ) {}

    public function store(StoreChatLeadRequest $request): JsonResponse
    {
        $lead = $this->service->create($request->validated());

        return $this->success(
            ['id' => $lead->id],
            'Chat lead captured successfully',
            201
        );
    }
}
