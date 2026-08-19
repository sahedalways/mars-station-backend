<x-mail.layout :subject="'Admin Login Code — ' . config('app.name')">

    {{-- Lock Icon --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td align="center" style="padding-bottom: 20px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width:64px; height:64px; background:#f3e8ff; border-radius:50%; text-align:center; vertical-align:middle;">
                            <span style="color:#7c3aed; font-size:28px; line-height:64px;">&#128274;</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ========== TITLE ========== --}}
    <h1 style="margin: 0 0 8px; color: #111827; font-size: 24px; font-weight: 800; text-align: center; line-height: 30px;">
        Admin Login Code
    </h1>
    <p style="margin: 0 0 4px; color: #4b5563; font-size: 16px; font-weight: 500; text-align: center; line-height: 22px;">
        Use the code below to sign in
    </p>

    {{-- Purple divider --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 14px auto 28px;">
        <tr>
            <td style="width:60px; height:3px; background:#7c3aed; border-radius:2px; line-height:3px; font-size:0;">&nbsp;</td>
        </tr>
    </table>

    {{-- ========== GREETING ========== --}}
    <p style="margin: 0 0 12px; color: #111827; font-size: 15px; font-weight: 700; line-height: 22px;">
        Hello Admin,
    </p>
    <p style="margin: 0 0 8px; color: #374151; font-size: 14px; line-height: 22px;">
        You requested a login code for the Mars Station admin panel.
    </p>
    <p style="margin: 0 0 24px; color: #374151; font-size: 14px; line-height: 22px;">
        Please use the verification code below to complete your sign-in. This code is unique to you and should not be shared with anyone.
    </p>

    {{-- ========== OTP CODE BOX ========== --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 20px;">
        <tr>
            <td style="background:#f5f3ff; border:1px solid #e9d5ff; border-radius:12px; padding:24px; text-align:center;">
                <p style="margin:0 0 10px; color:#7c3aed; font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase;">
                    Your Verification Code
                </p>
                <div style="color:#111827; font-size:36px; font-weight:800; letter-spacing:10px; font-family: 'SF Mono', 'Monaco', 'Menlo', 'Courier New', monospace;">
                    {{ $code }}
                </div>
                <p style="margin:10px 0 0; color:#6b7280; font-size:12px; line-height:18px;">
                    Expires in <strong style="color:#7c3aed;">{{ $expiresInMinutes }} minutes</strong>
                </p>
            </td>
        </tr>
    </table>

    {{-- ========== INFO BOX ========== --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 24px;">
        <tr>
            <td style="background:#f5f3ff; border-radius:12px; padding:16px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td style="width:36px; vertical-align:top;">
                            <div style="width:28px; height:28px; background:#ede9fe; border-radius:50%; text-align:center; line-height:28px; color:#7c3aed; font-size:14px; font-weight:700;">
                                i
                            </div>
                        </td>
                        <td style="vertical-align:top;">
                            <p style="margin:0 0 4px; color:#7c3aed; font-size:13px; font-weight:700;">Security Notice</p>
                            <p style="margin:0; color:#4b5563; font-size:12px; line-height:18px;">
                                This code can only be used <strong>once</strong> and will expire automatically.
                                Never share this code with anyone — Mars Station staff will never ask for it.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ========== DETAILS TABLE ========== --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 24px; background:#f9fafb; border-radius:12px;">
        <tr>
            <td style="padding:16px 20px;">
                <p style="margin:0 0 10px; color:#7c3aed; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">
                    Request Details
                </p>

                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td class="summary-cell" style="padding:6px 0; width:50%; vertical-align:top;">
                            <p style="margin:0; color:#6b7280; font-size:11px;">Requested At</p>
                            <p style="margin:2px 0 0; color:#111827; font-size:13px; font-weight:600;">{{ now()->format('M d, Y · h:i A') }}</p>
                        </td>
                        <td class="summary-cell" style="padding:6px 0; width:50%; vertical-align:top;">
                            <p style="margin:0; color:#6b7280; font-size:11px;">Valid For</p>
                            <p style="margin:2px 0 0; color:#111827; font-size:13px; font-weight:600;">{{ $expiresInMinutes }} minutes</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ========== CTA BUTTON ========== --}}
    @isset($loginUrl)
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 16px;">
            <tr>
                <td align="center">
                    <a href="{{ $loginUrl }}"
                       style="display:inline-block; background:#0a0518; color:#ffffff; font-size:15px; font-weight:700; padding:14px 40px; border-radius:999px; text-decoration:none; letter-spacing:0.3px;">
                        🔓 Continue to Admin Panel
                    </a>
                </td>
            </tr>
        </table>
        <p style="margin: 0 0 20px; color:#6b7280; font-size:12px; text-align:center;">
            Click the button above to enter your code and sign in.
        </p>
    @endisset

    {{-- ========== NOT YOU? ========== --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 20px;">
        <tr>
            <td style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:14px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td style="width:36px; vertical-align:top;">
                            <div style="width:28px; height:28px; background:#fee2e2; border-radius:50%; text-align:center; line-height:28px; color:#dc2626; font-size:14px; font-weight:700;">
                                !
                            </div>
                        </td>
                        <td style="vertical-align:middle;">
                            <p style="margin:0; color:#7f1d1d; font-size:12px; line-height:18px;">
                                <strong>Didn't request this?</strong> You can safely ignore this email. Someone may have entered your email by mistake.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ========== NEED HELP ========== --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td style="background:#f9fafb; border-radius:12px; padding:14px 16px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td style="width:40px; vertical-align:top;">
                            <div style="width:32px; height:32px; background:#ede9fe; border-radius:50%; text-align:center; line-height:32px; color:#7c3aed; font-size:15px;">
                                🎧
                            </div>
                        </td>
                        <td style="vertical-align:middle;">
                            <p style="margin:0 0 2px; color:#111827; font-size:13px; font-weight:700;">Need Help?</p>
                            <p style="margin:0; color:#6b7280; font-size:12px; line-height:18px;">
                                Contact us at
                                <a href="mailto:support@marsstation.dev" style="color:#7c3aed; text-decoration:none; font-weight:600;">support@marsstation.dev</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</x-mail.layout>
