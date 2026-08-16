<?php

namespace App\Services;

use App\Models\Agreement;
use App\Models\AgreementVersion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfService
{
    public function renderAgreementPdf(Agreement $agreement, AgreementVersion $version): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('pdf.agreement', [
            'agreement' => $agreement,
            'version' => $version,
        ])->setPaper('a4');
    }

    public function storeSignedPdf(Agreement $agreement, AgreementVersion $version): string
    {
        $pdf = $this->renderAgreementPdf($agreement, $version);

        $path = 'agreements/pdf/'
            .$agreement->agreement_number
            .'-V'.$version->version
            .'-'.Str::lower(Str::random(8))
            .'.pdf';

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    public function downloadAgreementPdf(Agreement $agreement, AgreementVersion $version)
    {
        return response()->streamDownload(
            fn () => print($this->renderAgreementPdf($agreement, $version)->output()),
            $agreement->agreement_number.'-V'.$version->version.'.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    public function streamSignedPdf(Agreement $agreement, AgreementVersion $version)
    {
        if (! $version->signed_pdf_path || ! Storage::disk('local')->exists($version->signed_pdf_path)) {
            abort(404, 'Signed PDF not found.');
        }

        return response()->streamDownload(
            fn () => print(Storage::disk('local')->get($version->signed_pdf_path)),
            $agreement->agreement_number.'-V'.$version->version.'-signed.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
