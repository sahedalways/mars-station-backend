<?php

namespace App\Services\Api;

use App\Models\GetServiceRequest;
use App\Models\GetServiceAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GetServiceApiService
{
    public function create(array $data): GetServiceRequest
    {
        return DB::transaction(function () use ($data) {
            $request = GetServiceRequest::create([
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'company' => $data['company'] ?? null,
                'preferred_contact' => $data['preferred_contact'] ?? 'email',
                'selected_services' => $data['selected_services'] ?? null,
                'additional_notes' => $data['additional_notes'] ?? null,
                'status' => 'new',
            ]);

            if (!empty($data['attachments']) && is_array($data['attachments'])) {
                foreach ($data['attachments'] as $file) {
                    $this->storeAttachment($request, $file);
                }
            }

            return $request;
        });
    }

    public function getAll(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return GetServiceRequest::latest()->paginate($perPage);
    }

    public function getById(int $id): ?GetServiceRequest
    {
        return GetServiceRequest::with('attachments')->find($id);
    }

    private function storeAttachment(GetServiceRequest $request, UploadedFile $file): void
    {
        $path = $file->store('get-service-attachments', 'public');

        GetServiceAttachment::create([
            'get_service_request_id' => $request->id,
            'storage_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
        ]);
    }
}
