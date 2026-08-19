<?php

namespace App\Services;

use App\Models\Agreement;
use App\Models\AgreementVersion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class PdfService
{
    private string $chromePath = '/usr/bin/google-chrome-stable';
    private string $nodePath = '/home/sahed-ahmed/.nvm/versions/node/v24.19.0/bin/node';

    public function renderAgreementPdf(Agreement $agreement, AgreementVersion $version): string
    {
        $html = view('pdf.agreement-browser', [
            'agreement' => $agreement,
            'version' => $version,
        ])->render();

        return Browsershot::html($html)
            ->setChromePath($this->chromePath)
            ->setNodeBinary($this->nodePath)
            ->width(794)
            ->height(1123)
            ->margins(0, 0, 0, 0)
            ->printBackground()
            ->pdf();
    }

    public function storeSignedPdf(Agreement $agreement, AgreementVersion $version): string
    {
        $pdfContent = $this->renderAgreementPdf($agreement, $version);

        $path = 'agreements/pdf/'
            .$agreement->agreement_number
            .'-V'.$version->version
            .'-'.Str::lower(Str::random(8))
            .'.pdf';

        Storage::disk('local')->put($path, $pdfContent);

        return $path;
    }

    public function downloadAgreementPdf(Agreement $agreement, AgreementVersion $version)
    {
        $pdfContent = $this->renderAgreementPdf($agreement, $version);

        return response()->streamDownload(
            fn () => print($pdfContent),
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
