<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $agreement->agreement_number }} - V{{ $version->version }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1f2937; font-size: 12px; line-height: 1.6; margin: 0; padding: 40px; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 20px; margin-bottom: 28px; display: flex; justify-content: space-between; align-items: flex-start; }
        .brand { font-size: 22px; font-weight: 700; color: #111827; }
        .brand span { color: #4f46e5; }
        .meta { text-align: right; font-size: 11px; color: #6b7280; }
        .meta strong { color: #111827; }
        h2 { font-size: 16px; color: #111827; margin: 28px 0 12px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb; }
        h3 { font-size: 13px; color: #374151; margin: 16px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin: 8px 0; }
        th, td { text-align: left; padding: 8px 10px; border: 1px solid #e5e7eb; font-size: 11px; }
        th { background: #f3f4f6; font-weight: 600; color: #374151; }
        .signature { margin-top: 48px; }
        .signature-box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 16px; }
        .signature-row { display: flex; justify-content: space-between; gap: 24px; }
        .signature-col { flex: 1; }
        .signature-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .signature-name { font-size: 16px; font-weight: 700; color: #111827; margin: 6px 0; }
        .footer { margin-top: 48px; padding-top: 16px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #9ca3af; text-align: center; }
        .content { white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="brand">{{ config('app.name') }}<span>.</span></div>
            <div style="font-size: 11px; color: #6b7280; margin-top: 4px;">Agreement No. <strong>{{ $agreement->agreement_number }}</strong></div>
        </div>
        <div class="meta">
            <div>Version: <strong>V{{ $version->version }}</strong></div>
            <div>Issued: <strong>{{ $version->created_at->format('d M Y') }}</strong></div>
            @if ($version->isSigned())
                <div>Signed: <strong>{{ $version->signed_at?->format('d M Y H:i') }}</strong></div>
            @endif
            <div>Status: <strong>{{ $version->isSigned() ? 'Signed' : 'Pending Signature' }}</strong></div>
        </div>
    </div>

    <h2>Client Information</h2>
    <table>
        <tr>
            <th style="width: 30%;">Full Name</th>
            <td>{{ $version->client_name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $version->client_email }}</td>
        </tr>
        @if ($version->client_mobile)
            <tr>
                <th>Mobile</th>
                <td>{{ $version->client_mobile }}</td>
            </tr>
        @endif
        @if ($version->validity_date)
            <tr>
                <th>Validity Date</th>
                <td>{{ $version->validity_date->format('d M Y') }}</td>
            </tr>
        @endif
    </table>

    <h2>{{ $version->title }}</h2>
    <div class="content">{{ $version->content }}</div>

    <h2>Payment</h2>
    @if ($agreement->payment_type->value === 'none')
        <p>No payment is required for this agreement.</p>
    @else
        <table>
            <tr>
                <th style="width: 30%;">Type</th>
                <td>{{ $agreement->payment_type->label() }}</td>
            </tr>
            @if ($agreement->payment_type->value === 'full' && isset($version->payment_config['amount_pence']))
                <tr>
                    <th>Title</th>
                    <td>{{ $version->payment_config['title'] ?? $version->title }}</td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td>{{ \App\Support\Money::format($version->payment_config['amount_pence']) }}</td>
                </tr>
            @endif
            @if ($agreement->payment_type->value === 'milestone' && isset($version->payment_config['milestones']))
                <tr>
                    <th>Milestones</th>
                    <td>
                        <table>
                            <tr>
                                <th style="width: 8%;">#</th>
                                <th style="width: 35%;">Title</th>
                                <th>Description</th>
                                <th style="width: 20%;">Amount</th>
                            </tr>
                            @foreach ($version->payment_config['milestones'] as $milestone)
                                <tr>
                                    <td>{{ $milestone['order_index'] }}</td>
                                    <td>{{ $milestone['title'] }}</td>
                                    <td>{{ $milestone['description'] ?? '' }}</td>
                                    <td>{{ \App\Support\Money::format($milestone['amount_pence']) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endif
            @if ($agreement->payment_type->value === 'subscription')
                <tr>
                    <th>Title</th>
                    <td>{{ $version->payment_config['title'] ?? $version->title }}</td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td>{{ \App\Support\Money::format($version->payment_config['amount_pence']) }}</td>
                </tr>
                <tr>
                    <th>Frequency</th>
                    <td>{{ ucfirst($version->payment_config['frequency'] ?? 'monthly') }}</td>
                </tr>
            @endif
        </table>
    @endif

    <div class="signature">
        <h2>Signature</h2>
        <div class="signature-box">
            @if ($version->isSigned())
                <div class="signature-row">
                    <div class="signature-col">
                        <div class="signature-label">Signed by</div>
                        <div class="signature-name">{{ $version->signed_name }}</div>
                    </div>
                    <div class="signature-col">
                        <div class="signature-label">Email</div>
                        <div style="font-size: 13px; color: #374151; margin-top: 8px;">{{ $version->signed_email }}</div>
                    </div>
                    <div class="signature-col">
                        <div class="signature-label">Date &amp; Time</div>
                        <div style="font-size: 13px; color: #374151; margin-top: 8px;">{{ $version->signed_at?->format('d M Y H:i') }}</div>
                    </div>
                </div>
            @else
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Awaiting signature.</p>
            @endif
        </div>
    </div>

    <div class="footer">
        This document was generated by {{ config('app.name') }}. Signed versions are immutable.
    </div>
</body>
</html>
