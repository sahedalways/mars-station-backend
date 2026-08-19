<x-mail.layout>
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 72px; height: 72px; background: #fef3c7; border-radius: 50%; text-align: center; line-height: 72px;">
            <span style="display: inline-block; color: #d97706; font-size: 36px; line-height: 72px; font-weight: 700;">!</span>
        </div>
    </div>

    <h1 style="margin: 0 0 8px; color: #111827; font-size: 26px; line-height: 32px; font-weight: 700; text-align: center;">
        Payment <span style="color: #d97706;">Action Required</span>
    </h1>
    <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 24px; text-align: center;">
        Your payment requires additional verification.
    </p>
    <div style="width: 60px; height: 2px; background: #a78bfa; margin: 0 auto 32px;"></div>

    <p style="margin: 0 0 12px; color: #111827; font-size: 14px; line-height: 22px; font-weight: 600;">
        Hello {{ $agreement->client_name }},
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        Your recent payment requires additional authentication to complete. This may involve verifying your identity through your bank (e.g. 3D Secure).
    </p>
    <p style="margin: 0 0 24px; color: #374151; font-size: 14px; line-height: 22px;">
        Please click the button below to complete the verification and finalise your payment.
    </p>

    <div style="background: #fffbeb; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #fef3c7; border-radius: 50%; text-align: center; line-height: 24px; color: #d97706; font-weight: 700; font-style: italic;">i</span>
                </td>
                <td style="vertical-align: top;">
                    <h3 style="margin: 0 0 6px; color: #d97706; font-size: 14px; font-weight: 700;">
                        Why am I receiving this?
                    </h3>
                    <p style="margin: 0; color: #374151; font-size: 13px; line-height: 20px;">
                        Your bank or card issuer requires extra verification to confirm this payment. This is a standard security measure to protect your account.
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div style="background: #fffbeb; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
        <h3 style="margin: 0 0 16px; color: #d97706; font-size: 14px; font-weight: 700;">
            Payment Details
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
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Payment Type</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ ucfirst($agreement->payment_type->value) }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Amount</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $payment->formattedAmount() }}
                    </p>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Agreement No.</p>
                    <p style="margin: 2px 0 0; color: #d97706; font-size: 13px; font-weight: 600;">
                        {{ $agreement->agreement_number }}
                    </p>
                </td>
                <td colspan="2" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Payment Status</p>
                    <p style="margin: 4px 0 0;">
                        <span style="display: inline-block; padding: 3px 12px; background: #fef3c7; color: #d97706; border-radius: 6px; font-size: 12px; font-weight: 600;">
                            Verification Required
                        </span>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin-bottom: 12px;">
        <a href="{{ $link->publicUrl() }}"
           style="display: inline-block; padding: 16px 48px; background: #d97706; color: #ffffff; text-decoration: none; border-radius: 999px; font-size: 15px; font-weight: 600;">
            Complete Payment Verification
        </a>
    </div>
    <p style="margin: 0 0 24px; color: #6b7280; font-size: 13px; line-height: 20px; text-align: center;">
        This secure link will allow you to complete the required verification for your payment.
    </p>

    <div style="background: #fffbeb; border-radius: 10px; padding: 16px; margin-bottom: 16px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #fef3c7; border-radius: 50%; text-align: center; line-height: 24px; color: #d97706;">&#127760;</span>
                </td>
                <td style="vertical-align: top;">
                    <p style="margin: 0 0 4px; color: #374151; font-size: 13px;">
                        If the button doesn't work, copy and paste this link into your browser:
                    </p>
                    <a href="{{ $link->publicUrl() }}" style="color: #d97706; font-size: 12px; word-break: break-all; text-decoration: underline;">
                        {{ $link->publicUrl() }}
                    </a>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; padding: 8px 0;">
        <p style="margin: 0 0 4px; color: #6b7280; font-size: 13px; line-height: 20px;">
            Thank you for your trust in Mars Station.
        </p>
        <p style="margin: 0; color: #111827; font-size: 14px; line-height: 20px; font-weight: 700;">
            We look forward to serving you!
        </p>
    </div>
</x-mail.layout>
