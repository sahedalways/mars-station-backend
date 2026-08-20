<?php

namespace App\Services\Api;

use App\Models\Review;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ReviewApiService
{
    public function create(array $data): Review
    {
        $review = Review::create([
            'name' => $data['name'],
            'position' => $data['position'] ?? null,
            'rating' => $data['rating'],
            'description' => $data['description'],
            'status' => 'pending',
        ]);

        if (!empty($data['dp']) && $data['dp'] instanceof UploadedFile) {
            $path = $data['dp']->store('reviews', 'public');
            $review->update(['dp_path' => $path]);
        }

        return $review;
    }

    public function getApproved(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Review::approved()->latest()->paginate($perPage);
    }

    public function getAll(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Review::latest()->paginate($perPage);
    }

    public function getById(int $id): ?Review
    {
        return Review::find($id);
    }
}
