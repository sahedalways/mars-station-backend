<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ComplaintExportController extends Controller
{
    public function export(): StreamedResponse
    {
        $complaints = Complaint::query()->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="complaints-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($complaints) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Full Name', 'Email', 'Phone', 'Description', 'Status', 'Date']);

            foreach ($complaints as $c) {
                fputcsv($file, [
                    'CMP-' . str_pad($c->id, 6, '0', STR_PAD_LEFT),
                    $c->full_name,
                    $c->email,
                    $c->phone,
                    $c->description,
                    ucfirst(str_replace('_', ' ', $c->status->value)),
                    $c->created_at->format('M d, Y h:i A'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
