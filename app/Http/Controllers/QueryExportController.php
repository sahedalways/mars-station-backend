<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QueryExportController extends Controller
{
    public function export(): StreamedResponse
    {
        $queries = Query::query()->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="queries-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($queries) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Full Name', 'Email', 'Phone', 'Preferred Contact', 'Selected Services', 'Query', 'Status', 'Date']);

            foreach ($queries as $q) {
                fputcsv($file, [
                    'QRY-' . str_pad($q->id, 6, '0', STR_PAD_LEFT),
                    $q->full_name,
                    $q->email,
                    $q->phone,
                    ucfirst($q->preferred_contact ?? ''),
                    implode(', ', $q->selected_services ?? []),
                    $q->query,
                    ucfirst(str_replace('_', ' ', $q->status->value)),
                    $q->created_at->format('M d, Y h:i A'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
