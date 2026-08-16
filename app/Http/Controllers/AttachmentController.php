<?php

namespace App\Http\Controllers;

use App\Models\AgreementAttachment;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(AgreementAttachment $attachment)
    {
        abort_unless(Storage::disk('local')->exists($attachment->storage_path), 404);

        return Storage::disk('local')->download(
            $attachment->storage_path,
            $attachment->original_name,
            ['Content-Type' => $attachment->mime_type]
        );
    }
}
