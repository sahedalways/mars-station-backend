<x-mail.layout>
    {{-- Alert Icon Header --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 72px; height: 72px; background: #fee2e2; border-radius: 50%; text-align: center; line-height: 72px;">
            <span style="display: inline-block; color: #dc2626; font-size: 36px; line-height: 72px; font-weight: 700;">!</span>
        </div>
    </div>

    {{-- Title Section --}}
    <h1 style="margin: 0 0 8px; color: #111827; font-size: 26px; line-height: 32px; font-weight: 700; text-align: center;">
        Your Agreement Has Been <span style="color: #dc2626;">Terminated</span>
    </h1>
    <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 24px; text-align: center;">
        This agreement has been terminated.
    </p>
    <div style="width: 60px; height: 2px; background: #a78bfa; margin: 0 auto 32px;"></div>

    {{-- Greeting --}}
    <p style="margin: 0 0 12px; color: #111827; font-size: 14px; line-height: 22px; font-weight: 600;">
        Hello {{ $agreement->client_name }},
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        We regret to inform you that the agreement listed below has been terminated by <strong>Mars Station</strong>.
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        If you believe this is a mistake or have any questions,
    </p>
    <p style="margin: 0 0 24px; color: #374151; font-size: 14px; line-height: 22px;">
        please contact our support team.
    </p>

    {{-- Agreement Summary Box --}}
    <div style="background: #fef2f2; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <h3 style="margin: 0 0 16px; color: #dc2626; font-size: 14px; font-weight: 700;">
            Agreement Summary
        </h3>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Agreement Title</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->title }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Termination Date</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->terminated_at?->format('M d, Y') ?? now()->format('M d, Y') }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Status</p>
                    <p style="margin: 4px 0 0;">
                        <span style="display: inline-block; padding: 3px 12px; background: #fee2e2; color: #dc2626; border-radius: 6px; font-size: 12px; font-weight: 600;">
                            Terminated
                        </span>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Agreement No.</p>
                    <p style="margin: 2px 0 0; color: #dc2626; font-size: 13px; font-weight: 600;">
                        {{ $agreement->agreement_number }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Client Name</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->client_name }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Reason</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->termination_reason ?? 'Terminated by Admin' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- What This Means Info Box --}}
    <div style="background: #eff6ff; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #dbeafe; border-radius: 50%; text-align: center; line-height: 24px; color: #2563eb; font-weight: 700; font-style: italic;">i</span>
                </td>
                <td style="vertical-align: top;">
                    <h3 style="margin: 0 0 6px; color: #2563eb; font-size: 14px; font-weight: 700;">
                        What This Means
                    </h3>
                    <p style="margin: 0 0 2px; color: #374151; font-size: 13px; line-height: 20px;">
                        This agreement is no longer active.
                    </p>
                    <p style="margin: 0 0 2px; color: #374151; font-size: 13px; line-height: 20px;">
                        All associated services, subscriptions, and access (if any) have been ended.
                    </p>
                    <p style="margin: 0; color: #374151; font-size: 13px; line-height: 20px;">
                        No further actions can be taken on this agreement.
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Need Help Box --}}
    <div style="background: #f3f4f6; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="60%" style="vertical-align: top;">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="32" style="vertical-align: top;">
                                <span style="display: inline-block; width: 24px; height: 24px; background: #e5e7eb; border-radius: 50%; text-align: center; line-height: 24px; color: #6b7280;">🎧</span>
                            </td>
                            <td style="vertical-align: top;">
                                <p style="margin: 0; color: #111827; font-size: 13px; font-weight: 600;">Need Help?</p>
                                <p style="margin: 2px 0 0; color: #6b7280; font-size: 12px; line-height: 18px;">
                                    If you have any questions or need clarification,<br>
                                    please contact our support team.
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="40%" style="vertical-align: top; text-align: right;">
                    <p style="margin: 0;">
                        <a href="mailto:support@marsstation.dev" style="color: #7c3aed; font-size: 12px; text-decoration: underline;">
                            ✉ support@marsstation.dev
                        </a>
                    </p>
                    <p style="margin: 4px 0 0;">
                        <a href="https://marsstation.dev" style="color: #7c3aed; font-size: 12px; text-decoration: underline;">
                            🌐 marsstation.dev
                        </a>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Bottom Section: Stamp + Thank You --}}
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
        <tr>
            {{-- Terminated Stamp --}}
            <td width="35%" style="vertical-align: middle; text-align: center; padding-right: 12px;">
                <div style="display: inline-block; width: 140px; height: 140px; border: 4px double #dc2626; border-radius: 50%; text-align: center; line-height: 140px; transform: rotate(-18deg); opacity: 0.9; background: radial-gradient(circle at center, #fef2f2 40%, #ffffff 100%); box-shadow: 0 0 20px rgba(220, 38, 38, 0.15), inset 0 0 15px rgba(220, 38, 38, 0.05); position: relative;">
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(18deg); width: 120px; height: 120px; border: 2px solid #dc2626; border-radius: 50; border-radius: 50%; line-height: 120px;">
                        <span style="display: inline-block; color: #dc2626; font-size: 22px; font-weight: 900; letter-spacing: 3px; line-height: 1.3; vertical-align: middle; text-transform: uppercase; text-shadow: 0 1px 2px rgba(220, 38, 38, 0.1);">
                            ✕ Terminated ✕
                        </span>
                    </div>
                </div>
            </td>

            {{-- Thank You Box --}}
            <td width="65%" style="vertical-align: middle;">
                <div style="background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%); border: 1px solid #fecaca; border-radius: 12px; padding: 20px;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="32" style="vertical-align: top;">
                                <span style="display: inline-block; width: 28px; height: 28px; background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 50%; text-align: center; line-height: 28px; color: #dc2626; font-size: 14px;">🛡</span>
                            </td>
                            <td style="vertical-align: top;">
                                <h3 style="margin: 0 0 6px; color: #dc2626; font-size: 14px; font-weight: 700;">
                                    Thank You
                                </h3>
                                <p style="margin: 0 0 2px; color: #374151; font-size: 13px; line-height: 20px;">
                                    Thank you for working with Mars Station.
                                </p>
                                <p style="margin: 0 0 2px; color: #374151; font-size: 13px; line-height: 20px;">
                                    We appreciate the opportunity to have served you.
                                </p>
                                <p style="margin: 0; color: #374151; font-size: 13px; line-height: 20px;">
                                    We wish you the best in your future endeavors.
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</x-mail.layout>
