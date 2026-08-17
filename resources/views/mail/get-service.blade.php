<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Agreement — {{ $agreement->agreement_number ?? 'G7H46XK4' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            background: #f8f7fb;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1a1a2e;
            -webkit-font-smoothing: antialiased;
        }

        .doc-wrapper {
            max-width: 860px;
            margin: 40px auto 60px;
            background: #ffffff;
            box-shadow: 0 20px 60px rgba(76, 29, 149, 0.08), 0 4px 12px rgba(0,0,0,0.05);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #ede9fe;
        }

        /* ===== HEADER ===== */
        .doc-header {
            padding: 28px 40px 24px;
            border-bottom: 2px solid #4c1d95;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
        }

        .brand-block {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-shape {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #5d2e9e, #3b0f70);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(76,29,149,0.25);
        }

        .logo-shape svg {
            width: 28px;
            height: 28px;
            color: #fff;
        }

        .brand-text h1 {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #3b0f70;
            margin: 0 0 4px;
            line-height: 1.2;
        }

        .brand-text p {
            margin: 0;
            font-size: 11px;
            color: #6b7280;
            letter-spacing: 0.3px;
            font-weight: 500;
        }

        /* Info Grid */
        .header-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 22px;
            min-width: 320px;
            max-width: 420px;
        }

        .meta-row {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .meta-icon {
            width: 28px;
            height: 28px;
            background: #f5f3ff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #4c1d95;
        }

        .meta-icon svg { width: 14px; height: 14px; }

        .meta-text .label {
            font-size: 10px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 1px;
        }

        .meta-text .value {
            font-size: 12px;
            color: #111827;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            background: #dcfce7;
            color: #166534;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            border: 1px solid #bbf7d0;
        }

        /* ===== MAIN CONTENT ===== */
        .doc-body {
            padding: 36px 40px 0;
        }

        h2.section-label {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #4c1d95;
            font-weight: 700;
            border-left: 3px solid #4c1d95;
            padding-left: 12px;
            margin: 32px 0 14px;
        }

        p.intro {
            font-size: 13px;
            line-height: 1.75;
            color: #374151;
            margin: 0 0 8px;
        }

        p.sub-intro {
            font-size: 12px;
            color: #6b7280;
            font-style: italic;
            margin: 0 0 20px;
        }

        /* Sections */
        .section-block {
            margin-bottom: 20px;
        }

        .section-block h3 {
            font-size: 12px;
            color: #4c1d95;
            font-weight: 700;
            margin: 0 0 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-block h3 .num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: #4c1d95;
            color: #fff;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 800;
        }

        .section-block p {
            font-size: 12.5px;
            line-height: 1.7;
            color: #374151;
            margin: 0 0 6px;
        }

        /* Services Table */
        .table-wrap {
            overflow-x: auto;
            margin: 12px 0 24px;
            border: 1px solid #ede9fe;
            border-radius: 10px;
        }

        table.offer-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
        }

        table.offer-table thead th {
            background: #3b0f70;
            color: #fff;
            text-align: left;
            padding: 12px 14px;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 0.3px;
        }

        table.offer-table thead th:first-child { border-top-left-radius: 10px; }
        table.offer-table thead th:last-child { border-top-right-radius: 10px; }

        table.offer-table tbody td {
            padding: 10px 14px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: top;
        }

        table.offer-table tbody tr:nth-child(even) { background: #faf8ff; }
        table.offer-table tbody tr:hover { background: #f5f3ff; }

        .table-num {
            color: #4c1d95;
            font-weight: 700;
            font-size: 11px;
        }

        .deliverable-name {
            font-weight: 600;
            color: #1a1a2e;
        }

        /* ===== CLIENT ACCEPTANCE ===== */
        .acceptance-card {
            background: linear-gradient(135deg, #f8f6ff 0%, #f0ecff 100%);
            border: 1.5px solid #ddd6fe;
            border-radius: 14px;
            padding: 24px 28px;
            margin-top: 24px;
            display: flex;
            gap: 24px;
            align-items: flex-start;
            box-shadow: inset 0 2px 12px rgba(76,29,149,0.04);
        }

        .acceptance-icon {
            width: 48px;
            height: 48px;
            background: #fff;
            border: 2px solid #4c1d95;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #4c1d95;
            box-shadow: 0 2px 8px rgba(76,29,149,0.1);
        }

        .acceptance-icon svg { width: 22px; height: 22px; }

        .acceptance-body h4 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            color: #2e1065;
            margin: 0 0 12px;
            font-weight: 700;
        }

        .sig-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 24px;
        }

        .sig-item .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 1px;
        }

        .sig-item .val {
            font-size: 12px;
            color: #111827;
            font-weight: 500;
        }

        .sig-note {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px dashed #ddd6fe;
            font-size: 10.5px;
            color: #4c1d95;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ===== FOOTER ===== */
        .doc-footer {
            background: #2e1065;
            color: #fff;
            padding: 32px 40px 20px;
            margin-top: 36px;
        }

        .footer-top {
            display: flex;
            gap: 40px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .footer-brand h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 18px;
            margin: 0 0 8px;
            letter-spacing: 0.5px;
        }

        .footer-brand p {
            margin: 0;
            font-size: 11px;
            color: #c4b5fd;
            line-height: 1.5;
        }

        .footer-links {
            display: flex;
            gap: 32px;
            flex-wrap: wrap;
        }

        .footer-col h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin: 0 0 10px;
            color: #ddd6fe;
            font-weight: 600;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-col li {
            margin-bottom: 4px;
        }

        .footer-col a {
            color: #e9d5ff;
            text-decoration: none;
            font-size: 11px;
        }

        .footer-col a:hover { text-decoration: underline; }

        .meta-strip {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 16px;
            font-size: 10.5px;
            color: #c4b5fd;
        }

        .meta-strip strong { color: #fff; font-weight: 600; }

        .copyright-bar {
            background: #1a0b3a;
            padding: 14px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #a78bfa;
        }

        .copyright-bar a { color: #e9d5ff; text-decoration: none; margin-left: 12px; }

        /* Utilities */
        .text-purple { color: #4c1d95; }
        .font-bold { font-weight: 700; }
    </style>
</head>
<body>

<div class="doc-wrapper">
    <!-- HEADER -->
    <div class="doc-header">
        <div class="brand-block">
            <img src="{{ config('app.url') }}/logo.png" alt="Mars Station" width="180" style="display:block; max-width:180px; height:auto;">
        </div>

        <div class="header-meta">
            <div class="meta-row">
                <div class="meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                </div>
                <div class="meta-text">
                    <div class="label">Agreement No.</div>
                    <div class="value">{{ $agreement->agreement_number ?? 'G7H46XK4' }}</div>
                </div>
            </div>
            <div class="meta-row">
                <div class="meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </div>
                <div class="meta-text">
                    <div class="label">Agreement Date</div>
                    <div class="value">{{ $agreement->created_at?->format('M d, Y') ?? 'May 16, 2025' }}</div>
                </div>
            </div>
            <div class="meta-row">
                <div class="meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="meta-text">
                    <div class="label">Client Name</div>
                    <div class="value">{{ $agreement->client_name ?? 'Md Rony' }}</div>
                </div>
            </div>
            <div class="meta-row">
                <div class="meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                </div>
                <div class="meta-text">
                    <div class="label">Client Email</div>
                    <div class="value">{{ $agreement->client_email ?? 'rony14@hotmail.com' }}</div>
                </div>
            </div>
            <div class="meta-row">
                <div class="meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="meta-text">
                    <div class="label">Last Updated</div>
                    <div class="value">May 16, 2025</div>
                </div>
            </div>
            <div class="meta-row">
                <div class="meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 9 17 15 17 15 11"/><path d="M9 11V7a3 3 0 0 1 3-3h0a3 3 0 0 1 3 3v4"/></svg>
                </div>
                <div class="meta-text">
                    <div class="label">Document Status</div>
                    <div class="value"><span class="status-badge">Signed</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- BODY -->
    <div class="doc-body">
        <h2 class="section-label">AGREEMENT</h2>

        <p class="intro">
            This Agreement is made and entered into on <strong>May 16, 2025</strong>, by and between <strong>Mars Station (the "Company")</strong> and <strong>Md Rony (the "Client")</strong>.
        </p>
        <p class="sub-intro">Both parties agree to the terms and conditions outlined below.</p>

        <!-- 1. SERVICES -->
        <div class="section-block">
            <h3><span class="num">1</span> SERVICES</h3>
            <p>The Company agrees to provide the services described in this Agreement (the "Services") to the Client in accordance with the terms and conditions set forth herein.</p>
        </div>

        <!-- 2. SCOPE -->
        <div class="section-block">
            <h3><span class="num">2</span> SCOPE OF WORK</h3>
            <p>The Company will work with the Services with the goal of delivering high-quality results. The specific deliverables, timelines, and milestones are outlined below.</p>
        </div>

        <!-- 3. SERVICES & DELIVERABLES -->
        <div class="section-block">
            <h3><span class="num">3</span> SERVICES &amp; DELIVERABLES</h3>
            <div class="table-wrap">
                <table class="offer-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service / Deliverable</th>
                            <th>Description</th>
                            <th>Delivery Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="table-num">1</td>
                            <td class="deliverable-name">Website Design</td>
                            <td>UI/UX design for all pages</td>
                            <td>May 16, 2025</td>
                        </tr>
                        <tr>
                            <td class="table-num">2</td>
                            <td class="deliverable-name">Front-end Development</td>
                            <td>Responsive development of approved design</td>
                            <td>Jun 12, 2025</td>
                        </tr>
                        <tr>
                            <td class="table-num">3</td>
                            <td class="deliverable-name">Back-end Development</td>
                            <td>CMS integration and custom functionality</td>
                            <td>Jun 23, 2025</td>
                        </tr>
                        <tr>
                            <td class="table-num">4</td>
                            <td class="deliverable-name">Testing &amp; QA</td>
                            <td>Cross-browser, device, and usability testing</td>
                            <td>Jul 05, 2025</td>
                        </tr>
                        <tr>
                            <td class="table-num">5</td>
                            <td class="deliverable-name">Deployment</td>
                            <td>Final deployment to live server</td>
                            <td>Jul 12, 2025</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. PAYMENT TERMS -->
        <div class="section-block">
            <h3><span class="num">4</span> PAYMENT TERMS</h3>
            <p>Payment terms and schedule are outlined in the payment plan associated with this Agreement. All payments are non-refundable except as stated in our Payment &amp; Refund Policy.</p>
        </div>

        <!-- 5. CLIENT RESPONSIBILITIES -->
        <div class="section-block">
            <h3><span class="num">5</span> CLIENT RESPONSIBILITIES</h3>
            <p>The Client agrees to provide all necessary content, information, and feedback in a timely manner to ensure the successful delivery of the Services.</p>
        </div>

        <!-- 6. CONFIDENTIALITY -->
        <div class="section-block">
            <h3><span class="num">6</span> CONFIDENTIALITY</h3>
            <p>Both parties agree to keep all proprietary and confidential information shared during the course of this Agreement confidential and not disclose it to any third party.</p>
        </div>

        <!-- 7. TERMINATION -->
        <div class="section-block">
            <h3><span class="num">7</span> TERMINATION</h3>
            <p>Either party may terminate this Agreement with written notice if the other party breaches any material term of this Agreement and fails to remedy the breach within 15 days of notice.</p>
        </div>

        <!-- CLIENT ACCEPTANCE -->
        <div class="acceptance-card">
            <div class="acceptance-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
            </div>
            <div class="acceptance-body">
                <h4>CLIENT ACCEPTANCE</h4>
                <div class="sig-grid">
                    <div class="sig-item">
                        <div class="label">Client Name</div>
                        <div class="val">{{ $agreement->client_name ?? 'Md Rony' }}</div>
                    </div>
                    <div class="sig-item">
                        <div class="label">Client Email</div>
                        <div class="val">{{ $agreement->client_email ?? 'rony14@hotmail.com' }}</div>
                    </div>
                    <div class="sig-item">
                        <div class="label">Signed on</div>
                        <div class="val">{{ $agreement->signed_at?->format('M d, Y') ?? 'May 16, 2025' }}</div>
                    </div>
                    <div class="sig-item">
                        <div class="label">Signature</div>
                        <div class="val">Electronically accepted and signed by the Client</div>
                    </div>
                </div>
                <div class="sig-note">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/></svg>
                    This agreement is valid from May 16, 2025 to the end of the service period.
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="doc-footer">

        <div class="meta-strip">
            <div><strong>Agreement No.</strong> {{ $agreement->agreement_number ?? 'G7H46XK4' }}</div>
            <div><strong>Version</strong> v1.2</div>
            <div><strong>Date</strong> May 16, 2025</div>
            <div><strong>Page</strong> 1 of 6</div>
        </div>
    </div>

    <div class="copyright-bar">
<div>© {{ now()->year }} Mars Station. All rights reserved. Registered in United Kingdom.</div>
        <div>
            <a href="https://marsstation.dev/privacy-policy">Privacy Policy</a> · <a href="https://marsstation.dev/terms-conditions">Terms of Service</a> · <a href="https://marsstation.dev/">Contact</a>
        </div>
    </div>
</div>

</body>
</html>
