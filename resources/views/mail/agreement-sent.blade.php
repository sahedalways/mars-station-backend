<x-mail.layout :subject="'Your Service Agreement — ' . $agreement->title">

    {{-- Document Icon --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td align="center" style="padding-bottom: 20px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width:64px; height:64px; background:#f3e8ff; border-radius:50%; text-align:center; vertical-align:middle;">
                            <span style="color:#7c3aed; font-size:28px; line-height:64px;">&#128196;</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ========== TITLE ========== --}}
    <h1 style="margin: 0 0 8px; color: #111827; font-size: 24px; font-weight: 800; text-align: center; line-height: 30px;">
        You have a new Service Agreement
    </h1>
    <p style="margin: 0; color: #4b5563; font-size: 16px; font-weight: 500; text-align: center; line-height: 22px;">
        from Mars Station
    </p>

    {{-- Purple divider --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 14px auto 28px;">
        <tr>
            <td style="width:60px; height:3px; background:#7c3aed; border-radius:2px; line-height:3px; font-size:0;">&nbsp;</td>
        </tr>
    </table>

    {{-- ========== GREETING ========== --}}
    <p style="margin: 0 0 12px; color: #111827; font-size: 15px; font-weight: 700; line-height: 22px;">
        Hello {{ $agreement->client_name }},
    </p>
    <p style="margin: 0 0 8px; color: #374151; font-size: 14px; line-height: 22px;">
        Mars Station has created a service agreement for you. Please review the agreement
        and follow the steps to accept, sign, and complete any payment if required.
    </p>
    <p style="margin: 0 0 20px; color: #374151; font-size: 14px; line-height: 22px;">
        This link is unique to you and should not be shared with others.
    </p>

    {{-- ========== AGREEMENT SUMMARY CARD ========== --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 24px;">
        <tr>
            <td style="background:#f5f3ff; border:1px solid #e9d5ff; border-radius:12px; padding:18px;">

                {{-- Card title --}}
                <p style="margin:0 0 14px; color:#7c3aed; font-size:13px; font-weight:700;">
                    Agreement Summary
                </p>

                {{-- 3-column responsive grid --}}
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        {{-- Row 1 Col 1: Agreement Title --}}
                        <td class="summary-cell" style="width:33.33%; vertical-align:top; padding:6px 8px 6px 0;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="width:28px; vertical-align:middle; padding-right:8px;">
                                        <div style="width:26px; height:26px; background:#ede9fe; border-radius:6px; text-align:center; line-height:26px; color:#7c3aed; font-size:12px;">📄</div>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <p style="margin:0; color:#6b7280; font-size:11px;">Agreement Title</p>
                                        <p style="margin:2px 0 0; color:#111827; font-size:12px; font-weight:600; line-height:16px;">
                                            {{ \Illuminate\Support\Str::limit($agreement->title, 24) }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        {{-- Row 1 Col 2: Valid Until --}}
                        <td class="summary-cell" style="width:33.33%; vertical-align:top; padding:6px 8px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="width:28px; vertical-align:middle; padding-right:8px;">
                                        <div style="width:26px; height:26px; background:#ede9fe; border-radius:6px; text-align:center; line-height:26px; color:#7c3aed; font-size:12px;">📅</div>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <p style="margin:0; color:#6b7280; font-size:11px;">Valid Until</p>
                                        <p style="margin:2px 0 0; color:#111827; font-size:12px; font-weight:600; line-height:16px;">
                                            {{ $agreement->validity_date?->format('M d, Y') ?? 'No expiry' }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        {{-- Row 1 Col 3: Created By --}}
                        <td class="summary-cell" style="width:33.33%; vertical-align:top; padding:6px 0 6px 8px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="width:28px; vertical-align:middle; padding-right:8px;">
                                        <div style="width:26px; height:26px; background:#ede9fe; border-radius:6px; text-align:center; line-height:26px; color:#7c3aed; font-size:12px;">👤</div>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <p style="margin:0; color:#6b7280; font-size:11px;">Created By</p>
                                        <p style="margin:2px 0 0; color:#111827; font-size:12px; font-weight:600; line-height:16px;">
                                            Mars Station
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        {{-- Row 2 Col 1: Agreement No. --}}
                        <td class="summary-cell" style="width:33.33%; vertical-align:top; padding:10px 8px 6px 0;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="width:28px; vertical-align:middle; padding-right:8px;">
                                        <div style="width:26px; height:26px; background:#ede9fe; border-radius:6px; text-align:center; line-height:26px; color:#7c3aed; font-size:12px; font-weight:700;">#</div>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <p style="margin:0; color:#6b7280; font-size:11px;">Agreement No.</p>
                                        <p style="margin:2px 0 0; color:#7c3aed; font-size:12px; font-weight:700; line-height:16px; font-family:'SF Mono','Monaco','Menlo',monospace;">
                                            {{ $agreement->agreement_number }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        {{-- Row 2 Col 2: Payment Type --}}
                        <td class="summary-cell" style="width:33.33%; vertical-align:top; padding:10px 8px 6px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="width:28px; vertical-align:middle; padding-right:8px;">
                                        <div style="width:26px; height:26px; background:#ede9fe; border-radius:6px; text-align:center; line-height:26px; color:#7c3aed; font-size:12px;">💳</div>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <p style="margin:0; color:#6b7280; font-size:11px;">Payment Type</p>
                                        <p style="margin:2px 0 0; color:#111827; font-size:12px; font-weight:600; line-height:16px;">
                                            {{ $agreement->payment_type->label() ?? 'N/A' }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        {{-- Row 2 Col 3: Created On --}}
                        <td class="summary-cell" style="width:33.33%; vertical-align:top; padding:10px 0 6px 8px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="width:28px; vertical-align:middle; padding-right:8px;">
                                        <div style="width:26px; height:26px; background:#ede9fe; border-radius:6px; text-align:center; line-height:26px; color:#7c3aed; font-size:12px;">🕒</div>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <p style="margin:0; color:#6b7280; font-size:11px;">Created On</p>
                                        <p style="margin:2px 0 0; color:#111827; font-size:12px; font-weight:600; line-height:16px;">
                                            {{ $agreement->created_at->format('M d, Y h:i A') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ========== CTA BUTTON (Dark Pill) ========== --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 10px;">
        <tr>
            <td align="center">
                <a href="{{ $link->publicUrl() }}"
                   style="display:inline-block; background:#0a0518; color:#ffffff; font-size:15px; font-weight:700; padding:14px 44px; border-radius:999px; text-decoration:none; letter-spacing:0.3px;">
                    🛡️  View &amp; Manage Agreement
                </a>
            </td>
        </tr>
    </table>
    <p style="margin: 0 0 24px; color:#6b7280; font-size:12px; text-align:center;">
        This secure link will allow you to view, accept, sign and track your agreement.
    </p>

    {{-- ========== FALLBACK LINK CARD ========== --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 14px;">
        <tr>
            <td style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:14px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td style="width:40px; vertical-align:top;">
                            <div style="width:32px; height:32px; background:#ede9fe; border-radius:50%; text-align:center; line-height:32px; color:#7c3aed; font-size:14px;">
                                🌐
                            </div>
                        </td>
                        <td style="vertical-align:top;">
                            <p style="margin:0 0 4px; color:#374151; font-size:12px; line-height:18px;">
                                If the button above doesn't work, copy and paste this link into your browser:
                            </p>
                            <a href="{{ $link->publicUrl() }}" style="color:#7c3aed; font-size:12px; font-weight:600; text-decoration:underline; word-break:break-all;">
                                {{ $link->publicUrl() }}
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ========== NEED HELP ========== --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:14px 16px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td style="width:44px; vertical-align:top;">
                            <div style="width:34px; height:34px; background:#ede9fe; border-radius:50%; text-align:center; line-height:34px; color:#7c3aed; font-size:16px;">
                                🎧
                            </div>
                        </td>
                        <td style="vertical-align:middle;">
                            <p style="margin:0 0 3px; color:#111827; font-size:13px; font-weight:700;">Need Help?</p>
                            <p style="margin:0; color:#6b7280; font-size:12px; line-height:18px;">
                                If you have any questions, feel free to contact us. We're here to help.
                            </p>
                        </td>
                        <td style="vertical-align:middle; text-align:right; padding-left:12px;">
                            <p style="margin:0 0 3px; color:#7c3aed; font-size:11px;">
                                ✉️
                                <a href="mailto:support@marsstation.dev" style="color:#7c3aed; font-weight:600; text-decoration:underline;">support@marsstation.dev</a>
                            </p>
                            <p style="margin:0; color:#7c3aed; font-size:11px;">
                                🌐
                                <a href="https://marsstation.dev" style="color:#7c3aed; font-weight:600; text-decoration:underline;">marsstation.dev</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</x-mail.layout>
