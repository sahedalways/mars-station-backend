{{-- Shared agreement document partial - used by both preview and PDF --}}
<div class="doc-wrapper">
    <div class="pw-header">
        <div class="pw-brand">
            <img src="{{ $pdfMode ? public_path('logo.png') : asset('logo.png') }}" alt="Mars Station">
        </div>
        <div class="pw-meta">
            <div class="pw-meta-row">
                <div class="pw-meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                </div>
                <div>
                    <div class="pw-meta-label">Agreement No.</div>
                    <div class="pw-meta-value">{{ $agreement->agreement_number }}</div>
                </div>
            </div>
            <div class="pw-meta-row">
                <div class="pw-meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </div>
                <div>
                    <div class="pw-meta-label">Agreement Date</div>
                    <div class="pw-meta-value">{{ $agreement->created_at->format('M d, Y') }}</div>
                </div>
            </div>
            <div class="pw-meta-row">
                <div class="pw-meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <div class="pw-meta-label">Client Name</div>
                    <div class="pw-meta-value">{{ $agreement->client_name }}</div>
                </div>
            </div>
            <div class="pw-meta-row">
                <div class="pw-meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                </div>
                <div>
                    <div class="pw-meta-label">Client Email</div>
                    <div class="pw-meta-value">{{ $agreement->client_email }}</div>
                </div>
            </div>
            <div class="pw-meta-row">
                <div class="pw-meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <div class="pw-meta-label">Last Updated</div>
                    <div class="pw-meta-value">{{ $agreement->updated_at->format('M d, Y') }}</div>
                </div>
            </div>
            <div class="pw-meta-row">
                <div class="pw-meta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 9 17 15 17 15 11"/><path d="M9 11V7a3 3 0 0 1 3-3h0a3 3 0 0 1 3 3v4"/></svg>
                </div>
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
                <div>
                    <div class="pw-sig-label">Client Name</div>
                    <div class="pw-sig-value">{{ $agreement->client_name }}</div>
                </div>
                <div>
                    <div class="pw-sig-label">Client Email</div>
                    <div class="pw-sig-value">{{ $agreement->client_email }}</div>
                </div>
                <div>
                    <div class="pw-sig-label">Signed on</div>
                    <div class="pw-sig-value">{{ $version->signed_at?->format('M d, Y') ?? 'Pending' }}</div>
                </div>
                <div>
                    <div class="pw-sig-label">Valid Until</div>
                    <div class="pw-sig-value">{{ $agreement->validity_date?->format('M d, Y') ?? 'Not set' }}</div>
                </div>
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
                <img src="{{ $pdfMode ? public_path('logo.png') : asset('logo.png') }}" alt="Mars Station">
            </div>
            <div class="pw-footer-divider"></div>
            <div class="pw-footer-strip">
                <div><strong>Agreement No.</strong> {{ $agreement->agreement_number }}</div>
                <div><strong>Version</strong> v{{ $version->version }}</div>
                <div><strong>Date</strong> {{ $agreement->created_at->format('M d, Y') }}</div>
                <div><strong>Status</strong> {{ $agreement->status->label() }}</div>
                <div><strong>Page</strong> 1 of 1</div>
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
                    <a href="https://facebook.com/marsstation" target="_blank" title="Facebook">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://linkedin.com/company/marsstation" target="_blank" title="LinkedIn">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="https://twitter.com/marsstation" target="_blank" title="Twitter">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
