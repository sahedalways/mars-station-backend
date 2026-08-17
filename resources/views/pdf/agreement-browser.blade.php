<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $agreement->agreement_number }} - V{{ $version->version }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page { size: A4; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', 'Helvetica', 'Arial', sans-serif; color: #1f2937; font-size: 12px; line-height: 1.6; margin: 0; padding: 0; background: #fff; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

        .doc-wrapper { position: relative; width: 794px; margin: 0 auto; background: #fff; border-top: 3px solid #7c3aed; }
        .doc-wrapper::before {
            content: ''; position: absolute; top: 18%; right: 2%; width: 360px; height: 360px;
            background: url('{{ public_path('watermark-logo.png') }}') no-repeat center center;
            background-size: contain; opacity: 0.04; pointer-events: none; z-index: 0;
        }
        .doc-wrapper > * { position: relative; z-index: 1; }

        .pw-header {
            padding: 22px 28px 20px; border-bottom: 3px solid #4c1d95;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            background: linear-gradient(180deg, #f9f7ff 0%, #ffffff 100%);
        }
        .pw-brand img { display: block; max-width: 280px; height: auto; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.05)); }
        .pw-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 18px; min-width: 280px; max-width: 380px; }
        .pw-meta-row { display: flex; gap: 10px; align-items: flex-start; }
        .pw-meta-icon {
            width: 28px; height: 28px; background: linear-gradient(135deg, #ede9fe, #ddd6fe); border-radius: 8px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #4c1d95;
            box-shadow: 0 1px 3px rgba(76,29,149,0.1);
        }
        .pw-meta-icon svg { width: 13px; height: 13px; }
        .pw-meta-label { font-size: 9px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px; }
        .pw-meta-value { font-size: 11px; color: #111827; font-weight: 600; }
        .pw-status { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 10px; font-weight: 700; letter-spacing: 0.3px; }
        .pw-status-signed { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #166534; border: 1px solid #86efac; box-shadow: 0 1px 3px rgba(22,101,52,0.1); }
        .pw-status-pending { background: linear-gradient(135deg, #fef9c3, #fde68a); color: #854d0e; border: 1px solid #fcd34d; box-shadow: 0 1px 3px rgba(133,77,14,0.1); }
        .pw-status-expired { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #fca5a5; box-shadow: 0 1px 3px rgba(153,27,27,0.1); }
        .pw-status-default { background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #374151; border: 1px solid #d1d5db; }

        .pw-body { padding: 24px 28px 0; }
        .pw-section-label { font-size: 12px; letter-spacing: 2px; text-transform: uppercase; color: #4c1d95; font-weight: 800; border-left: 4px solid #4c1d95; padding-left: 12px; margin: 24px 0 12px; font-family: 'Inter', sans-serif; }
        .pw-intro { font-size: 11px; line-height: 1.75; color: #374151; margin: 0 0 6px; }
        .pw-sub-intro { font-size: 10px; color: #6b7280; font-style: italic; margin: 0 0 16px; }
        .pw-block { margin-bottom: 16px; }
        .pw-block h3 { font-size: 11px; color: #4c1d95; font-weight: 800; margin: 0 0 6px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.3px; }
        .pw-block h3 .num {
            display: inline-flex; align-items: center; justify-content: center;
            width: 22px; height: 22px; background: linear-gradient(135deg, #7c3aed, #4c1d95); color: #fff;
            border-radius: 50%; font-size: 9px; font-weight: 800;
            box-shadow: 0 2px 6px rgba(76,29,149,0.25);
        }
        .pw-block p { font-size: 11px; line-height: 1.65; color: #374151; margin: 0 0 4px; }

        .pw-content-area { font-size: 11px; line-height: 1.7; color: #000000; }
        .pw-content-area * { color: #000000 !important; }
        .pw-content-area h1, .pw-content-area h2, .pw-content-area h3, .pw-content-area h4 { color: #000000 !important; margin: 12px 0 6px; font-weight: 700; }
        .pw-content-area strong, .pw-content-area b { font-weight: 700; }
        .pw-content-area ul, .pw-content-area ol { padding-left: 18px; margin: 6px 0; }
        .pw-content-area li { margin-bottom: 3px; }
        .pw-content-area p { margin: 0 0 6px; }
        .pw-content-area td, .pw-content-area th, .pw-content-area span, .pw-content-area a,
        .pw-content-area em, .pw-content-area i { color: #000000 !important; }

        .pw-acceptance {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #f5f3ff 100%);
            border: 2px solid #c4b5fd; border-radius: 14px; padding: 22px 26px;
            margin: 24px 28px 0; display: flex; gap: 20px; align-items: flex-start;
            box-shadow: 0 4px 16px rgba(76,29,149,0.08), inset 0 2px 12px rgba(76,29,149,0.04);
            position: relative; overflow: hidden;
        }
        .pw-acceptance::before {
            content: ''; position: absolute; top: 50%; right: 16px;
            transform: translateY(-50%); width: 160px; height: 160px;
            background: url('{{ public_path('watermark-logo.png') }}') no-repeat center center;
            background-size: contain; opacity: 0.07; pointer-events: none;
        }
        .pw-acceptance > * { position: relative; z-index: 1; }
        .pw-acceptance-icon {
            width: 44px; height: 44px; background: linear-gradient(135deg, #7c3aed, #4c1d95);
            border: none; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; color: #fff; box-shadow: 0 4px 12px rgba(76,29,149,0.25);
        }
        .pw-acceptance-icon svg { width: 20px; height: 20px; }
        .pw-acceptance h4 { font-size: 12px; color: #1e1b4b; margin: 0 0 10px; font-weight: 800; letter-spacing: 0.5px; }
        .pw-sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 20px; }
        .pw-sig-label { font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; font-weight: 600; margin-bottom: 2px; }
        .pw-sig-value { font-size: 11px; color: #111827; font-weight: 600; }
        .pw-sig-note {
            margin-top: 14px; padding-top: 12px; border-top: 1px dashed #c4b5fd;
            font-size: 10px; color: #4c1d95; font-weight: 600; display: flex; align-items: center; gap: 6px;
        }

        .pw-footer {
            background: linear-gradient(180deg, #1e1b4b 0%, #0f0524 100%); color: #fff;
            padding: 0; margin-top: 28px; position: relative; overflow: hidden;
        }
        .pw-footer::before {
            content: ''; position: absolute; top: -30px; right: -30px;
            width: 160px; height: 160px;
            background: url('{{ public_path('watermark-logo.png') }}') no-repeat center center;
            background-size: contain; opacity: 0.04; pointer-events: none;
        }
        .pw-footer > * { position: relative; z-index: 1; }
        .pw-footer-inner { padding: 28px 28px 0; }
        .pw-footer-logo { text-align: center; margin-bottom: 18px; }
        .pw-footer-logo img { height: 38px; width: auto; filter: brightness(0) invert(1); opacity: 0.85; }
        .pw-footer-divider { height: 1px; margin: 0 0 16px; background: linear-gradient(90deg, transparent, rgba(167,139,250,0.25), transparent); }
        .pw-footer-strip { display: flex; justify-content: center; gap: 24px; flex-wrap: wrap; font-size: 9.5px; color: #c4b5fd; }
        .pw-footer-strip strong { color: #e9d5ff; font-weight: 700; }
        .pw-footer-disclaimer { margin-top: 16px; padding: 12px 16px; background: rgba(255,255,255,0.03); border-radius: 6px; font-size: 8.5px; color: #8b7fc7; line-height: 1.65; text-align: center; }
        .pw-footer-bottom { margin-top: 16px; padding: 12px 28px; background: rgba(0,0,0,0.25); display: flex; justify-content: space-between; align-items: center; font-size: 8.5px; color: #7c6daa; }
        .pw-footer-bottom a { color: #a78bfa; text-decoration: none; }
        .pw-footer-links { display: flex; gap: 16px; align-items: center; }
        .pw-footer-social { display: flex; gap: 10px; align-items: center; }
        .pw-footer-social a { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: rgba(167,139,250,0.12); color: #a78bfa; }
        .pw-footer-social a svg { width: 11px; height: 11px; }

        /* Repeating page header/footer */
        .repeat-header, .repeat-footer { position: absolute; left: 0; right: 0; z-index: 50; overflow: hidden; }
        .repeat-header { height: 60px; display: flex; align-items: center; padding: 0 28px; border-bottom: 2px solid #ede9fe; background: linear-gradient(180deg, #f9f7ff 0%, #ffffff 100%); }
        .repeat-header img { height: 22px; width: auto; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.05)); }
        .repeat-header .rh-meta { margin-left: auto; display: flex; gap: 20px; font-size: 9px; color: #6b7280; }
        .repeat-header .rh-meta strong { color: #111827; font-weight: 700; }
        .repeat-footer { height: 50px; display: flex; align-items: center; justify-content: space-between; padding: 0 28px; background: #1e1b4b; color: #7c6daa; font-size: 8.5px; }
        .repeat-footer .rf-left { display: flex; gap: 16px; align-items: center; }
        .repeat-footer .rf-left a { color: #a78bfa; text-decoration: none; }
        .repeat-footer .rf-right { display: flex; gap: 10px; align-items: center; color: #7c6daa; }
        .repeat-footer .rf-social { display: flex; gap: 8px; }
        .repeat-footer .rf-social a { display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: rgba(167,139,250,0.12); color: #a78bfa; }
        .repeat-footer .rf-social a svg { width: 10px; height: 10px; }
    </style>
</head>
<body>
    @if ($version)
        <div class="doc-wrapper" id="doc">
            <div class="pw-header">
                <div class="pw-brand">
                    <img src="{{ public_path('logo.png') }}" alt="Mars Station">
                </div>
                <div class="pw-meta">
                    <div class="pw-meta-row">
                        <div class="pw-meta-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg></div>
                        <div><div class="pw-meta-label">Agreement No.</div><div class="pw-meta-value">{{ $agreement->agreement_number }}</div></div>
                    </div>
                    <div class="pw-meta-row">
                        <div class="pw-meta-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
                        <div><div class="pw-meta-label">Agreement Date</div><div class="pw-meta-value">{{ $agreement->created_at->format('M d, Y') }}</div></div>
                    </div>
                    <div class="pw-meta-row">
                        <div class="pw-meta-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                        <div><div class="pw-meta-label">Client Name</div><div class="pw-meta-value">{{ $agreement->client_name }}</div></div>
                    </div>
                    <div class="pw-meta-row">
                        <div class="pw-meta-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></div>
                        <div><div class="pw-meta-label">Client Email</div><div class="pw-meta-value">{{ $agreement->client_email }}</div></div>
                    </div>
                    <div class="pw-meta-row">
                        <div class="pw-meta-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                        <div><div class="pw-meta-label">Last Updated</div><div class="pw-meta-value">{{ $agreement->updated_at->format('M d, Y') }}</div></div>
                    </div>
                    <div class="pw-meta-row">
                        <div class="pw-meta-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 9 17 15 17 15 11"/><path d="M9 11V7a3 3 0 0 1 3-3h0a3 3 0 0 1 3 3v4"/></svg></div>
                        <div>
                            <div class="pw-meta-label">Document Status</div>
                            <div class="pw-meta-value">
                                @php
                                    $statusVal = $agreement->status->value ?? 'pending';
                                    $statusClass = match($statusVal) {
                                        'signed' => 'pw-status-signed',
                                        'pending' => 'pw-status-pending',
                                        'expired' => 'pw-status-expired',
                                        default => 'pw-status-default',
                                    };
                                @endphp
                                <span class="pw-status {{ $statusClass }}">{{ $agreement->status->label() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pw-body">
                <h2 class="pw-section-label">AGREEMENT</h2>
                <p class="pw-intro">
                    This Agreement is made and entered into on <strong>{{ $agreement->created_at->format('F d, Y') }}</strong>, by and between <strong>Mars Station (the "Company")</strong> and <strong>{{ $agreement->client_name }} (the "Client")</strong>.
                </p>
                <p class="pw-sub-intro">Both parties agree to the terms and conditions outlined below.</p>

                <div class="pw-block">
                    <h3><span class="num">1</span> SERVICES</h3>
                    <p>The Company agrees to provide the services described in this Agreement (the "Services") to the Client in accordance with the terms and conditions set forth herein.</p>
                </div>

                <div class="pw-block">
                    <h3><span class="num">2</span> SCOPE OF WORK</h3>
                    <p>The Company will work with the goal of delivering high-quality results. The specific deliverables, timelines, and milestones are outlined below.</p>
                </div>

                <div class="pw-block">
                    <h3><span class="num">3</span> SERVICES &amp; DELIVERABLES</h3>
                    @if ($version->content)
                        <div class="pw-content-area" style="margin-top: 8px;">{!! $version->content !!}</div>
                    @else
                        <p>No services or deliverables defined yet.</p>
                    @endif
                </div>

                @if ($agreement->amountTotalPence() > 0)
                    <div class="pw-block">
                        <h3><span class="num">4</span> PAYMENT TERMS</h3>
                        <p>Payment terms and schedule are outlined in the payment plan associated with this Agreement. All payments are non-refundable except as stated in our Payment &amp; Refund Policy.</p>
                        <p style="margin-top: 6px; font-weight:700; color:#4c1d95;">Total: {{ $agreement->formatted_amount }} ({{ $agreement->payment_type->label() }})</p>
                    </div>
                @endif
            </div>

            <div class="pw-acceptance">
                <div class="pw-acceptance-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                </div>
                <div style="flex:1;">
                    <h4>CLIENT ACCEPTANCE</h4>
                    <div class="pw-sig-grid">
                        <div><div class="pw-sig-label">Client Name</div><div class="pw-sig-value">{{ $agreement->client_name }}</div></div>
                        <div><div class="pw-sig-label">Client Email</div><div class="pw-sig-value">{{ $agreement->client_email }}</div></div>
                        <div><div class="pw-sig-label">Signed on</div><div class="pw-sig-value">{{ $version->signed_at?->format('M d, Y') ?? 'Pending' }}</div></div>
                        <div><div class="pw-sig-label">Valid Until</div><div class="pw-sig-value">{{ $agreement->validity_date?->format('M d, Y') ?? 'Not set' }}</div></div>
                    </div>
                    <div class="pw-sig-note">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/></svg>
                        This agreement is valid from {{ $agreement->created_at->format('M d, Y') }}@if ($agreement->validity_date) to {{ $agreement->validity_date->format('M d, Y') }}@endif.
                    </div>
                </div>
            </div>

            <div class="pw-footer">
                <div class="pw-footer-inner">
                    <div class="pw-footer-logo">
                        <img src="{{ public_path('logo.png') }}" alt="Mars Station">
                    </div>
                    <div class="pw-footer-divider"></div>
                    <div class="pw-footer-strip">
                        <div><strong>Agreement No.</strong> {{ $agreement->agreement_number }}</div>
                        <div><strong>Version</strong> v{{ $version->version }}</div>
                        <div><strong>Date</strong> {{ $agreement->created_at->format('M d, Y') }}</div>
                        <div><strong>Status</strong> {{ $agreement->status->label() }}</div>
                        <div><strong>Page</strong> <span class="page-num">1</span> of <span class="page-total">1</span></div>
                    </div>
                    <div class="pw-footer-disclaimer">
                        This agreement is an official electronic document issued by Mars Station. Any unauthorized modification, or alteration may invalidate the document, please refer the official agreement available through the secure Mars Station link for verification.
                    </div>
                </div>
                <div class="pw-footer-bottom">
                    <div>&copy; {{ now()->year }} Mars Station. All rights reserved. Registered in United Kingdom.</div>
                    <div class="pw-footer-links">
                        <a href="https://marsstation.dev/privacy-policy">Privacy</a>
                        <a href="https://marsstation.dev/terms-conditions">Terms</a>
                        <a href="https://marsstation.dev/">Contact</a>
                        <div class="pw-footer-social">
                            <a href="https://facebook.com/marsstation" target="_blank" title="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                            <a href="https://linkedin.com/company/marsstation" target="_blank" title="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>
                            <a href="https://twitter.com/marsstation" target="_blank" title="Twitter"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        (function() {
            var PAGE_W = 794;
            var PAGE_H = 1123;
            var HEADER_H = 60;
            var FOOTER_H = 50;
            var GAP = 10;

            var headerHTML = '<div class="repeat-header">'
                + '<img src="{{ public_path("logo.png") }}" alt="Mars Station">'
                + '<div class="rh-meta">'
                + '<div><strong>Agreement No.</strong> {{ $agreement->agreement_number }}</div>'
                + '<div><strong>Version</strong> v{{ $version->version }}</div>'
                + '<div><strong>Client</strong> {{ $agreement->client_name }}</div>'
                + '<div><strong>Status</strong> {{ $agreement->status->label() }}</div>'
                + '</div></div>';

            var footerHTML = '<div class="repeat-footer">'
                + '<div class="rf-left">'
                + '<span>&copy; {{ now()->year }} Mars Station. All rights reserved.</span>'
                + '<a href="https://marsstation.dev/privacy-policy">Privacy</a>'
                + '<a href="https://marsstation.dev/terms-conditions">Terms</a>'
                + '<a href="https://marsstation.dev/">Contact</a>'
                + '<div class="rf-social">'
                + '<a href="#"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>'
                + '<a href="#"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>'
                + '<a href="#"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>'
                + '</div></div></div>'
                + '<div class="rf-right"><span>Page <span class="rf-pg"></span> of <span class="rf-total"></span></span></div></div>';

            function build() {
                var doc = document.getElementById('doc');
                if (!doc) return;
                var totalH = doc.scrollHeight;
                var numPages = Math.ceil(totalH / (PAGE_H - HEADER_H - FOOTER_H));
                if (numPages < 1) numPages = 1;

                var els = doc.querySelectorAll('.page-num, .page-total');
                els.forEach(function(el) {
                    if (el.classList.contains('page-num')) el.textContent = '1';
                    if (el.classList.contains('page-total')) el.textContent = numPages;
                });

                for (var i = 0; i < numPages; i++) {
                    var pageTop = i * (PAGE_H - HEADER_H - FOOTER_H);

                    if (i > 0) {
                        var h = document.createElement('div');
                        h.className = 'repeat-header';
                        h.style.top = pageTop + 'px';
                        h.innerHTML = headerHTML.replace(/class="repeat-header"/, '').replace('<div class="repeat-footer">', '').replace(/<\/div><\/div>$/, '</div>');
                        h.innerHTML = '<img src="{{ public_path("logo.png") }}" alt="Mars Station" style="height:22px;width:auto;">'
                            + '<div class="rh-meta" style="margin-left:auto;display:flex;gap:20px;font-size:9px;color:#6b7280;">'
                            + '<div><strong style="color:#111827;">Agreement No.</strong> {{ $agreement->agreement_number }}</div>'
                            + '<div><strong style="color:#111827;">Version</strong> v{{ $version->version }}</div>'
                            + '<div><strong style="color:#111827;">Client</strong> {{ $agreement->client_name }}</div>'
                            + '<div><strong style="color:#111827;">Status</strong> {{ $agreement->status->label() }}</div>'
                            + '</div>';
                        doc.appendChild(h);

                        var f = document.createElement('div');
                        f.className = 'repeat-footer';
                        f.style.top = (pageTop + PAGE_H - FOOTER_H) + 'px';
                        f.innerHTML = '<div style="display:flex;gap:16px;align-items:center;">'
                            + '<span>&copy; {{ now()->year }} Mars Station</span>'
                            + '<a href="#" style="color:#a78bfa;text-decoration:none;">Privacy</a>'
                            + '<a href="#" style="color:#a78bfa;text-decoration:none;">Terms</a>'
                            + '<a href="#" style="color:#a78bfa;text-decoration:none;">Contact</a>'
                            + '</div>'
                            + '<span>Page ' + (i + 1) + ' of ' + numPages + '</span>';
                        doc.appendChild(f);
                    }

                    if (i < numPages - 1) {
                        var padBottom = document.createElement('div');
                        padBottom.style.height = (HEADER_H + GAP) + 'px';
                        padBottom.style.pageBreakAfter = 'always';
                        padBottom.style.breakAfter = 'page';

                        var breakPoint = pageTop + PAGE_H - HEADER_H - FOOTER_H;
                        var children = doc.children;
                        var inserted = false;
                        for (var c = 0; c < children.length; c++) {
                            if (children[c].offsetTop + children[c].offsetHeight >= breakPoint && !children[c].classList.contains('repeat-header') && !children[c].classList.contains('repeat-footer')) {
                                children[c].parentNode.insertBefore(padBottom, children[c].nextSibling);
                                inserted = true;
                                break;
                            }
                        }
                    }
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', build);
            } else {
                build();
            }
        })();
        </script>
    @else
        <div style="padding: 60px; text-align: center; color: #6b7280;">
            <p>No version available for this agreement.</p>
        </div>
    @endif
</body>
</html>
