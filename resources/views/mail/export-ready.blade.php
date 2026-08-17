<x-mail.layout>
    <x-slot:title>Your export is ready</x-slot:title>

    {{-- Download Icon Header --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 72px; height: 72px; background: #dcfce7; border-radius: 50%; text-align: center; line-height: 72px;">
            <span style="display: inline-block; color: #16a34a; font-size: 32px; line-height: 72px; font-weight: 700;">⬇</span>
        </div>
    </div>

    {{-- Title Section --}}
    <h1 style="margin: 0 0 8px; color: #111827; font-size: 26px; line-height: 32px; font-weight: 700; text-align: center;">
        Your Export is <span style="color: #16a34a;">Ready!</span>
    </h1>
    <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 24px; text-align: center;">
        Your payment export has been generated successfully.
    </p>
    <div style="width: 60px; height: 2px; background: #a78bfa; margin: 0 auto 32px;"></div>

    {{-- Greeting --}}
    <p style="margin: 0 0 12px; color: #111827; font-size: 14px; line-height: 22px; font-weight: 600;">
        Hello {{ $user->name ?? 'Admin' }},
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        The payment export you requested has been processed and is now available.
    </p>
    <p style="margin: 0 0 24px; color: #374151; font-size: 14px; line-height: 22px;">
        You can download the file from the admin panel using the link below.
    </p>

    {{-- File Info Box --}}
    <div style="background: #f5f3ff; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="48" style="vertical-align: middle;">
                    <div style="width: 40px; height: 40px; background: #ede9fe; border-radius: 8px; text-align: center; line-height: 40px;">
                        <span style="color: #7c3aed; font-size: 18px;">📊</span>
                    </div>
                </td>
                <td style="vertical-align: middle; padding-left: 12px;">
                    <p style="margin: 0 0 4px; color: #7c3aed; font-size: 14px; font-weight: 700;">
                        Your export file is ready for download.
                    </p>
                    <p style="margin: 0; color: #6b7280; font-size: 12px; line-height: 18px; word-break: break-all;">
                        File: {{ basename($filePath) }}
                        @if(isset($fileSize))
                            ({{ $fileSize }})
                        @endif
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Export Summary Box --}}
    <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <h3 style="margin: 0 0 16px; color: #16a34a; font-size: 14px; font-weight: 700;">
            Export Summary
        </h3>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Export Type</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $exportType ?? 'Payment Report' }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Generated On</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ now()->format('M d, Y h:i A') }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Status</p>
                    <p style="margin: 4px 0 0;">
                        <span style="display: inline-block; padding: 3px 12px; background: #dcfce7; color: #16a34a; border-radius: 6px; font-size: 12px; font-weight: 600;">
                            Ready
                        </span>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">File Format</p>
                    <p style="margin: 2px 0 0; color: #16a34a; font-size: 13px; font-weight: 600; text-transform: uppercase;">
                        {{ pathinfo($filePath, PATHINFO_EXTENSION) ?: 'CSV' }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Total Records</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ number_format($totalRecords ?? 0) }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Available Until</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ ($expiresAt ?? now()->addDays(7))->format('M d, Y') }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Info Box --}}
    <div style="background: #eff6ff; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #dbeafe; border-radius: 50%; text-align: center; line-height: 24px; color: #2563eb; font-weight: 700; font-style: italic;">i</span>
                </td>
                <td style="vertical-align: top;">
                    <h3 style="margin: 0 0 6px; color: #2563eb; font-size: 14px; font-weight: 700;">
                        Important Note
                    </h3>
                    <p style="margin: 0 0 2px; color: #374151; font-size: 13px; line-height: 20px;">
                        This export file will be available for download for the next 7 days.
                    </p>
                    <p style="margin: 0; color: #374151; font-size: 13px; line-height: 20px;">
                        After that, it will be automatically removed from the server.
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- CTA Button --}}
    <div style="text-align: center; margin-bottom: 12px;">
        <a href="{{ $downloadUrl ?? url('/admin/exports') }}"
           style="display: inline-block; padding: 16px 48px; background: #15803d; color: #ffffff; text-decoration: none; border-radius: 999px; font-size: 15px; font-weight: 600;">
            ⬇ Download Export File
        </a>
    </div>
    <p style="margin: 0 0 24px; color: #6b7280; font-size: 13px; line-height: 20px; text-align: center;">
        Click the button above to download your export from the admin panel.
    </p>

    {{-- Fallback Link Box --}}
    <div style="background: #f0fdf4; border-radius: 10px; padding: 16px; margin-bottom: 16px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #dcfce7; border-radius: 50%; text-align: center; line-height: 24px; color: #15803d;">🌐</span>
                </td>
                <td style="vertical-align: top;">
                    <p style="margin: 0 0 4px; color: #374151; font-size: 13px;">
                        If the button doesn't work, copy and paste this link into your browser:
                    </p>
                    <a href="{{ $downloadUrl ?? url('/admin/exports') }}" style="color: #15803d; font-size: 12px; word-break: break-all; text-decoration: underline;">
                        {{ $downloadUrl ?? url('/admin/exports') }}
                    </a>
                </td>
            </tr>
        </table>
    </div>

    {{-- Need Help Box --}}
    <div style="background: #f0fdf4; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="60%" style="vertical-align: top;">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="32" style="vertical-align: top;">
                                <span style="display: inline-block; width: 24px; height: 24px; background: #dcfce7; border-radius: 50%; text-align: center; line-height: 24px; color: #15803d;">🎧</span>
                            </td>
                            <td style="vertical-align: top;">
                                <p style="margin: 0; color: #111827; font-size: 13px; font-weight: 600;">Need Help?</p>
                                <p style="margin: 2px 0 0; color: #6b7280; font-size: 12px; line-height: 18px;">
                                    If you have any trouble downloading the file, we're here to help.
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="40%" style="vertical-align: top; text-align: right;">
                    <p style="margin: 0;">
                        <a href="mailto:support@marsstation.dev" style="color: #15803d; font-size: 12px; text-decoration: underline;">
                            ✉ support@marsstation.dev
                        </a>
                    </p>
                    <p style="margin: 4px 0 0;">
                        <a href="https://marsstation.dev" style="color: #15803d; font-size: 12px; text-decoration: underline;">
                            🌐 marsstation.dev
                        </a>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Footer Message --}}
    <div style="text-align: center; padding: 8px 0;">
        <p style="margin: 0 0 4px; color: #6b7280; font-size: 13px; line-height: 20px;">
            This is an automated notification from {{ config('app.name', 'Mars Station') }}.
        </p>
        <p style="margin: 0; color: #111827; font-size: 14px; line-height: 20px; font-weight: 700;">
            Thank you for using our platform!
        </p>
    </div>
</x-mail.layout>
