<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $agreement->agreement_number }} - V{{ $version->version }}</title>
    <style>
        @page { margin: 0; size: A4; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif; color: #1f2937; font-size: 12px; line-height: 1.6; margin: 0; padding: 0; background: #fff; }
        img { border: 0; height: auto; }

        /* Header */
        .pw-header { padding: 22px 28px 20px; border-bottom: 3px solid #4c1d95; background: #f9f7ff; }
        .pw-header-table { width: 100%; border-collapse: collapse; }
        .pw-brand { vertical-align: middle; width: 200px; }
        .pw-brand img { max-width: 180px; height: auto; }
        .pw-meta { vertical-align: middle; }
        .pw-meta-table { width: 100%; border-collapse: collapse; }
        .pw-meta-table td { padding: 4px 8px 4px 0; vertical-align: top; }
        .pw-meta-label { font-size: 8px; color: #6b7280; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; }
        .pw-meta-value { font-size: 11px; color: #111827; font-weight: 700; }
        .pw-status { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 10px; font-weight: 700; letter-spacing: 0.3px; }
        .pw-status-signed { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .pw-status-pending { background: #fef9c3; color: #854d0e; border: 1px solid #fcd34d; }
        .pw-status-default { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }

        /* Body */
        .pw-body { padding: 24px 28px 0; }
        .pw-section-label { font-size: 13px; letter-spacing: 2px; text-transform: uppercase; color: #4c1d95; font-weight: 800; border-left: 4px solid #4c1d95; padding-left: 12px; margin: 0 0 12px; }
        .pw-intro { font-size: 12px; line-height: 1.75; color: #374151; margin: 0 0 6px; }
        .pw-sub-intro { font-size: 11px; color: #6b7280; font-style: italic; margin: 0 0 18px; }
        .pw-block { margin-bottom: 18px; }
        .pw-block h3 { font-size: 12px; color: #4c1d95; font-weight: 800; margin: 0 0 8px; letter-spacing: 0.3px; }
        .pw-num { display: inline-block; width: 22px; height: 22px; background: #7c3aed; color: #fff; border-radius: 50%; font-size: 10px; font-weight: 800; text-align: center; line-height: 22px; margin-right: 8px; }
        .pw-block p { font-size: 12px; line-height: 1.7; color: #374151; margin: 0 0 4px; }
        .pw-content-area { font-size: 12px; line-height: 1.7; color: #000000; }
        .pw-content-area * { color: #000000 !important; }

        /* Acceptance */
        .pw-acceptance { background: #f5f3ff; border: 2px solid #c4b5fd; border-radius: 14px; padding: 22px 26px; margin: 24px 28px 0; }
        .pw-acceptance h4 { font-size: 13px; color: #1e1b4b; margin: 0 0 12px; font-weight: 800; letter-spacing: 0.5px; }
        .pw-sig-table { width: 100%; border-collapse: collapse; }
        .pw-sig-table td { padding: 4px 12px 4px 0; vertical-align: top; width: 50%; }
        .pw-sig-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; font-weight: 700; margin-bottom: 2px; }
        .pw-sig-value { font-size: 12px; color: #111827; font-weight: 700; }
        .pw-sig-note { margin-top: 14px; padding-top: 12px; border-top: 1px dashed #c4b5fd; font-size: 10px; color: #4c1d95; font-weight: 600; }

        /* Footer */
        .pw-footer { background: #1e1b4b; color: #fff; margin-top: 28px; }
        .pw-footer-inner { padding: 28px 28px 0; text-align: center; }
        .pw-footer-logo { margin-bottom: 16px; text-align: center; }
        .pw-footer-logo img { height: 36px; width: auto; }
        .pw-footer-divider { height: 1px; margin: 0 0 14px; background: rgba(167,139,250,0.25); }
        .pw-footer-strip { text-align: center; font-size: 10px; color: #c4b5fd; margin-bottom: 14px; }
        .pw-footer-strip strong { color: #e9d5ff; font-weight: 700; margin: 0 12px; }
        .pw-footer-disclaimer { margin: 0 0 16px; padding: 12px 16px; background: rgba(255,255,255,0.03); border-radius: 6px; font-size: 9px; color: #8b7fc7; line-height: 1.65; text-align: center; }
        .pw-footer-bottom { padding: 14px 28px; background: rgba(0,0,0,0.25); font-size: 9px; color: #7c6daa; text-align: center; }
        .pw-footer-links { margin-top: 4px; }
        .pw-footer-links span { margin: 0 10px; color: #a78bfa; }
    </style>
</head>
<body>
    @if ($version)
        <div class="pw-header">
            <table class="pw-header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="pw-brand">
                        <img src="{{ public_path('logo.png') }}" alt="Mars Station">
                    </td>
                    <td class="pw-meta">
                        <table class="pw-meta-table" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="width:50%;">
                                    <div class="pw-meta-label">Agreement No.</div>
                                    <div class="pw-meta-value">{{ $agreement->agreement_number }}</div>
                                </td>
                                <td style="width:50%;">
                                    <div class="pw-meta-label">Agreement Date</div>
                                    <div class="pw-meta-value">{{ $agreement->created_at->format('M d, Y') }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="pw-meta-label">Client Name</div>
                                    <div class="pw-meta-value">{{ $agreement->client_name }}</div>
                                </td>
                                <td>
                                    <div class="pw-meta-label">Client Email</div>
                                    <div class="pw-meta-value">{{ $agreement->client_email }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="pw-meta-label">Last Updated</div>
                                    <div class="pw-meta-value">{{ $agreement->updated_at->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <div class="pw-meta-label">Document Status</div>
                                    <div class="pw-meta-value">
                                        @php
                                            $statusVal = $agreement->status->value ?? 'pending';
                                            $statusClass = match($statusVal) {
                                                'signed' => 'pw-status-signed',
                                                'pending' => 'pw-status-pending',
                                                default => 'pw-status-default',
                                            };
                                        @endphp
                                        <span class="pw-status {{ $statusClass }}">{{ $agreement->status->label() }}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="pw-body">
            <h2 class="pw-section-label">AGREEMENT</h2>
            <p class="pw-intro">
                This Agreement is made and entered into on <strong>{{ $agreement->created_at->format('F d, Y') }}</strong>, by and between <strong>Mars Station (the "Company")</strong> and <strong>{{ $agreement->client_name }} (the "Client")</strong>.
            </p>
            <p class="pw-sub-intro">Both parties agree to the terms and conditions outlined below.</p>

            <div class="pw-block">
                <h3><span class="pw-num">1</span> SERVICES</h3>
                <p>The Company agrees to provide the services described in this Agreement (the "Services") to the Client in accordance with the terms and conditions set forth herein.</p>
            </div>

            <div class="pw-block">
                <h3><span class="pw-num">2</span> SCOPE OF WORK</h3>
                <p>The Company will work with the goal of delivering high-quality results. The specific deliverables, timelines, and milestones are outlined below.</p>
            </div>

            <div class="pw-block">
                <h3><span class="pw-num">3</span> SERVICES &amp; DELIVERABLES</h3>
                @if ($version->content)
                    <div class="pw-content-area" style="margin-top: 8px;">{!! $version->content !!}</div>
                @else
                    <p>No services or deliverables defined yet.</p>
                @endif
            </div>

            @if ($agreement->amountTotalPence() > 0)
                <div class="pw-block">
                    <h3><span class="pw-num">4</span> PAYMENT TERMS</h3>
                    <p>Payment terms and schedule are outlined in the payment plan associated with this Agreement. All payments are non-refundable except as stated in our Payment &amp; Refund Policy.</p>
                    <p style="margin-top: 6px; font-weight: 700; color: #4c1d95;">Total: {{ $agreement->formatted_amount }} ({{ $agreement->payment_type->label() }})</p>
                </div>
            @endif
        </div>

        <div class="pw-acceptance">
            <h4>CLIENT ACCEPTANCE</h4>
            <table class="pw-sig-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div class="pw-sig-label">Client Name</div>
                        <div class="pw-sig-value">{{ $agreement->client_name }}</div>
                    </td>
                    <td>
                        <div class="pw-sig-label">Client Email</div>
                        <div class="pw-sig-value">{{ $agreement->client_email }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="pw-sig-label">Signed on</div>
                        <div class="pw-sig-value">{{ $version->signed_at?->format('M d, Y') ?? 'Pending' }}</div>
                    </td>
                    <td>
                        <div class="pw-sig-label">Valid Until</div>
                        <div class="pw-sig-value">{{ $agreement->validity_date?->format('M d, Y') ?? 'Not set' }}</div>
                    </td>
                </tr>
            </table>
            <div class="pw-sig-note">
                This agreement is valid from {{ $agreement->created_at->format('M d, Y') }}@if ($agreement->validity_date) to {{ $agreement->validity_date->format('M d, Y') }}@endif.
            </div>
        </div>

        <div class="pw-footer">
            <div class="pw-footer-inner">
                <div class="pw-footer-logo">
                    <img src="{{ public_path('logo.png') }}" alt="Mars Station">
                </div>
                <div class="pw-footer-divider"></div>
                <div class="pw-footer-strip">
                    <strong>Agreement No.</strong> {{ $agreement->agreement_number }}
                    <strong>Version</strong> v{{ $version->version }}
                    <strong>Date</strong> {{ $agreement->created_at->format('M d, Y') }}
                    <strong>Status</strong> {{ $agreement->status->label() }}
                    <strong>Page</strong> 1 of 1
                </div>
                <div class="pw-footer-disclaimer">
                    This agreement is an official electronic document issued by Mars Station. Any unauthorized modification, or alteration may invalidate the document, please refer the official agreement available through the secure Mars Station link for verification.
                </div>
            </div>
            <div class="pw-footer-bottom">
                &copy; {{ now()->year }} Mars Station. All rights reserved. Registered in United Kingdom.
                <div class="pw-footer-links">
                    <span>Privacy</span>
                    <span>Terms</span>
                    <span>Contact</span>
                </div>
            </div>
        </div>
    @else
        <div style="padding: 60px; text-align: center; color: #6b7280;">
            <p>No version available for this agreement.</p>
        </div>
    @endif
</body>
</html>
