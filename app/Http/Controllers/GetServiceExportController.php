<?php

namespace App\Http\Controllers;

use App\Enums\GetServiceStatus;
use App\Models\GetServiceRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetServiceExportController extends Controller
{
    public function export(): StreamedResponse
    {
        $requests = GetServiceRequest::query()->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="service-requests-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($requests) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Full Name', 'Email', 'Phone', 'Company', 'Contact Method', 'Services', 'Notes', 'Status', 'Date']);

            foreach ($requests as $r) {
                fputcsv($file, [
                    'GS-' . str_pad($r->id, 6, '0', STR_PAD_LEFT),
                    $r->full_name,
                    $r->email,
                    $r->phone,
                    $r->company,
                    ucfirst($r->preferred_contact),
                    implode(', ', $r->selected_services ?? []),
                    $r->additional_notes,
                    ucfirst($r->status->value),
                    $r->created_at->format('M d, Y h:i A'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
